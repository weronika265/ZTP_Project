<?php

/**
 * Security controller tests.
 */

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityControllerTest.
 */
class SecurityControllerTest extends WebTestCase
{
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    public function testLoginRoute(): void
    {
        // given
        $expectedStatusCode = 302;

        // when
        $crawler = $this->httpClient->request('GET', '/login');
        $form = $crawler->selectButton('Zaloguj')->form();

        $form['email'] = 'admin@internal.com';
        $form['password'] = 'projectAdmin1';

        $this->httpClient->submit($form);

        // then
        $result = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $result);
    }

    public function testLogoutRoute(): void
    {
        // given
        $expectedStatusCode = 302;

        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@internal.com');

        // when
        $this->httpClient->loginUser($adminUser);
        $this->httpClient->request('GET', '/logout');

        // then
        $result = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $result);
    }
}