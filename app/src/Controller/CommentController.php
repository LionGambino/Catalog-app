<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Element;
use App\Entity\User;
use App\Form\Type\CommentType;
use App\Service\CommentServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;

/**
 * Class CommentController.
 */
#[Route('/comment')]
class CommentController extends AbstractController
{
    /**
     * Comment service.
     */
    private CommentServiceInterface $commentService;

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
     * @param CommentServiceInterface $commentService Comment service
     * @param TranslatorInterface  $translator  Translator
     * @param Security  $security    Security helper
     */
    public function __construct(CommentServiceInterface $commentService, TranslatorInterface $translator, Security $security)
    {
        $this->commentService = $commentService;
        $this->translator = $translator;
        $this->security = $security;
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     * @param Element    $element    Element entity
     *
     * @return Response HTTP response
     */
    #[Route('/create/{id}', name: 'comment_create', methods: 'GET|POST', )]
    public function create(Request $request, Element $element): Response
    {
        $comment = new Comment();
        $comment->setElement($element);
        $user = $this->getUser();
        $comment->setUser($user);
        $form = $this->createForm(
            CommentType::class,
            $comment,
            ['action' => $this->generateUrl('comment_create', ['id' => $element->getId()])]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->save($comment);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('element_show', array ('id' => $element->getId()));
        }

        return $this->render('comment/create.html.twig',  ['form' => $form->createView(),'element'=>$element]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Comment    $comment    Comment entity
     * @param Element    $element    Element entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete/{element}', name: 'comment_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Comment $comment, Element $element): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN'))
        {
            $form = $this->createForm(
                FormType::class,
                $comment,
                [
                    'method' => 'DELETE',
                    'action' => $this->generateUrl('comment_delete', ['id' => $comment->getId(), 'element' => $element->getId()]),
                ]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->commentService->delete($comment);

                $this->addFlash(
                    'success',
                    $this->translator->trans('message.deleted_successfully')
                );

                return $this->redirectToRoute('element_show', array ('id' => $element->getId()));
            }

            return $this->render(
                'comment/delete.html.twig',
                [
                    'form' => $form->createView(),
                    'comment' => $comment,
                ]
            );
        }
        $this->addFlash(
            'warning',
            $this->translator->trans('message.access_denied')
        );
        return $this->redirectToRoute('element_index');
    }


}
