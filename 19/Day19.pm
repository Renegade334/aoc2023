package Day19;

use strict;
use warnings;
use Exporter 'import';

use Clone 'clone';
use List::Util 'product';

our @EXPORT = qw(read_input apply_rules test_ranges);

our @comparisons = (
	sub { $_[0] < $_[1] },
	sub { $_[0] > $_[1] }
);

sub read_input {
	my %workflows;
	my @parts;

	open(my $fh, '<', $_[0]);
	while (<$fh>) {
		chomp;
		if (/^([a-z]+)\{(.+),([AR]|[a-z]+)\}$/) {
			my ($key, $final, @rules) = ($1, $3);
			for (split(/,/, $2)) {
				/^([xmas])([<>])(\d+):(.+)$/;
				push(@rules, { property => $1, comparison => int($2 eq '>'), value => $3, destination => $4 });
			}
			$workflows{$key} = { rules => \@rules, final => $final };
		}
		elsif (/^\{x=(\d+),m=(\d+),a=(\d+),s=(\d+)\}$/) {
			push(@parts, { 'x' => $1, 'm' => $2, 'a' => $3, 's' => $4 });
		}
	}
	close $fh;

	(\%workflows, \@parts);
}

sub apply_rules {
	my ($part, $workflows) = @_;
	my $key = 'in';

	workflow: while ($key !~ /^[AR]$/) {
		for my $rule (@{$workflows->{$key}->{rules}}) {
			if (&{$comparisons[$rule->{comparison}]}($part->{$rule->{property}}, $rule->{value})) {
				$key = $rule->{destination};
				next workflow;
			}
		}
		$key = $workflows->{$key}->{final};
	}

	$key eq 'A' ? $part : undef;
}

sub test_ranges {
	my $workflows = shift;
	my @queue = {
		key => 'in',
		map { $_ => { first => 1, last => 4000 } } qw(x m a s)
	};
	my $sum = 0;

	while (@queue) {
		my $range = shift @queue;

		workflow: while ($range->{key} !~ /^[AR]$/) {
			for my $rule (@{$workflows->{$range->{key}}->{rules}}) {
				my $property = $rule->{property};
				if ($rule->{comparison}) {
					next if $rule->{value} >= $range->{$property}->{last};
					if ($rule->{value} >= $range->{$property}->{first}) {
						my $new = clone $range;
						$new->{$property} = { first => $range->{$property}->{first}, last => $rule->{value} };
						push(@queue, $new);
						$range->{$property}->{first} = $rule->{value} + 1;
					}
				}
				else {
					next if $rule->{value} <= $range->{$property}->{first};
					if ($rule->{value} <= $range->{$property}->{last}) {
						my $new = clone $range;
						$new->{$property} = { first => $rule->{value}, last => $range->{$property}->{last} };
						push(@queue, $new);
						$range->{$property}->{last} = $rule->{value} - 1;
					}
				}
				$range->{key} = $rule->{destination};
				next workflow;
			}
			$range->{key} = $workflows->{$range->{key}}->{final};
		}

		$sum += product(map { $range->{$_}->{last} - $range->{$_}->{first} + 1 } qw(x m a s)) if $range->{key} eq 'A';
	}

	$sum;
}

1;
