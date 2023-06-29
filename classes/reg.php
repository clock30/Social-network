<?php
namespace classes;
class Reg extends Page{
    public function echoPassword(){
        if(isset($_POST['password'])){
            return $_POST['password'];
        }
        else return "";
    }

    public function echoPassword2(){
        if(isset($_POST['password2'])){
            return $_POST['password2'];
        }
        else return "";
    }

    public function echoEmail(){
        if(isset($_POST['email'])){
            return $_POST['email'];
        }
        else return "";
    }

    public function checkLogin($login){
        if ($login != '') {
            if (preg_match('#^[a-zA-Z0-9-_]{3,20}$#', $login) == 0) {
                return "&nbsp;&nbsp;<span style='color:red;'>Логин должен содержать следующие символы: a-z, A-Z, 0-9, -, _ . Длина не более 12 символов.</span>";
            } else {
                require 'classes/link.php';

                $query = "SELECT * FROM auth WHERE login='$login'";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;
                if (!empty($data)) {
                    return "&nbsp;&nbsp;<span style='color:red;'>Пользователь с таким логином существует. Выберите другой логин.</span>";
                } else return "";
            }
        }
        else return "&nbsp;&nbsp;<span style='color:red;'>Введите логин.</span>";
    }

    public function checkName($name){
        if($name==''){
            return "&nbsp;&nbsp;<span style='color:red;'>Введите Ваше имя</span>";
        }
        else return "";
    }

    public function checkPatronymic($patronymic){
        if($patronymic==''){
                return "&nbsp;&nbsp;<span style='color:red;'>Введите Ваше отчество</span>";
        }
        else return "";
    }

    public function checkSurname($surname){
        if ($surname == '') {
            return "&nbsp;&nbsp;<span style='color:red;'>Введите Вашу фамилию</span>";
        }
        else return "";
    }

    public function checkBirth_date($birth_date){
        if ($birth_date == '') {
            return "&nbsp;&nbsp;<span style='color:red;'>Введите Дату рождения</span>";
        }
        else return "";
    }

    public function checkEmail($email){
        if ($email != '') {
            if (preg_match('#^[a-zA-Z0-9-.]+@[a-zA-Z0-9-]+\.[a-z]{2,10}$#', $email) == 0) {
                return "&nbsp;&nbsp;<span style='color:red;'>Email должен соответствовать формату: example@email.com</span>";
            }
            else return "";
        }
        else return "&nbsp;&nbsp;<span style='color:red;'>Введите email.</span>";
    }

    public function checkPassword($password){
        if($password!=''){
            $az = preg_match('#[a-zа-яё]+#u',$password);
            $AZ = preg_match('#[A-ZА-ЯЁ]+#u',$password);
            $num = preg_match('#[0-9]+#u',$password);
            //$sym = preg_match('#[._\-+\*/\\\\%@\#\$&?!{}<>,=;:\^~\[\]|№]+#u',$password);
            $sym = preg_match('#^[^\'\"]*$#u',$password);
            $lenght = preg_match('#.{10,16}#u',$password);
            if(($lenght+$num+$AZ+$az+$sym)<5){
                return "&nbsp;&nbsp;<span style='color:red;'>Пароль должен содержать минимум одну маленькую букву, одну большую букву, одну цифру, не содержать ковычки и состоять из 10-16 символов</span>";
            }
            else return "";
        }
        else return "&nbsp;&nbsp;<span style='color:red;'>Введите пароль</span>";
    }

    public function checkConfirmPassword($password, $confirm_password){
        if($password!=''&&$confirm_password!=''){
            if ($password === $confirm_password){
                return "";
            }
            else return "&nbsp;&nbsp;<span style='color:red;'>Повторно введенный пароль не совпадает с первоначальным.</span>";
        }
        if($password!=''&&$confirm_password==''){
            return "&nbsp;&nbsp;<span style='color:red;'>Повторите пароль.</span>";
        }
        else return "";
    }

    public function reg(){
        if(isset($_POST['hidden'])) {
            if ($this->checkLogin($_POST['login']) == '' && $this->checkPassword($_POST['password']) == '' && $this->checkEmail($_POST['email']) == '' && $this->checkName($_POST['name']) == ''
                && $this->checkSurname($_POST['surname']) == '' && $this->checkPatronymic($_POST['patronymic']) == '' && $this->checkBirth_date($_POST['birth_date']) == '') {
                $login = $_POST['login'];
                $pass = $_POST['password'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $name = $_POST['name'];
                $patronymic = $_POST['patronymic'];
                $surname = $_POST['surname'];
                $birth_date = date('Y-m-d',strtotime($_POST['birth_date']));
                $email = $_POST['email'];
                $date = date('Y-m-d H:i:s', time());
                $status_id = 2;

                require 'classes/link.php';

                $query = "SELECT * FROM auth WHERE login='$login'";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;

                if (empty($data)) {
                    $query = "INSERT INTO auth (login, password, pass, name, patronymic, surname, birth_date, email, reg_date, status_id) VALUES ('$login', '$password', '$pass', '$name', '$patronymic', '$surname', '$birth_date', '$email', '$date', '$status_id')";
                    mysqli_query($link, $query) or die(mysqli_error($link));

                    $_SESSION['auth'] = true;
                    $_SESSION['login'] = $_POST['login'];
                    $_SESSION['status_id'] = 2;
                    $id = mysqli_insert_id($link);
                    $_SESSION['id'] = $id; // пишем id в сессию
                    $_SESSION['reg'] = "<p style='color: green;'>Регистрация успешно завершена, Вы авторизированы.</p>";

                    header("Location: auth.php");
                }
            }
        }
    }
}
