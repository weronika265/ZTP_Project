<?php

/**
 * Advertisement controller.
 */

namespace App\Controller;

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use Knp\Component\Pager\PaginatorInterface;
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
     * Index acton.
     *
     * @param Request                 $request                 HTTP Request
     * @param AdvertisementRepository $advertisementRepository Advertisement repository
     * @param PaginatorInterface      $paginator               Paginator
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'advertisement_index',
        methods: 'GET'
    )]
    public function index(Request $request, AdvertisementRepository $advertisementRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $advertisementRepository->findAll(),
            $request->query->getInt('page', 1),
            AdvertisementRepository::PAGINATOR_ITEMS_PER_PAGE
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
