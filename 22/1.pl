#!/usr/bin/perl

use strict;
use feature 'say';

use FindBin;
use lib $FindBin::Bin;

use Day22 qw(read_input cascade);

use List::Util 'all';

my @bricks = read_input 'input';
cascade @bricks;

my $count = grep { all { $bricks[$_]->{below}->@* > 1 } $_->{above}->@* } @bricks;

say $count;
