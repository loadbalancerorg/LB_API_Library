#!/bin/bash
# loadbalancer.org api/v2 curl apicall
# Created by Andruw Smalley 
# really simple curl command build from the input, no real validation.
while true; do
  case "$1" in
    -l | --loadbalancer ) loadbalancer="$2"; shift 2 ;;
    -u | --username ) username="$2"; shift 2 ;;
    -p | --password ) password="$2"; shift 2 ;;
    -j | --json ) json="$2"; shift 2 ;;
    -a | --apikey ) apikey==$(echo $2 | base64); shift 2 ;;
    * ) break ;;
  esac
done
if [ $loadbalancer != "" ] || [ $username != "" ] || [ $password != "" ] || [ $json != "" ] || [ $apikey != "" ]; then
	curl -u ${username}:${password} -X POST  \
	     --header "X_LB_APIKEY: ${apikey}" \
	     --header Content-Type:application/json \
	     -d @${json} https://${loadbalancer}:9443/api/v2/ -k
else
	echo "./apicall.sh --loadbalancer 192.168.2.21 --username loadbalancer --password loadbalancer --json /path/to/json.json --apikey eP68pvSMM8dvn051LL4d35569d438ue0"
fi
