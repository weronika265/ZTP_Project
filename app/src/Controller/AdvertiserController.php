<?php

/**
 * Advertiser controller.
 */

namespace App\Controller;

use App\Entity\Advertiser;
use App\Form\Type\AdvertiserType;
use App\Service\AdvertiserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param AdvertiserServiceInterface $taskService Task service
     * @param TranslatorInterface        $translator  Translator
     */
    public function __construct(AdvertiserServiceInterface $advertiserService, TranslatorInterface $translator)
    {
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

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'advertiser_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $advertiser = new Advertiser();
        $form = $this->createForm(
            AdvertiserType::class,
            $advertiser,
            ['action' => $this->generateUrl('advertiser_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->advertiserService->save($advertiser);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('advertiser_index');
        }

        return $this->render(
            'advertiser/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request    $request    HTTP request
     * @param Advertiser $advertiser Advertiser entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'advertiser_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Advertiser $advertiser): Response
    {
        $form = $this->createForm(
            AdvertiserType::class,
            $advertiser,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('advertiser_edit', ['id' => $advertiser->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->advertiserService->save($advertiser);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('advertiser_index');
        }

        return $this->render(
            'advertiser/edit.html.twig',
            [
                'form' => $form->createView(),
                'advertiser' => $advertiser,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request    $request    HTTP request
     * @param Advertiser $advertiser Advertiser entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'advertiser_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Advertiser $advertiser): Response
    {
        $form = $this->createForm(FormType::class, $advertiser, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('advertiser_delete', ['id' => $advertiser->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->advertiserService->delete($advertiser);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('advertiser_index');
        }

        return $this->render(
            'advertiser/delete.html.twig',
            [
                'form' => $form->createView(),
                'advertiser' => $advertiser,
            ]
        );
    }
}
