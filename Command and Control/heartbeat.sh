#!/bin/bash

# Script created by Logan Miller on 1/3/2023

# This script is used to send a heartbeat to a central server
# It also checks if the server has pending commands to run on this machine

# The server URL
SERVER="http://server.com"

# The server port
PORT="80"

# The server path
PATH="/control.php"

# The server password
PASSWORD="password"

# The Client ID is used to identify the client
# This is the devices MAC Address
ID=`ifconfig | grep HWaddr | awk '{print $5}'`

# The Client IP is used to identify the client
# This is the devices IP Address
IP=`ifconfig | grep "inet addr" | awk '{print $2}' | cut -d ":" -f 2`

# Check if the server has any commands for this client
# If it does, run them
wget -q -O - $SERVER:$PORT$PATH?password=$PASSWORD\&id=$ID\&ip=$IP\&action=check | bash

# Send a heartbeat to the server
wget -q -O - $SERVER:$PORT$PATH?password=$PASSWORD\&id=$ID\&ip=$IP\&action=heartbeat

# Echo successful command execution
echo "Heartbeat sent to $SERVER:$PORT$PATH"


# Run this script every 5 minutes
# */5 * * * * /path/to/heartbeat.sh
