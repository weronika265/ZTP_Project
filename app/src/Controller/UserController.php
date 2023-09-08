<?php

/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserPasswordType;
use App\Form\Type\UserType;
use App\Service\AdvertisementService;
use App\Service\AdvertisementServiceInterface;
use App\Service\UserService;
use App\Service\UserServiceInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 */
#[Route('/admin')]
class UserController extends AbstractController
{
    /**
     * User service.
     *
     * @var UserService User service
     */
    private UserServiceInterface $userService;

    /**
     * Advertisement service.
     *
     * @var AdvertisementService Advertisement service
     */
    private AdvertisementServiceInterface $advertisementService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param UserService                   $userService          User service
     * @param AdvertisementServiceInterface $advertisementService Advertisement service
     * @param TranslatorInterface           $translator           Translator
     */
    public function __construct(UserServiceInterface $userService, AdvertisementServiceInterface $advertisementService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
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
        name: 'user_index',
        methods: 'GET',
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
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    #[Route(
        '/{id}/edit',
        name: 'user_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT',
    )]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        $formPass = $this->createForm(UserPasswordType::class, $user, ['method' => 'PUT']);
        $formPass->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);
            $this->translator->trans('message.updated_successfully');
        }

        if ($formPass->isSubmitted() && $formPass->isValid()) {
            $this->userService->savePassword($user);
            $this->translator->trans('message.updated_successfully');
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
