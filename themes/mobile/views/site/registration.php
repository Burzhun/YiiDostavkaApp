<script type="text/javascript">
    $(document).on("click", ".reg-button-top-b", function (event) {
        $.ajax({
            url: '<? echo CController::createUrl('/site/registrationAjax');?>',
            type: "post",
            dataType: "json",
            cache: false,
            data: $("#regForm").serialize(),
            <?/*//data:{"name":$('#RegistrationForm_name').attr('value'), "email":$('#RegistrationForm_email').attr('value'), "pass":$('#RegistrationForm_password').attr('value')},*/?>
            success: function (data) {
                if (data['error']) {
                    $("#reg-error").html(data['error']);
                }
                if (data['redirect']) {
                    //alert(111);
                    window.location.href = '' + data['redirect'];
                }
            }
        });
        return false;
    });
</script>

<div id="reg-form">
    <form action="" method="post" id="regForm">
        <p class="reg-title">РЕГИСТРАЦИЯ</p>
        <span id="reg-error" style="color:#DD3333"></span>

        <p><span id="reg-name-field">ВАШЕ ИМЯ*</span><input name="RegistrationForm[name]" id="RegistrationForm_name"
                                                            type="text"></p>

        <p><span id="reg-email-field">E-MAIL*</span><input name="RegistrationForm[email]" id="RegistrationForm_email"
                                                           type="text"></p>

        <p><span id="reg-pass-field">ПАРОЛЬ*</span><input name="RegistrationForm[password]"
                                                          id="RegistrationForm_password" type="password"></p>

        <p><span id="reg-phone-field">Телефон*</span><input name="RegistrationForm[phone]" id="RegistrationForm_phone"
                                                            type="phone"></p>
        <button class="reg-button-top-b">Зарегистрировать</button>
    </form>
</div>