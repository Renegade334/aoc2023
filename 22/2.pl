#!/usr/bin/perl

use strict;
use feature 'say';

use FindBin;
use lib $FindBin::Bin;

use Day22 qw(read_input cascade);

use List::Util qw(all sum);

my @bricks = read_input 'input';
cascade @bricks;

my $count = 0;
for my $candidate (0 .. $#bricks) {
	next unless $bricks[$candidate]->{above}->@*;

	my @destroyed;
	my @queue = $candidate;

	while (@queue) {
		my $id = shift @queue;
		next if $destroyed[$id];
		$destroyed[$id] = 1;
		for my $above ($bricks[$id]->{above}->@*) {
			push(@queue, $above) if all { $destroyed[$_] } $bricks[$above]->{below}->@*;
		}
	}

	$count += -1 + sum @destroyed;
}

say $count;
