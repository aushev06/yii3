<?php

use Yiisoft\Html\Html;
use App\Todos\Todo\TodoForm;

/**
 * @var TodoForm $form
 */
?>
<div class="form-group">
    <label for="name">Title</label>
    <?= Html::textInput(TodoForm::ATTR_NAME, $form->getName(), [
        'class'    => 'form-control',
        'id'       => 'name',
        'required' => true,
    ]) ?>
</div>
<div class="form-group">
    <label for="content">Content</label>
    <?= Html::textarea($form::ATTR_CONTENT, $form->getContent(), [
        'class'    => 'form-control',
        'id'       => 'content',
        'required' => true,
    ]) ?>
</div>
