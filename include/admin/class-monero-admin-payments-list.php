<?php
/*
 * Copyright (c) 2018, Ryo Currency Project
 * Admin interface for Monero gateway
 * Authors: mosu-forge
 */

if(!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Monero_Admin_Payments_List extends WP_List_Table {

    function __construct() {
        parent::__construct(array(
            'singular'=> 'payment',
            'plural' => 'payments',
            'ajax'   => false
        ));
    }

    function extra_tablenav($which) {
        if ($which == "top") {
            $hidden_fields = wp_nonce_field() . wp_referer_field();
            $tab_info = array(
                'all' => array(),
                'pending' => array(),
                'paid' => array(),
                'confirmed' => array(),
                'expired' => array(),
            );
            foreach($tab_info as $type=>&$info) {
                $info['active'] = '';
                $info['count'] = $this->get_item_count($type);
            }
            if(isset($_GET['type'])) {
                switch($_GET['type']) {
                case 'all':
                    $tab_info['all']['active'] = 'class="current" aria-current="page"';
                    break;
                case 'pending':
                    $tab_info['pending']['active'] = 'class="current" aria-current="page"';
                    break;
                case 'paid':
                    $tab_info['paid']['active'] = 'class="current" aria-current="page"';
                    break;
                case 'confirmed':
                    $tab_info['confirmed']['active'] = 'class="current" aria-current="page"';
                    break;
                case 'expired':
                    $tab_info['expired']['active'] = 'class="current" aria-current="page"';
                    break;
                }
            } else {
                $tab_info['all']['active'] = 'class="current" aria-current="page"';
            }
            if(Monero_Gateway::get_confirm_type() == 'monero-wallet-rpc') {
                $balance = Monero_Gateway::admin_balance_info();
                $balance_info = <<<HTML
<div style="border:1px solid #ddd;padding:5px 10px;">
    Wallet height: {$balance['height']}</br>
    Your balance is: {$balance['balance']}</br>
    Unlocked balance: {$balance['unlocked_balance']}</br>
</div>

HTML;
            } else {
                $balance_info = '';
            }
            echo <<<HTML
            <div class="wrap">
                <h1 class="wp-heading-inline">Monero Payments</h1>
                $balance_info
                <hr class="wp-header-end">
                <ul class="subsubsub">
                    <li>
                        <a href="?page=monero_gateway_payments&type=all" {$tab_info['all']['active']}>
                            All <span class="count">({$tab_info['all']['count']})</span>
                        </a> |
                    </li>
                    <li style="display:none">
                        <a href="?page=monero_gateway_payments&type=pending" {$tab_info['pending']['active']}>
                            Pending <span class="count">({$tab_info['pending']['count']})</span>
                        </a> |
                    </li>
                    <li>
                        <a href="?page=monero_gateway_payments&type=paid" {$tab_info['paid']['active']}>
                            Received <span class="count">({$tab_info['paid']['count']})</span>
                        </a> |
                    </li>
                    <li>
                        <a href="?page=monero_gateway_payments&type=confirmed" {$tab_info['confirmed']['active']}>
                            Confirmed <span class="count">({$tab_info['confirmed']['count']})</span>
                        </a> |
                    </li>
                    <li>
                        <a href="?page=monero_gateway_payments&type=expired" {$tab_info['expired']['active']}>
                            Expired <span class="count">({$tab_info['expired']['count']})</span>
                        </a>
                    </li>
                </ul>
                <form id="monero-payments-filter" method="get" style="display:none">
                    <p class="search-box">
                        <label class="screen-reader-text" for="post-search-input">Search payments:</label>
                        <input type="search" id="post-search-input" name="s" value="">
                        <input type="submit" id="search-submit" class="button" value="Search payments">
                    </p>
                    $hidden_fields
                </form>
                <h2 class="screen-reader-text">Monero Payments List</h2>
                <style>
                    #col_order_id { width: 150px; }
                    #col_payment_id { width: 150px; }
                    #col_height { width: 100px; }
                    #col_amount { width: 150px; }
                </style>
                <table class="wp-list-table widefat fixed striped posts">

HTML;
        } else if ($which == "bottom") {
            echo '<br class="clear"></div>';
        }
    }

    /**
     * Get column value.
     *
     * @param mixed  $item Item being displayed.
     * @param string $column_name Column name.
     */
    public function column_default($item, $column_name) {

        switch($column_name) {
        case 'col_order_id':
            echo $this->get_order_link($item->order_id);
            break;
        case 'col_payment_id':
            echo $item->payment_id;
            break;
        case 'col_txid':
            $url = MONERO_GATEWAY_EXPLORER_URL.'/tx/'.$item->txid;
            echo '<a href="'.$url.'" target="_blank">'.$item->txid.'</a>';
            break;
        case 'col_height':
            echo $item->height;
            break;
        case 'col_amount':
            echo Monero_Gateway::format_monero($item->amount).' Monero';
            break;
        }
    }

    protected function get_order_link($order_id) {
        $order = new WC_Order($order_id);
        $buyer = '';

        if($order->get_billing_first_name() || $order->get_billing_last_name()) {
            $buyer = trim(sprintf(_x('%1$s %2$s', 'full name', 'woocommerce'), $order->get_billing_first_name(), $order->get_billing_last_name()));
        } else if ($order->get_billing_company()) {
            $buyer = trim($order->get_billing_company());
        } else if ($order->get_customer_id()) {
            $user = get_user_by('id', $order->get_customer_id());
            $buyer = ucwords($user->display_name);
        }

        return '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $order->get_id() ) ) . '&action=edit' ) . '" class="order-view"><strong>#' . esc_attr( $order->get_order_number() ) . ' ' . esc_html( $buyer ) . '</strong></a>';

    }

    function get_columns() {
        return $columns= array(
            'col_order_id' => __('Order'),
            'col_payment_id' => __('Payment ID'),
            'col_txid' => __('Txid'),
            'col_height' => __('Height'),
            'col_amount' => __('Amount'),
        );
    }

    public function get_sortable_columns() {
        return array();
        return $sortable = array(
            'col_order_id' => 'col_order_id',
            'col_payment_id' => 'payment_id',
            'col_txid' => 'txid',
            'col_height' => 'height',
            'col_amount' => 'amount',
        );
    }

    function prepare_items() {

        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        $current_page = absint($this->get_pagenum());

        $per_page = 25;

        $this->get_items($current_page, $per_page);

    }

    public function no_items() {
        esc_html_e('No Monero payments found', 'monero_gateway');
    }

    protected function get_filter_vars() {
        $type = isset($_GET['type']) ? $_GET['type'] : null;
        return (object) array(
            'type' => $type,
        );
    }

    protected function get_item_count($type) {
        global $wpdb;
        $table_name_1 = $wpdb->prefix.'monero_gateway_quotes';
        $table_name_2 = $wpdb->prefix.'monero_gateway_quotes_txids';
        $query_where = ' WHERE 1=1 '.$this->get_clause_type($type);
        $query = "SELECT COUNT(*) AS count FROM {$table_name_2} t2 LEFT JOIN $table_name_1 t1 ON t2.payment_id = t1.payment_id {$query_where}";
        $item_count = $wpdb->get_var($query);
        if(is_null($item_count)) $item_count = 0;
        return $item_count;
    }

    protected function get_clause_type($type) {
        global $wpdb;
        switch($type) {
        case 'pending':
            $query_where = $wpdb->prepare(' AND pending = 1 AND paid = 0 ', array());
            break;
        case 'paid':
            $query_where = $wpdb->prepare(' AND paid = 1 AND confirmed = 0 ', array());
            break;
        case 'confirmed':
            $query_where = $wpdb->prepare(' AND confirmed = 1 ', array());
            break;
        case 'expired':
            $query_where = $wpdb->prepare(' AND paid = 0 AND pending = 0 ', array());
            break;
        case 'all':
        default:
            $query_where = ' ';
        }
        return $query_where;
    }

    public function get_items($current_page, $per_page) {
        global $wpdb;

        $this->items = array();
        $filters = $this->get_filter_vars();

        $table_name_1 = $wpdb->prefix.'monero_gateway_quotes';
        $table_name_2 = $wpdb->prefix.'monero_gateway_quotes_txids';

        $query_where = ' WHERE 1=1 ';

        $query_where .= $this->get_clause_type($filters->type);

        $query_order = $wpdb->prepare('ORDER BY id DESC LIMIT %d, %d;', ($current_page-1)*$per_page, $per_page);

        $query = "SELECT t1.order_id, t1.confirmed, t1.paid, t1.pending, t2.* FROM {$table_name_2} t2 LEFT JOIN $table_name_1 t1 ON t2.payment_id = t1.payment_id {$query_where} {$query_order}";

        $this->items = $wpdb->get_results($query);

        $max_items = $this->get_item_count($filters->type);

        $this->set_pagination_args(
            array(
                'total_items' => $max_items,
                'per_page'    => $per_page,
                'total_pages' => ceil($max_items/$per_page),
            )
        );
    }

}
