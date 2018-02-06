<style type="text/css">
    .errorPay {
        text-align: center;
        color: red;
        font-size: 21px;
        line-height: 35px;
        display: block;
        border: 1px solid #ddd;
        position: absolute;
        left: 50%;
        top: 50%;
        padding: 30px;
        border-radius: 20px;
        transform: translate(-50%, -50%);

    }

    .errorPay .balance {

    }

    .errorPay a.back {
        display: inline-block;
        padding: 10px;
        border: 1px solid #ddd;
        margin-top: 10px;
        text-decoration: none;
    }

    .errorPay a.back:hover {
        background: #ddd;
    }
</style>
<div class="errorPay">
    <h1 style='text-align: center; color: #3D3030;'>Платеж для "<?= $partner->name ?>"</h1>
    Платеж в размере <?= $vars->amount ?> <?php echo City::getMoneyKod(); ?> завершился неудачей.
    <a class="back" href="/partner/info">Вернуться</a>
</div>