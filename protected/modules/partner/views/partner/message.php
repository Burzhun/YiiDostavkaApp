<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <? foreach ($message as $m) { ?>
        <div class="alert alert-success">
            <? /*button class="close" data-dismiss="alert" id="<?php echo $m->id?>">?</button*/ ?>
            <?php echo $m->date; ?> - <?php echo $m->text; ?>
        </div>
        <br>
    <?php } ?>
</div>