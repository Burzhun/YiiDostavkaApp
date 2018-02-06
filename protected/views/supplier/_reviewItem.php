<?/** @var Review $data */?>
<li>
    <div class="topBox">
        <? if ($data->review == 1) { ?>
            <img src="/images/reviewLike.png">
        <? } elseif ($data->review == 2) { ?>
            <img src="/images/reviewDisLike.png">
        <? } ?>
        <span class="reviewName"><?= $data->user->name ?></span>
        <span class="reviewDate"><?= $data->dateFormat(); //date('d-m-Y',$data->created) ?></span>
    </div>

    <div class="reviewText">
        <?= $data->content ?>
    </div>
</li>