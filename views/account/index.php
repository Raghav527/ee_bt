<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
$session = Yii::$app->session;
$userType = $session->get('user_type');
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if($userType == 1):?>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
<?php 

if($userType ==1){
 $view =   ['class' => 'yii\grid\ActionColumn'];
}else{
   $view = ['class' => 'yii\grid\ActionColumn','template' => '{view}']; 
}


?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'price',
            'description:ntext',

            $view,
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
