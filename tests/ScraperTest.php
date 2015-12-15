<?php
use \PHPHtmlParser\Dom;
use \stt\Scraper;
use \stt\WebpageLoader;

class ScraperTest extends \PHPUnit_Framework_TestCase
{
	private $curl;
	private $htmlNode;
	private $scraper;
	
	public function setUp()
	{
        $htmlNodeSubOne = $this->getMock('\PHPHtmlParser\Dom\HTMLNode', array('getAttribute'));
		$htmlNodeSubOne->method('getAttribute')
					   ->willReturn('fakeurl');
		$htmlNodeSubOne->text = ' this is a title ';			

        $htmlNodeSubTwo = $this->getMock('\PHPHtmlParser\Dom\HTMLNode');
		$htmlNodeSubTwo->text = '&pound44.33';			

        $this->htmlNode = $this->getMock('\PHPHtmlParser\Dom\HTMLNode', array('find'));

		$htmlNodeMap = array(
			array('div.productInfo > h3 > a', $htmlNodeSubOne),
			array('p.pricePerUnit', $htmlNodeSubTwo)
		);

        $this->htmlNode->method('find')
             ->will($this->returnValueMap($htmlNodeMap));

        $webpageLoader = $this->getMockBuilder('\stt\WebpageLoader')
                     ->disableOriginalConstructor()
                     ->getMock();

        $webpageLoader->method('loadPageInfo')
             ->willReturn( array('size' => '22.9kb', 'source' => '<html><head><meta name="description" content="example description" /></head></html><body>Empty</body>'));

        $htmlNodeSubThree = $this->getMock('\PHPHtmlParser\Dom\HTMLNode', array('getAttribute'));
		$htmlNodeSubThree->method('getAttribute')
						 ->willReturn('This is a description');

		$Dom = $this->getMock('\PHPHtmlParser\Dom');
		$Dom->method('load')->willReturn('');
		
		$Dom->method('find')->willReturn($htmlNodeSubThree);
        
		$this->scraper = new Scraper($webpageLoader, $Dom );
	}
	
    public function testLoadProductInfo()
    {
		$output = $this->scraper->loadProductInfo( $this->htmlNode );

        $this->assertEquals('this is a title', $output['title']);
        $this->assertEquals('22.9kb', $output['size']);
        $this->assertEquals('44.33', $output['unit_price']);
        $this->assertEquals('This is a description', $output['description']);
    }
}
?>
