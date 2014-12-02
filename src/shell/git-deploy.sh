#!/bin/bash

# Check for correct parameters
clear
if [ $# -lt 4 ]
	then
	echo "ERROR: Bad usage."
	echo "Usage: `basename $0` repository-path since-revision from-path deploy-from-scratch"
	exit 1
elif [ -z $1 ]
	then
	echo "ERROR: Some parameter is NULL."
	exit 1
fi

# SETUP VARIABLES
shellDir=`dirname $0`
configFile="$shellDir/config.sh"
source $configFile
shellGitListChanges="$shellDir/git-l-changes.sh"

chmod +x $shellGitListChanges
$shellGitListChanges $1 $2 $3 $4
exit