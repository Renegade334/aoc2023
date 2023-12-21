import { lcm } from '../common.js'
import { LogicLevel, TransmitterMap } from './common.js'

const map = new TransmitterMap('input')

const iterations = new Map<string, number>, needed = Object.keys(map.feeder.state).length

for (let counter = 1; iterations.size < needed; counter++) {
	for (const { transmitter } of map.pulse()) {
		if (transmitter !== map.feeder) {
			continue
		}
		for (const [ source, state ] of Object.entries(map.feeder.state)) {
			if (state === LogicLevel.Hi && !iterations.has(source)) {
				iterations.set(source, counter)
			}
		}
	}
}

console.log(lcm([ ...iterations.values() ]))
