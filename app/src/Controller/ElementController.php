<?php
/**
 * Element controller.
 */

namespace App\Controller;

use App\Entity\Element;
use App\Entity\User;
use App\Form\Type\ElementType;
use App\Repository\CommentRepository;
use App\Service\ElementServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ElementController.
 */
#[Route('/')]
class ElementController extends AbstractController
{
    /**
     * Element service.
     */
    private ElementServiceInterface $elementService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param ElementServiceInterface $elementService Element service
     * @param TranslatorInterface     $translator     Translator
     */
    public function __construct(ElementServiceInterface $elementService, TranslatorInterface $translator)
    {
        $this->elementService = $elementService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'element_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $filters = $this->getFilters($request);
        $pagination = $this->elementService->getPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );

        return $this->render('element/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Element           $element           Element entity
     * @param CommentRepository $commentRepository Comment repository
     *
     * @return Response HTTP response
     */
    #[Route('/{id}', name: 'element_show', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    public function show(Element $element, CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAll();

        return $this->render('element/show.html.twig', ['element' => $element, 'comments' => $comments]);
    }

    /**
     * Show favourited action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route('/favourite', name: 'element_favourited', methods: 'GET')]
    public function favourited(Request $request): Response
    {
        $filters = $this->getFilters($request);
        /** @var User $user */
        $user = $this->getUser();
        $pagination = $this->elementService->getPaginatedListFav(
            $request->query->getInt('page', 1),
            $user,
            $filters
        );

        return $this->render('favourite/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'element_create', methods: 'GET|POST')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $element = new Element();
        $form = $this->createForm(
            ElementType::class,
            $element,
            ['action' => $this->generateUrl('element_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->elementService->save($element);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('element_index');
        }

        return $this->render('element/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Element $element Element entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'element_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Element $element): Response
    {
        $form = $this->createForm(
            ElementType::class,
            $element,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('element_edit', ['id' => $element->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->elementService->save($element);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('element_index');
        }

        return $this->render(
            'element/edit.html.twig',
            [
                'form' => $form->createView(),
                'element' => $element,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Element $element Element entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'element_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Element $element): Response
    {
        $form = $this->createForm(
            FormType::class,
            $element,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('element_delete', ['id' => $element->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->elementService->delete($element);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('element_index');
        }

        return $this->render(
            'element/delete.html.twig',
            [
                'form' => $form->createView(),
                'element' => $element,
            ]
        );
    }

    /**
     * Add favourite action.
     *
     * @param Request $request HTTP request
     * @param Element $element Element entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/add_favourite', name: 'element_add_favourite', methods: 'GET|POST')]
    public function createFavourite(Request $request, Element $element): Response
    {
        $form = $this->createForm(
            FormType::class,
            $element,
            [
                'action' => $this->generateUrl('element_add_favourite', ['id' => $element->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $element->addFavourited($user);
            $this->elementService->save($element);

            $this->addFlash(
                'success',
                $this->translator->trans('message.added_successfully')
            );

            return $this->redirectToRoute('element_favourited');
        }

        return $this->render(
            'favourite/add.html.twig',
            [
                'form' => $form->createView(),
                'element' => $element,
            ]
        );
    }

    /**
     * Delete favourite action.
     *
     * @param Request $request HTTP request
     * @param Element $element Element entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete_favourite', name: 'element_delete_favourite', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function deleteFavourite(Request $request, Element $element): Response
    {
        $form = $this->createForm(
            FormType::class,
            $element,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('element_delete_favourite', ['id' => $element->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $element->removeFavourited($user);
            $this->elementService->save($element);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('element_favourited');
        }

        return $this->render(
            'favourite/delete.html.twig',
            [
                'form' => $form->createView(),
                'element' => $element,
            ]
        );
    }

    /**
     * Get filters from request.
     *
     * @param Request $request HTTP request
     *
     * @return array<string, int> Array of filters
     *
     * @psalm-return array{category_id: int, tag_id: int, status_id: int}
     */
    private function getFilters(Request $request): array
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        return $filters;
    }
}
