<?php
require('vendor/autoload.php');

use anlutro\cURL\cURL;
use PHPHtmlParser\Dom;
use stt\WebpageLoader;
use stt\Scraper;

// Set up the objects
$webpageLoader = new WebpageLoader( new cURL );
$scraper = new Scraper($webpageLoader, new Dom );

// Get the data
$startUrl = 'http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html';
$scrapedData = $scraper->scrape($startUrl);

// Output as json
echo json_encode( $scrapedData ) . "\n";


?>
