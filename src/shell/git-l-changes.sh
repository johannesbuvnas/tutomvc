#!/bin/bash

clear
if [ $# -lt 1 ]
	then
	echo "ERROR: Bad usage."
	echo "Usage: $0 repository-path since-commit"
	exit 1
elif [ -z $1 ]
	then
	echo "ERROR: Some parameter is NULL."
	echo "Usage: $0 repository-path since-commit"
	exit 1
fi

cd $1
if [ ! -d ./.git ]
then
    echo "Git is not initialized."
    exit 1
fi

if [ -z $2 ]
then
    latestCommit=$(git log --pretty=format:'%H' -n 1)
else
    latestCommit=$2
    search=$(git log $latestCommit --pretty=format:'%H' --max-count=1)
    if [ "$latestCommit" != "$search" ]
    then
        echo "Commit $latestCommit doesn't exists"
        exit 1
    fi
fi

totalCommits=$(git rev-list HEAD --count)

for(( commitsBehind = 0; commitsBehind <= $totalCommits; commitsBehind++))
do
    iplus1=$commitsBehind+1
    commit=$(git log --pretty=format:'%H' -n $iplus1 --skip=$commitsBehind --max-count=1)
    if [ "$commit" = $latestCommit ]
    then
        break
    else
        git diff-tree --no-commit-id --name-only -r $commit
    fi
done

#echo $commitsBehind commit\(s\) behind
exit