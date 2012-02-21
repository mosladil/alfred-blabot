<?php

/**
 * Czech Lorem Ipsum from Blabot.net
 * 
 * Does not implement all available API features.
 * For full documentation see Blabot.net
 * 
 * @author	Miroslav Osladil <xxlmira@gmail.com>
 * @link	http://www.blabot.net
 */
class Blabot {
	private $host   = 'http://api.blabot.net/';
	private $url	= '?format=json&scount=1';
	private $proxy  = 'localhost:9999';
	private $curl	= '';
	
	/**
	 * Constructor
	 * 
	 * @param	integer	Number of sencences
	 * @return	void
	 */
	function Blabot($scount) {
		$url = sprintf('?format=json&scount=%d', $scount);

		$this->curl = curl_init();

		curl_setopt($this->curl, CURLOPT_URL, $this->host . $url);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
	}

	/**
	 * Destructor
	 * 
	 * @param	void
	 * @return	void
	 */
	function __destruct() {
		curl_close($this->curl);
	}

	/**
	 * Get message
	 * 
	 * @param	void
	 * @return	string	Blabot.net message(s)
	 */
	function get() {
		$content = curl_exec($this->curl);
		$message = json_decode($content); 
		
		if (isset($message)) {
			return $message->blabot->result[0];
		}
	}
	
	/**
	 * Use SOCKS5 proxy
	 * 
	 * @param	void
	 * @return	void
	 */
	function useSocks() {
		curl_setopt($this->curl, CURLOPT_PROXY, $this->proxy);
		curl_setopt($this->curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
	}
}

/* Input argument */
$count = isset($argv[1]) ? $argv[1] : 1;

/* Get Blabot message */
$blabot = new Blabot($count);
$message = $blabot->get();

/* Try SOCKS5 proxy if empty result */
if ($message) {
	print $message;
}
else {
	$blabot->useSocks();
	print $blabot->get();
}
