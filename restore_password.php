<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic' . MY_DS . 'phpmailer.php');
require_once('generic/functions.php');
$title = $page_name = 'Восстановление доступа';
include('generic/header.php');
$user_id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
$hash_md5 = !empty($_GET['hash']) ? $_GET['hash'] : null;

if (!$user_id || !$hash_md5) {
    exit;
}


//ищем по id пользователя
$stmt = $connect->prepare("SELECT * FROM users WHERE id=:user_id");
$stmt->execute(array('user_id' => $user_id));
$result = $stmt->fetch(PDO::FETCH_ASSOC);

//сравниваем хеш
if (($result) && ($hash_md5 === md5($result['hash']))) {


    //все нормально


        ?>
<script type="text/javascript">
   swal({
      title: "Смена пароля",
      text: "<input style='display:block' type='password' placeholder='Новый пароль' id='new_password' reguired><input style='display:block' type='password' placeholder='Новый пароль (еще раз)' id='new_password_repeat' reguired>",
      confirmButtonText: "Готово",
      showCancelButton: false,
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      html: true
    },function(){
        var new_password = $('#new_password').val();
        var new_password_repeat = $('#new_password_repeat').val();

        if (new_password && new_password_repeat && (new_password===new_password_repeat)) {

            if (new_password.length<5){
                swal.showInputError("Пароль слишком короткий (не менее 5 символов)");
                return false;
            }

            $.ajax({
                type:'POST',
                url: "/save_new_password",
                data: {
                    'id':<?php echo($user_id);?>,
                    'hash':'<?php echo($hash_md5);?>',
                    'password':new_password
                }
            }).done(function(data) {
                if (data == 1) {
                    document.location.href = '/enter';
                } else if (data == 0) {
                    swal.showInputError("Переданы некорректные данные");
                }
            });
        }
    });
        </script>
    <?php



}
    else {



    //такая почта уже зарегистрирована

    ?>
    <script type="text/javascript">
swal({
      title: "Ошибка!",
      text: "Данная ссылка не корректна. \n Возможно она активирована ранее.",
      type:'warning',
      showCancelButton: false,
      closeOnConfirm: false
      },function(){
         document.location.href = '/';
      });
    </script>
    <?php
}
?>


<?php include('generic/footer.php'); ?>
