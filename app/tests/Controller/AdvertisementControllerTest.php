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
        $expectedStatusCode = 200;
        $client = static::createClient();

        // when
        $client->request('GET', '/advertisement');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
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
    }

    /**
     * Test advertisement route content.
     */
    public function testAdvertisementSingleRoute(): void
    {
        // given
        $expectedStatusCode = 200;
        $client = static::createClient();
        //        dodać element do bazy danych do przetestowania

        // when
        $client->request('GET', '/advertisement/1');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }
}

// TODO: TESTY!!
