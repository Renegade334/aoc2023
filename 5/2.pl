#!/usr/bin/perl

use strict;
use feature 'say';

my @ranges;
my $maps = {};
my $current_map;

open(my $input, '<', 'input');
while (<$input>) {
	chomp;
	next unless length;

	if (/^seeds: (.+)$/) {
		my @match = split(/ /, $1);
		while (@match) {
			my ($start, $length) = splice(@match, 0, 2);
			push(@ranges, {start => $start, limit => $start + $length});
		}
		next;
	}

	if (/^([^-]+)-to-([^-]+) map:$/) {
		$current_map = [];
		$maps->{$1} = {to => $2, maps => $current_map};
		next;
	}

	/^(\d+) (\d+) (\d+)$/;
	push(@$current_map, {dest => $1, source => $2, limit => $2 + $3, delta => $1 - $2});
}
close $input;

my $current_unit = 'seed';
while ($current_unit ne 'location') {
	my $map = $maps->{$current_unit};
	my @mapped_ranges;
	for my $range (@ranges) {
		for my $element (@{$map->{maps}}) {
			if ($range->{start} >= $element->{source}) {
				next if $range->{start} >= $element->{limit};

				if ($range->{limit} <= $element->{limit}) {
					$range = {
						start => $range->{start} + $element->{delta},
						limit => $range->{limit} + $element->{delta}
					};
				}
				else {
					push(
						@ranges,
						{
							start => $element->{limit},
							limit => $range->{limit}
						}
					);
					$range = {
						start => $range->{start} + $element->{delta},
						limit => $element->{limit} + $element->{delta}
					};
				}
			}
			else {
				next if $range->{limit} <= $element->{source};

				push(
					@ranges,
					{
						start => $element->{source},
						limit => $range->{limit}
					},
					{
						start => $range->{start},
						limit => $element->{source}
					}
				);

				$range = undef;
			}

			last;
		}
		push(@mapped_ranges, $range) if defined $range;
	}
	@ranges = @mapped_ranges;
	$current_unit = $map->{to};
}

my @sorted = sort {$a->{start} <=> $b->{start}} @ranges;
say $sorted[0]->{start};
