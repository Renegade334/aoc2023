#!/usr/bin/perl

use strict;
use feature 'say';

use FindBin;
use lib $FindBin::Bin;

use Day11 qw(galaxies pathsum);

open(my $fh, '<', 'input');
my $map = [map { chomp; [split //] } <$fh>];
close $fh;

my @galaxies = galaxies($map, 2);

say pathsum @galaxies;
