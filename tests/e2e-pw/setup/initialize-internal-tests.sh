#!/bin/bash

# Print the script name.
echo $(basename "$0")

echo "Installing latest build of monerowp"
wp plugin install ./setup/monerowp.latest.zip --activate --force

wp cron event run --due-now

# Need to get the exchange rate immediately.
wp eval 'do_action("monero_update_event");'
