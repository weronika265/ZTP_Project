<?php

/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserPasswordType;
use App\Form\Type\UserType;
use App\Service\AdvertisementServiceInterface;
use App\Service\UserService;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController.
 */
#[Route('/admin')]
class UserController extends AbstractController
{
    /**
     * User service.
     *
     * @var \App\Service\UserService User service
     */
    private UserServiceInterface $userService;

    /**
     * Advertisement service.
     *
     * @var \App\Service\AdvertisementService Advertisement service
     */
    private AdvertisementServiceInterface $advertisementService;

    /**
     * Constructor.
     *
     * @param UserService                   $userService          User service
     * @param AdvertisementServiceInterface $advertisementService Advertisement service
     */
    public function __construct(UserServiceInterface $userService, AdvertisementServiceInterface $advertisementService)
    {
        $this->userService = $userService;
        $this->advertisementService = $advertisementService;
    }

    /**
     * Index acton.
     *
     * @param Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     */
    #[Route(
        name: 'user_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $user = $this->userService->getCurrentUser();

        $paginationAdvertisements = $this->advertisementService->getPaginatedListWithUnacceptedEntity(
            $request->query->getInt('page', 1)
        );

        return $this->render(
            'user/index.html.twig',
            [
                'user' => $user,
                'pagination' => $paginationAdvertisements,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\User                          $user    User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_edit",
     * )
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        $formPass = $this->createForm(UserPasswordType::class, $user, ['method' => 'PUT']);
        $formPass->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->addFlash('success', 'message_updated_successfully');
        }

        if ($formPass->isSubmitted() && $formPass->isValid()) {
            $this->userService->savePassword($user);
            $this->addFlash('success', 'message_updated_successfully');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'formPass' => $formPass->createView(),
                'user' => $user,
            ]
        );
    }
}
