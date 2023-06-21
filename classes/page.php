<?php
class Page{
    public function header(){
        if (isset($_SESSION['auth'])) {
            if(isset($_SESSION['status_id'])){
                if($_SESSION['status_id']==1){
                    $menu_admin = "<b><a class='menu' href='admin.php'>Страница администратора</a></b>&nbsp;&nbsp;";
                }
                else $menu_admin = "";
                $menu = "<a class='menu' style='color: brown;' href='profile.php?id=$_SESSION[id]'>Мой профиль</a>&nbsp;&nbsp;
                <a class='menu' href='feed.php?id=$_SESSION[id]'>Мои заметки</a>&nbsp;&nbsp;
                <a class='menu' href='gallery.php?id=$_SESSION[id]'>Фотографии</a>&nbsp;&nbsp;
                <a class='menu' href='mail.php?id=$_SESSION[id]'>Почта".$this->unreadMessages()."</a>&nbsp;&nbsp;
                <a class='menu' href='friends.php?id=$_SESSION[id]'>Друзья</a>&nbsp;&nbsp;
                <a class='menu' href='forum.php'>Форум</a>&nbsp;&nbsp;<a class='menu' href='users.php'>Поиск</a>&nbsp;&nbsp;
                <a class='menu' href='account.php'>Личный кабинет</a>&nbsp;&nbsp;
                ".$menu_admin."
                <a class='menu' href='exit.php'>Выход</a>";
            }
            else $menu ="";
        }
        else $menu ="";
        return "<header><h1>Социальная сеть</h1><div class='menu'>".$menu."</div></header>";
    }

    public function footer(){
        if (isset($_SESSION['auth'])) {
            if(isset($_SESSION['status_id'])){
                if($_SESSION['status_id']==1){
                    $footer_admin = "";
                }
                else $footer_admin = "";
                $footer = "<footer><p>&copy; Социальная сеть <span>".$footer_admin."</span></p></footer>";
            }
            else $footer ="";
        }
        else $footer ="";
        return $footer;
    }

    public function echoLogin(){
        if (isset($_POST['login'])){
            return $_POST['login'];
        }
        else return "";
    }

    public function echoName(){
        if(isset($_POST['name'])){
            return $_POST['name'];
        }
        else return "";
    }

    public function echoPatronymic(){
        if(isset($_POST['patronymic'])){
            return $_POST['patronymic'];
        }
        else return "";
    }

    public function echoSurname(){
        if(isset($_POST['surname'])){
            return $_POST['surname'];
        }
        else return "";
    }

    public function echoBirth_date(){
        if(isset($_POST['birth_date'])){
            return $_POST['birth_date'];
        }
        else return "";
    }

    public function getlogin($id)
    {
        require 'classes/link.php';

        $query = "SELECT login FROM auth WHERE id='$id'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        $data = mysqli_fetch_assoc($result);
        return $data['login'];
    }

    public function getId(){
        if(isset($_GET['id'])){
            return $_GET['id'];
        }
        else return "";
    }

    public function getName($id){
        require 'classes/link.php';
        $query = "SELECT name FROM auth WHERE id='$id'";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        $data = mysqli_fetch_assoc($result);
        return $data['name'];
    }

    public function getSurname($id){
        require 'classes/link.php';
        $query = "SELECT surname FROM auth WHERE id='$id'";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        $data = mysqli_fetch_assoc($result);
        return $data['surname'];
    }

    public function getUsers(){
        require 'classes/link.php';

        $query = "SELECT * FROM auth ORDER BY login ASC";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
        $users = '';
        for($i=0;$i<count($data);$i++){
            $users.= "<a style='color:black;' href='profile.php?id=".$data[$i]['id']."'>".$data[$i]['login']."</a><br>";
        }
        return $users;
    }

    public function exitAuth(){
        unset($_SESSION['auth']);
        unset($_SESSION['menu']);
        unset($_SESSION['login']);
        unset($_SESSION['status_id']);
        unset($_SESSION['id']);
        $_SESSION['exit'] = "Уважаемый пользователь, Вы вышли из своей учетной записи";
        header('Location: auth.php');
    }

