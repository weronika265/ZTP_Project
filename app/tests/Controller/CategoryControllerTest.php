<?php

/**
 * Category controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
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

    /**
     * Test category create route.
     */
    public function testCategoryCreateRoute(): void
    {
        // given
        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@internal.com');
        $this->httpClient->loginUser($adminUser);

        $expectedCategoryName = 'Test category name';

        // when
        $crawler = $this->httpClient->request('GET', self::TEST_ROUTE.'/create');

        $form = $crawler->selectButton('Zapisz')->form();

        $form['category[name]'] = $expectedCategoryName;

        $this->httpClient->submit($form);

        // then
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $savedACategory = $categoryRepository->findOneByName($expectedCategoryName);

        $this->assertEquals($expectedCategoryName, $savedACategory->getName());
    }

    /**
     * Test category edit route.
     */
    public function testCategoryEditRoute(): void
    {
        // given
        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@internal.com');
        $this->httpClient->loginUser($adminUser);

        $expectedCategoryName = 'Test category name edited';

        $testCategory = $this->createCategory($expectedCategoryName);

        $categoryId = $testCategory->getId();

        // when
        $crawler = $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$categoryId.'/edit');

        $form = $crawler->selectButton('Edytuj')->form();

        $form['category[name]'] = $expectedCategoryName;

        $this->httpClient->submit($form);

        // then
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $savedCategory = $categoryRepository->findOneByName($expectedCategoryName);

        $this->assertEquals($expectedCategoryName, $savedCategory->getName());
    }

    /**
     * Test category delete route.
     */
    public function testCategoryDeleteRoute(): void
    {
        // given
        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@internal.com');
        $this->httpClient->loginUser($adminUser);

        $expectedCategoryName = 'Test category name deleted';

        $testCategory = $this->createCategory($expectedCategoryName);

        $categoryId = $testCategory->getId();

        $categoryRepository = static::getContainer()->get(CategoryRepository::class);

        // when
        $crawler = $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$categoryId.'/delete');

        $form = $crawler->selectButton('Usuń')->form();

        $this->httpClient->submit($form);

        // then
        $this->assertNull($categoryRepository->findOneById($categoryId));
    }

    /**
     * Create category.
     *
     * @param string $name Category name
     *
     * @return Category Category entity
     */
    public function createCategory(string $name): Category
    {
        $category = new Category();
        $category->setName($name);
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($category);

        return $category;
    }
}
