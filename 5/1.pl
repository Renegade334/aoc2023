#!/usr/bin/perl

use strict;
use feature 'say';

my @seeds;
my $maps = {};
my $current_map;

open(my $input, '<', 'input');
while (<$input>) {
	chomp;
	next unless length;

	if (/^seeds: (.+)$/) {
		@seeds = map {{seed => $_}} split(/ /, $1);
		next;
	}

	if (/^([^-]+)-to-([^-]+) map:$/) {
		$current_map = [];
		$maps->{$1} = {to => $2, maps => $current_map};
		next;
	}

	/^(\d+) (\d+) (\d+)$/;
	push(@$current_map, {dest => $1, source => $2, range => $3});
}
close $input;

my $current_unit = 'seed';
while ($current_unit ne 'location') {
	my $map = $maps->{$current_unit};
	for my $seed (@seeds) {
		my $value = $seed->{$current_unit};
		for my $element (@{$map->{maps}}) {
			my $delta = $value - $element->{source};
			if ($delta >= 0 and $delta < $element->{range}) {
				$value = $element->{dest} + $delta;
				last;
			}
		}
		$seed->{$map->{to}} = $value;
	}
	$current_unit = $map->{to};
}

my @sorted = sort {$a->{location} <=> $b->{location}} @seeds;
say $sorted[0]->{location};
