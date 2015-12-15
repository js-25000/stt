<?php
use \stt\WebpageLoader;
use \anlutro\cURL\cURL;

class WebpageLoaderTest extends \PHPUnit_Framework_TestCase
{
	private $webpageLoader;
	
	public function setUp()
	{
        $curl = $this->getMock('\anlutro\cURL\cURL', array('get'));
        
        $curl->method('get')
             ->willReturn((object) array('headers' => array('content-length' => 23456), 'body' => 'example body   >'));
				
		$this->webpageLoader = new WebpageLoader($curl);
	}
	
    public function testloadPageInfo()
    {
		$output = $this->webpageLoader->loadPageInfo('');
		
        $this->assertEquals('22.9kb', $output['size']);
        $this->assertEquals('example body>', $output['source']); // include check for parser fix
    }
}
?>
