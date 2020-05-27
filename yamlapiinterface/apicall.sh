#!/bin/bash 
# loadbalancer.org api/v2 curl apicall
# Created by Andruw Smalley 
# really simple curl command build from the input, no real validation.
username="loadbalancer"
password="loadbalancer"
port="9443"

while true; do
  case "$1" in
    -l | --loadbalancer ) loadbalancer="$2"; shift 2 ;;
    -u | --username ) username="$2"; shift 2 ;;
    -p | --password ) password="$2"; shift 2 ;;
    -o | --port ) port="$2"; shift 2 ;;
    -y | --yaml ) yaml="$2"; shift 2 ;;
    -a | --apikey ) apikey=$(echo $2 | base64); shift 2 ;;
    * ) break ;;
  esac
done

#if [ $loadbalancer != "" ] || [ $port != "" ] || [ $username != "" ] || [ $password != "" ] || [ $yaml != "" ] || [ $apikey != "" ]; then
	curl -u ${username}:${password} -X POST  \
	     --header "X_LB_APIKEY: ${apikey}" \
	     --data-binary @${yaml} https://${loadbalancer}:${port}/api/v2/yaml/ -k
	exit $?
#else
#	echo "./apicall.sh --loadbalancer 192.168.2.21 --port 9443 --username loadbalancer --password loadbalancer --yaml /path/to/yaml.yaml --apikey eP68pvSMM8dvn051LL4d35569d438ue0"
#	echo "Defaults are set for username,password,port so you only really need --yaml --apikey and --loadbalancer if you have defaults"
#fi
