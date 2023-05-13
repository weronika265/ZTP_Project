<?php

/**
 * Advertisement controller tests.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AdvertisementControllerTest.
 */
class AdvertisementControllerTest extends WebTestCase
{
    /**
     * Test '/advertisement' route.
     */
    public function testAdvertisementRoute(): void
    {
        // given
        $client = static::createClient();

        // when
        $client->request('GET', '/advertisement');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultHttpStatusCode);
    }

    /**
     * Test advertisement route content.
     */
    public function testAdvertisementRouteContent(): void
    {
        // given
        $client = static::createClient();

        // when
        $client->request('GET', '/advertisement');

        // then
                $this->assertSelectorTextContains('html title', 'Ogłoszenia');
        //        $this->assertSelectorTextContains('html h1', 'XXX');
    }
}

// porcjowanie wyników na stronie (show.html.twig) + testy serwisu (?)
