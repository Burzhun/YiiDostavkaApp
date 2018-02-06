<?php
/* @var $this ReviewController */
/* @var $data Review */
?>

<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('review')); ?>:</b>
    <?php echo CHtml::encode($data->review); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('partner_id')); ?>:</b>
    <?php echo CHtml::encode($data->partner_id); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('visible')); ?>:</b>
    <?php echo CHtml::encode($data->visible); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
    <?php echo CHtml::encode($data->content); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
    <?php echo CHtml::encode($data->user_id); ?>
    <br/>

    <b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
    <?php echo CHtml::encode($data->created); ?>
    <br/>


</div>