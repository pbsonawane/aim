#!/usr/bin/perl
use DBI;
use strict;
my $driver = "mysql";
my $database = "mysql";
my $userid = "emroot";
my $password = 'supp0rt@ESDS';
my $dbhost = "10.10.99.2";
my $port = "3308";
my $dsn = "DBI:mysql:database=$database;host=$dbhost;port=$port";
my $dbh = DBI->connect($dsn, $userid, $password) || die "can't open DB: $DBI::errstr\n";

my $sth = $dbh->prepare("SELECT * FROM user WHERE user='emroot'");
$sth->execute;
my ($user,$host) = $sth->fetchrow_array();
use Data::Dumper;

print "host:$host user:$user\n";
