<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use app\models\Comments;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$session = Yii::$app->session;
$userType = $session->get('user_type');
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>
 <?php if($userType == 1):?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
<?php endif; ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'price',
            'description:ntext',
        ],
    ]) ?>
	

</div>

<h4>Comments for this product:</h4>
<table class="table table-striped table-bordered detail-view">
<tbody>

<tr><th>Comment Id</th><td>Comments</td><td>Comments Status</td>
<?php if ($userType == 1): ?>
    <td>Actions</td>
<?php endif;?>
</tr>
<?php foreach ($commentModel as $key=>$comment): ?>
<?php if ($userType == 1): ?>
<tr><th></td><?php echo ++$key; ?></td>
<td><?php echo $comment['comments']?> </td>
<td><?php
 if ($comment['comments_status'] == '1'){
        echo "Approved";
 }elseif ($comment['comments_status'] == '2') {
        echo "Rejected";
 } ?> </td>
 <td><?php echo Html::a("updated status", ["comments/update",'id'=>$comment['comment_id']]); ?>
 <!-- <?php echo Html::a("Delete", ["comments/delete",'id'=>$comment['comment_id']]); ?></td> -->
</th></tr>

<?php elseif($userType ==2 && $comment['comments_status'] !=2):?>
    
<tr><th></td><?php echo ++$key; ?></td>
<td><?php echo $comment['comments']?> </td>
<td><?php
 if ($comment['comments_status'] == '1'){
        echo "Approved";
 }elseif ($comment['comments_status'] == '2') {
        echo "Rejected";
 } ?> </td>
 </tr>


<?php endif;?>

<?php endforeach; ?>
 </tbody>
</table>

<h4>Add Cooments for this product:</h4>
<?php
 $commentsmodel = new Comments();
?>
    <div class="row">
	<div class="col-md-4">
    <?php $form = ActiveForm::begin(['action' => ['account/savecomment']]); ?>
        <?= $form->field($commentsmodel, 'comments')->textarea(['rows' => 6])->label(false) ?>
        <?= $form->field($commentsmodel, 'product_id')->hiddenInput(['value'=> $model->id])->label(false); ?>
    </div>
    <?php if($userType == 1):?>
    <div class="col-md-4">
        <?= $form->field($commentsmodel, 'comments_status')->dropdownList([
        1 => 'Approve', 
        2 => 'Reject'
    ], ['Select'=>'Select Comments Status'])->label(false); ?>
    </div>
    <?php else:?>
<?= $form->field($commentsmodel, 'comments_status')->hiddenInput(['value'=> 2])->label(false); ?>
    <?php endif;?>

    <div class="col-md-4">  
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
     </div>
    <?php ActiveForm::end(); ?>
    </div><!-- acount-comment -->


