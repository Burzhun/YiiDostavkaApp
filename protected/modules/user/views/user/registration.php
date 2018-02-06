<div class="body-bg bg-none"></div>
<div class="page" id="page">
    <div class="blok">
        <p class="crumbs"><a href="">главная</a> / <a class="crumbs_a" href="">Вход</a></p>
        <div class="page_basket">
            <div class="basket_home">
                <h1>Вход</h1>

                <div class="well">
                    <?php if (Yii::app()->user->hasFlash('registration')) { ?>
                        <div class="success" style="font-size:17px;">
                            <?php echo Yii::app()->user->getFlash('registration'); ?>
                        </div>
                    <?php } else { ?>
                        <div class="order_left">
                            <?php $form = $this->beginWidget('UActiveForm', array(
                                'id' => 'registration-form',
                                'enableAjaxValidation' => true,
                                'disableAjaxValidationAttributes' => array('RegistrationForm_verifyCode'),
                                'htmlOptions' => array('enctype' => 'multipart/form-data'),
                            )); ?>
                            <br>
                            <?php echo $form->errorSummary(array($model)); ?>
                            <br>
                            <table>
                                <tr>
                                    <td><b>Введите email</b></td>
                                    <td><?php echo $form->textField($model, 'email'); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $form->error($model, 'email'); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Ваше Имя *</b></td>
                                    <td><?php echo $form->textField($model, 'name'); ?></td>
                                </tr>
                                <? if (isset($_GET['user_id'])) { ?>
                                    <input type="hidden" value="<?= $_GET['user_id']; ?>" name="user_from_id">
                                <? } ?>
                                <tr>
                                    <td><?php echo $form->error($model, 'name'); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Введите пароль *</b></td>
                                    <td><?php echo $form->passwordField($model, 'pass'); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $form->error($model, 'pass'); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Повторите пароль *</b></td>
                                    <td><?php echo $form->passwordField($model, 'verifyPassword'); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $form->error($model, 'verifyPassword'); ?></td>
                                </tr>
                            </table>
                            <p class="mandatory">* - обязательно нужно заполнить!</p>
                            <?php echo CHtml::submitButton(UserModule::t(""), array('class' => 'button_reg', 'style' => "background:url('/images/img_input.png') no-repeat; border-style: none;;width:75px;height:37px;cursor:pointer;")); ?>
                            <?php $this->endWidget(); ?>
                            <table>
                                <tr>
                                    <td><?php echo CHtml::link('Зарегистрироваться как партнер', '/partners'); ?></td>
                                </tr>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
