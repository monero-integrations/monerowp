<?php
/**
 * library.php
 *
 * Written using the JSON RPC specification -
 * http://json-rpc.org/wiki/specification
 *
 * @author Kacper Rowinski <krowinski@implix.com>
 * http://implix.com
 * Modified to work with monero-rpc wallet by Serhack and cryptochangements
 */
class Monero_Library
{
    protected $url = null, $is_debug = false, $parameters_structure = 'array';
    private $username;
    private $password; 
    protected $curl_options = array(
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 8
    );
    
    
    private $httpErrors = array(
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        408 => '408 Request Timeout',
        500 => '500 Internal Server Error',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable'
    );
   
    public function __construct($pUrl, $pUser, $pPass)
    {
        $this->url = $pUrl;
		$this->username = $pUser;
		$this->password = $pPass;
    }
    
    public function requestDaemon($method, $params = null)
	{
		$request1 = json_encode(array('jsonrpc' => '2.0', 'id' => '0', 'method' => $method, 'params' => $params));
		$response = wp_remote_post( 'http://' . $this->url, array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking' => true,
			'headers' => array('Content-type' => 'application/json'),
			'body' => $request1
			));

		if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		return $error_message;
		}
		else {
			return $response;
		}
	}
    
	public function _run($method, $params = null)
	{
       $result = $this->requestDaemon($method, $params);
       $decode = json_decode($result['body'], true);
       return $decode; //the result is returned as an array
    }
    
    //prints result as json
    public function _print($json)
    {
        $json_encoded = json_encode($json,  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo $json_encoded;
    }
    
    /* 
     * The following functions can all be called to interact with the monero rpc wallet
     * They will majority of them will return the result as an array
     * Example: $daemon->address(); where $daemon is an instance of this class, will return the wallet address as string within an array
     */
    public function address()
    {
        $result = $this->_run('getaddress');
        $address = $result['result']['address'];
        return $address;
    }
    
    public function getbalance()
    {
         $result = $this->_run('getbalance');
         $balance = $result['result']['balance'];
         return $balance;
    }
    
    public function getbalance_unlocked()
    {
         $result = $this->_run('getbalance');
         $balance = $result['result']['unlocked_balance'];
         return $balance;
    }
    
    public function getheight()
    {
         $result = $this->_run('getheight');
         $height = $result['result']['height'];
         return $height;
    }
    
    public function incoming_transfer($type)
    {
        $incoming_parameters = array('transfer_type' => $type);
        $incoming_transfers = $this->_run('incoming_transfers', $incoming_parameters);
        return $incoming_transfers;
    }
    
	public function get_transfers($input_type, $input_value)
	{
        $get_parameters = array($input_type => $input_value);
        $get_transfers = $this->_run('get_transfers', $get_parameters);
        return $get_transfers;
    }
    
    public function view_key()
    {
        $query_key = array('key_type' => 'view_key');
        $query_key_method = $this->_run('query_key', $query_key);
        return $query_key_method;
     }
     
     /* A payment id can be passed as a string
        A random payment id will be generatd if one is not given */
    public function make_integrated_address($payment_id)
    {
        $integrate_address_parameters = array('payment_id' => $payment_id);
        $result = $this->_run('make_integrated_address', $integrate_address_parameters);
        $integrated_address = $result['result']['integrated_address'];
        return $integrated_address;
    }
    
    public function split_integrated_address($integrated_address)
    {
        if(!isset($integrated_address)){
            echo "Error: Integrated_Address mustn't be null";
        }
        else{
			$split_params = array('integrated_address' => $integrated_address);
			$split_methods = $this->_run('split_integrated_address', $split_params);
			return $split_methods;
        }
    }
    
    public function make_uri($address, $amount, $recipient_name = null, $description = null)
    {
        // If I pass 1, it will be 0.0000001 xmr. Then 
        $new_amount = $amount * 100000000;
       
        $uri_params = array('address' => $address, 'amount' => $new_amount, 'payment_id' => '', 'recipient_name' => $recipient_name, 'tx_description' => $description);
        $uri = $this->_run('make_uri', $uri_params);
        return $uri;
    }
    
    public function parse_uri($uri)
    {
        $uri_parameters = array('uri' => $uri);
        $parsed_uri = $this->_run('parse_uri', $uri_parameters);
        return $parsed_uri;
    }
    
    public function transfer($amount, $address, $mixin = 4)
    {
        $new_amount = $amount  * 1000000000000;
        $destinations = array('amount' => $new_amount, 'address' => $address);
        $transfer_parameters = array('destinations' => array($destinations), 'mixin' => $mixin, 'get_tx_key' => true, 'unlock_time' => 0, 'payment_id' => '');
        $transfer_method = $this->_run('transfer', $transfer_parameters);
        return $transfer_method;
    }
    
    public function get_payments($payment_id)
    {
		$get_payments_parameters = array('payment_id' => $payment_id);
		$get_payments = $this->_run('get_payments', $get_payments_parameters);
		return $get_payments;
	}
	
	public function get_bulk_payments($payment_id, $min_block_height)
	{
      $get_bulk_payments_parameters = array('payment_id' => $payment_id, 'min_block_height' => $min_block_height);
      $get_bulk_payments = $this->_run('get_bulk_payments', $get_bulk_payments_parameters);
      return $get_bulk_payments;
	}
} 
