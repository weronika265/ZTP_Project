<?php

/**
 * Advertisement controller.
 */

namespace App\Controller;

use App\Entity\Advertisement;
use App\Form\Type\AdvertisementType;
use App\Service\AdvertisementServiceInterface;
use App\Service\AdvertiserServiceInterface;
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
     * Advertiser service.
     */
    private AdvertiserServiceInterface $advertiserService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param AdvertisementServiceInterface $advertisementService Advertisement service
     * @param AdvertisementServiceInterface $advertiserService    Advertiser service
     * @param TranslatorInterface           $translator           Translator
     */
    public function __construct(AdvertisementServiceInterface $advertisementService, AdvertiserServiceInterface $advertiserService, TranslatorInterface $translator)
    {
        $this->advertisementService = $advertisementService;
        $this->advertiserService = $advertiserService;
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
        $advertisement = new Advertisement();
        $form = $this->createForm(
            AdvertisementType::class,
            $advertisement,
            ['action' => $this->generateUrl('advertisement_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $email = $request->get('email');
            $formData = $form->getData();
            $email = $formData->getAdvertiser()->getEmail();
            $existingAdvertiser = $this->advertiserService->advertiserEmailExists($email);
            if ($existingAdvertiser) {
                $advertisement->setAdvertiser($existingAdvertiser);
            }
            /* TODO: check if there is an advertiser with the email
                     yes -> set existing advertiser
                     no -> create new one?
                --> dziala???
            */

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

//    TODO: tak jak advertiser w create czy zostaje jak jest, bo tak ma byc?
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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

    #[Route('/{id}/accept', name: 'advertisement_accept', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function accept(Request $request, Advertisement $advertisement): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(
            FormType::class,
            $advertisement,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('advertisement_accept', ['id' => $advertisement->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->advertisementService->accept($advertisement);
            $this->advertisementService->save($advertisement);

            $this->addFlash(
                'success',
                $this->translator->trans('message.accepted_successfully')
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'advertisement/accept.html.twig',
            [
                'form' => $form->createView(),
                'advertisement' => $advertisement,
            ]
        );
    }

//    TODO: czy nie wystarczy tylko metoda delete, bez tej tutaj reject?
    /**
     * Reject action.
     *
     * @param Request       $request       HTTP request
     * @param Advertisement $advertisement Advertisement entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/reject', name: 'advertisement_reject', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function reject(Request $request, Advertisement $advertisement): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(FormType::class, $advertisement, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('advertisement_reject', ['id' => $advertisement->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->advertisementService->delete($advertisement);

            $this->addFlash(
                'success',
                $this->translator->trans('message.rejected_successfully')
            );

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'advertisement/reject.html.twig',
            [
                'form' => $form->createView(),
                'advertisement' => $advertisement,
            ]
        );
    }
}
