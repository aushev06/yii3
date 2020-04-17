<?php


namespace App\Todos;


use App\Controller;
use App\Todos\Entity\Todo;
use App\Todos\Todo\TodoForm;
use App\Todos\Todo\TodoRepository;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Router\FastRoute\UrlGenerator;
use Yiisoft\View\WebView;
use Yiisoft\Yii\Web\Data\DataResponseFactoryInterface;
use Yiisoft\Yii\Web\User\User;

class TodoController extends Controller
{
    /**
     * @var ORMInterface
     */
    private ORMInterface $orm;

    const ACTION_INDEX   = 'index';
    const ACTION_CREATE  = 'create';
    const ACTION_STORE   = 'store';
    const ACTION_SHOW    = 'show';
    const ACTION_EDIT    = 'edit';
    const ACTION_UPDATE  = 'update';
    const ACTION_DESTROY = 'destroy';

    const PARAM_ID = 'id';

    public function __construct(
        DataResponseFactoryInterface $responseFactory,
        User $user,
        Aliases $aliases,
        WebView $view,
        ORMInterface $orm
    ) {
        parent::__construct($responseFactory, $user, $aliases, $view);
        $this->orm = $orm;
    }

    /**
     * @return string
     * @author Aushev Ibra <aushevibra@yandex.ru>
     */
    protected function getId(): string
    {
        return 'todo';
    }

    public function index(Request $request, UrlGenerator $urlGenerator)
    {
        /**
         * @var TodoRepository $todoRepository
         */
        $todoRepository = $this->orm->getRepository(Todo::class);
        $pageNum        = (int)$request->getAttribute('page', 1);

        $dataReader = $todoRepository->findAllPreloaded();

        $data = [
            'todos'        => $dataReader->read(),
            'urlGenerator' => $urlGenerator
        ];

        return $this->render('index', $data);

    }

    public function create(Request $request, UrlGenerator $urlGenerator)
    {
        $csrf = $request->getAttribute('csrf_token');
        return $this->render('create', [
            'urlGenerator' => $urlGenerator,
            'csrf'         => $csrf,
            'form'         => new TodoForm(new Todo(), $this->user),
        ]);
    }

    /**
     * @param Request $request
     * @param UrlGenerator $urlGenerator
     * @return \Yiisoft\Yii\Web\Data\DataResponse
     * @throws \Throwable
     * @author Aushev Ibra <aushevibra@yandex.ru>
     */
    public function store(Request $request, UrlGenerator $urlGenerator)
    {
        $body = $request->getParsedBody();
        $todo = new Todo();
        $form = new TodoForm($todo, $this->user);

        if ($form->load($body) && $form->save($this->orm)) {
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader(
                    'Location',
                    $urlGenerator->generate(TodoController::getRouteName(static::ACTION_SHOW), [
                        static::PARAM_ID => $todo->getId()
                    ])
                );
        }

    }

    public function show(Request $request, UrlGenerator $urlGenerator)
    {
        $id   = $request->getAttribute(static::PARAM_ID);
        $todo = $this->find($id);
        $csrf = $request->getAttribute('csrf_token');
        return $this->render('show', [
            'todo'         => $todo,
            'csrf'         => $csrf,
            'urlGenerator' => $urlGenerator
        ]);
    }

    /**
     * @param Request $request
     * @param UrlGenerator $urlGenerator
     * @return Response
     * @author Aushev Ibra <aushevibra@yandex.ru>
     */
    public function edit(Request $request, UrlGenerator $urlGenerator)
    {
        $id   = $request->getAttribute(static::PARAM_ID);
        $csrf = $request->getAttribute('csrf_token');
        $todo = $this->find($id);

        $form = new TodoForm($todo, $this->user);


        return $this->render('edit', [
            'urlGenerator' => $urlGenerator,
            'csrf'         => $csrf,
            'form'         => $form,
            'id'           => $id,
        ]);


    }

    public function update(Request $request, UrlGenerator $urlGenerator)
    {
        $id   = $request->getAttribute(static::PARAM_ID);
        $todo = $this->find($id);

        $form = new TodoForm($todo, $this->user);

        if ($form->load($request->getParsedBody()) && $form->save($this->orm)) {
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader(
                    'Location',
                    $urlGenerator->generate(TodoController::getRouteName(static::ACTION_SHOW), [
                        static::PARAM_ID => $todo->getId()
                    ])
                );
        }

        return $this->responseFactory
            ->createResponse(302)
            ->withHeader(
                'Location',
                $urlGenerator->generate(TodoController::getRouteName(static::ACTION_INDEX))
            );


    }

    public function destroy(Request $request, UrlGenerator $urlGenerator)
    {
        $id   = $request->getAttribute(static::PARAM_ID);
        $todo = $this->find($id);
        try {
            $tr = new Transaction($this->orm);
            $tr->delete($todo, Transaction::MODE_ENTITY_ONLY);
            $tr->run();

            return $this->responseFactory
                ->createResponse(302)
                ->withHeader(
                    'Location',
                    $urlGenerator->generate(TodoController::getRouteName(static::ACTION_INDEX))
                );


        } catch (\Throwable $exception) {
            dd($exception);
        }


    }

    /**
     * @param string $action
     * @return string
     * @author Aushev Ibra <aushevibra@yandex.ru>
     */
    public static function getRouteName(string $action)
    {
        return 'todos/' . $action;
    }


    /**
     * @param $id
     * @return object
     * @author Aushev Ibra <aushevibra@yandex.ru>
     */
    public function find($id)
    {
        /**
         * @var TodoRepository $todoRepository
         */
        $todoRepository = $this->orm->getRepository(Todo::class);
        return $todoRepository->findOrFail($id);
    }

}