    public function checkComment($comment){
        if($comment==''){
            return "<span class='alert'>Вы не можете отправить пустой комментарий</span><br>";
        }
        else{
            if(preg_match('#^[А-ЯЁа-яё\sA-Za-z0-9-_,.!?%"()^/|$*+\\\\@№&<>:;=~—\#{}]{1,1000}$#u',$comment)==1){
                return "";
            }
            else return "<span class='alert'>Текст комментария должен быть длиной до 1000 символов. Также не допускается использование одинарных ковычек</span><br>";
        }
    }

    public function checkGag(){
        $id = $_SESSION['id'];
        require 'classes/link.php';
        $query = "SELECT * FROM auth WHERE id='$id'";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        $data = mysqli_fetch_assoc($result);
        $timestamp_current = time();
        $timestamp_gag_from_auth = strtotime($data['gag']);
        if($timestamp_gag_from_auth>$timestamp_current){
            return "<span class='alert'>Вы не можете оставлять комментарии, на Вас наложен кляп до <b>$data[gag]</b></span><br>";
        }
        else return "";
    }

    public function writeMessage(){
        if($_SESSION['id']!=$_GET['id']){
            return "&nbsp;&nbsp;<a class='profile_message' href='mail.php?id=$_SESSION[id]&addressee=$_GET[id]'>Написать сообщение</a>";
        }
        else return "";
    }

    public function searchUsers(){
        if(isset($_POST['user_search'])){
            if($_POST['login'] != "" || $_POST['surname'] != "" || $_POST['name'] != "" || $_POST['patronymic'] != "" || $_POST['birth_date'] != "") {
                require 'classes/link.php';
                $query = "SELECT id, login, surname, name, patronymic, birth_date FROM auth WHERE";
                if ($_POST['login'] != "") {
                    $query .= " login LIKE '%$_POST[login]%'";
                }
                if ($_POST['surname'] != "") {
                    if ($_POST['login'] != "") {
                        $query .= " AND surname LIKE '%$_POST[surname]%'";
                    } else $query .= " surname LIKE '%$_POST[surname]%'";
                }
                if ($_POST['name'] != "") {
                    if ($_POST['login'] != "" || $_POST['surname'] != "") {
                        $query .= " AND name LIKE '%$_POST[name]%'";
                    } else $query .= " name LIKE '%$_POST[name]%'";
                }
                if ($_POST['patronymic'] != "") {
                    if ($_POST['login'] != "" || $_POST['surname'] != "" || $_POST['name'] != "") {
                        $query .= " AND patronymic LIKE '%$_POST[patronymic]%'";
                    } else $query .= " patronymic LIKE '%$_POST[patronymic]%'";
                }
                if ($_POST['birth_date'] != "") {
                    if ($_POST['login'] != "" || $_POST['surname'] != "" || $_POST['name'] != "" || $_POST['patronymic'] != "") {
                        $query .= " AND birth_date LIKE '%$_POST[birth_date]%'";
                    } else $query .= " birth_date LIKE '%$_POST[birth_date]%'";
                }
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;
                if(empty($data)){
                    return "<p>По Вашему запросу ничего не найдено</p>";
                }
                else {
                    if (count($data) > 15) {
                        return "<span class='alert'>Уточните параметры поиска, чтобы уменьшить количество результатов</span>";
                    } else {
                        $exit_data = "";
                        $number = 1;
                        foreach ($data as $item) {
                            $exit_data .= "<p>$number. <a target='_blank' style='color: #680425;' href='profile.php?id=" . $item['id'] . "'>$item[login]</a> <span>$item[surname]</span> <span>$item[name]</span> <span>$item[patronymic]</span> <span>$item[birth_date]</span></p>";
                            $number++;
                        }
                        return $exit_data;
                    }
                }
            }
            else return "<span class='alert'>Заполните хотя бы одно поле в форме поиска</span>";
        }
        else return "<p>Заполните форму поиска и нажмите кнопку НАЙТИ</p>";
    }

    public function unreadMessages(){
        require 'classes/link.php';
        $query = "SELECT readed_addressee FROM messages WHERE addressee='$_SESSION[id]' AND readed_addressee=0";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
        if(empty($data)){
            return "";
        }
        else return "<span class='unreaded_messages'>(".count($data).")</span>";
    }
}