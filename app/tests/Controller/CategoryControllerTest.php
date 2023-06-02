<?php

/**
 * Category controller tests.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CategoryControllerTest.
 */
class CategoryControllerTest extends WebTestCase
{
    /**
     *  Test route.
     *
     * @const string
     */
    public const TEST_ROUTE = '/category';

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
     * Test '/category' route.
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
     * Test '/category' route content.
     */
    public function testCategoryRouteContent(): void
    {
        // given
        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);

        // then
        $this->assertSelectorTextContains('html title', 'Kategorie');
    }

    /**
     * Test single category route.
     */
    public function testCategorySingleRoute(): void
    {
        // given
        $expectedStatusCode = '200';

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/1');
        $resultHttpResponse = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpResponse);
    }
}
