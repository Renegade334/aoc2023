package Day11;

use strict;
use warnings;
use Exporter 'import';

use List::Util qw(all sum);

our @EXPORT = qw(galaxies pathsum);
our @EXPORT_OK = qw(expand);

sub expand {
	my ($map) = @_;

	my @rows = map { all { $_ eq '.' } @$_ } @$map;
	my @columns = map { my $i = $_; all { $_->[$i] eq '.' } @$map } (0 .. $#{$map->[0]});

	{rows => \@rows, columns => \@columns};
}

sub galaxies {
	my ($map, $factor) = @_;
	my $expansions = expand $map;

	my @galaxies;
	for my $x (0 .. $#$map) {
		for my $y (0 .. $#{$map->[$x]}) {
			push(
				@galaxies,
				{
					x => ($factor - 1) * sum(0, @{$expansions->{rows}}[0 .. $x - 1]) + $x,
					y => ($factor - 1) * sum(0, @{$expansions->{columns}}[0 .. $y - 1]) + $y
				}
			) if $map->[$x]->[$y] eq '#';
		}
	}

	@galaxies;
}

sub pathsum {
	my $sum;
	for my $i (0 .. $#_) {
		for my $j ($i .. $#_) {
			next if $i == $j;
			my ($from, $to) = @_[$i, $j];
			$sum += abs($from->{x} - $to->{x}) + abs($from->{y} - $to->{y});
		}
	}

	$sum;
}

1;
