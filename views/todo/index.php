<?php
/**
 * @var \App\Todos\Entity\Todo[] $todos
 * @var \Yiisoft\Router\FastRoute\UrlGenerator $urlGenerator
 */

use App\Todos\TodoController;
use Yiisoft\Html\Html;

?>

<div>
    <a href="<?= $urlGenerator->generate(TodoController::getRouteName(TodoController::ACTION_CREATE))?>">Create Todo</a>

    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Content</th>
            <th></th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($todos as $todo): ?>
            <tr>
                <td><?= Html::encode($todo->getId()) ?></td>
                <td><?= Html::encode($todo->getTitle()) ?></td>
                <td><?= Html::encode($todo->getContent()) ?></td>
                <td>
                    <a href="<?= $urlGenerator->generate(TodoController::getRouteName(TodoController::ACTION_SHOW), [
                        TodoController::PARAM_ID => $todo->getId()
                    ]) ?>">
                        View
                    </a>

                    <a href="<?= $urlGenerator->generate(TodoController::getRouteName(TodoController::ACTION_EDIT), [
                        TodoController::PARAM_ID => $todo->getId()
                    ]) ?>">
                        Edit
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
