<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\Comments;
use app\models\ProductSearch;
use app\models\CommentsSearch;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use yii\helpers\ArrayHelper;

/**
 * AccountController implements the CRUD actions for Product model.
 */
class AccountController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'commentModel'=> $this->findCommentModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    protected function findCommentModel($id)
    {
        
        $comments = CommentsSearch::find()->where(['product_id' => $id])->all();
        return ArrayHelper::toArray($comments);

    }


     /**
     * Deletes an existing Comments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionLogin() {
        $model = new LoginForm();

        if (!Yii::$app->session->get('logged_in')) {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }else{
             return $this->redirect(['index']);
        }



        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->render('loginview', [
                        'model' => $model,
            ]);
        }
    }

    public function actionPostlogin() {
        $model = new UsersSearch();
        $modelLoginForm = new LoginForm();
        if (Yii::$app->request->post()) {
            $loginPostData = Yii::$app->request->post();
            $loginPostUserName = $loginPostData['LoginForm']['username'];
            $loginPostUserPwd = $loginPostData['LoginForm']['password'];
            $result = UsersSearch::find()->where(['user_name' => $loginPostUserName])->all();
            $array = ArrayHelper::toArray($result);
            foreach ($array as $column) {
                if (isset($column['password']) && $column['password'] != '') {
                    if ($this->validate($column['password'], $loginPostUserPwd)) {
                        $session = Yii::$app->session;
                        $session->set('logged_in', 1);
                        $session->set('user_name', $column['user_name']);
                        $session->set('user_type', $column['user_type']);
                       $this->sendToDashboard($column['user_type']);
                    }else{
                        return $this->redirect(['login']);
                    }
                }
            }
        }
         //return $this->redirect(['login']);
    }

    protected function sendToDashboard($userType){
        if($userType == 1){
            return $this->redirect(['index']);
        }else{
            return $this->redirect(['userdashboard']);
        }
        
    }
    
    public function actionUserdashboard() {

            $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('loginview', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    private function validate($postPass,$realPass){
        if($postPass === $realPass){
            return true;
        }
        return false;
    }
    
    public function actionLogout(){
        $session = Yii::$app->session;
        $session->set('logged_in',0);
        $session->remove('user_name');
        $session->remove('user_type');
        return $this->redirect(['login']);
    }

    public function actionSavecomment() {
        $model = new Comments();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->comment_id]);
            return $this->goHome();
        }
    }

}
