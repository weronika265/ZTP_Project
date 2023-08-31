<?php

/**
 * Home controller tests.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class HomeControllerTest.
 */
class HomeControllerTest extends WebTestCase
{
    /**
     *  Test route.
     *
     * @const string
     */
    public const TEST_ROUTE = '/';

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
     * Test '/' route.
     */
    public function testCategoryRoute(): void
    {
        // given
        $expectedStatusCode = '200';

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultHttpResponse = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpResponse);
    }

    /**
     * Test '/' route content.
     */
    public function testCategoryRouteContent(): void
    {
        // given
        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);

        // then
        $this->assertSelectorTextContains('html title', 'Strona główna');
        $this->assertSelectorTextContains('html h1', 'Serwis ogłoszeniowy – lokalnie i nie tylko');
    }
}