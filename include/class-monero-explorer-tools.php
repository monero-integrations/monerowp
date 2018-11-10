<?php
/**
 * monero_explorer_tools.php
 *
 * Uses CURL to call API functions from the block explorer
 * https://xmrchain.net/
 *
 * @author Serhack
 * @author cryptochangements
 * @author mosu-forge
 *
 */

defined( 'ABSPATH' ) || exit;

class Monero_Explorer_Tools
{
    private $url;
    public function __construct($testnet = false)
    {
        $this->url = $testnet ? MONERO_GATEWAY_TESTNET_EXPLORER_URL : MONERO_GATEWAY_MAINNET_EXPLORER_URL;
    }

    private function call_api($endpoint)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->url . $endpoint,
        ));
        $data = curl_exec($curl);
        curl_close($curl);
        return json_decode($data, true);
    }

    public function get_last_block_height()
    {
        $data = $this->call_api('/api/networkinfo');
        if($data['status'] == 'success')
            return $data['data']['height'] - 1;
        else
            return 0;
    }

    public function getheight()
    {
        return $this->get_last_block_height();
    }

    public function get_txs_from_block($height)
    {
        $data = $this->call_api("/api/search/$height");
        if($data['status'] == 'success')
            return $data['data']['txs'];
        else
            return [];
    }

    public function get_outputs($address, $viewkey)
    {
        $data = $this->call_api("/api/outputsblocks?address=$address&viewkey=$viewkey&limit=5&mempool=1");
        if($data['status'] == 'success')
            return $data['data']['outputs'];
        else
            return [];
    }

    public function check_tx($tx_hash, $address, $viewkey)
    {
        $data = $this->call_api("/api/outputs?txhash=$tx_hash&address=$address&viewkey=$viewkey&txprove=0");
        if($data['status'] == 'success') {
            foreach($data['data']['outputs'] as $output) {
                if($output['match'])
                    return true;
            }
        } else {
            return false;
        }
    }

    function get_mempool_txs()
    {
        $data = $this->call_api('/api/mempool');
        if($data['status'] == 'success')
            return $data['txs'];
        else
            return [];
    }

}
