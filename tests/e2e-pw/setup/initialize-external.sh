#!/bin/bash

# Script which runs outside Docker

# Print the script name.
echo $(basename "$0")

# This presumes the current working directory is the project root and the directory name matches the plugin slug.
echo "Building monerowp plugin zip"

# Build the plugin
vendor/bin/wp dist-archive . ./tests/e2e-pw/setup/monerowp.latest.zip --plugin-dirname=monerowp

# Configure the environment
wp-env run cli ./setup/initialize-internal.sh;
wp-env run tests-cli ./setup/initialize-internal.sh;
wp-env run cli ./setup/initialize-internal-dev.sh;
wp-env run tests-cli ./setup/initialize-internal-tests.sh;