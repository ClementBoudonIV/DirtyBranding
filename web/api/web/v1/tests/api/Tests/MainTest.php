<?php
namespace DirtyBranding\Tests;

require_once __DIR__.'/../../../vendor/autoload.php';


use Silex\WebTestCase;

class MainTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../../app.php';
        require __DIR__.'/../../../config.php';
        $app['debug'] = true;
    	$app['exception_handler']->disable();
    	return $app;
    }

    public function testInitialEndpoint()
    {
        $client = $this->createClient();
	    $crawler = $client->request('GET', '/');

	    $this->assertTrue($client->getResponse()->isOk());
	    $this->assertContains('API DirtyBranding - Modif fonctionnelle.', $client->getResponse()->getContent());
    }

    public function testExtensions()
    {
        $client = $this->createClient();
	    $crawler = $client->request('GET', '/extensions/');

	    $this->assertTrue($client->getResponse()->isOk());
	    $this->assertCount(1, $crawler->filter('html:contains("com")'));
    }

    public function testIPOffices()
    {
        $client = $this->createClient();
	    $crawler = $client->request('GET', '/ipoffices/');

	    $this->assertTrue($client->getResponse()->isOk());
	    $this->assertCount(1, $crawler->filter('html:contains("inpi")'));
    }

    public function testBrandsFromIdeas()
    {
        $client = $this->createClient();
	    $crawler = $client->request('GET', '/ideas/test/brands?suffixes[]=suf1');

	    $this->assertTrue($client->getResponse()->isOk());
	    $this->assertCount(1, $crawler->filter('html:contains("test suf1")'));
    }

    public function testAlternativesFromBrands()
    {
        $client = $this->createClient();
	    $crawler = $client->request('GET', '/brands/test%20suf1/alternatives?separators[]=SEP');

	    $this->assertTrue($client->getResponse()->isOk());
	    $this->assertCount(1, $crawler->filter('html:contains("testSEPsuf1")'));
    }

    public function testDomainsFromBrands()
    {
        $client = $this->createClient();
	    $crawler = $client->request('GET', '/brands/test/domains?extensions[]=com');

	    $this->assertTrue($client->getResponse()->isOk());
	    $this->assertCount(1, $crawler->filter('html:contains("test.com")'));
    }

    public function testBrandAvailability()
    {
        $client = $this->createClient();
	    $crawler = $client->request('GET', '/brands/test/available?ipoffices[]=inpi');

	    $this->assertTrue($client->getResponse()->isOk());
	    $this->assertRegExp('/(false|true)/', $client->getResponse()->getContent());
    }

    public function testDomainAvailability()
    {
        $client = $this->createClient();
	    $crawler = $client->request('GET', '/domains/test.com/available');

	    $this->assertTrue($client->getResponse()->isOk());
	    $this->assertRegExp('/(false|true)/', $client->getResponse()->getContent());
    }
}
