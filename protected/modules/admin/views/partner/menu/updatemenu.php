<?php $this->renderPartial('header', array('model' => $model, 'breadcrumbs' => $breadcrumbs, 'h1' => $h1)) ?>

<div class="well">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data')
    )); ?>

    <p class="note"> Поля с <span class="required">*</span> обязательны для заполнения.</p>

    <?php echo $form->errorSummary($menu_model); ?>

    <?php echo $form->labelEx($menu_model, 'name'); ?>
    <?php echo $form->textField($menu_model, 'name', array('class' => 'txt', 'size' => 60, 'maxlength' => 255, 'style' => 'margin:0px;')); ?>
    <?php echo $form->error($menu_model, 'name'); ?>

    <input type="hidden" value="<?php echo $menu_model->parent_id ?>" name="oldparent" id="oldparent">
    <?php //if ($menu_model->parent_id != 0) { ?>
        <?php echo $form->labelEx($menu_model, 'parent_id'); ?>
        <?php echo $form->dropDownList($menu_model, 'parent_id',['Главное меню'] +CHtml::listData(Menu::model()->findAll(array('condition' => 'parent_id=0 AND partner_id=' . $menu_model->partner_id.' and id<>'.$menu_model->id)), 'id', 'name')); ?>
        <?php echo $form->error($menu_model, 'parent_id'); ?>
    <?php// } ?>
<br>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => "btn btn-primary")); ?>

    <?php $this->endWidget(); ?>
</div>
<script>
    $("window").ready(function(){
        var submit_form=false;
        var last=-1;
        $("#user-form").submit(function(){
            var t=$("#user-form select").val();
            if(t==0){
                return true;
            }else{
                if(last==t){
                    return submit_form;
                }else{
                    $.get('/admin/partner/checkmenu/' +t,{},function(data){
                        if(data==1){
                            submit_form=true;
                            last=t;
                            $("#user-form").submit();
                        }
                        if(data==0){
                            submit_form=false;
                            alert('Удалите товары из раздела для добавления подкатегорий');
                        }
                    });
                }

            }
            return false;
        });
    });
</script>