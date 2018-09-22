<?php if($details['status'] == 'confirmed'): ?>

<h2 style="color: #96588a; display: block; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;">
    <?php echo $method_title ?>
</h2>

<p style="margin: 0 0 16px;">Your order has been confirmed. Thank you for paying with Monero!</p>

<?php elseif($details['status'] == 'expired' || $details['status'] == 'expired_partial'): ?>

<h2 style="color: #96588a; display: block; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;">
    <?php echo $method_title ?>
</h2>

<p style="margin: 0 0 16px;">Your order has expired. Please place another order to complete your purchase.</p>

<?php elseif($details['status'] == 'unpaid'): ?>

<h2 style="color: #96588a; display: block; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;">
    <?php echo $method_title ?>
</h2>

<p style="margin: 0 0 16px;">Please pay the amount due to complete your transactions. Your order will expire in <?php echo $details['order_expires']; ?> if payment is not received.</p>

<div style="margin-bottom: 40px;">
    <table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; color: #636363; border: 1px solid #e5e5e5; vertical-align: middle;" border="1">
        <tbody>
            <tr>
                <td class="td" style="text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap: break-word; color: #636363; border: 1px solid #e5e5e5; padding: 12px;">
                    PAY TO: <br/>
                    <strong>
                        <?php echo $details['integrated_address']; ?>
                    </strong>
                </td>
            </tr>
            <tr>
                <td class="td" style="text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap: break-word; color: #636363; border: 1px solid #e5e5e5; padding: 12px;">
                    TOTAL DUE: <br/>
                    <strong>
                        <?php echo $details['amount_total_formatted']; ?> XMR
                    </strong>
                </td>
            </tr>
            <tr>
                <td class="td" style="text-align: left; vertical-align: middle; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap: break-word; color: #636363; border: 1px solid #e5e5e5; padding: 12px;">
                    EXCHANGE RATE: <br/>
                    <strong>
                        1 XMR = <?php echo $details['rate_formatted'] . ' ' . $details['currency']; ?>
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php endif; ?>