<?php

/**
 * Advertisement controller.
 */

namespace App\Controller;

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// DODAĆ PAGINACJĘ W KONTROLERZE I W INDEX
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
        name: 'advertisement_index',
        methods: 'GET'
    )]
    public function index(AdvertisementRepository $advertisementRepository): Response
    {
        // testy wyświetlania zawartości na stronie to bardziej w repozytorium?
        // testy się failują jak jest odkomentowane
        $advertisements = $advertisementRepository->findAll();

        return $this->render(
            'advertisement/index.html.twig',
            ['advertisements' => $advertisements]
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
