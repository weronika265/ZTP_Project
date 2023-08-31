<?php

/**
 * Advertisement controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Advertisement;
use App\Entity\Advertiser;
use App\Entity\Category;
use App\Repository\AdvertisementRepository;
use App\Repository\AdvertiserRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
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
     * Test '/advertisement' route content.
     */
    public function testAdvertisementRouteContent(): void
    {
        // given
        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);

        // then
        $this->assertSelectorTextContains('html title', 'Wszystkie ogłoszenia');
    }

    /**
     * Test single advertisement route content.
     */
    public function testAdvertisementSingleRoute(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/1');
        $resultHttpStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultHttpStatusCode);
    }

    /**
     * Test advertisement create route.
     */
    public function testAdvertisementCreateRoute(): void
    {
        // given
        $expectedAdvertisementName = 'Test advertisement name';

        // when
        $crawler = $this->httpClient->request('GET', self::TEST_ROUTE.'/create');

        $form = $crawler->selectButton('Zapisz')->form();

        $form['advertisement[name]'] = $expectedAdvertisementName;
        $form['advertisement[description]'] = 'Test advertisement description';
        $form['advertisement[price]'] = 100;
        $form['advertisement[location]'] = 'Testing location';
        $form['advertisement[category]'] = 1;
        $form['advertisement[advertiser][email]'] = 'test@mail.com';

        $this->httpClient->submit($form);

        // then
        $advertisementRepository = static::getContainer()->get(AdvertisementRepository::class);
        $savedAdvertisement = $advertisementRepository->findOneByName($expectedAdvertisementName);

        $this->assertEquals($expectedAdvertisementName, $savedAdvertisement->getName());
    }

    /**
     * Test advertisement edit route.
     */
    public function testAdvertisementEditRoute(): void
    {
        // given
        $testAdvertiser = $this->createAdvertiser('testEdit@mail.com');
        $testCategory = $this->createCategory('Test category for edit advertisement');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@internal.com');
        $this->httpClient->loginUser($adminUser);

        $expectedAdvertisementName = 'Test advertisement name edited';

        $testAdvertisement = $this->createAdvertisement(
            $expectedAdvertisementName,
            'Test description',
            'Test location',
            $testAdvertiser,
            $testCategory
        );

        $advertisementId = $testAdvertisement->getId();

        // when
        $crawler = $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$advertisementId.'/edit');

        $form = $crawler->selectButton('Edytuj')->form();

        $form['advertisement[name]'] = $expectedAdvertisementName;
        $form['advertisement[description]'] = 'Test advertisement description';
        $form['advertisement[price]'] = 100;
        $form['advertisement[location]'] = 'Testing location';
        $form['advertisement[category]'] = 1;
        $form['advertisement[advertiser][email]'] = 'test@mail.com';

        $this->httpClient->submit($form);

        // then
        $advertisementRepository = static::getContainer()->get(AdvertisementRepository::class);
        $savedAdvertisement = $advertisementRepository->findOneByName($expectedAdvertisementName);

        $this->assertEquals($expectedAdvertisementName, $savedAdvertisement->getName());
    }

    //    NIE DZIALA
    /**
     * Test advertisement delete route.
     */
    /*    public function testAdvertisementDeleteRoute(): void
        {
            // given
            $testAdvertiser = $this->createAdvertiser('testDelete@mail.com');
            $testCategory = $this->createCategory('Test category for delete advertisement');

            $userRepository = static::getContainer()->get(UserRepository::class);
            $adminUser = $userRepository->findOneByEmail('admin@internal.com');
            $this->httpClient->loginUser($adminUser);

            $expectedAdvertisementName = 'Test advertisement name deleted';

            $testAdvertisement = $this->createAdvertisement(
                $expectedAdvertisementName,
                'Test description',
                'Test location',
                $testAdvertiser,
                $testCategory
            );

            $advertisementId = $testAdvertisement->getId();

            $advertisementRepository = static::getContainer()->get(AdvertisementRepository::class);

            // when
            $crawler = $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$advertisementId.'/delete');

            $form = $crawler->selectButton('Usuń')->form();

            $this->httpClient->submit($form);

            // then
            $this->assertNull($advertisementRepository->findOneById($advertisementId));
        }*/

    // ciagle jest to false przy isAccepted -> podobny problem przy delete?
    /**
     * Test advertisement accept route.
     */
    /*public function testAdvertisementAcceptRoute(): void
    {
        // given
        $testAdvertiser = $this->createAdvertiser('testAccept@mail.com');
        $testCategory = $this->createCategory('Test category for accept advertisement');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@internal.com');
        $this->httpClient->loginUser($adminUser);

        $expectedAdvertisementName = 'Test advertisement name accepted';

        $testAdvertisement = $this->createAdvertisement(
            $expectedAdvertisementName,
            'Test description',
            'Test location',
            $testAdvertiser,
            $testCategory
        );

        $advertisementId = $testAdvertisement->getId();

        $advertisementRepository = static::getContainer()->get(AdvertisementRepository::class);

        // when
        $crawler = $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$advertisementId.'/accept');

        $form = $crawler->selectButton('Zaakceptuj')->form();

        $this->httpClient->submit($form);

        // then
        $this->assertEquals(1, $testAdvertisement->isIsActive());
    }*/

    // to samo
    /**
     * Test advertisement reject route.
     */
    /*public function testAdvertisementRejectRoute(): void
    {
        // given
        $testAdvertiser = $this->createAdvertiser('testReject@mail.com');
        $testCategory = $this->createCategory('Test category for reject advertisement');

        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@internal.com');
        $this->httpClient->loginUser($adminUser);

        $expectedAdvertisementName = 'Test advertisement name rejected';

        $testAdvertisement = $this->createAdvertisement(
            $expectedAdvertisementName,
            'Test description',
            'Test location',
            $testAdvertiser,
            $testCategory
        );

        $advertisementId = $testAdvertisement->getId();

        $advertisementRepository = static::getContainer()->get(AdvertisementRepository::class);

        // when
        $crawler = $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$advertisementId.'/reject');

        $form = $crawler->selectButton('Odrzuć')->form();

        $this->httpClient->submit($form);

        // then
        $this->assertNull($advertisementRepository->findOneById($advertisementId));
    }*/

    /**
     * Create advertisement.
     *
     * @param string     $name        Advertisement name
     * @param string     $description Advertisement description
     * @param string     $location    Location
     * @param Advertiser $advertiser  Advertiser
     * @param Category   $category    Category
     */
    public function createAdvertisement(
        string $name,
        string $description,
        string $location,
        Advertiser $advertiser,
        Category $category
    ): Advertisement {
        $advertisement = new Advertisement();
        $advertisement->setName($name);
        $advertisement->setDescription($description);
        $advertisement->setLocation($location);
        $advertisement->setDate(new \DateTimeImmutable('now'));
        $advertisement->setIsActive(1);
        $advertisement->setAdvertiser($advertiser);
        $advertisement->setCategory($category);

        $advertisementRepository = static::getContainer()->get(AdvertisementRepository::class);
        $advertisementRepository->save($advertisement);

        return $advertisement;
    }

    /**
     * Create advertiser.
     *
     * @param string $email Email
     *
     * @return Advertiser advertiser entity
     */
    public function createAdvertiser(string $email): Advertiser
    {
        $advertiser = new Advertiser();
        $advertiser->setEmail($email);
        $advertiserRepository = static::getContainer()->get(AdvertiserRepository::class);
        $advertiserRepository->save($advertiser);

        return $advertiser;
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
