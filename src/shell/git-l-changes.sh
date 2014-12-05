#!/bin/bash

clear
if [ $# -lt 1 ]
	then
	echo "ERROR: Bad usage."
	echo "Usage: `basename $0` repository-path since-commit restrict-path output-all"
	exit 1
elif [ -z $1 ]
	then
	echo "ERROR: Some parameter is NULL."
	exit 1
elif [ ! -z $3 ] && [ ! -d $1/$3 ]
then
    # If restrict-path is set and does not exists
    echo "Repository path doesn't exists: $3"
    exit 1
fi

cd $1
if [ ! -d .git ]
then
    echo "Git is not initialized."
    exit 1
fi

if [ ! -z $4 ] && [ $4 = "true" ]
then
    # If output-all is set to "yes", then log all files
    git log --pretty=format: --name-only --diff-filter=A $3 | sort -u
    exit
fi

if [ -z $2 ]
then
    # since-commit is NULL
    # Set since-commit to one back
    # latestCommit=$(git log --pretty=format:'%H' -n 2 --skip=1 --max-count=1)
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
        git diff-tree --no-commit-id --name-only -r $commit $3
        git diff-tree --no-commit-id --name-only -r $commit $3
        break
    else
        git diff-tree --no-commit-id --name-only -r $commit $3
        git diff-tree --no-commit-id --name-only -r $commit $3
    fi
done

#echo $commitsBehind commit\(s\) behind
exit