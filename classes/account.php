<?php
namespace classes;
class Account extends Page{
    public function echoLogin()
    {
        if (isset($_POST['login'])) {
            return $_POST['login'];
        }
        else {
            require 'classes/link.php';

            $id = $_SESSION['id'];
            $query = "SELECT login FROM auth WHERE id='$id'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            return $data['login'];
        }
    }

    public function echoName(){
        if(isset($_POST['name'])){
            return $_POST['name'];
        }
        else{
            require 'classes/link.php';

            $id = $_SESSION['id'];
            $query = "SELECT name FROM auth WHERE id='$id'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            return $data['name'];
        }
    }

    public function echoPatronymic(){
        if(isset($_POST['patronymic'])){
            return $_POST['patronymic'];
        }
        else{
            require 'classes/link.php';

            $id = $_SESSION['id'];
            $query = "SELECT patronymic FROM auth WHERE id='$id'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            return $data['patronymic'];
        }
    }

    public function echoSurname(){
        if(isset($_POST['surname'])){
            return $_POST['surname'];
        }
        else{
            require 'classes/link.php';

            $id = $_SESSION['id'];
            $query = "SELECT surname FROM auth WHERE id='$id'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            return $data['surname'];
        }
    }

    public function echoBirth_date(){
        if(isset($_POST['birth_date'])){
            return $_POST['birth_date'];
        }
        else{
            require 'classes/link.php';

            $id = $_SESSION['id'];
            $query = "SELECT birth_date FROM auth WHERE id='$id'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            return $data['birth_date'];
        }
    }

    public function echoEmail(){
        if(isset($_POST['email'])){
            return $_POST['email'];
        }
        else{
            require 'classes/link.php';

            $id = $_SESSION['id'];
            $query = "SELECT email FROM auth WHERE id='$id'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            return $data['email'];
        }
    }

