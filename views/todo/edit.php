<?php
namespace App\views\todo;

use App\Todos\Todo\TodoForm;
use App\Todos\TodoController;
use Yiisoft\Html\Html;

/**
 * @var $this \Yiisoft\View\View
 * @var $urlGenerator \Yiisoft\Router\UrlGeneratorInterface
 * @var $csrf string
 * @var $form TodoForm
 * @var $id string
 */

$error = $error ?? null;
?>
<?php if ($error !== null): ?>
    <div class="alert alert-danger" role="alert">
        <?= Html::encode($error) ?>
    </div>
<?php endif ?>

<form id="signupForm" method="POST"
      action="<?= $urlGenerator->generate(TodoController::getRouteName(TodoController::ACTION_UPDATE), [
          TodoController::PARAM_ID => $id
      ]) ?>"
      enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?= $csrf ?>">
    <?php echo $this->render('_form', [
        'form' => $form
    ]) ?>
    <button type="submit" class="btn btn-primary">Edit</button>
</form>
