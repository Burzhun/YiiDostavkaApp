<li>
    <div class="topBox">
        <? if ($data->review == 1) { ?>
            <img src="<?= Yii::app()->theme->baseUrl; ?>/img/like.png">
            <span class="reviewName" style="color:#719124"> <?= $data->user->name ?></span>
        <? } elseif ($data->review == 2) { ?>
            <img src="<?= Yii::app()->theme->baseUrl; ?>/img/dislike.png" style="position: relative;
top: 5px;">
            <span class="reviewName" style="color:#c52b2b"> <?= $data->user->name ?></span>
        <? } ?>
        <span class="reviewDate"><?= $data->dateFormat(); //date('d-m-Y',$data->created) ?></span>
    </div>

    <div class="reviewText">
        <?= $data->content ?>
    </div>
</li>