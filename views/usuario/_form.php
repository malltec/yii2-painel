<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use conquer\select2\Select2Widget;
use app\models\CidadeModel;
use app\models\UFModel;
use yii\helpers\ArrayHelper;




/* @var $this yii\web\View */
/* @var $model app\models\UsuarioModel */
/* @var $form yii\widgets\ActiveForm */
//$this->registerJsFile('@web/jsc/js_controller.js',['depends'=>\app\assets\AppAsset::className()]);
?>

<div class="usuario-model-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form','name'=>'mm'],
    ]);

    ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'user_nome')->textInput(['maxlength' => true, 'placeholder' => 'Nome do usuário']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'user_sobrenome')->textInput(['maxlength' => true, 'placeholder' => 'Segundo nome']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'user_telefone')->textInput(['maxlength' => true])->widget(MaskedInput::className(), ['mask' => '(99) 9.9999-9999']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'user_email')->textInput(['maxlength' => true, 'placeholder' => 'E-mail que será ultilizado para fazer login']) ?>
        </div>

        <div class="col-md-4">
            <?php
            if ($model->isNewRecord):
                echo $form->field($model, 'user_password')->passwordInput(['maxlength' => true, 'placeholder' => 'Escolha uma senha segura, use numeros e caracteres especiais']);
            else:
                echo $form->field($model, 'user_password')->passwordInput(['value' => '', 'maxlength' => true, 'placeholder' => 'Atualização da senha']);
            endif;
            ?>
        </div>


        <div class="col-md-4">
            <?= $form->field($model, 'user_cpf')->textInput(['maxlength' => true, 'placeholder' => 'Informe CPF válido'])->widget(MaskedInput::className(), ['mask' => '999-999-999-99']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?php
            $uf = UFModel::find()->all();
            echo $form->field($model, 'id_uf')->widget(Select2Widget::className(),
                ['items' => ArrayHelper::map($uf, 'uf_codi', 'uf_nome')]);
            ?>
        </div>

        <div class="col-md-4">
            <?php
            $cidade = CidadeModel::find()->all();
            echo $form->field($model, 'id_cidade')->widget(Select2Widget::className(),
                ['items' => ArrayHelper::map($cidade, 'cidd_codi', 'cidd_nome')]);
            ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'arquivo')->fileInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <div class="col-md-6 aviso">
        <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
            echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
        } ?></div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
