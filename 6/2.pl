#!/usr/bin/perl

use strict;
use feature 'say';

use FindBin;
use lib $FindBin::Bin;

use Day6 'solve';

my $time;
my $distance;

open(my $input, '<', 'input');
while (<$input>) {
	my @tokens = split(/\s+/);
	for (shift @tokens) {
		my $joined = join('', @tokens);
		$time = $joined if /^Time:/;
		$distance = $joined if /^Distance:/;
	}
}
close $input;

say solve($time, $distance);
