<? $this->widget('DivaPopupHeaderWidget', array(
    'icon' => 'center16',
    'title' => 'Реквизиты партнера',
    'params' => array(
        array('label' => 'Название', 'value' => $model->partner->name),
    )
)); ?>

<section class="main">
    <div class="row-fluid">
        <div class="span6">
            <label>Название:</label> - <?php echo $model->partner->name ?><br>
            <label>Город:</label> - <?php echo $model->partner->city->name ?><br>
            <label>Email:</label> - <?php echo $model->partner->user->email ?><br>
            <label>Телефон:</label> - <?php echo $model->partner->user->phone ?><br>
        </div>
        <br>
        <form>
            <label>Написать сообщение</label><br>
            <textarea id="text" name="text" rows="3" cols="40"></textarea>
            <input id="partner" name="partner" type="hidden" value="<?php echo $model->partner->id; ?>">
            <br>
            <?php echo CHtml::ajaxSubmitButton('Отправить', '/message/ajaxCreate',
                array(
                    'type' => 'POST',
                    'success' => 'function(data) {
                        if(data == "Сообщение отправленно!"){
                            $("#text").hide();
                            $("#submitButton").hide();
                        }
                        $("#success").text(data);
                    }',
                ),
                array(
                    'type' => 'submit',
                    'id' => 'submitButton'
                )); ?>
            <br>
            <span id="success"></span>
        </form>
    </div>
</section>

<? $this->widget('DivaPopupFooterWidget', array(
    'rightBar' => array(
        array('type' => 'button', 'preset' => 'close')
    )
)); ?>