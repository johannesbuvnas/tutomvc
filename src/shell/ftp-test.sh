#!/bin/bash

clear
if [ $# -lt 3 ]
	then
	echo "ERROR: Bad usage."
	echo "Usage: `basename $0` ftp-address ftp-port username password"
	exit 1
elif [ -z $1 ] || [ -z $2 ] || [ -z $3 ] || [ -z $4 ]
	then
	echo "ERROR: Some parameter is NULL."
	echo "Usage: `basename $0` ftp-address ftp-port username password"
	exit 1
fi

address="$1"
username="$3"
password="$4"

#echo $address $username $password
#exit

ftp -nv "$address" $2 <<END_SCRIPT
    quote USER "$username"
    quote PASS "$password"
    prompt off
    ls
    bye
END_SCRIPT
exit 0