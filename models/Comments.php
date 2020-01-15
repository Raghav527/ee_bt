<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property int $comment_id
 * @property string $comments
 * @property int $product_id
 * @property int $comments_status
 */
class Comments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comments', 'product_id', 'comments_status'], 'required'],
            [['product_id', 'comments_status'], 'integer'],
            [['comments'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'comment_id' => 'Comment ID',
            'comments' => 'Comments',
            'product_id' => 'Product ID',
            'comments_status' => 'Comments Status',
        ];
    }

    /**
     * {@inheritdoc}
     * @return CommentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CommentsQuery(get_called_class());
    }
	
	public function status()
    {
		
		if ($model->comments_status === 3){
			$val = "default";
		}
		
		return $val;
		
	}
	
}
