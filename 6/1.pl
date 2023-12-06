#!/usr/bin/perl

use strict;
use feature 'say';

use FindBin;
use lib $FindBin::Bin;

use Day6 'solve';
use List::Util 'product';

my @times;
my @distances;

open(my $input, '<', 'input');
while (<$input>) {
	my @tokens = split(/\s+/);
	for (shift @tokens) {
		@times = @tokens if /^Time:/;
		@distances = @tokens if /^Distance:/;
	}
}
close $input;

say product(map {solve($times[$_], $distances[$_])} keys @times);
