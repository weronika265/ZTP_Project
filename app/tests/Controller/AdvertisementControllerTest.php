<?php

/**
 * Advertisement controller tests.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AdvertisementControllerTest.
 */
class AdvertisementControllerTest extends WebTestCase
{
    /**
     * Test route.
     *
     * @const string
     */
    public const TEST_ROUTE = '/advertisement';

    /**
     * Test client.
     */
    private KernelBrowser $httpClient;

    /**
     * Set up tests.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test '/advertisement' route.
     */
    public function testAdvertisementRoute(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultHttpStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test advertisement route content.
     */
    public function testAdvertisementRouteContent(): void
    {
        // given
        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);

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
        //        dodać element do bazy danych do przetestowania

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/1');
        $resultHttpStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }
}

// TODO: TESTY!!
