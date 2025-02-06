#!/bin/sh
commit=`git rev-list --tags --no-walk --max-count=1`
revisioncount=`git rev-list  $commit..HEAD --count`
curbranch=`git rev-parse --abbrev-ref HEAD`
projectversion=`git tag | xargs -I@ git log --format=format:"%ai @%n" -1 @ | sort -r | awk '{print $4}' | head -1`

echo "$projectversion.$curbranch.$revisioncount" > release_version.txt
#`git add .`
#`git commit -m 'repo version updated to $projectversion-$curbranch-$revisioncount'`
#`git push`