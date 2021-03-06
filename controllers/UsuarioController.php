<?php

namespace app\controllers;

use app\models\UsuarioModel;
use app\models\UsuarioPesquisa;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


/**
 * UsuarioController implements the CRUD actions for UsuarioModel model.
 */
class UsuarioController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access'=>[
                'class' => AccessControl::className(),
                //'only' =>['create', 'update', 'delete','index', 'view'],
                'rules'=>[
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete','index', 'view'],
                        'roles' => ['@'],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all UsuarioModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsuarioPesquisa();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UsuarioModel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UsuarioModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UsuarioModel();
        $model->scenario = 'cadastro';
        $post = Yii::$app->request->post();

        if ($model->load($post)) {

            //SENHA PARA MD5
            $model->user_password = md5($post['UsuarioModel']['user_password']);

            //RECEBE TODOS OS ATRIBUTOS DO FILES
            $model->arquivo = UploadedFile::getInstance($model, 'arquivo');

            if(!empty($model->arquivo->name)):

                //ATRIBUINDO NOME PARA FILE
                $novaimagem = md5(uniqid()) . '-' . time() . '.'.$model->arquivo->extension;
                $model->user_imagem = $novaimagem;

                //SALVA O ARQUIVO NO DIRETÓRIO
                $uploadPath = Yii::getAlias('@webroot/files/');
                $model->arquivo->saveAs($uploadPath . $model->arquivo->name);
            endif;

            if ($model->save()):
                //Mensagem de cadastro com sucesso.
                Yii::$app->getSession()->setFlash('success', 'Cadastro com sucesso.', true);
                return $this->redirect(Yii::$app->request->getReferrer());
            else:
                //Mensagem de de erro ao cadastrar
                Yii::$app->getSession()->setFlash('danger', 'Erro ao cadastrar', true);
                return $this->redirect(Yii::$app->request->getReferrer());
            endif;

        } else {
            return $this->render('create', [
                'tituloFormulario'=>'Cadastro de usuário',
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UsuarioModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            // PASTA DE ULPLOAD
            $uploadPath = Yii::getAlias('@webroot/files/');

            //RECEBE TODOS OS ATRIBUTOS DO FILES
            $model->arquivo = UploadedFile::getInstance($model, 'arquivo');

            //NOVA SENHA PARA MD5
            $model->user_password = md5($post['UsuarioModel']['user_password']);

            if(!empty($model->arquivo->name)):

                //DELETAR FOTO ANTINGA
                if (file_exists($uploadPath.$model->user_imagem)):
                    @unlink($uploadPath.$model->user_imagem);
                endif;

                //ATRIBUINDO NOME PARA FILE
                $novaimagem = md5(uniqid()) . '-' . time() . '.'.$model->arquivo->extension;
                $model->user_imagem = $novaimagem;

                //SALVA O ARQUIVO NO DIRETÓRIO
                $model->arquivo->saveAs($uploadPath.$novaimagem,false);
            endif;

            if ($model->save()):
                Yii::$app->getSession()->setFlash('success', 'Atualização com sucesso', true);
                return $this->redirect(Yii::$app->request->getReferrer());
            else:
                Yii::$app->getSession()->setFlash('danger', 'Erro ao atualizar', true);
                return $this->redirect(Yii::$app->request->getReferrer());
            endif;
        } else {
            return $this->render('update', [
                'tituloFormulario'=>'Atualização de cadastro',
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing UsuarioModel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UsuarioModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UsuarioModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UsuarioModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Deseja deletar essa informação ?');
        }
    }
}
