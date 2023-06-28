<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use App\Form\Type\EditPasswordType;
use App\Form\Type\RegisterType;
use App\Form\Type\UserType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserController.
 */
#[Route('/user')]
class UserController extends AbstractController
{

    /**
     * User service.
     */
    private UserServiceInterface $userService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Security helper.
     *
     * @var Security
     */
    private Security $security;

    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService User service
     * @param TranslatorInterface  $translator  Translator
     * @param Security             $security    Security helper
     */
    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator, Security $security)
    {
        $this->userService = $userService;
        $this->translator = $translator;
        $this->security = $security;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route('/index', name: 'user_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        if (!($this->security->isGranted('ROLE_ADMIN'))) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.access_denied')
            );

            return $this->redirectToRoute('element_index');
        }
        $pagination = $this->userService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('user/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @return Response HTTP response
     */
    #[Route(name: 'user_show')]
    public function show(): Response
    {
        $user = $this->getUser();

        return $this->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * Edit password action.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/password', name: 'password_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function password_edit(Request $request, User $user): Response
    {
        if ($this->canManage() or $this->getUser() == $user) {
            $form = $this->createForm(
                EditPasswordType::class,
                $user,
                [
                    'method' => 'PUT',
                    'action' => $this->generateUrl('password_edit', ['id' => $user->getId()]),
                ]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->userService->password($user);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.edited_successfully')
                );

                return $this->redirectToRoute('user_show');
            }

            return $this->render(
                'user/password.html.twig',
                [
                    'form' => $form->createView(),
                    'user' => $user,
                ]
            );
        }
        $this->addFlash(
            'warning',
            $this->translator->trans('message.access_denied')
        );

        return $this->redirectToRoute('element_index');
    }
    /**
     * Edit user action.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'user_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, User $user): Response
    {
        if ($this->canManage() or $this->getUser() == $user) {
            $form = $this->createForm(
                UserType::class,
                $user,
                [
                    'method' => 'PUT',
                    'action' => $this->generateUrl('user_edit', ['id' => $user->getId()]),
                ]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->userService->save($user);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.edited_successfully')
                );

                return $this->redirectToRoute('user_show');
            }

            return $this->render(
                'user/edit.html.twig',
                [
                    'form' => $form->createView(),
                    'user' => $user,
                ]
            );
        }
        $this->addFlash(
            'warning',
            $this->translator->trans('message.access_denied')
        );

        return $this->redirectToRoute('element_index');
    }
    /**
     * Register user action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/register', name: 'register', methods: 'GET|POST')]
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(
            RegisterType::class,
            $user,
            ['action' => $this->generateUrl('register')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles([UserRole::ROLE_USER->value]);
            $this->userService->password($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('element_index');
        }

        return $this->render(
            'user/register.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
    /**
     * Checks if user can manage user data.
     *
     * @return bool Result
     */
    private function canManage(): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }
}
