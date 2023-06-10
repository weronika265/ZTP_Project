<?php

/**
 * Advertisement controller.
 */

namespace App\Controller;

use App\Entity\Advertisement;
use App\Form\Type\AdvertisementType;
use App\Service\AdvertisementServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param AdvertisementServiceInterface $advertisementService Advertisement service
     * @param TranslatorInterface           $translator           Translator
     */
    public function __construct(AdvertisementServiceInterface $advertisementService, TranslatorInterface $translator)
    {
        $this->advertisementService = $advertisementService;
        $this->translator = $translator;
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

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'advertisement_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
//        TODO: ustaw advertiser + lekki offtop -> raczej nie trzeba formularzy advertiser?
//        $user = $this->getUser();
//        $task = new Task();
//        $task->setAuthor($user);

        $advertisement = new Advertisement();
        $form = $this->createForm(
            AdvertisementType::class,
            $advertisement,
            ['action' => $this->generateUrl('advertisement_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->advertisementService->save($advertisement);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('advertisement_index');
        }

        return $this->render(
            'advertisement/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request       $request       HTTP request
     * @param Advertisement $advertisement Advertisement entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'advertisement_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Advertisement $advertisement): Response
    {
        $form = $this->createForm(
            AdvertisementType::class,
            $advertisement,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('advertisement_edit', ['id' => $advertisement->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->advertisementService->save($advertisement);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('advertisement_index');
        }

        return $this->render(
            'advertisement/edit.html.twig',
            [
                'form' => $form->createView(),
                'advertisement' => $advertisement,
            ]
        );
    }

    // ...
    /**
     * Delete action.
     *
     * @param Request       $request       HTTP request
     * @param Advertisement $advertisement Advertisement entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'advertisement_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Advertisement $advertisement): Response
    {
        $form = $this->createForm(FormType::class, $advertisement, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('advertisement_delete', ['id' => $advertisement->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->advertisementService->delete($advertisement);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('advertisement_index');
        }

        return $this->render(
            'advertisement/delete.html.twig',
            [
                'form' => $form->createView(),
                'advertisement' => $advertisement,
            ]
        );
    }
}
