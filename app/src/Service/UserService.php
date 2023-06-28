<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    /**
     * User repository.
     */
    private UserRepository $userRepository;

    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Constructor.
     *
     * @param UserRepository $userRepository User repository
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     * @param PaginatorInterface $paginator      Paginator
     */
    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            userRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    public function password(User $user): void
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user, $user->getPassword()
            )
        );
        $this->userRepository->save($user);
    }
}