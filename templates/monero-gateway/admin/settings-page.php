<?php foreach($errors as $error): ?>
<div class="error"><p><strong>Monero Gateway Error</strong>: <?php echo $error; ?></p></div>
<?php endforeach; ?>

<h1>Monero Gateway Settings</h1>

<?php if($confirm_type === 'monero-wallet-rpc'): ?>
<div style="border:1px solid #ddd;padding:5px 10px;">
    <?php
         echo 'Wallet height: ' . $balance['height'] . '</br>';
         echo 'Your balance is: ' . $balance['balance'] . '</br>';
         echo 'Unlocked balance: ' . $balance['unlocked_balance'] . '</br>';
         ?>
</div>
<?php endif; ?>

<table class="form-table">
    <?php echo $settings_html ?>
</table>

<h4><a href="https://github.com/monero-integrations/monerowp">Learn more about using the Monero payment gateway</a></h4>

<script>
function moneroUpdateFields() {
    var confirmType = jQuery("#woocommerce_monero_gateway_confirm_type").val();
    if(confirmType == "monero-wallet-rpc") {
        jQuery("#woocommerce_monero_gateway_monero_address").closest("tr").hide();
        jQuery("#woocommerce_monero_gateway_viewkey").closest("tr").hide();
        jQuery("#woocommerce_monero_gateway_daemon_host").closest("tr").show();
        jQuery("#woocommerce_monero_gateway_daemon_port").closest("tr").show();
    } else {
        jQuery("#woocommerce_monero_gateway_monero_address").closest("tr").show();
        jQuery("#woocommerce_monero_gateway_viewkey").closest("tr").show();
        jQuery("#woocommerce_monero_gateway_daemon_host").closest("tr").hide();
        jQuery("#woocommerce_monero_gateway_daemon_port").closest("tr").hide();
    }
    var useMoneroPrices = jQuery("#woocommerce_monero_gateway_use_monero_price").is(":checked");
    if(useMoneroPrices) {
        jQuery("#woocommerce_monero_gateway_use_monero_price_decimals").closest("tr").show();
    } else {
        jQuery("#woocommerce_monero_gateway_use_monero_price_decimals").closest("tr").hide();
    }
}
moneroUpdateFields();
jQuery("#woocommerce_monero_gateway_confirm_type").change(moneroUpdateFields);
jQuery("#woocommerce_monero_gateway_use_monero_price").change(moneroUpdateFields);
</script>

<style>
#woocommerce_monero_gateway_monero_address,
#woocommerce_monero_gateway_viewkey {
    width: 100%;
}
</style>