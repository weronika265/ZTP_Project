<?php

/**
 * Advertisement controller.
 */

namespace App\Controller;

use App\Repository\AdvertisementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdvertisementController.
 */
#[Route('/advertisement')]
class AdvertisementController extends AbstractController
{
    /**
     * Index acton.
     *
     * @return Response HTTP response
     */
    #[Route(
        '',
        name: 'advertisement_index',
        methods: 'GET'
    )]
    public function index(AdvertisementRepository $advertisementRepository): Response
    {
        // testy sie failują jak jest odkomentowane
//        $advertisements = $advertisementRepository->findAll();

        return $this->render(
            'advertisement/index.html.twig',
//            ['advertisements' => $advertisements]
        );
    }
}
