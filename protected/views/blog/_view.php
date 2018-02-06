<? /*h2><a href="/blog/<?=$data->id?>"><?=$data->title?></a></h2><br>
<?=$data->date?><br>
<?=$data->shorttext?><br>
<?if($data->tags){?>
	Теги: 
	<?foreach ($data->tags as $t) {?>
		<?if($t)?>
		<?=$t->name?>&nbsp&nbsp
	<?}?><br>
<?}?>
<br>
<br*/ ?>



<div class='blog_content_box'>
    <a href='/blog/<?= $data->id ?>'>
        <h2 class='blog_title'><?= $data->title ?></h2>
    </a>

    <div class='blog_block'>
        <div
            class='blog_tame'><?= date('d ' . ZHtml::rusMonth(strtotime($data->date)) . ' Y в H:i', strtotime($data->date)) ?></div>
    </div>
    <div class='blog_img'>
        <img src='/upload/post/<?= $data->img ?>'>
    </div>
    <div class='blog_text'>
        <?= $data->shorttext ?>
    </div>
    <a href='/blog/<?= $data->id ?>' class='blog_button'></a>

    <div style='clear:both'></div>
    <div class='blog_tags'>
        <? foreach ($data->tags as $t) { ?>
            <? if ($t) ?>
                <a href='/blog/category/<?= $t->tname ?>'><?= $t->name ?></a>&nbsp&nbsp
        <? } ?>
    </div>
</div>