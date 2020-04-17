<?php

use App\Todos\TodoController;

/**
 * @var \App\Todos\Entity\Todo $todo
 * @var \Yiisoft\Router\FastRoute\UrlGenerator $urlGenerator
 * @var \Yiisoft\View\WebView $this
 * @var string $csrf
 */
$deleteUrl = $urlGenerator->generate(TodoController::getRouteName(TodoController::ACTION_DESTROY),
    [TodoController::PARAM_ID => $todo->getId()]);


$js = <<<JS
    $('.delete').on('click', function(e) {
        e.preventDefault();
        if (confirm("Delete?")) {
            const formData = new FormData;
            formData.append('_csrf', '$csrf')
            formData.append('_method', 'DELETE')

            fetch($(this).attr('href'), {
                method: "POST",
                body: formData,
            })

        }
    })
JS;

$this->registerJs($js);

?>

<div>

    <div>

        <form action="<?= $deleteUrl ?>" method="POST">
            <input type="hidden" name="_csrf" value="<?= $csrf ?>">
            <button class="btn btn-danger">DELETE</button>
        </form>

    </div>

    <table class="table">
        <tbody>
        <tr>
            <th>#</th>
            <td><?= \Yiisoft\Html\Html::encode($todo->getId()) ?></td>
        </tr>

        <tr>
            <th>Title</th>
            <td><?= \Yiisoft\Html\Html::encode($todo->getTitle()) ?></td>
        </tr>

        <tr>
            <th>Content</th>
            <td><?= \Yiisoft\Html\Html::encode($todo->getContent()) ?></td>
        </tr>

        <tr>
            <th>Created at</th>
            <td><?= \Yiisoft\Html\Html::encode($todo->getCreatedAt()->format('Y-m-d')) ?></td>
        </tr>
        <tr>
            <th>Updated at</th>
            <td><?= \Yiisoft\Html\Html::encode($todo->getUpdatedAt()->format('Y-m-d')) ?></td>
        </tr>


        </tbody>
    </table>
</div>
