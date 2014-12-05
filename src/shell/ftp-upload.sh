#!/bin/bash

# Check for correct parameters
clear
if [ $# -lt 6 ]
	then
	echo "ERROR: Bad usage."
	echo "Usage: `basename $0` ftp-address ftp-port username password remote-file local-file"
	exit 1
elif [ -z $1 ] || [ -z $2 ] || [ -z $3 ] || [ -z $4 ] || [ -z $5 ] || [ -z $6 ]
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
    set timeout 1
    spawn $shellFTPCommand $1 $2 $3 $4 "put $6 $5"
    expect {
        "221-Goodbye." {
            exit 1
        }
        "226-File successfully transferred" {
            exit 0
        }
        timeout {
            exp_continue
        }
    }
    exit 1
EOF
result=$?

if [ $result -eq 0 ]
then
    echo "SUCCESS"
else
    echo "FAILURE"
fi

exit $result