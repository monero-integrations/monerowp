<?php
/*
 * Copyright (c) 2018, Ryo Currency Project
 * Admin interface for Monero gateway
 * Authors: mosu-forge
 */

defined( 'ABSPATH' ) || exit;

require_once('class-monero-admin-payments-list.php');

if (class_exists('Monero_Admin_Interface', false)) {
    return new Monero_Admin_Interface();
}

class Monero_Admin_Interface {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'meta_boxes'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_head', array( $this, 'admin_menu_update'));
    }

    /**
     * Add meta boxes.
     */
    public function meta_boxes() {
        add_meta_box(
            'monero_admin_order_details',
            __('Monero Gateway','monero_gateway'),
            array($this, 'meta_box_order_details'),
            'shop_order',
            'normal',
            'high'
        );
    }

    /**
     * Meta box for order page
     */
    public function meta_box_order_details($order) {
        Monero_Gateway::admin_order_page($order);
    }

    /**
     * Add menu items.
     */
    public function admin_menu() {
        add_menu_page(
            __('Monero', 'monero_gateway'),
            __('Monero', 'monero_gateway'),
            'manage_woocommerce',
            'monero_gateway',
            array($this, 'orders_page'),
            MONERO_GATEWAY_PLUGIN_URL.'/assets/images/monero-icon-admin.png',
            56 // Position on menu, woocommerce has 55.5, products has 55.6
        );

        add_submenu_page(
            'monero_gateway',
            __('Payments', 'monero_gateway'),
            __('Payments', 'monero_gateway'),
            'manage_woocommerce',
            'monero_gateway_payments',
            array($this, 'payments_page')
        );

        $settings_page = add_submenu_page(
            'monero_gateway',
            __('Settings', 'monero_gateway'),
            __('Settings', 'monero_gateway'),
            'manage_options',
            'monero_gateway_settings',
            array($this, 'settings_page')
        );
        add_action('load-'.$settings_page, array($this, 'settings_page_init'));
    }

    /**
     * Remove duplicate sub-menu item
     */
    public function admin_menu_update() {
        global $submenu;
        if (isset($submenu['monero_gateway'])) {
            unset($submenu['monero_gateway'][0]);
        }
    }

    /**
     * Monero payments page
     */
    public function payments_page() {
        $payments_list = new Monero_Admin_Payments_List();
        $payments_list->prepare_items();
        $payments_list->display();
    }

    /**
     * Monero settings page
     */
    public function settings_page() {
        WC_Admin_Settings::output();
    }

    public function settings_page_init() {
        global $current_tab, $current_section;

        $current_section = 'monero_gateway';
        $current_tab = 'checkout';

        // Include settings pages.
        WC_Admin_Settings::get_settings_pages();

        // Save settings if data has been posted.
        if (apply_filters("woocommerce_save_settings_{$current_tab}_{$current_section}", !empty($_POST))) {
            WC_Admin_Settings::save();
        }

        // Add any posted messages.
        if (!empty($_GET['wc_error'])) {
            WC_Admin_Settings::add_error(wp_kses_post(wp_unslash($_GET['wc_error'])));
        }

        if (!empty($_GET['wc_message'])) {
            WC_Admin_Settings::add_message(wp_kses_post(wp_unslash($_GET['wc_message'])));
        }

        do_action('woocommerce_settings_page_init');
    }

}

return new Monero_Admin_Interface();
