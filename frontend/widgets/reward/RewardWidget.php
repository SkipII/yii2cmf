<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/5/31
 * Time: 下午4:56
 */

namespace frontend\widgets\reward;


use frontend\models\RewardForm;
use yii\base\Widget;
use Yii;

class RewardWidget extends Widget
{
    public $articleId;

    public function run()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->user->loginRequired();
        } else {
            $model = new RewardForm();
            $model->money = Yii::$app->user->identity->profile->money;
            $model->article_id = $this->articleId;
            return $this->render('index', ['model' => $model]);
        }
    }
}