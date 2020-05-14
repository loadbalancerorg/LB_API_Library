#!/bin/bash

# loadbalancer.org api/v2 curl apicall
# Created by Andruw Smalley 
# really simple curl command build from the input, no real validation.
#
# ./securebydefault.sh --loadbalancer 192.168.2.21 \
#   --username loadbalancer --password loadbalancer \
#   --mode custom --ssh off --httpsonly off --cert server
# Populate defaults so if you do 
# not the setting will not be lost
username="loadbalancer"
password="loadbalancer"
loadbalancer=192.168.2.21
wuiport=9443
root=on
mode=secure  # change to custom 
ssh=on       # off for ssh aceess
cert=server 
httpsonly="on"
ciphers="ECDHE-RSA-AES128-SHA:DHE-RSA-AES256-SHA:AES256-SHA:HIGH:!MD5:!aNULL:!EDH"

while true; do
  case "$1" in
  	-u | --username ) username="$2"; shift 2 ;;
    -p | --password ) password="$2"; shift 2 ;;
    -l | --loadbalancer ) loadbalancer="$2"; shift 2 ;;
    -w | --wuiport ) wuiport=$2; shift 2 ;;
    -m | --mode ) mode="$2"; shift 2 ;;
    -r | --root ) root="$2"; shift 2 ;;
    -s | --ssh ) ssh="$2"; shift 2 ;;
    -c | --cert ) cert="$2"; shift 2 ;;
    -h | --httpsonly ) httpsonly="$2"; shift 2 ;;
    -i | --ciphers ) ciphers="$2"; shift 2 ;;
    * ) break ;;
  esac
done

curl -u ${username}:${password} -X POST \
   --form applianceSecurityMode=${mode} \
   --form disableRootAccess=${root} \
   --form disableSSHPass=${ssh} \
   --form wui_https_only=${httpsonly} \
   --form wui_https_port=${wuiport} \
   --form wui_https_cert=${cert} \
   --form wui_https_ciphers=${ciphers} \
   --form go=Update \
   --insecure -k "https://${loadbalancer}:${wuiport}/lbadmin/config/secure.php?action=edit" -o /dev/null

