<?php
/**
 * Comment entity.
 */

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
class Comment
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Content.
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank]
    private ?string $content = null;

    /**
     * Created at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Element.
     *
     * @var Element
     */
    #[ORM\ManyToOne(targetEntity: Element::class, fetch: 'EXTRA_LAZY')]
    #[Assert\Type(Element::class)]
    #[Assert\NotBlank]
    #[ORM\JoinColumn(nullable: false)]
    private ?Element $element = null;

    /**
     * User.
     *
     * @var User
     */
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[Assert\Type(User::class)]
    #[Assert\NotBlank]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: false)]
    private ?User $user = null;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for content.
     *
     * @return string|null Content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for title.
     *
     * @param string $content Content
     *
     * @return Comment|null Comment Entity
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter for created at.
     *
     * @return \DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     *
     * @param \DateTimeImmutable $createdAt Created at
     *
     * @return Comment|null Comment Entity
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for element.
     *
     * @return Element|null Element
     */
    public function getElement(): ?Element
    {
        return $this->element;
    }

    /**
     * Setter for category.
     *
     * @param Element|null $element Element
     *
     * @return Comment|null Comment Entity
     */
    public function setElement(?Element $element): self
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Getter for category.
     *
     * @return User|null User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for user.
     *
     * @param User|null $user User
     *
     * @return Comment|null Comment Entity
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
