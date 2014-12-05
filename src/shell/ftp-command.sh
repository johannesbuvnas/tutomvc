#!/bin/bash

# Check for correct parameters
clear
if [ $# -lt 5 ]
	then
	echo "ERROR: Bad usage."
	echo "Usage: `basename $0` ftp-address ftp-port username password command"
	exit 1
elif [ -z $1 ] || [ -z $2 ] || [ -z $3 ] || [ -z $4 ] || [ -z $5 ]
	then
	echo "ERROR: Some parameter is NULL."
	exit 1
fi

address="$1"
port="$2"
username="$3"
password="$4"

ftp -nv "$address" $port <<END_SCRIPT
    quote USER "$username"
    quote PASS "$password"
    prompt off
    $5
    bye
END_SCRIPT
exit