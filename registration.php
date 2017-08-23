<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Регистрация';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic' . MY_DS . 'actions.php');
$invite_table = 'ok_imports';
$register = !empty($_GET['register']) ? $_GET['register'] : null;
require_once('generic/generic_functions.php');


///////////include('generic/auth_control.php');
include('generic/header.php');
?>
<style>
.sa-error-container{
    margin-top:10px;
}
</style>

<script type="text/javascript">







swal({
      title: "Регистрация",
      text: "<input style='display:block' type='email' placeholder='Ваш email' id='email_swal' reguired><input style='display:block' type='text' placeholder='Придумайте пароль' id='password_swal' reguired><div style='text-align:center;' class='btn btn-sm btn-success' id='generate_password'>сгенерировать пароль автоматически</div>",
      confirmButtonText: "Готово",
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      html: true
    },function(){
        var email = $('#email_swal').val();
        var password = $('#password_swal').val();

        if (!validate_email(email)){
            swal.showInputError("Неверно введен email");
            return false;
        }
        if (password.length<5){
            swal.showInputError("Пароль слишком короткий (не менее 5 символов)");
            return false;
        }
            if (password && email && validate_email(email)){

                $.ajax({
                    url: "/register_user.php",
                    data: {
                        'email':email,
                        'password':password
                        }
                }).done(function(data) {
                    if (data==0){
                        swal.showInputError("Такой email уже существует");
                    } else if (data==1){
                        swal.showInputError("Письмо с подтверджением уже отправлено");
                    } else if (data==2){
                        swal({
                          title: "Регистрация почти закончена!",
                          text: "Вам отправлено письмо с подтверждением. Пожалуйста, войдите в свою почту, чтобы закончить регистрацию.",
                          type: "success",
                          showCancelButton: false,
                          closeOnConfirm: false
                        },
                        function(){
                          window.location.replace('/');
                        });
                    } else if (data==99){
                        swal.showInputError("Ошибка отправки. Приносим свои извинения. Пожалуйста обратитесь в службу поддержки или попробуйте чуть позже");
                    }
                });

            } else {
                return false;
            }
    });
$("#email_swal").change( function(){

        if (!validate_email($(this).val())){
            $(this).css('border','1px solid #b40000');
        } else {
            $(this).css('border','1px solid #04b400');
        }
});

$('#generate_password').click(function(){
    $('#password_swal').val(my_create_password());
});




/*
$.ajax({
    url: "/register_user.php",
    data: {
                    'client_code':'<?php echo($client_code);?>'
                    }
            }).done(function(data) {

//если код уже существует - перезагружаем страницу
if (data == 0) {
    document.location.href = '/main/' + my_create_password() + '?firstly=1';
    return;
}




    swal({
      title: "Добро пожаловать на вашу персональную страницу!",
      text: "Вы сможете заходить на неё по прямой ссылке или со страницы <a href='http://inviter.biz/main'>inviter.biz/main</a>, используя ваш персональный код: <b><?php echo($client_code);?></b><br><i>(код присутствует в адресе)</i><br><br>Вы можете привязать свой email для восстановления кода <input style='display:block' type='text' placeholder='Привязать email (не обязательно)' id='email_swal'>",
      confirmButtonText: "Готово",
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      html: true
    },function(){
        var value = $('#email_swal').val();
        function go(){
            document.location.href = '/main/<?php echo($client_code);?>';
        }
        if (!value) {
            go();
        } else {
            $.ajax({
                url: "/save_email.php",
                data: {
                    'email':value,
                    'client_code':'<?php echo($client_code);?>'
                    }
            }).done(function(data) {

                if (data==0){
                    swal.showInputError("Такой email уже существует");
                    $('.sa-input-error').removeClass('show');
                    $('button.confirm').prop("disabled", false);

                } else {
                go()
                }
            });
        }
    });
});*/
</script>



<?php include('generic/footer.php'); ?>