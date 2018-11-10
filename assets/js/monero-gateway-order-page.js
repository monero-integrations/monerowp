/*
 * Copyright (c) 2018, Ryo Currency Project
*/
function monero_showNotification(message, type='success') {
    var toast = jQuery('<div class="' + type + '"><span>' + message + '</span></div>');
    jQuery('#monero_toast').append(toast);
    toast.animate({ "right": "12px" }, "fast");
    setInterval(function() {
        toast.animate({ "right": "-400px" }, "fast", function() {
            toast.remove();
        });
    }, 2500)
}
function monero_showQR(show=true) {
    jQuery('#monero_qr_code_container').toggle(show);
}
function monero_fetchDetails() {
    var data = {
        '_': jQuery.now(),
        'order_id': monero_details.order_id
    };
    jQuery.get(monero_ajax_url, data, function(response) {
        if (typeof response.error !== 'undefined') {
            console.log(response.error);
        } else {
            monero_details = response;
            monero_updateDetails();
        }
    });
}

function monero_updateDetails() {

    var details = monero_details;

    jQuery('#monero_payment_messages').children().hide();
    switch(details.status) {
        case 'unpaid':
            jQuery('.monero_payment_unpaid').show();
            jQuery('.monero_payment_expire_time').html(details.order_expires);
            break;
        case 'partial':
            jQuery('.monero_payment_partial').show();
            jQuery('.monero_payment_expire_time').html(details.order_expires);
            break;
        case 'paid':
            jQuery('.monero_payment_paid').show();
            jQuery('.monero_confirm_time').html(details.time_to_confirm);
            jQuery('.button-row button').prop("disabled",true);
            break;
        case 'confirmed':
            jQuery('.monero_payment_confirmed').show();
            jQuery('.button-row button').prop("disabled",true);
            break;
        case 'expired':
            jQuery('.monero_payment_expired').show();
            jQuery('.button-row button').prop("disabled",true);
            break;
        case 'expired_partial':
            jQuery('.monero_payment_expired_partial').show();
            jQuery('.button-row button').prop("disabled",true);
            break;
    }

    jQuery('#monero_exchange_rate').html('1 XMR = '+details.rate_formatted+' '+details.currency);
    jQuery('#monero_total_amount').html(details.amount_total_formatted);
    jQuery('#monero_total_paid').html(details.amount_paid_formatted);
    jQuery('#monero_total_due').html(details.amount_due_formatted);

    jQuery('#monero_integrated_address').html(details.integrated_address);

    if(monero_show_qr) {
        var qr = jQuery('#monero_qr_code').html('');
        new QRCode(qr.get(0), details.qrcode_uri);
    }

    if(details.txs.length) {
        jQuery('#monero_tx_table').show();
        jQuery('#monero_tx_none').hide();
        jQuery('#monero_tx_table tbody').html('');
        for(var i=0; i < details.txs.length; i++) {
            var tx = details.txs[i];
            var height = tx.height == 0 ? 'N/A' : tx.height;
            var row = ''+
                '<tr>'+
                '<td style="word-break: break-all">'+
                '<a href="'+monero_explorer_url+'/tx/'+tx.txid+'" target="_blank">'+tx.txid+'</a>'+
                '</td>'+
                '<td>'+height+'</td>'+
                '<td>'+tx.amount_formatted+' Monero</td>'+
                '</tr>';

            jQuery('#monero_tx_table tbody').append(row);
        }
    } else {
        jQuery('#monero_tx_table').hide();
        jQuery('#monero_tx_none').show();
    }

    // Show state change notifications
    var new_txs = details.txs;
    var old_txs = monero_order_state.txs;
    if(new_txs.length != old_txs.length) {
        for(var i = 0; i < new_txs.length; i++) {
            var is_new_tx = true;
            for(var j = 0; j < old_txs.length; j++) {
                if(new_txs[i].txid == old_txs[j].txid && new_txs[i].amount == old_txs[j].amount) {
                    is_new_tx = false;
                    break;
                }
            }
            if(is_new_tx) {
                monero_showNotification('Transaction received for '+new_txs[i].amount_formatted+' Monero');
            }
        }
    }

    if(details.status != monero_order_state.status) {
        switch(details.status) {
            case 'paid':
                monero_showNotification('Your order has been paid in full');
                break;
            case 'confirmed':
                monero_showNotification('Your order has been confirmed');
                break;
            case 'expired':
            case 'expired_partial':
                monero_showNotification('Your order has expired', 'error');
                break;
        }
    }

    monero_order_state = {
        status: monero_details.status,
        txs: monero_details.txs
    };

}
jQuery(document).ready(function($) {
    if (typeof monero_details !== 'undefined') {
        monero_order_state = {
            status: monero_details.status,
            txs: monero_details.txs
        };
        setInterval(monero_fetchDetails, 30000);
        monero_updateDetails();
        new ClipboardJS('.clipboard').on('success', function(e) {
            e.clearSelection();
            if(e.trigger.disabled) return;
            switch(e.trigger.getAttribute('data-clipboard-target')) {
                case '#monero_integrated_address':
                    monero_showNotification('Copied destination address!');
                    break;
                case '#monero_total_due':
                    monero_showNotification('Copied total amount due!');
                    break;
            }
            e.clearSelection();
        });
    }
});