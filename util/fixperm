#!/bin/bash
#23456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789

# Arguments
[ $# -lt 2 -o "${1##*-}" == 'help' ] && cat <<EOF && exit
USAGE: ${0##*/} {-s|-v|-d|-p} <directory> [<user>] [<group>]

SYNOPSIS:
 Fix file permissions for the specified directory, according to its type:
  -s -- source directory (ex: */{cgi-bin,htdocs,etc})
  -v -- variable data directory (ex: */{cache,data,log})
  -d -- documentation directory
  -p -- private directory

 If user/group are specified, fix the owner/group of the specified directory.
 On Debian systems, it is recommended to use the 'root' user and 'www-data'
 group for maximum security.
EOF
FIX_TYPE="$1"
FIX_DIRECTORY="$2"
[ $# -ge 3 ] && FIX_USER="$3"
[ $# -ge 4 ] && FIX_GROUP="$4"

# Fix permissions
if [ -n "${FIX_USER}" -o -n "${FIX_GROUP}" ]; then
  echo "Fixing permissions (user=${FIX_USER}; group=${FIX_GROUP})"
  if [ -n "${FIX_USER}" ]; then
    chown -R ${FIX_USER} "${FIX_DIRECTORY}"
    [ $? -ne 0 ] && echo "ERROR: failed to change user attribute on output directory; Directory: ${FIX_DIRECTORY}" && exit
  fi
  if [ -n "${FIX_GROUP}" ]; then
    chgrp -R ${FIX_GROUP} "${FIX_DIRECTORY}"
    [ $? -ne 0 ] && echo "ERROR: failed to change group attribute on output directory; Directory: ${FIX_DIRECTORY}" && exit
  fi
  case "${FIX_TYPE}" in
    '-s')
      find "${FIX_DIRECTORY}" -type d -exec chmod 510 {} \;
      find "${FIX_DIRECTORY}" -type f -exec chmod 440 {} \;
      find "${FIX_DIRECTORY}" -type f -name "*.sh" -exec chmod 550 {} \;
      ;;

    '-v')
      find "${FIX_DIRECTORY}" -type d -exec chmod 6770 {} \;
      find "${FIX_DIRECTORY}" -type f -exec chmod 660 {} \;
      ;;

    '-d')
      find "${FIX_DIRECTORY}" -type d -exec chmod 550 {} \;
      find "${FIX_DIRECTORY}" -type f -exec chmod 440 {} \;
      ;;

    '-p')
      chmod -R go= "${FIX_DIRECTORY}"
      ;;
  esac
else
  echo "Fixing permissions (no user/group supplied)"
  case "${FIX_TYPE}" in
    '-s')
      find "${FIX_DIRECTORY}" -type d -exec chmod 711 {} \;
      find "${FIX_DIRECTORY}" -type f -exec chmod 644 {} \;
      find "${FIX_DIRECTORY}" -type f -name "*.sh" -exec chmod 755 {} \;
      ;;

    '-v')
      find "${FIX_DIRECTORY}" -type d -exec chmod 6777 {} \;
      find "${FIX_DIRECTORY}" -type f -exec chmod 666 {} \;
      ;;

    '-d')
      find "${FIX_DIRECTORY}" -type d -exec chmod 755 {} \;
      find "${FIX_DIRECTORY}" -type f -exec chmod 644 {} \;
      ;;

    '-p')
      chmod -R o= "${FIX_DIRECTORY}"
      ;;
  esac
fi

# Cleanup
find "${FIX_DIRECTORY}" -type f -name "semantic.cache" -exec rm {} \;
find "${FIX_DIRECTORY}" -type f -name "*~" -exec rm {} \;
find "${FIX_DIRECTORY}" -type f -name "#*#" -exec rm {} \;
