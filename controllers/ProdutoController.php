<?php

namespace app\controllers;

use app\models\ProdutoModel;
use app\models\ProdutoSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * ProdutoController implements the CRUD actions for ProdutoModel model.
 */
class ProdutoController extends Controller
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
     * Lists all ProdutoModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProdutoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'tituloLista'=>'Lista de produtos cadastrados',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProdutoModel model.
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
     * Creates a new ProdutoModel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProdutoModel();
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            $msg = '<div  class="alert alert-success" role="alert" style="font-weight: bold">Cadastro efetuado com sucesso.</div>';
            return json_encode(['msg'=>$msg,'acao_java'=>'create']);
        } else {
            return $this->render('create', [
                'titleForm'=>'Cadastro de produto',
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProdutoModel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
           // return $this->redirect(['view', 'id' => $model->id]);
            $msg = '<div  class="alert alert-success" role="alert" style="font-weight: bold">Atualização com sucesso.</div>';
            //return json_encode(['msg'=>$msg,'acao_java'=>'update']);
            return json_encode(['msg'=>$msg,'acao_java'=>'update', 'urlRedirect' => Url::toRoute(['produto/index'])]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProdutoModel model.
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
     * Finds the ProdutoModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProdutoModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProdutoModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
