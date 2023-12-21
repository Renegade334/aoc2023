import { LogicLevel, TransmitterMap } from './common.js'

const map = new TransmitterMap('input')

const counts = {
	[LogicLevel.Lo]: 0,
	[LogicLevel.Hi]: 0
}

for (let i = 0; i < 1000; i++) {
	for (const { pulse } of map.pulse()) {
		counts[pulse.level]++
	}
}

console.log(counts[LogicLevel.Lo] * counts[LogicLevel.Hi])
