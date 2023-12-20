#!/usr/bin/perl

use strict;
use feature 'say';

use FindBin;
use lib $FindBin::Bin;

use Day19 qw(read_input apply_rules);

use List::Util 'sum';

my ($workflows, $parts) = read_input 'input';

my $sum = 0;
for my $part (@$parts) {
	my $result = apply_rules($part, $workflows);
	$sum += sum values %$result if $result;
}

say $sum;
