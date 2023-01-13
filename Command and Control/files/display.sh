#!/bin/bash

# Logan Miller
# Miller Cyber Technologies LLC 
# 2023

# This script will display a website in kiosk mode on device startup

# First check to see if a url is in the /home/pi/url.txt file
if [ -f /home/pi/url.txt ]; then
    URL=$(cat /home/pi/url.txt)
else
    # If the file is empty, fetch the url from the command server
    URL=$(curl -s http://******/remote_adm.php?password=******&mac=$(cat /sys/class/net/eth0/address)&action=geturl)
    # Check the url to see if it returned as "unknown"
    if [ $URL == "unknown" ]; then
        # If the url is unknown, set the url to the default url
        URL="http://******/default.html"
    fi
    #save this url to the file incase server is offline
    echo $URL > /home/pi/url.txt
fi

#start the browser in kiosk mode
chromium-browser --noerrdialogs --disable-session-crashed-bubble --disable-infobars --incognito --kiosk $URL &

# Hide the cursor
unclutter -idle 0.5 -root &

# Ensure that the X server cursor is hidden when Chromium is running
xserver-command -cursor_name left_ptr