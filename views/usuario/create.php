<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UsuarioModel */

$this->title = $tituloFormulario;
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