    public function checkLogin($login){
        if(isset($_POST['submit'])) {
            if ($login != '') {
                if (preg_match('#^[a-zA-Z0-9-_]{3,20}$#', $login) == 0) {
                    return "&nbsp;&nbsp;<span style='color:red;'>Логин должен содержать следующие символы: a-z, A-Z, 0-9, -, _ . Длина от 3 до 12 символов.</span>";
                }
                else {
                    require 'classes/link.php';
                    $id=$_SESSION['id'];

                    $query = "SELECT * FROM auth WHERE id='$id'";
                    $result = mysqli_query($link, $query) or die(mysqli_error($link));
                    $data = mysqli_fetch_assoc($result);
                    $old_login = $data['login'];

                    $query = "SELECT * FROM auth WHERE login='$login'";
                    $result = mysqli_query($link, $query) or die(mysqli_error($link));
                    $data = mysqli_fetch_assoc($result);

                    if (!empty($data)){
                        if($_POST['login']!=$old_login) {
                            return "&nbsp;&nbsp;<span style='color:red;'>Пользователь с таким логином существует. Выберите другой логин.</span>";
                        }
                        else return "";
                    }
                    else return "";
                }
            }
            else return "&nbsp;&nbsp;<span style='color:red;'>Введите логин.</span>";
        }
        else return "";
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
            if (preg_match('#^[a-zA-Z0-9-]+@[a-zA-Z0-9-]+\.[a-z]{2,10}$#', $email) == 0) {
                return "&nbsp;&nbsp;<span style='color:red;'>Email должен соответствовать формату: example@email.com</span>";
            }
            else return "";
        }
        else return "&nbsp;&nbsp;<span style='color:red;'>Введите email.</span>";
    }

    public function checkPassword_old($login,$password_old){
        if(isset($_POST['submit'])) {
            if($this->checkLogin($login)=='') {
                if ($password_old != '') {
                    require 'classes/link.php';

                    $id = $_SESSION['id'];
                    $query = "SELECT * FROM auth WHERE id='$id'";
                    $result = mysqli_query($link,$query) or die(mysqli_error($link));
                    $data = mysqli_fetch_assoc($result);
                    if(!empty($data)){
                        $hash = $data['password'];
                        if(password_verify($password_old,$hash)){
                            return "";
                        }
                        else return "&nbsp;&nbsp;<span style='color:red;'>Неправильно введен логин или пароль.</span>";
                    }
                }
                else return "&nbsp;&nbsp;<span style='color:red;'>Введите пароль</span>";
            }
            else return "";
        }
        else return "";
    }

    public function checkPassword($login,$password_old,$password){
        if(isset($_POST['submit'])) {
            if($this->checkLogin($login)==''&&$this->checkPassword_old($login,$password_old)=='') {
                if($password!=''){
                    $az = preg_match('#[a-zа-яё]+#u', $password);
                    $AZ = preg_match('#[A-ZА-ЯЁ]+#u', $password);
                    $num = preg_match('#[0-9]+#u', $password);
                    $sym = preg_match('#^[^\'\"]*$#u', $password);
                    $lenght = preg_match('#.{10,16}#u', $password);
                    if (($lenght + $num + $AZ + $az + $sym) < 5) {
                        return "&nbsp;&nbsp;<span style='color:red;'>Пароль должен содержать минимум одну маленькую букву, одну большую букву, одну цифру, не содержать ковычки и состоять из 10-16 символов</span>";
                    }
                    else return "";
                }
                else return "";
            }
            else return "";
        }
        else return "";
    }

    public function checkConfirmPassword($login,$password_old,$password, $confirm_password){
        if(isset($_POST['submit'])) {
            if($password!='' && $this->checkPassword($login,$password_old,$password)=='') {
                if ($confirm_password != '') {
                    if ($password === $confirm_password) {
                        return "";
                    } else return "&nbsp;&nbsp;<span style='color:red;'>Повторно введенный пароль не совпадает с первоначальным.</span>";
                }
                else return "&nbsp;&nbsp;<span style='color:red;'>Повторите новый пароль .</span>";
            }
            elseif($password=='' && $confirm_password != ''){
                return "&nbsp;&nbsp;<span style='color:red;'>Повторно введенный пароль не совпадает с первоначальным.</span>";
            }
            else return "";
        }
        else return "";
    }

    public function account(){
        if(isset($_POST['submit'])) {
            if ($this->checkEmail($_POST['email']) == '' && $this->checkName($_POST['name']) == '' && $this->checkSurname($_POST['surname']) == '' && $this->checkPatronymic($_POST['patronymic']) == '' && $this->checkBirth_date($_POST['birth_date']) == '') {
                $id = $_SESSION['id'];
                $name = $_POST['name'];
                $patronymic = $_POST['patronymic'];
                $surname = $_POST['surname'];
                $birth_date = date('Y-m-d', strtotime($_POST['birth_date']));
                $email = $_POST['email'];

                require 'classes/link.php';

                $query = "SELECT * FROM auth WHERE id='$id'";
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                $data = mysqli_fetch_assoc($result);

                if ($name != $data['name'] || $patronymic != $data['patronymic'] || $surname != $data['surname'] || $birth_date != $data['birth_date'] || $email != $data['email']) {
                    $_SESSION['personal_data_change'] = "<p style='color:green;'>Персональные данные успешно изменены.</p>";
                } else $_SESSION['personal_data_change'] = "<p style='color:green;'>Персональные данные не изменены. Вы отправили новые значения персональных данных без изменений.</p>";

                $query = "UPDATE auth SET name='$name', patronymic='$patronymic', surname='$surname', birth_date='$birth_date', email='$email' WHERE id='$id'";
                mysqli_query($link, $query) or die(mysqli_error($link));

                header('Location: account.php');
            }
        }
    }

    public function change_pass(){
        if(isset($_POST['submit'])) {
            if ($this->checkLogin($_POST['login']) == '' && $this->checkPassword_old($_POST['login'],$_POST['password_old']) == '' && $this->checkPassword($_POST['login'],$_POST['password_old'],$_POST['password']) == '' && $this->checkConfirmPassword($_POST['login'],$_POST['password_old'],$_POST['password'],$_POST['confirm_password']) == '') {
                $id = $_SESSION['id'];
                $login = $_POST['login'];
                $pass = $_POST['password'];
                $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

                require 'classes/link.php';

                $query = "SELECT * FROM auth WHERE id='$id'";
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                $data = mysqli_fetch_assoc($result);
                $old_login = $data['login'];

                if($_POST['password']!=''){
                    if ($login != $data['login'] && $password != $data['password']) {
                        $_SESSION['personal_data_change'] = "<p style='color:green;'>Логин и пароль успешно изменены.</p>";
                    }
                    if($login == $data['login'] && $password != $data['password']){
                        $_SESSION['personal_data_change'] = "<p style='color:green;'>Пароль успешно изменен.</p>";
                    }
                    if ($login != $data['login'] && $password == $data['password']){
                        $_SESSION['personal_data_change'] = "<p style='color:green;'>Логин успешно изменен.</p>";
                    }
                    if ($login == $data['login'] && $password == $data['password']){
                        $_SESSION['personal_data_change'] = "<p style='color:green;'>Персональные данные не изменены. Вы отправили новые значения персональных данных без изменений.</p>";
                    }

                    $query = "UPDATE auth SET login='$login', password='$password', pass='$pass' WHERE id='$id'";
                    mysqli_query($link,$query) or die(mysqli_error($link));
                }

                if($_POST['password']==''){
                    if ($login != $data['login']) {
                        $_SESSION['personal_data_change'] = "<p style='color:green;'>Логин успешно изменен.</p>";
                    }
                    if($login == $data['login']){
                        $_SESSION['personal_data_change'] = "<p style='color:green;'>Персональные данные не изменены. Вы отправили новые значения персональных данных без изменений.</p>";
                    }

                    $query = "UPDATE auth SET login='$login' WHERE id='$id'";
                    mysqli_query($link,$query) or die(mysqli_error($link));
                }
                if(is_dir('users/'.$old_login)===true){
                    rename('users/'.$old_login,'users/'.$login);
                }
                else mkdir('users/'.$login);
                header('Location: change_pass.php');
            }
        }
    }

    public function echoPasswordDeleteAccount(){
        if(isset($_POST['password'])){
            return $_POST['password'];
        }
        else return "";
    }

    function checkPasswordDeleteAccount($password){
        $id = $_SESSION['id'];
        if(isset($_POST['submit'])) {
            if ($_POST['password'] != '') {
                require 'classes/link.php';

                $query = "SELECT * FROM auth WHERE id='$id'";
                $result = mysqli_query($link, $query) or die(mysqli_erro($link));
                $data = mysqli_fetch_assoc($result);
                if (password_verify($_POST['password'], $data['password']) == 1) {
                    return "";
                }
                else return "&nbsp;&nbsp;<span style='color: red;'>Вы ввели неправильный пароль</span>";
            }
            else return "&nbsp;&nbsp;<span style='color: red;'>Введите пароль</span>";
        }
        else return "";
    }

    public function checkConfirmPasswordDeleteAccount($password,$confirm_password){
        if($confirm_password!=''&&$this->checkPasswordDeleteAccount($password)==''){
            if($password!=''){
                if($password===$confirm_password){
                    return "";
                }
                else return "&nbsp;&nbsp;<span style='color: red;'>Повторно введенный пароль не совпадает с первоначальным</span>";
            }
            else return "";
        }
        if($confirm_password==''&&$this->checkPasswordDeleteAccount($password)==''){
            if($password!=''){
                return "&nbsp;&nbsp;<span style='color: red;'>Подтвердите пароль</span>";
            }
            else return "";
        }
        else return "";
    }

    public function deleteAccount(){
        if(isset($_POST['submit'])){
            if($this->checkPasswordDeleteAccount($_POST['password'])==''&&$this->checkConfirmPasswordDeleteAccount($_POST['password'],$_POST['confirm_password'])=='') {
                if (isset($_SESSION['id']) && isset($_SESSION['auth'])) {
                    $id = $_SESSION['id'];
                    require 'classes/link.php';
                    $query = "SELECT * FROM auth WHERE id='$id'";
                    $result = mysqli_query($link,$query) or die(mysqli_query($link));
                    $data = mysqli_fetch_assoc($result);
                    $login = $data['login'];
                    $query = "DELETE FROM auth WHERE id='$id'";
                    mysqli_query($link, $query) or die(mysqli_query($link));
                    unset($_SESSION['auth']);
                    unset($_SESSION['menu']);
                    unset($_SESSION['login']);
                    unset($_SESSION['status_id']);
                    unset($_SESSION['id']);
                    if(is_dir('users/'.$login)===true){
                        $this->deleteUserDir('users/'.$login);
                    }
                    $_SESSION['delete_account'] = "<p style='color: green;'>Аккаунт $login удален.</p>";
                    header('Location: auth.php');
                }
            }
        }
    }
}