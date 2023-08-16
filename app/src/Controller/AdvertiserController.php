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
}