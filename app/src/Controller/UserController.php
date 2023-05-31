<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
#use App\Form\Type\UserType;
#use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 */
#[Route('/user')]
class UserController extends AbstractController
{

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


}
