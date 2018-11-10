<?php

defined( 'ABSPATH' ) || exit;

return array(
    'enabled' => array(
        'title' => __('Enable / Disable', 'monero_gateway'),
        'label' => __('Enable this payment gateway', 'monero_gateway'),
        'type' => 'checkbox',
        'default' => 'no'
    ),
    'title' => array(
        'title' => __('Title', 'monero_gateway'),
        'type' => 'text',
        'desc_tip' => __('Payment title the customer will see during the checkout process.', 'monero_gateway'),
        'default' => __('Monero Gateway', 'monero_gateway')
    ),
    'description' => array(
        'title' => __('Description', 'monero_gateway'),
        'type' => 'textarea',
        'desc_tip' => __('Payment description the customer will see during the checkout process.', 'monero_gateway'),
        'default' => __('Pay securely using Monero. You will be provided payment details after checkout.', 'monero_gateway')
    ),
    'discount' => array(
        'title' => __('Discount for using Monero', 'monero_gateway'),
        'desc_tip' => __('Provide a discount to your customers for making a private payment with Monero', 'monero_gateway'),
        'description' => __('Enter a percentage discount (i.e. 5 for 5%) or leave this empty if you do not wish to provide a discount', 'monero_gateway'),
        'type' => __('number'),
        'default' => '0'
    ),
    'valid_time' => array(
        'title' => __('Order valid time', 'monero_gateway'),
        'desc_tip' => __('Amount of time order is valid before expiring', 'monero_gateway'),
        'description' => __('Enter the number of seconds that the funds must be received in after order is placed. 3600 seconds = 1 hour', 'monero_gateway'),
        'type' => __('number'),
        'default' => '3600'
    ),
    'confirms' => array(
        'title' => __('Number of confirmations', 'monero_gateway'),
        'desc_tip' => __('Number of confirms a transaction must have to be valid', 'monero_gateway'),
        'description' => __('Enter the number of confirms that transactions must have. Enter 0 to zero-confim. Each confirm will take approximately four minutes', 'monero_gateway'),
        'type' => __('number'),
        'default' => '5'
    ),
    'confirm_type' => array(
        'title' => __('Confirmation Type', 'monero_gateway'),
        'desc_tip' => __('Select the method for confirming transactions', 'monero_gateway'),
        'description' => __('Select the method for confirming transactions', 'monero_gateway'),
        'type' => 'select',
        'options' => array(
            'viewkey'        => __('viewkey', 'monero_gateway'),
            'monero-wallet-rpc' => __('monero-wallet-rpc', 'monero_gateway')
        ),
        'default' => 'viewkey'
    ),
    'monero_address' => array(
        'title' => __('Monero Address', 'monero_gateway'),
        'label' => __('Useful for people that have not a daemon online'),
        'type' => 'text',
        'desc_tip' => __('Monero Wallet Address (MoneroL)', 'monero_gateway')
    ),
    'viewkey' => array(
        'title' => __('Secret Viewkey', 'monero_gateway'),
        'label' => __('Secret Viewkey'),
        'type' => 'text',
        'desc_tip' => __('Your secret Viewkey', 'monero_gateway')
    ),
    'daemon_host' => array(
        'title' => __('Monero wallet RPC Host/IP', 'monero_gateway'),
        'type' => 'text',
        'desc_tip' => __('This is the Daemon Host/IP to authorize the payment with', 'monero_gateway'),
        'default' => '127.0.0.1',
    ),
    'daemon_port' => array(
        'title' => __('Monero wallet RPC port', 'monero_gateway'),
        'type' => __('number'),
        'desc_tip' => __('This is the Wallet RPC port to authorize the payment with', 'monero_gateway'),
        'default' => '18080',
    ),
    'testnet' => array(
        'title' => __(' Testnet', 'monero_gateway'),
        'label' => __(' Check this if you are using testnet ', 'monero_gateway'),
        'type' => 'checkbox',
        'description' => __('Advanced usage only', 'monero_gateway'),
        'default' => 'no'
    ),
    'onion_service' => array(
        'title' => __(' SSL warnings ', 'monero_gateway'),
        'label' => __(' Check to Silence SSL warnings', 'monero_gateway'),
        'type' => 'checkbox',
        'description' => __('Check this box if you are running on an Onion Service (Suppress SSL errors)', 'monero_gateway'),
        'default' => 'no'
    ),
    'show_qr' => array(
        'title' => __('Show QR Code', 'monero_gateway'),
        'label' => __('Show QR Code', 'monero_gateway'),
        'type' => 'checkbox',
        'description' => __('Enable this to show a QR code after checkout with payment details.'),
        'default' => 'no'
    ),
    'use_monero_price' => array(
        'title' => __('Show Prices in Monero', 'monero_gateway'),
        'label' => __('Show Prices in Monero', 'monero_gateway'),
        'type' => 'checkbox',
        'description' => __('Enable this to convert ALL prices on the frontend to Monero (experimental)'),
        'default' => 'no'
    ),
    'use_monero_price_decimals' => array(
        'title' => __('Display Decimals', 'monero_gateway'),
        'type' => __('number'),
        'description' => __('Number of decimal places to display on frontend. Upon checkout exact price will be displayed.'),
        'default' => 12,
    ),
);
