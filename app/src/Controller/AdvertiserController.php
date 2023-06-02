<?php

/**
 * Advertiser controller.
 */

namespace App\Controller;

use App\Entity\Advertiser;
use App\Service\AdvertiserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdvertiserController.
 */
#[Route('/advertiser')]
class AdvertiserController extends AbstractController
{
    /**
     * Advertiser service.
     */
    private AdvertiserServiceInterface $advertiserService;

    /**
     * Constructor.
     */
    public function __construct(AdvertiserServiceInterface $advertiserService)
    {
        $this->advertiserService = $advertiserService;
    }

    /**
     * Index acton.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'advertiser_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $pagination = $this->advertiserService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render(
            'advertiser/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param Advertiser $advertiser Advertiser entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'advertiser_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(Advertiser $advertiser): Response
    {
        return $this->render(
            'advertiser/show.html.twig',
            ['advertiser' => $advertiser]
        );
    }
}
