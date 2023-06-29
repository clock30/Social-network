<?php
namespace classes;
class Profile extends Page{

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

    public function gag(){
        require 'classes/link.php';
        $id = $_GET['id'];
        $query = "SELECT * FROM auth WHERE id='$id'";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        $data = mysqli_fetch_assoc($result);
        $status_id = $data['status_id'];
        $gag_from_auth = $data['gag'];
        $timestamp_current = time();
        $timestamp_gag_from_auth = strtotime($gag_from_auth);
        if($status_id!=1) {
            if (($_SESSION['status_id']) == 1) {
                if ($timestamp_gag_from_auth <= $timestamp_current) {
                    return "&nbsp;&nbsp;<form action='' method='POST' style='display: inline-block;'><label class='alert'>Наложить кляп на <input type='text' name='gag' size='3' style='text-align: center;'> дней <input type='submit' name='put_gag' value='Выполнить'></label></form>";
                } else return "&nbsp;&nbsp;<form method='POST' action='' style='display: inline-block;'><label class='alert'>На пользователя наложен кляп до <b>$gag_from_auth</b>. <input type='submit' name='off_gag' value='Снять'></label></form>";
            }
            if (($_SESSION['status_id']) == 2) {
                if ($timestamp_gag_from_auth > $timestamp_current) {
                    return "&nbsp;&nbsp;<span class='alert'>На пользователя наложен кляп до <b>$gag_from_auth</b></span>";
                } else return "";
            }
        }
        else return "";
    }

    public function putGag(){
        if(isset($_POST['put_gag'])&&isset($_POST['gag'])){
            $id = $_GET['id'];
            $current_date = date_create(date('Y-m-d H:i:s',time()));
            $gag_days = (int)$_POST['gag'];
            $gd = date_modify($current_date, "$gag_days days");
            $gag_date = date_format($gd,'Y-m-d H:i:s');
            require 'classes/link.php';
            $query = "UPDATE auth SET gag='$gag_date' WHERE id='$id'";
            mysqli_query($link,$query) or die(mysqli_error($link));
            header("Location: profile.php?id=$id");
        }
    }

    public function offGag(){
        $id = $_GET['id'];
        if(isset($_POST['off_gag'])){
            require 'classes/link.php';
            $off_gag = date('Y-m-d H:i:s', time());
            $query = "UPDATE auth SET gag='$off_gag' WHERE id='$id'";
            mysqli_query($link,$query) or die(mysqli_error($link));
            header("Location: profile.php?id=$id");
        }
    }

    public function profilePhoto(){
        require 'classes/link.php';
        $id = $_GET['id'];
        $query = "SELECT * FROM auth WHERE id='$id'";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        $data = mysqli_fetch_assoc($result);
        $null = NULL;
        if($data['profile_photo']!=null){
            $profile_photo = "<img class='profile_photo' alt='profile_photo' src='/users/".$data['login']."/icons/".$data['profile_photo']."'>";
            if(is_file("users/".$data['login']."/icons/".$data['profile_photo'])===false){
                $query = "UPDATE auth SET profile_photo='$null' WHERE id='$id'";
                mysqli_query($link,$query) or die(mysqli_error($link));
            }
        }
        else $profile_photo ="<img class='profile_photo' alt='profile_photo' src='/img/photo_profile.jpg'>";
        return $profile_photo;
    }

    public function friendship(){
        if($_SESSION['id']!=$_GET['id']){
            require 'classes/link.php';
            $query = "SELECT * FROM offer_friendship WHERE (addressee_friendship='$_GET[id]' AND sender_friendship='$_SESSION[id]') OR (addressee_friendship='$_SESSION[id]' AND sender_friendship='$_GET[id]')";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            if(!empty($data)){
                $date = date_create($data['date']);
                $current_date = time();
                date_modify($date, '30 days');
                $date = date_format($date,'Y-m-d H:i:s');
                if(strtotime($date)<$current_date){
                    $query = "DELETE FROM offer_friendship WHERE (addressee_friendship='$_GET[id]' AND sender_friendship='$_SESSION[id]') OR (addressee_friendship='$_SESSION[id]' AND sender_friendship='$_GET[id]')";
                    mysqli_query($link,$query) or die(mysqli_error($link));
                    return "<form class='friendship' method='POST' action=''><input type='hidden' name='addressee_friendship' value='".$_GET['id']."'><input type='hidden' name='sender_friendship' value='".$_SESSION['id']."'><input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'><input type='submit' name='friendship' value='Добавить в друзья'></form>";
                }
                else{
                    if($_GET['id']==$data['sender_friendship']){
                        if($data['addressee_friendship_cancel']==0) {
                            $query = "SELECT login FROM auth WHERE id='$_GET[id]'";
                            $result = mysqli_query($link, $query) or die(mysqli_error($link));
                            $login = mysqli_fetch_assoc($result);
                            return "<p class='friendship'><b>$login[login]</b> предлагает дружбу</p><form class='friendship' method='POST' action=''><input type='hidden' name='addressee_friendship' value='$data[addressee_friendship]'><input type='hidden' name='sender_friendship' value='$data[sender_friendship]'><input type='hidden' name='date' value='" . date('Y-m-d H:i:s', time()) . "'><input type='submit' name='accept_friendship' value='Принять'></form>";
                        }
                        else return "<form class='friendship' method='POST' action=''><input type='hidden' name='addressee_friendship' value='".$_GET['id']."'><input type='hidden' name='sender_friendship' value='".$_SESSION['id']."'><input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'><input type='submit' name='friendship' value='Добавить в друзья'></form>";
                    }
                    else return "<p class='friendship'>Запрос на добавление в друзья отправлен</p>";
                }
            }
            if(empty($data)) {
                $query = "SELECT * FROM friendship WHERE (addressee_friendship='$_GET[id]' AND sender_friendship='$_SESSION[id]') OR (addressee_friendship='$_SESSION[id]' AND sender_friendship='$_GET[id]')";
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                $data2 = mysqli_fetch_assoc($result);
                if(!empty($data2)){
                    return "<p class='friendship'>Вы и <b>".$this->getlogin($_GET['id'])."</b> друзья</p>";
                }
                else return "<form class='friendship' method='POST' action=''><input type='hidden' name='addressee_friendship' value='".$_GET['id']."'><input type='hidden' name='sender_friendship' value='".$_SESSION['id']."'><input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'><input type='submit' name='friendship' value='Добавить в друзья'></form>";
            }
            else return "<form class='friendship' method='POST' action=''><input type='hidden' name='addressee_friendship' value='".$_GET['id']."'><input type='hidden' name='sender_friendship' value='".$_SESSION['id']."'><input type='hidden' name='date' value='".date('Y-m-d H:i:s')."'><input type='submit' name='friendship' value='Добавить в друзья'></form>";
        }
        else return "";
    }

