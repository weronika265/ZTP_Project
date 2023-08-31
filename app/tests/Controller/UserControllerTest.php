<?php

/**
 * User Controller tests.
 */

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest.
 */
class UserControllerTest extends WebTestCase
{
    /**
     * Test route.
     *
     * @const string
     */
    public const TEST_ROUTE = '/admin';

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
     * Test '/admin' route.
     */
    public function testUserRoute(): void
    {
        // given
        $expectedStatusCode = 200;

        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@internal.com');
        $this->httpClient->loginUser($adminUser);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultHttpStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test admin edit route.
     */
    public function testUserEditRoute(): void
    {
        // given
        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@internal.com');
        $this->httpClient->loginUser($adminUser);

        $adminId = $adminUser->getId();

        $expectedAdminEmail = 'adminTest@internal.com';

        // when
        $crawler = $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$adminId.'/edit');

        $formData = $crawler->selectButton('Zaktualizuj dane')->form();

        $formData['user[email]'] = $expectedAdminEmail;

        $this->httpClient->submit($formData);

        //then
        $userRepository = static::getContainer()->get(UserRepository::class);
        $updatedAdmin = $userRepository->findOneById($adminId);

        $this->assertEquals($expectedAdminEmail, $updatedAdmin->getEmail());
    }
}