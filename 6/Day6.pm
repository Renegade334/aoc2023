package Day6;

use strict;
use warnings;
use Exporter 'import';

our @EXPORT = qw(solve);

sub solve {
        my ($time, $distance) = @_;
        int((sqrt($time**2 - (4 * $distance)) + $time) / 2) * 2 - $time + 1;
}

1;