    public function offerFriendship(){
        if(isset($_POST['friendship'])){
            $addressee_friendship = $_POST['addressee_friendship'];
            $sender_friendship = $_POST['sender_friendship'];
            $date = $_POST['date'];
            require 'classes/link.php';
            $query = "SELECT * FROM offer_friendship WHERE (addressee_friendship='$addressee_friendship' AND sender_friendship='$sender_friendship') OR (addressee_friendship='$sender_friendship' AND sender_friendship='$addressee_friendship')";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            if(empty($data)){
                $query = "INSERT into offer_friendship SET addressee_friendship='$addressee_friendship', sender_friendship='$sender_friendship', date='$date'";
                mysqli_query($link,$query) or die(mysqli_error($link));
                header("Location: profile.php?id=$_GET[id]");
            }
            else{
                if($data['addressee_friendship_cancel']==1 && $data['addressee_friendship']==$_SESSION['id']){
                    $query = "UPDATE offer_friendship SET addressee_friendship='$addressee_friendship', sender_friendship='$sender_friendship', date='$date', addressee_friendship_cancel=0 WHERE (addressee_friendship='$_SESSION[id]') AND (sender_friendship='$addressee_friendship')";
                    mysqli_query($link,$query) or die(mysqli_error($link));
                    header("Location: profile.php?id=$_GET[id]");
                }
                else header("Location: profile.php?id=$_GET[id]");
            }
        }
        else return "";
    }

    public function friendshipOfferList(){
        require 'classes/link.php';
        $query = "SELECT * FROM offer_friendship WHERE addressee_friendship='$_SESSION[id]' AND addressee_friendship_cancel=0";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
        $exit_data = "";
        if($_SESSION['id'] == $_GET['id']) {
            if(isset($_SESSION['friendship'])){
                $exit_data .= $_SESSION['friendship'];
                unset($_SESSION['friendship']);
            }
            if (!empty($data)) {
                $exit_data .= "<div class='friendship_offer'>";
                foreach ($data as $item) {
                    $exit_data .= "<p><b><a href='profile.php?id=" . $item['sender_friendship'] . "'>" . $this->getlogin($item['sender_friendship']) . "</a></b> предлагает дружбу</p>
                    <form method='POST' action=''><input type='hidden' name='addressee_friendship' value='" . $item['addressee_friendship'] . "'>
                    <input type='hidden' name='sender_friendship' value='" . $item['sender_friendship'] . "'><input type='hidden' name='date' value='" . date('Y-m-d H:i:s', time()) . "'>
                    <input type='submit' name='accept_friendship' value='Принять'>&nbsp;&nbsp;<input type='submit' name='cancel_friendship' value='Отклонить'></form>";
                }
                $exit_data .= "</div>";
            }
        }
        return $exit_data;
    }

    public function addresseeFriendshipAnswer(){
        require 'classes/link.php';
        if(isset($_POST['cancel_friendship'])){
            $query = "UPDATE offer_friendship SET addressee_friendship_cancel=1 WHERE (addressee_friendship='$_POST[addressee_friendship]') AND (sender_friendship='$_POST[sender_friendship]')";
            mysqli_query($link,$query) or die(mysqli_error($link));
            header("Location: profile.php?id=$_GET[id]");
        }
        elseif (isset($_POST['accept_friendship'])){
            $query = "INSERT INTO friendship SET addressee_friendship='$_POST[addressee_friendship]', sender_friendship='$_POST[sender_friendship]', date='$_POST[date]'";
            mysqli_query($link,$query) or die(mysqli_error($link));
            $query = "DELETE FROM offer_friendship WHERE (addressee_friendship='$_POST[addressee_friendship]') AND (sender_friendship='$_POST[sender_friendship]')";
            mysqli_query($link,$query) or die(mysqli_error($link));
            $_SESSION['friendship'] = "<p class='answer_friendship'>Вы и <b>".$this->getlogin($_POST['sender_friendship'])."</b> теперь друзья!</p>";
            header("Location: profile.php?id=$_GET[id]");
        }
        else return "";
    }
}