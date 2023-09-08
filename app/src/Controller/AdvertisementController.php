<?php

/**
 * Advertisement controller.
 */

namespace App\Controller;

use App\Entity\Advertisement;
use App\Form\Type\AdvertisementType;
use App\Service\AdvertisementServiceInterface;
use App\Service\AdvertiserServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
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
     * Advertiser service.
     */
    private Security $security;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param AdvertisementServiceInterface $advertisementService Advertisement service
     * @param AdvertiserServiceInterface    $advertiserService    Advertiser service
     * @param Security                      $security             Security
     * @param TranslatorInterface           $translator           Translator
     */
    public function __construct(AdvertisementServiceInterface $advertisementService, AdvertiserServiceInterface $advertiserService, Security $security, TranslatorInterface $translator)
    {
        $this->advertisementService = $advertisementService;
        $this->advertiserService = $advertiserService;
        $this->security = $security;
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
        $pagination = $this->advertisementService->getPaginatedListWithAcceptedEntity(
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
    #[isGranted('VIEW', subject: 'advertisement')]
    public function show(Advertisement $advertisement): Response
    {
        /*        if (false === $advertisement->isIsActive() && true === $this->isGranted('ROLE_ADMIN')) {
                    $this->denyAccessUnlessGranted('ROLE_ADMIN');
                }*/

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
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('advertisement_index');
        }

        $advertisement = new Advertisement();
        $form = $this->createForm(
            AdvertisementType::class,
            $advertisement,
            ['action' => $this->generateUrl('advertisement_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $email = $formData->getAdvertiser()->getEmail();
            $existingAdvertiser = $this->advertiserService->advertiserEmailExists($email);
            if ($existingAdvertiser) {
                $advertisement->setAdvertiser($existingAdvertiser);
            }

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
    #[isGranted('EDIT', subject: 'advertisement')]
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

    /**
     * Delete action.
     *
     * @param Request       $request       HTTP request
     * @param Advertisement $advertisement Advertisement entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'advertisement_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[isGranted('DELETE', subject: 'advertisement')]
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

    /**
     * Accept action.
     *
     * @param Request       $request       HTTP request
     * @param Advertisement $advertisement Advertisement entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/accept', name: 'advertisement_accept', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[isGranted('ACCEPT', subject: 'advertisement')]
    public function accept(Request $request, Advertisement $advertisement): Response
    {
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

    /**
     * Reject action.
     *
     * @param Request       $request       HTTP request
     * @param Advertisement $advertisement Advertisement entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/reject', name: 'advertisement_reject', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[isGranted('DELETE', subject: 'advertisement')]
    public function reject(Request $request, Advertisement $advertisement): Response
    {
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
