#!/bin/bash

# Check for correct parameters
clear
if [ $# -lt 4 ]
	then
	echo "ERROR: Bad usage."
	echo "Usage: `basename $0` ftp-address ftp-port username password"
	exit 1
elif [ -z $1 ] || [ -z $2 ] || [ -z $3 ] || [ -z $4 ]
	then
	echo "ERROR: Some parameter is NULL."
	exit 1
fi

# SETUP VARIABLES
shellDir=`dirname $0`
configFile="$shellDir/config.sh"
source $configFile
shellFTPCommand="$shellDir/ftp-command.sh"
chmod +x $shellFTPCommand

expect << EOF
    set timeout 10
    spawn $shellFTPCommand $1 $2 $3 $4 "ls"
    expect {
        "150 Accepted data connection" {
            exit 0
        }
    }
    exit 1
EOF