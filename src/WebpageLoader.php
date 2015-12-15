<?php
namespace stt;

/**
 * Simple class to load a url + details
 * @author: James Stone
 */
class WebpageLoader {
	
	private $curl;

	/**
	 * @param \anlutro\cURL\cURL $curl
	 */
	function __construct( $curl ){
		$this->curl = $curl;
	}
								
	/**
	 * Load the required webpage
	 * 
	 * @param String $url url of the page we wish to load
	 * @return Array
	 */
	public function loadPageInfo( $url ){
		
		$response = $this->curl->get( $url );
		return array(	'size' 		=> (string)round($response->headers["content-length"]/1024,1).'kb',
						'source' 	=> preg_replace('/\s+>/', '>', $response->body) ); // Work around for bug in PHPHtmlParser
	}
}


?>
