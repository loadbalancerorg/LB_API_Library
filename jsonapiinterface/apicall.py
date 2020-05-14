#!/usr/bin/env python3
from io import TextIOWrapper
from sys import stdin
from ssl import SSLContext
from json import load
from base64 import b64encode
from getpass import getpass
from argparse import ArgumentParser, FileType
from ipaddress import ip_address, IPv6Address
from urllib.parse import urlunparse
from urllib.request import urlopen, Request, HTTPPasswordMgrWithPriorAuth, HTTPBasicAuthHandler, HTTPSHandler, build_opener

# Parse command line arguments
parser = ArgumentParser (
        description = 'Send JSON to the Loadbalancer.org v2 API.')
parser.add_argument (
        'address',
        type = ip_address,
        help = 'ip address')
parser.add_argument (
        'apikey',
        type = str,
        help = 'shared secret')
parser.add_argument (
        'username',
        type = str,
        help = 'username')
parser.add_argument (
        'password',
        type = str,
        nargs = '?',
        help = 'password, prompts interactively if unspecified')
parser.add_argument (
        '--json',
        type = FileType ('r'),
        default = stdin,
        help = 'file to send, stdin if not specified.')
parser.add_argument (
        '--port',
        type = int,
        default = 9443,
        help = 'port, defaults to 9443')

args = parser.parse_args ()

# Setup the Request
password = args.password
if password is None:
    password = getpass ('Password: ')

address  = str (args.address)
if type (args.address) is IPv6Address:
    address = '[' + address + ']'

url = 'https://{host}:{port}/api/v2/'.format (
        host = address,
        port = args.port)

request = Request (
        url = url,
        method = 'POST',
        data = args.json.read ().encode ('utf-8'),
        headers = {
            'X-LB-APIKEY': b64encode (args.apikey.encode ('utf-8'))
        })

# Create an OpenerDirector that will ignore Certificate errors and
# will _always_ send our username and password with a request.
password_manager = HTTPPasswordMgrWithPriorAuth ()
password_manager.add_password (None, url, args.username, password)
opener = build_opener (
        HTTPBasicAuthHandler (password_manager),
        HTTPSHandler (context = SSLContext ()))

# POST the request and do something with the response
with opener.open (request) as response:
    if response.status != 200:
        exit (response.status)
    result = load (TextIOWrapper (response, 'utf-8'))
    print (result)
    print (result['lbapi'][0]['itteration'][0])

exit (0);

