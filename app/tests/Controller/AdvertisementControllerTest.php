<?php

/**
 * Advertisement controller tests.
 */

namespace App\Tests\Controller;

use App\Repository\AdvertisementRepository;
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
//    public function testAdvertisementRouteContent(): void
//    {
//        // given
//        $client = static::createClient();
////        $advertisementRepository = static::getContainer()->get(advertisementRepository::class);
////        $advertisementRepository->findAll();
//
//        // when
//        $client->request('GET', '/advertisement');
//
//        // then
//        $this->assertSelectorTextContains('html title', 'Ogłoszenia');
//        //        $this->assertSelectorTextContains('html h1', 'XXX');
//    }

    /**
     * Test advertisement route content.
     */
    public function testAdvertisementSingleRoute(): void
    {
        // given
        $expectedStatusCode = 200;
        $client = static::createClient();

        // when
        $client->request('GET', '/advertisement/1');
        $resultHttpStatusCode = $client->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }
}

// Doctrine - relacje, Doctrine - optymalizacja zapytań, Warstwa serwisów + testy serwisu i paginacji (?)
// nie dziala nic z Repository, bo zwraca 500 - to przez to, że testowanie bazy danych jest nieustawione i 500 przez to, że pusta?
