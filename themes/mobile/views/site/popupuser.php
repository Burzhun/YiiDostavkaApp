<? $this->widget('DivaPopupHeaderWidget', array(
    'icon' => 'center16',
    'title' => 'Реквизиты пользователя',
    'params' => array(
        array('label' => 'Название', 'value' => $model->customer_name),
    )
)); ?>

    <section class="main">
        <div class="row-fluid">
            <div class="span6">
                <label>Имя:</label> - <?php echo $model->customer_name ?><br>
                <label>Телефон:</label> - <?php echo $model->phone ?><br>
                <label>Город:</label> - <?php echo $model->city ?><br>
                <label>Улица:</label> - <?php echo $model->street ?><br>
                <label>Дом:</label> - <?php echo $model->house ?><br>
                <label>Этаж:</label> - <?php echo $model->storey ?><br>
                <label>Номер квартиры/офиса:</label> - <?php echo $model->number ?><br>
            </div>
            <br><br>
        </div>
    </section>

<? $this->widget('DivaPopupFooterWidget', array(
    'rightBar' => array(
        array('type' => 'button', 'preset' => 'close')
    )
)); ?>