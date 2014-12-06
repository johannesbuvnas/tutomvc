#!/bin/bash

# Check for correct parameters
clear
if [ $# -lt 10 ]
	then
	echo "ERROR: Bad usage."
	echo "Usage: `basename $0` repository-path since-revision from-path deploy-from-scratch ftp-address ftp-port username password ftp-path object-id"
	echo $1 $2 $3 $4 $5 $6 $7 $8 $9 ${10}
	exit 1
elif [ -z $1 ]
	then
	echo "ERROR: Some parameter is NULL."
	exit 1
fi

# SETUP VARIABLES
shellDir=`dirname $0`
objectID=${10}
queue=0
configFile="$shellDir/config.sh"
source $configFile
shellGitListChanges="$shellDir/git-l-changes.sh"
chmod +x $shellGitListChanges
shellFTPUpload="$shellDir/ftp-upload.sh"
chmod +x $shellFTPUpload
shellFTPCommand="$shellDir/ftp-command.sh"
chmod +x $shellFTPCommand
shellFTPDelete="$shellDir/ftp-delete.sh"
chmod +x $shellFTPDelete

fileList=`$shellGitListChanges $1 $2 $3 $4 | sort -u`

IFS=$'\n'
unsortedDirs="$1/../server_dirs_unsorted.$$"
sortedDirs="$1/../server_dirs.$$"

urlencode() {
    local length="${#1}"
    for (( i = 0; i < length; i++ )); do
        local c="${1:i:1}"
        case $c in
            [a-zA-Z0-9.~_-]) printf "$c" ;;
            *) printf '%%%02X' "'$c"
        esac
    done
}

cd $1
IFS=$'\n'
for file in $fileList
do
    queue=$(($queue+1))
    if [ -f $file ]
    then
        dirName=`dirname $file`
        dirName="$9/$dirName"

        rdirName=""
        IFS=$'/'

        for dir in $dirName
        do
            unset IFS

            if [ "$dir" != "." ]
            then
                if [ -z $rdirName ]
                then
                    rdirName="$dir"
                else
                    rdirName="$rdirName/$dir"
                fi
                echo $rdirName >> $unsortedDirs
            fi

            IFS=$'/'
        done

        IFS=$'\n'
    fi
done
unset IFS

sort -u < $unsortedDirs > $sortedDirs
IFS=$'\n'
for dir in `cat $sortedDirs`
do
    echo "MKDIR: $dir";
    $shellFTPCommand $5 $6 $7 $8 "mkdir $dir"
done
unset IFS

IFS=$'\n'
i=0
for file in $fileList
do
    i=$((i+1))
    if [ -f $file ]
    then
        echo "UPLOAD: $9/$file"
        fileSize=$( wc -c "$file" | awk '{print $1}' )
        $shellFTPUpload $5 $6 $7 $8 "$9/$file" "$file"
        result=$?
        curl --data "action=$postActionTransfer&objectID=$objectID&transferFile=`urlencode $file`&transferAction=upload&transferFileSize=$fileSize&transferExitCode=$result&transferQueueID=$i&transferTotals=$queue" $siteURL
#        exit
    elif [ ! -f $file ] || [ ! -d $file ]
    then
        echo "DELETE: $9/$file"
        fileSize=0
        $shellFTPDelete $5 $6 $7 $8 "$9/$file"
        result=$?
        curl --data "action=$postActionTransfer&objectID=$objectID&transferFile=`urlencode $file`&transferAction=delete&transferFileSize=$fileSize&transferExitCode=$result&transferQueueID=$i&transferTotals=$queue" $siteURL
#        exit
    fi
done
unset IFS

# CLEAN UP
rm -f $unsortedDirs
rm -f $sortedDirs

# DONE
echo "DONE"
curl --data "action=$postAction&objectID=$objectID&exitCode=0" $siteURL
exit