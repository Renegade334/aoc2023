#!/usr/bin/perl

use strict;
use feature 'say';

use FindBin;
use lib $FindBin::Bin;

use Day19 qw(read_input test_ranges);

my ($workflows) = read_input 'input';

say test_ranges $workflows;
