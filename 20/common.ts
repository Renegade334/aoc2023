import fs = require('node:fs')

enum LogicLevel { Lo, Hi }

interface Pulse { from: string; to: string; level: LogicLevel }

abstract class Transmitter {
	constructor(public targets: string[]) {}

	abstract propagate(pulse: Pulse): Pulse[]
}

class Broadcaster extends Transmitter {
	propagate(pulse: Pulse): Pulse[] {
		return this.targets.map(target => ({ from: pulse.to, to: target, level: pulse.level }))
	}
}

class FlipFlop extends Transmitter {
	public state = LogicLevel.Lo

	propagate(pulse: Pulse): Pulse[] {
		if (pulse.level === LogicLevel.Hi) {
			return []
		}

		this.state = this.state === LogicLevel.Hi ? LogicLevel.Lo : LogicLevel.Hi

		return this.targets.map(target => ({ from: pulse.to, to: target, level: this.state }))
	}
}

class Conjunction extends Transmitter {
	public state: Record<string, LogicLevel> = {}

	propagate(pulse: Pulse): Pulse[] {
		this.state[pulse.from] = pulse.level

		const level = Object.values(this.state).every(level => level === LogicLevel.Hi) ? LogicLevel.Lo : LogicLevel.Hi
		return this.targets.map(target => ({ from: pulse.to, to: target, level }))
	}
}

class TransmitterMap {
	public transmitters = new Map<string, Transmitter>

	constructor(filename: string) {
		const input = fs.readFileSync(filename, { encoding: 'latin1' })

		for (const line of input.trim().split('\n')) {
			const match = line.match(/^(?<type>[&%]?)(?<id>[a-z]+) -> (?<targets>(?:[a-z]+, )*[a-z]+)$/)
			if (!match?.groups) {
				continue
			}

			const targets = match.groups.targets.split(', ')
			switch (match.groups.type) {
				case '%':
					this.transmitters.set(match.groups.id, new FlipFlop(targets))
					break
				case '&':
					this.transmitters.set(match.groups.id, new Conjunction(targets))
					break
				default:
					this.transmitters.set(match.groups.id, new Broadcaster(targets))
			}
		}

		for (const [ id, transmitter ] of this.transmitters.entries()) {
			for (const target of transmitter.targets) {
				const resolved = this.transmitters.get(target)
				if (resolved instanceof Conjunction) {
					resolved.state[id] = LogicLevel.Lo
				}
			}
		}
	}

	get feeder(): Conjunction {
		const feeder = [ ...this.transmitters.values() ].find(
			(transmitter): transmitter is Conjunction => transmitter instanceof Conjunction && transmitter.targets.includes('rx')
		)
		if (!feeder) {
			throw new ReferenceError('Unable to locate rx feeder')
		}
		Object.defineProperty(this, 'feeder', { value: feeder })
		return feeder
	}

	*pulse(): Generator<{ pulse: Pulse; transmitter?: Transmitter }, void, undefined> {
		const queue: Pulse[] = [ { from: 'button', to: 'broadcaster', level: LogicLevel.Lo } ]

		do {
			const pulse = queue.shift()!
			const transmitter = this.transmitters.get(pulse.to)
			yield { pulse, transmitter }
			if (transmitter) {
				queue.push(...transmitter.propagate(pulse))
			}
		} while (queue.length)
	}
}

export { LogicLevel, TransmitterMap }
