<?php

declare(strict_types=1);

namespace App\Todos\Entity;

use App\Entity\User;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Relation\ManyToMany;
use Cycle\Annotated\Annotation\Table;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Relation\Pivoted\PivotedCollection;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Yiisoft\Security\Random;

/**
 * @Entity(
 *     repository="App\Todos\Todo\TodoRepository",
 *     mapper="App\Todos\Todo\TodoMapper",
 *     constrain="App\Todos\Todo\Scope\PublicScope"
 * )
 * @Table(
 *     indexes={
 *         @Index(columns={"published_at"}),
 *     }
 * )
 */
class Todo
{
    /**
     * @Column(type="primary")
     */
    private ?int $id = null;
    const ATTR_ID = 'id';

    /**
     * @Column(type="string(255)", default="")
     */
    private string $title = '';
    const ATTR_TITLE = 'title';

    /**
     * @Column(type="text")
     */
    private string $content;
    const ATTR_CONTENT = 'content';

    /**
     * @var @Column(type="int", )
     */
    private int $status;
    const ATTR_STATUS = 'status';

    /**
     * @Column(type="datetime")
     */
    private DateTimeImmutable $created_at;
    const ATTR_CREATED_AT = 'created_at';
    /**
     * @Column(type="datetime")
     */
    private DateTimeImmutable $updated_at;
    const ATTR_UPDATED_AT = 'updated_at';

    /**
     * @Column(type="datetime", nullable=true)
     */
    private ?DateTimeImmutable $published_at = null;
    const ATTR_PUBLISHED_AT = 'published_at';

    /**
     * @Column(type="datetime", nullable=true)
     */
    private ?DateTimeImmutable $deleted_at = null;
    const ATTR_DELETED_AT = 'deleted_at';

    /**
     * @BelongsTo(target="App\Entity\User", nullable=false)
     * @var User|\Cycle\ORM\Promise\Reference
     */
    private      $user    = null;
    private ?int $user_id = null;

    const STATUS_ACTIVE  = 1;
    const STATUS_DISABLE = 0;

    const WITH_USER = 'user';


    public function __construct()
    {
        $this->title      = "";
        $this->content    = "";
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getPublishedAt(): ?DateTimeImmutable
    {
        return $this->published_at;
    }

    public function setPublishedAt(?DateTimeImmutable $published_at): void
    {
        $this->published_at = $published_at;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int|null $user_id
     */
    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }


}
