<?php

/**
 * Advertisement controller.
 */

namespace App\Controller;

use App\Entity\Advertisement;
use App\Service\AdvertisementService;
use App\Service\AdvertisementServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdvertisementController.
 */
#[Route('/advertisement')]
class AdvertisementController extends AbstractController
{
    /**
     * Advertisement service.
     */
    private AdvertisementServiceInterface $advertisementService;

    /**
     * Constructor.
     */
    public function __construct(AdvertisementServiceInterface $advertisementService)
    {
        $this->advertisementService = $advertisementService;
    }

    /**
     * Index acton.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'advertisement_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->advertisementService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render(
            'advertisement/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Advertisement $advertisement Advertisement entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'advertisement_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Advertisement $advertisement): Response
    {
        return $this->render(
            'advertisement/show.html.twig',
            ['advertisement' => $advertisement]
        );
    }
}
