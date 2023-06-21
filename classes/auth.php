<?php
class Auth extends Page{

    public function age(){
        $date = $this->birth_date();
        $years = 0;
        while(strtotime(date_format(date_modify(date_create($date),'1 years'),'Y-m-d'))<=time()){
            $date = date_format(date_modify(date_create($date),'1 years'),'Y-m-d');
            $years++;
        }
        return $years;
    }

    public function login(){
        if(isset($_SESSION['auth'])) {
            require 'classes/link.php';
            $id = $_GET['id'];
            $query = "SELECT * FROM auth WHERE id='$id'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            return $data['login'];
        }
    }

    public function name(){
        if(isset($_SESSION['auth'])) {
            require 'classes/link.php';
            $id = $_GET['id'];
            $query = "SELECT * FROM auth WHERE id='$id'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            return $data['name'];
        }
    }
    public function patronymic(){
        if(isset($_SESSION['auth'])) {
            require 'classes/link.php';
            $id = $_GET['id'];
            $query = "SELECT * FROM auth WHERE id='$id'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            return $data['patronymic'];
        }
    }

    public function surname(){
        if(isset($_SESSION['auth'])) {
            require 'classes/link.php';
            $id = $_GET['id'];
            $query = "SELECT * FROM auth WHERE id='$id'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            return $data['surname'];
        }
    }

    public function birth_date(){
        if(isset($_SESSION['auth'])) {
            require 'classes/link.php';
            $id = $_GET['id'];
            $query = "SELECT * FROM auth WHERE id='$id'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            return $data['birth_date'];
        }
    }

    public function email(){
        if(isset($_SESSION['auth'])) {
            require 'classes/link.php';
            $id = $_GET['id'];
            $query = "SELECT * FROM auth WHERE id='$id'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            return $data['email'];
        }
    }
    public function authForm(){
        if (!isset($_SESSION['auth'])) {
            return "<h1 style='font-size: 20px;'>Пройдите авторизацию</h1>
    <form method='POST' action=''>
        <label>Введите логин: <input type='text' name='login' size='10'></label><br><br>
        <label>Введите пароль: <input type='password' name='password' size='10'></label><br><br>
        <input type='submit' name='submit' value='Войти'><br><br>
        <p>Если у Вас нет учетной записи, <a href='reg.php' style='color: black;'>зарегистрируйтесь</a>.</p>
    </form>";
        }
        else return "";
    }

    public function auth(){
        if(isset($_POST['login'])&&isset($_POST['password'])){
            $login = $_POST['login'];
            $query = "SELECT * FROM auth WHERE login='$login'";

            require 'classes/link.php';
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
            if(!empty($data)){
                $hash = $data[0]['password'];
                $id = $data[0]['id'];
                if(password_verify($_POST['password'], $hash)) {
                    $_SESSION['auth'] = true;
                    $_SESSION['status_id'] = $data[0]['status_id'];
                    $_SESSION['login'] = $login;
                    $_SESSION['id'] = $id;
                    $_SESSION['menu'] = true;
                    header("Location: profile.php?id=$id");
                }
                else{
                    $_SESSION['flash'] = "<p style='color: red;'>Вы ввели неправильный логин или пароль</p>";
                    header('Location: auth.php');
                }
            }
            else{
                $_SESSION['flash'] = "<p style='color: red;'>Вы ввели неправильный логин или пароль</p>";
                header('Location: auth.php');
            }
        }
    }
}