<?php
namespace YeastarHttp;

class HttpApi
{
	/**
	 * Http protocol for calls
	 * @var string
	 */
	protected $protocol = 'https';

	/**
	 * Host for calls
	 * @var string
	 */
	protected $host = '';

	/**
	 * Http protocol for calls
	 * @var string
	 */
	protected $port = 80;


	/**
	 * Account
	 * @var string
	 */
	protected $account = '';


	/**
	 * Passwword
	 * @var string
	 */
	protected $password = '';


	/**
	 * Class constructor
	 * 
	 * @param array $data Initial property value
	 * 
	 * @return void
	 */
	public function __construct($data = [])
	{
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
	}


	/**
	 * Set the host property
	 * @return void
	 */
	public function setProtocol(string $protocol)
	{
		$this->protocol = $protocol;
	}


	/**
	 * Set the host property
	 * @return void
	 */
	public function setHost(string $host)
	{
		$this->host = $host;
	}


	/**
	 * Set the port property
	 * @return void
	 */
	public function setPort(int $port)
	{
		$this->port = $port;
	}


	/**
	 * Set the account property
	 * @return void
	 */
	public function setAccount(string $account)
	{
		$this->account = $account;
	}


	/**
	 * Set the password property
	 * @return void
	 */
	public function setPassword(string $password)
	{
		$this->password = $password;
	}

	
	/**
	 * Get the full URL for the curl request
	 * @return string The full url
	 */
	public function getUrl()
	{
		return $this->protocol . '://' . $this->host . ':' . $this->port . '/cgi/WebCGI?1500101=account=' . $this->account . '&password=' . $this->password;
	}

	/**
	 * Send sms
	 * 
	 * @return array A full rapresentative response of the curl request
	 */
	public function sendSms($to, $message, $gateway_port = \App\Config::SMS_GATEWAY_PORT)
	{
    $message = urlencode($message);

    $url = $this->getUrl() . "&port=$gateway_port&destination=$to&content=$message";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($curl);
		$err = curl_error($curl);
		$curl_info_data = curl_getinfo($curl);
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$response_header = substr($response, 0, $header_size);
		$response_body = substr($response, $header_size);

		$result = [
			'response' => $response,
			'header' => $response_header,
			'body' => \json_decode($response_body, true),
			'error' => $err,
			'info' => $curl_info_data,
			'url' => $url
		];

		curl_close($curl);
		return $result;
	}
}