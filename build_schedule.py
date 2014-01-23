#!/usr/bin/env python2

schedule = [l[:-1].split('\t') for l in open("schedule.txt").readlines()]

print "<?php"
print "$schedule = array();"

for line in schedule:
    print """$schedule[] = array(
	"time" => "%s",
	"mathematicians" => "%s",
	"physicists" => "%s"
);""" % (line[0], line[1], line[2])

print "?>"
