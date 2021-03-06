<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/3/16
 * Time: 上午10:31
 */
use common\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Markdown;

?>
<!--评论-->
<div id="comments">
    <h4>共 <span class="text-danger"><?=$model->comment?></span> 条评论</h4>
    <div class="col-4">
        <ul class="media-list">
            <?php foreach ($commentModels as $item):?>
                <li class="media" data-key="<?=$item->id?>">
                    <div class="media-left">
                        <a href="<?= Url::to(['/user', 'id' => $item->user_id])?>">
                            <?= Html::img($item->profile->avatar, ['class' => 'media-object', 'alt' => $item->user->username]) ?>
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="media-heading"><a href="<?= Url::to(['/user', 'id' => $item->user_id])?>"><?=$item->user->username?></a> 评论于 <?=date('Y-m-d H:i', $item->created_at)?></div>
                        <div class="media-content" id="comment-<?= $item->id ?>"><?= Markdown::process($item->content, 'gfm')?></div>
                        <?php foreach ($item->sons as $son):?>
                            <div class="media">
                                <div class="media-left">
                                    <a href="<?= Url::to(['/user', 'id' => $son->user_id])?>" rel="author" title="">
                                        <?= Html::img($son->profile->avatar, ['class' => 'media-object', 'alt' => $son->user->username]) ?>
                                    </a>
                                </div>
                                <div class="media-body">
                                    <div class="media-heading">
                                        <a href="<?= Url::to(['/user', 'id' => $son->user_id])?>" rel="author" data-original-title="<?=$son->user->username?>" title=""><?=$son->user->username?></a> 回复于 <?=date('Y-m-d H:i', $son->created_at)?>
                                        <span class="pull-right"><a class="reply-btn j_replayAt" href="javascript:;">回复</a></span>
                                    </div>
                                    <div class="media-content" id="comment-<?= $son->id ?>"><?= Markdown::process(\common\helpers\Comment::process($son->content))?></div>
                                </div>
                            </div>
                        <?php endforeach;?>
                        <div class="media-action">
                            <a class="reply-btn" href="#">回复</a><span class="vote"><a class="up" href="<?=\yii\helpers\Url::to(['/vote', 'id' => $item->id, 'type' => 'comment', 'action' => 'up'])?>" title="" data-toggle="tooltip" data-original-title="顶"><i class="fa <?= $item->isUp ? 'fa-thumbs-up' : 'fa-thumbs-o-up' ?>"></i> <em><?=$item->up?></em></a><a class="down" href="<?=\yii\helpers\Url::to(['/vote', 'id' => $item->id, 'type' => 'comment', 'action' => 'down'])?>" title="" data-toggle="tooltip" data-original-title="踩"><i class="fa <?= $item->isDown ? 'fa-thumbs-down' : 'fa-thumbs-o-down' ?>"></i> <em><?=$item->down?></em></a></span>
                        </div>
                    </div>
                </li>
            <?php endforeach;?>
        </ul>
        <?= \yii\widgets\LinkPager::widget([
            'pagination' => $commentDataProvider->getPagination()
        ]); ?>
    </div>
</div>
<h4>发表评论</h4>

    <?php $form = \yii\widgets\ActiveForm::begin(['action' => Url::toRoute('comment/create')]); ?>
    <?= $form->field($commentModel, 'content')->label(false)->widget('\yidashi\markdown\Markdown', ['options' => ['style' => 'height:200px;']]); ?>
    <?= Html::hiddenInput(Html::getInputName($commentModel, 'type_id'), $model->id) ?>
    <?= $form->field($commentModel, 'type')->hiddenInput()->label(false) ?>
    <div class="form-group">
        <?php if (!Yii::$app->user->isGuest): ?>
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <?php else: ?>
            <?= Html::a('登录', ['/site/login'], ['class' => 'btn btn-primary'])?>
        <?php endif; ?>
    </div>
    <?php \yii\widgets\ActiveForm::end(); ?>
    <!--回复-->
    <?php $form = \yii\widgets\ActiveForm::begin(['action' => Url::toRoute('comment/create'), 'options' => ['class' => 'reply-form hidden']]); ?>
    <?= $form->field($commentModel, 'type')->hiddenInput()->label(false) ?>
    <?= Html::hiddenInput(Html::getInputName($commentModel, 'type_id'), $model->id) ?>
    <?= Html::hiddenInput(Html::getInputName($commentModel, 'parent_id'), 0, ['class' => 'parent_id']) ?>
    <?=$form->field($commentModel, 'content')->label(false)->textarea()?>
    <div class="form-group">
        <?php if (!Yii::$app->user->isGuest): ?>
            <button type="submit" class="btn btn-sm btn-primary">回复</button>
        <?php else: ?>
            <?= Html::a('登录', ['/site/login'], ['class' => 'btn btn-primary'])?>
        <?php endif; ?>
    </div>
    <?php \yii\widgets\ActiveForm::end(); ?>
