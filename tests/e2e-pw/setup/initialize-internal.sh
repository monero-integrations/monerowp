#!/bin/bash

# Print the script name.
echo $(basename "$0")

wp plugin activate --all

wp rewrite structure /%year%/%monthnum%/%postname%/ --hard;

if [ "yes" = $(wp option get woocommerce_task_list_complete) ]
then
  echo "Setup already run on container"
else
  echo "Running setup on container"

  wp wc tool run install_pages --user=admin;

  # https://randomadult.com/disable-woocommerce-setup-wizard/
  wp option patch insert woocommerce_onboarding_profile skipped 1
  wp option update woocommerce_show_marketplace_suggestions 'no'
  wp option update woocommerce_allow_tracking 'no'
  wp option update woocommerce_task_list_hidden 'yes'
  wp option update woocommerce_task_list_welcome_modal_dismissed 'yes'

  wp plugin install wordpress-importer --activate
  wp import wp-content/plugins/woocommerce/sample-data/sample_products.xml --authors=create

  wp option update woocommerce_task_list_complete 'yes'
fi