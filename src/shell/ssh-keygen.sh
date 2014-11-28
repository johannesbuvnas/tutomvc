#!/bin/bash

# Check for correct parameters
clear
if [ $# -lt 2 ]
	then
	echo "ERROR: Bad usage."
	echo "Usage: $0 key-label key-file key-passphrase"
	exit 1
elif [ -z $1 ] || [ -z $2 ] || [ -z $3 ]
	then
	echo "ERROR: key-label, key-path or key-passphrase is NULL."
	echo "Usage: $0 key-label key-file key-passphrase"
	exit 1
fi

keyDir=$(dirname $2)
if [ ! -d $keyDir ]
	then
	echo "Dir doesnt exist, creating it.";
	mkdir -p $keyDir
fi

expect << EOF
	set timeout 1
	spawn ssh-keygen -t rsa -C "$1"
	expect {
		"Enter file" {
			send "$2\r"
		}
	}
	expect {
		"Overwrite (y/n)" {
			send "n\r"
			exit 2
		}
		"Enter passphrase" {
			send "$3\r"
			expect {
				"Enter same" {
					send "$3\r"
					expect "Your identification has been saved" {
						exit 0
					}
				}
			}
		}
	}
	exit 1
EOF

result=$?

if [ $result -eq 0 ]
	then
	cat "$2.pub"
else
    echo "Creating SSH Key failed."
fi

exit $result