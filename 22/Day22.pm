package Day22;

use strict;
use warnings;
use Exporter 'import';

use List::Util 'uniq';

our @EXPORT = qw(read_input cascade);

sub read_input {
	my @bricks;

	open(my $input, '<', shift);
	while (<$input>) {
		chomp;
		/^(\d+),(\d+),(\d+)~(\d+),(\d+),(\d+)$/;
		my @voxels;
		for my $x ($1 .. $4) {
			for my $y ($2 .. $5) {
				for my $z ($3 .. $6) {
					push(@voxels, { x => $x, y => $y, z => $z });
				}
			}
		}
		push(@bricks, { voxels => \@voxels, below => [], above => [] });
	}
	close $input;

	@bricks;
}

sub cascade {
	my @bricks = @_;
	my $space = [];

	for my $id (sort { $bricks[$a]->{voxels}->[0]->{z} <=> $bricks[$b]->{voxels}->[0]->{z} } (0 .. $#bricks)) {
		my @voxels = $bricks[$id]->{voxels}->@*;
		my @supports;
		my $drop = 0;

		while ($drop < $voxels[0]->{z} - 1) {
			@supports = uniq grep(defined, map { $space->[$_->{x}]->[$_->{y}]->[$_->{z} - $drop - 1] } @voxels);
			last if @supports;
			$drop++;
		}

		for my $voxel (@voxels) {
			$voxel->{z} -= $drop;
			$space->[$voxel->{x}]->[$voxel->{y}]->[$voxel->{z}] = $id;
		}

		$bricks[$id]->{below} = \@supports;
		push($bricks[$_]->{above}->@*, $id) for @supports;
	}

	@bricks;
}

1;
