#!/bin/bash

clear
if [ $# -lt 2 ]
	then
	echo "ERROR: Bad usage."
	echo "Usage: `basename $0` ssh-key ssh-key-passphrase"
	exit 1
elif [ -z $1 ] || [ -z $2 ]
	then
	echo "ERROR: Some parameter is NULL."
	exit 1
fi

expect << EOF
	set timeout 1
#	log_user 0

	spawn ssh-add $1
	expect "Enter passphrase" {
		send "$2\r"
		expect {
			eof {
				exit 0
			}
			"Bad passphrase" {
				exit 2
			}
			timeout {
				exit 1
			}
		}
	}
	exit 1
EOF

result=$?

case $result in
	0)
		echo "Successfully added ssh-key.";
		exit 0;;
	2)
	    echo "ERROR: ssh-add";
		echo "Bad key passphrase.";
		exit 2;;
	*)
	    echo "ERROR: ssh-add";
		echo "Failed.";
		exit 1;;
esac