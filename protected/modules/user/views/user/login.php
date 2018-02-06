<div class="body-bg bg-none"></div>
<div class="page" id="page">
    <div class="blok">
        <p class="crumbs"><a href="">главная</a> / <a class="crumbs_a" href="">Вход</a></p>
        <div class="page_basket">
            <div class="basket_home">
                <h1>Вход</h1>
                <div class="well">
                    <?php if (Yii::app()->user->hasFlash('loginMessage')): ?>

                        <div class="success">
                            <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="order_left">
                        <?php echo CHtml::beginForm(); ?>
                        <div class="reg_table">
                            <tr>
                                <?php echo CHtml::errorSummary($model); ?>
                            </tr>
                            <table>
                                <tr>
                                    <td><b>Ваше Имя *</b></td>
                                    <td><?php echo CHtml::activeTextField($model, 'username') ?></td>
                                </tr>
                                <tr>
                                    <td><b>Пароль *</b></td>
                                    <td><?php echo CHtml::activePasswordField($model, 'password') ?></td>
                                </tr>
                            </table>
                        </div>
                        <p class="mandatory">* - обязательно нужно заполнить!</p>

                        <p class="mandatory"><?php echo CHtml::link('Забыли пароль?', array('/user/recovery')); ?></p>
                        <?php echo CHtml::submitButton(UserModule::t(""), array('class' => 'button_reg', 'style' => "background:url('/images/img_input.png') no-repeat; border-style: none;;width:75px;height:37px;cursor:pointer;")); ?>
                        <?php echo CHtml::endForm(); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php
    $form = new CForm(array(
        'elements' => array(
            'username' => array(
                'type' => 'text',
                'maxlength' => 32,
            ),
            'password' => array(
                'type' => 'password',
                'maxlength' => 32,
            ),
            'rememberMe' => array(
                'type' => 'checkbox',
            )
        ),

        'buttons' => array(
            'login' => array(
                'type' => 'submit',
                'label' => 'Войти',
            ),
        ),
    ), $model);
    ?>
</div>