<?php
class Admin extends Page{
    public function getAdminPanel(){
        if(isset($_SESSION['auth'])&&($_SESSION['status_id']==1)){
            require 'classes/link.php';

            $query = "SELECT auth.id, auth.login, auth.pass, auth.surname, auth.name, auth.patronymic, auth.birth_date, auth.reg_date, status.status FROM auth LEFT JOIN status ON auth.status_id=status.id";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

            $admin = "<br><table class='admin' cellpadding='2'>";
            $admin.= "<tr><td>id</td><td>Логин</td><td>Пароль</td><td>Фамилия</td><td>Имя</td><td>Отчество</td><td>Дата рождения</td><td>Дата регистрации</td><td>Статус</td></tr>";
            for($i=0;$i<count($data);$i++) {
                if($data[$i]['status']=='admin'){
                    $admin.= "<tr style='background-color: lightsteelblue;'>";
                }
                else $admin.= "<tr>";
                foreach($data[$i] as $value) {
                    $admin.= "<td>$value</td>";
                }
                $admin.= "<td><a href='delete_user.php?id=".$data[$i]['id']."' style='color: red;'>Удалить</a></td>";
                $admin.= "<td><a href='change_status.php?id=".$data[$i]['id']."&status=".$data[$i]['status']."' style='color: saddlebrown;'>Изменить статус</a></td>";
                $admin.= "</tr>";
            }
            $admin.= "</table>";
            if(isset($_SESSION['delete_user'])){
                echo $_SESSION['delete_user'];
                unset($_SESSION['delete_user']);
            }
            if(isset($_SESSION['change_status'])){
                echo $_SESSION['change_status'];
                unset($_SESSION['change_status']);
            }
            return $admin;
        }
        else header("Location: auth.php");
    }

    public function changeStatus(){
        if(isset($_SESSION['status_id'])) {
            if ($_SESSION['status_id'] == 1) {
                require 'classes/link.php';

                if(isset($_GET['id'])&&isset($_GET['status'])){
                    $id = $_GET['id'];
                    $status = $_GET['status'];
                    $query = "SELECT auth.id, auth.login, auth.gag, status.status FROM auth LEFT JOIN status ON auth.status_id=status.id WHERE auth.id='$id'";
                    $result = mysqli_query($link,$query) or die(mysqli_error($link));
                    $data = mysqli_fetch_assoc($result);
                    $user = $data['login'];

                    if($status=='admin'){
                        $new_status = 2;
                    }
                    if($status=='user'){
                        $new_status = 1;
                        $gag_from_auth = $data['gag'];
                        $timestamp_current = time();
                        $timestamp_gag_from_auth = strtotime($gag_from_auth);
                        $time_current = date('Y-m-d H:i:s',time());
                        if ($timestamp_gag_from_auth > $timestamp_current) {
                            $query = "UPDATE auth SET gag='$time_current' WHERE id='$id'";
                            mysqli_query($link,$query) or die(mysqli_error($link));
                        }
                    }
                    $query = "UPDATE auth SET status_id='$new_status' WHERE id='$id'";
                    mysqli_query($link, $query) or die(mysqli_error($link));

                    $query = "SELECT * FROM status WHERE id='$new_status'";
                    $result = mysqli_query($link, $query) or die(mysqli_error($link));
                    $status_changed = mysqli_fetch_assoc($result);

                    $_SESSION['change_status'] = "<p style='color: red;'>Статус пользователя <b>$user</b> изменен на <b>$status_changed[status]</b></p>";
                    header("Location: admin.php");
                }
                else header("Location: auth.php");
            }
            else header("Location: auth.php");
        }
        else header("Location: auth.php");
    }

    public function deleteUser(){
        if(isset($_SESSION['status_id'])) {
            if ($_SESSION['status_id'] == 1) {
                require 'classes/link.php';

                if(isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $query = "SELECT * FROM auth WHERE id='$id'";
                    $result = mysqli_query($link, $query) or die(mysqli_error($link));
                    $data = mysqli_fetch_assoc($result);
                    $user = $data['login'];
                    $path = "users/" .$user;
                    if(is_dir($path)){
                        $this->deleteUserDir($path);
                    }
                    $query = "DELETE FROM posts WHERE user_id='$id'";
                    mysqli_query($link, $query) or die(mysqli_error($link));
                    $query = "DELETE FROM messages WHERE addressee='$id' OR sender='$id'";
                    mysqli_query($link, $query) or die(mysqli_error($link));
                    $query = "DELETE FROM friendship WHERE addressee_friendship='$id' OR sender_friendship='$id'";
                    mysqli_query($link, $query) or die(mysqli_error($link));
                    $query = "DELETE FROM auth WHERE id='$id'";
                    mysqli_query($link, $query) or die(mysqli_error($link));
                    $_SESSION['delete_user'] = "<p style='color: red;'>Пользователь <b>$user</b> удален</p>";
                    header("Location: admin.php");
                }
                else header("Location: auth.php");
            }
            else header("Location: auth.php");
        }
        else header("Location: auth.php");
    }

    public function deleteUserDir($path){
        if(!empty(array_diff(scandir($path),['.','..']))){
            foreach(array_diff(scandir($path),['.','..']) as $item) {
                $path2 = $path;
                $path .= "/".$item;
                if (is_file($path)) {
                    unlink($path);
                } else {
                    $this->deleteUserDir($path);
                }
                $path = $path2;
            }
        }
        rmdir($path);
    }
}