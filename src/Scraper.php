<?php
namespace stt;

use PHPHtmlParser\Dom;
use stt\WebpageLoader;

class Scraper {

	private $webpageLoader;
	private $domParser;

	/**
	 * @param \stt\WebpageLoader $webpageLoader
	 * @param \PHPHtmlParser\Dom $domParser
	 */
	function __construct($webpageLoader, $domParser){
		$this->webpageLoader = $webpageLoader;
		$this->domParser = $domParser;
	}

	/**
	 * @param String $startUrl
	 * @return array
	 */
	public function scrape($startUrl)
	{
		$frontpage = $this->webpageLoader->loadPageInfo($startUrl);

		$this->domParser->load($frontpage['source']);
		$products = $this->domParser->find('ul.productLister > li');

		$foundPs = array();
		$total = 0;

		foreach( $products as $num => $p ){
			$foundPs[$num] = $this->loadProductInfo( $p );
			$total += $foundPs[$num]['unit_price'];
		}
		$output = array( 	'results' 	=> $foundPs,
							'total'		=> $total );

		return $output;
	}

	/**
	 * Fetch all required info for one specific product
	 * 
	 * @param \PHPHtmlParser\Dom\HTMLNode $productDiv Dom object with product div loaded in
	 * @return array
	 */
	public function loadProductInfo( $productDiv )
	{
		$url = $productDiv->find('div.productInfo > h3 > a' )->getAttribute('href');
		$title = trim($productDiv->find('div.productInfo > h3 > a' )->text);
		$unit_price = $productDiv->find('p.pricePerUnit' )->text;
		
		$subpage = $this->webpageLoader->loadPageInfo($url);		
		$this->domParser->load($subpage['source']);
		
		return array(	'title' 		=> $title,
						'size'			=> $subpage['size'],
						'unit_price' 	=> trim(str_replace('&pound', '', $unit_price)),
						'description'	=> html_entity_decode($this->domParser->find('meta[name=description]')->getAttribute('content'), ENT_QUOTES) );

	}
	
	

}
