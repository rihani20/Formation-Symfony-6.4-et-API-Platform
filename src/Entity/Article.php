<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use DateTime;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ArticleRepository;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\GetLastPostController;
use App\Filter\ArticleQueryFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[UniqueEntity('slug')]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
/*     shortName: 'Post',
 */   
 operations:
    [     
        new Get(
            normalizationContext: ['groups' => ['article:read']],
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['article:read']],
        ),
        new Post(
            normalizationContext: ['groups' => ['article:read']],
            denormalizationContext: ['groups' => ['article:write']],
        ),
        new Put(),
        new Patch(
            security: 'object.getUser() === user'
        ),
        new Delete(),

     /*    new Get(
            uriTemplate: '/articles/last', 
            name: 'article_get_last_post',
            controller: GetLastPostController::class,
        ), */
      /*   new GetCollection(
            routePrefix: '/v2',
            uriTemplate: '/users/{id}/aricles',
            uriVariables: [
                'id' => new Link(fromProperty: 'articles' , fromClass: User::class)
            ],
            normalizationContext:  ['groups' => 'article:list'],
            name: 'all_articles_by_user_id'
        ), */
   
     
   

    ],
    //routePrefix: '/v1',

)]
#[ApiFilter(SearchFilter::class, properties: [ 'title' => 'partial'])]
#[ApiFilter(ArticleQueryFilter::class )]

class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['article:list', 'article:read'])]
    #[ApiProperty(
        description:  "L'identifiant unique de l'article."
    )]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['article:list', 'article:read', 'article:write'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['article:list', 'article:read', 'article:write'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['article:list', 'article:read'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['article:list', 'article:read', 'article:write'])]
    private ?string $body = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['article:list', 'article:read'])]
    private ?User $user = null;

    #[ORM\Column]
    #[Groups(['article:list', 'article:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['article:list', 'article:read'])]
    private ?\DateTimeImmutable $editedAt = null;

    #[ORM\Column]
    #[Groups(['article:list', 'article:read', 'article:write'])]
    private ?\DateTimeImmutable $publishedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEditedAt(): ?\DateTimeImmutable
    {
        return $this->editedAt;
    }

    public function setEditedAt(?\DateTimeImmutable $editedAt): self
    {
        $this->editedAt = $editedAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedValue() : void 
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function computeSlug(SluggerInterface $sluggerInterface) {
      $this->slug = $sluggerInterface->slug($this->title)->lower();
    }

  

    public function __toString(): string
    {
        return $this->title;
    }
}
