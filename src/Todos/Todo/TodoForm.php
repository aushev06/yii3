<?php


namespace App\Todos\Todo;

use Yiisoft\Yii\Web\User\User;
use App\Todos\Entity\Todo;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction;
use Yiisoft\Form\Form;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\Required;

class TodoForm extends Form
{
    /**
     * @var string
     */
    private string $name;
    const ATTR_NAME = 'name';

    /**
     * @var string
     */
    private string $content;
    const ATTR_CONTENT = 'content';

    /**
     * @var int
     */
    private int $userId;
    const ATTR_USER_ID = 'userId';
    /**
     * @var Todo
     */
    private Todo $todo;
    /**
     * @var User
     */
    private User $user;

    public function __construct(Todo $todo, User $user)
    {
        $this->name    = $todo->getTitle();
        $this->content = $todo->getContent();

        $this->todo = $todo;
        $this->user = $user;
        parent::__construct();

    }

    /**
     * @return array|string[]
     * @author Aushev Ibra <aushevibra@yandex.ru>
     */
    public function attributeLabels(): array
    {
        return [
            static::ATTR_NAME    => 'Название',
            static::ATTR_CONTENT => 'Контент'
        ];
    }


    /**
     * @return string
     * @author Aushev Ibra <aushevibra@yandex.ru>
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * @return array|array[]
     * @author Aushev Ibra <aushevibra@yandex.ru>
     */
    protected function rules(): array
    {
        return [
            static::ATTR_NAME => [
                new Required(),
                (new HasLength())
                    ->min(1)
                    ->max(100)
                    ->tooLongMessage('Слишком коротко')
                    ->tooLongMessage('Long')
            ]
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }


    /**
     * @param ORMInterface $ORM
     * @return bool
     * @throws \Throwable
     * @author Aushev Ibra <aushevibra@yandex.ru>
     */
    public function save(ORMInterface $ORM)
    {

        if (false === $this->validate()) {
            return false;
        }

        $this->todo->setContent($this->content);
        $this->todo->setTitle($this->name);
        $this->todo->setUserId($this->user->getId());
        $this->todo->setStatus(Todo::STATUS_ACTIVE);

        try {
            $tr = new Transaction($ORM);
            $tr->persist($this->todo);
            $tr->run();


            return true;

        } catch (\Throwable $exception) {
            return false;
        }


    }

}
