<?php
/**
 * Element service.
 */

namespace App\Service;

use App\Entity\Element;
use App\Repository\ElementRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ElementService.
 */
class ElementService implements ElementServiceInterface
{
    /**
     * Element repository.
     */
    private ElementRepository $elementRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Category service.
     */
    private CategoryServiceInterface $categoryService;

    /**
     * Tag service.
     */
    private TagServiceInterface $tagService;

    /**
     * Constructor.
     *
     * @param ElementRepository     $elementRepository Element repository
     * @param PaginatorInterface $paginator      Paginator
     * @param CategoryServiceInterface $categoryService Category service
     * @param TagServiceInterface      $tagService      Tag service
     */
    public function __construct(
        ElementRepository $elementRepository,
        PaginatorInterface $paginator,
        CategoryServiceInterface $categoryService,
        TagServiceInterface $tagService,)
    {
        $this->elementRepository = $elementRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     * @param array<string, int> $filters Filters array
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->elementRepository->queryAll($filters),
            $page,
            ElementRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Element $element Element entity
     */
    public function save(Element $element): void
    {
        $this->elementRepository->save($element);
    }

    /**
     * Delete entity.
     *
     * @param Element $element Element entity
     */
    public function delete(Element $element): void
    {
        $this->elementRepository->delete($element);
    }

    /**
     * Prepare filters for the tasks list.
     *
     * @param array<string, int> $filters Raw filters from request
     *
     * @return array<string, object> Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (!empty($filters['category_id'])) {
            $category = $this->categoryService->findOneById($filters['category_id']);
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        if (!empty($filters['tag_id'])) {
            $tag = $this->tagService->findOneById($filters['tag_id']);
            if (null !== $tag) {
                $resultFilters['tag'] = $tag;
            }
        }

        return $resultFilters;
    }
}
