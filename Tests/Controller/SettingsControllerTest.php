<?php

namespace Vivait\SettingsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SettingsControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
		$container = $client->getContainer();
		$home_url = $container->getParameter('home_url');

        $crawler = $client->request('GET', $home_url .'/settings');

		$this->assertGreaterThan(
			0,
			$crawler->filter('html:contains("Settings")')->count()
		);
    }
}
