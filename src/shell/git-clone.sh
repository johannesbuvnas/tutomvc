#!/bin/bash

# Check for correct parameters
clear
if [ $# -lt 5 ]
	then
	echo "ERROR: Bad usage."
	echo "Usage: `basename $0` clone-to-path git-ssh-url branch ssh-key ssh-key-passphrase object-id"
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
shellSSHAdd="$shellDir/ssh-add.sh"

# Start agent
ssh-agent bash
# Delete keys
ssh-add -D
# Add key
chmod +x $shellSSHAdd
$shellSSHAdd $4 $5

result=$?

if [ $result -ne 0 ]
then
    echo "Failed to add SSH key.";
    exit 1
fi


if [ ! -d $1 ]
	then
	mkdir $1
fi

expect << EOF
	set timeout 1
	# log_user 0
	spawn git clone -b $3 $2 $1
	expect {
		timeout {
			exp_continue
		}
		"fatal: Could not read from remote repository" {
		    exit 3
        }
        "fatal" {
			exit 2
		}
		"ERROR" {
			exit 2
		}
		eof {
			exit 0
		}
	}
EOF

result=$?

curl --data "action=$postAction&objectID=$6&exitCode=$result" $siteURL

case $result in
	0)
		echo "Success.";;
	*)
		echo "Failed.";;
esac

exit $result