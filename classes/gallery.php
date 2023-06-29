<?php namespace classes;
class Gallery extends Page{

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

    public function uploadPhotoForm(){
        $id = $_SESSION['id'];
        if($id==$_GET['id']){
            return "<form class='upload_photo' method='POST' action='/scripts/upload.php' enctype='multipart/form-data'>
        <label>Загрузить фото <input type='file' name='uploadedFile' value='Загрузить фото'></label>&nbsp;&nbsp;<input type='submit' name='uploadBtn' value='Загрузить'>
    </form>";
        }
        else return "";
    }

    public function getPhotos(){
        require 'classes/link.php';
        $id = $_GET['id'];
        $query = "SELECT * FROM auth WHERE id='$id'";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        $data = mysqli_fetch_assoc($result);
        if(is_dir('users/'.$data['login'].'/icons/')===true){
            $files = scandir('users/'.$data['login'].'/icons/');
            $files = array_diff($files,['.','..']);
            $photos = '';
            foreach($files as $item){
                if($_SESSION['id']==$_GET['id']){
                    $make_main = "<a class='make_main' href='/gallery.php?id=$id&makemain=$item'>Сделать главным</a><br>
                    <a class='make_main' href='/gallery.php?id=$id&delete_photo=$item'>Удалить фото</a>";
                }
                else $make_main = "";
                $photos .= "<div class='photo'><a class='photo' href='gallery.php?id=$id&view_photo=$item'><img alt='photo-$data[login]' class='photo' src='users/$data[login]/icons/$item'></a>".$make_main."</div>";
            }
        }
        else $photos = '';
        return $photos;
    }

    public function makeMain(){
        if(isset($_SESSION['auth'])) {
            $id = $_SESSION['id'];
            $file = $_GET['makemain'];
            if ($_GET['id']==$id) {
                require 'classes/link.php';
                $query = "UPDATE auth SET profile_photo='$file' WHERE id='$id'";
                mysqli_query($link,$query) or die(mysqli_error($link));
                header("Location: gallery.php?id=$id");
            }
            else header("Location: auth.php");
        }
        else header("Location: auth.php");
    }

    public function deletePhoto(){
        if(isset($_SESSION['auth'])) {
            $id = $_SESSION['id'];
            $file = $_GET['delete_photo'];
            $arr_file_comments = explode('.',$file);
            $arr_file_comments[1] = 'txt';
            $file_comments = implode('.',$arr_file_comments);
            if ($_GET['id']==$id) {
                require 'classes/link.php';
                $query = "SELECT * FROM auth WHERE id='$id'";
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                $data = mysqli_fetch_assoc($result);
                unlink('users/'.$data['login'].'/photos/'.$file);
                unlink('users/'.$data['login'].'/icons/'.$file);
                if(file_exists('users/'.$data['login'].'/comments/'.$file_comments)===true){
                    unlink('users/'.$data['login'].'/comments/'.$file_comments);
                }
                header("Location: gallery.php?id=$id");
            }
            else header("Location: auth.php");
        }
        else header("Location: auth.php");
    }

    public function galleryBack(){
        if(isset($_GET['view_photo'])){
            $id = $_GET['id'];
            $layout = "<div class='gallery'></div>"; // <a class='gallery' href='profile.php?id=".$id."'></a>
        }
        else $layout = "";
        return $layout;
    }

    public function galleryDiv(){
        if(isset($_GET['view_photo'])){
            $layout = file_get_contents('templates/gallery_div.php');
        }
        else $layout = "";
        return $layout;
    }

    public function getCurrentPhoto(){
        if(isset($_GET['id'])&&isset($_GET['view_photo'])) {
            require 'classes/link.php';
            $id = $_GET['id'];
            $file = $_GET['view_photo'];
            $query = "SELECT * FROM auth WHERE id='$id'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            $path = '/users/' . $data['login'] . '/photos/' . $file;
            return $path;
        }
        else return "";
    }

    public function getGalleryLogin(){
        require 'classes/link.php';
        $id = $_GET['id'];
        $query="SELECT * FROM auth WHERE id='$id'";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        $data = mysqli_fetch_assoc($result);
        $gallery_login = $data['login'];
        return $gallery_login;
    }

    public function getPreviousPhoto(){
        if(isset($_GET['id'])&&isset($_GET['view_photo'])) {
            require 'classes/link.php';
            $id = $_GET['id'];
            $query = "SELECT * FROM auth WHERE id='$id'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            $path = 'users/' . $data['login'] . '/photos';
            $files = scandir($path);
            $files = array_diff($files, ['.', '..']);
            $file = $_GET['view_photo'];
            $current = array_search($file, $files);
            $previous = $current - 1;
            if (isset($files[$previous])) {
                $previous_photo_link = "gallery.php?id=$id&view_photo=$files[$previous]";
            } else $previous_photo_link = "gallery.php?id=$id&view_photo=$files[$current]";
            return $previous_photo_link;
        }
        else return "";
    }

    public function getNextPhoto(){
        if(isset($_GET['id'])&&isset($_GET['view_photo'])) {
            require 'classes/link.php';
            $id = $_GET['id'];
            $query = "SELECT * FROM auth WHERE id='$id'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            $path = 'users/' . $data['login'] . '/photos';
            $files = scandir($path);
            $files = array_diff($files, ['.', '..']);
            $file = $_GET['view_photo'];
            $current = array_search($file, $files);
            $next = $current + 1;
            if (isset($files[$next])) {
                $next_photo_link = "gallery.php?id=$id&view_photo=$files[$next]";
            } else $next_photo_link = "gallery.php?id=$id&view_photo=$files[$current]";
            return $next_photo_link;
        }
        else return "";
    }

    public function profileGalleryLink(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $profile_gallery_link = "gallery.php?id=$id";
            return $profile_gallery_link;
        }
        else return "";
    }

    public function commentGallery(){
        if(isset($_POST['login'])){
            $comment_head = "<span>Вы отвечаете на комментарий пользователя <b>".$_POST['login']."</b></span><br>";
            $login = $_POST['login'];
        }
        else {
            $comment_head = "<p>Оставьте Ваш комментарий:</p>";
            $login = "";
        }
        if(isset($_POST['comment'])){
            $check_comment = $this->checkComment($_POST['comment']);
            $check_gag = $this->checkGag();
        }
        else {
            $check_comment = '';
            $check_gag = '';
        }

        $result = "<form method='POST' action=''><input type='hidden' name='answer_to' value='".$login."'><label>".$comment_head."<textarea name='comment' cols='40' rows='3'>".$this->echoCommentGallery()."</textarea></label><br>".$check_comment.$check_gag."<br><input type='submit' name='submit'></form><br>";
        return $result;
    }

    public function commentsGallery(){
        if(isset($_GET['view_photo'])){
            $id = $_GET['id'];
            require 'classes/link.php';
            $query = "SELECT * FROM auth WHERE id='$id'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            $path_arr = explode('.',$_GET['view_photo']);
            $path = "users/".$data['login']."/comments/".$path_arr[0].".txt";
            if(file_exists($path)){
                $result = file_get_contents($path);
            }
            else $result = "<p>Комментариев еще не было</p>";
            return $result;
        }
        else return "";
    }

    public function echoCommentGallery(){
        if(isset($_POST['comment'])){
            return $_POST['comment'];
        }
        else return "";
    }

    public function sendCommentGallery(){
        if(isset($_POST['comment'])){
            if($this->checkComment($_POST['comment'])==''&&$this->checkGag()=='') {
                $id = $_GET['id'];
                require 'classes/link.php';
                $query = "SELECT * FROM auth WHERE id='$id'";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                $data = mysqli_fetch_assoc($result);
                $login = $data['login'];
                if(is_dir("users/".$login)===false){
                    mkdir("users/".$login);
                }
                if(is_dir("users/".$login."/comments/")===false){
                    mkdir("users/".$login."/comments");
                }
                $path_arr = explode('.',$_GET['view_photo']);
                $path = "users/".$login."/comments/".$path_arr[0].".txt";
                $id_writer = $_SESSION['id'];

                if (file_exists($path)) {
                    $temp = file_get_contents($path);
                    preg_match('#<div id=.comment(?<comment_number>[0-9]+).\sclass=.comment_gallery.>#',$temp,$match);
                    $comment_number = $match['comment_number'] + 1;
                } else {
                    $temp = "";
                    $comment_number = 1;
                }
                if($_POST['answer_to']!=''){
                    $answer_to = " ответил(а) <b>$_POST[answer_to]</b>:";
                }
                else $answer_to = ":";

                $answer = "<form method='POST' action='' class='comment_gallery'><input type='hidden' name='login' value='".$this->getName($_SESSION['id'])." ".$this->getSurname($_SESSION['id'])."'><input type='submit' name='answer' class='comment_gallery_submit' value='Ответить'></form>";
                $comment = "<div id='comment" . $comment_number . "' class='comment_gallery'><p class='comment_gallery'><b><a class='comment_login' href='profile.php?id=" . $id_writer . "'>".$this->getName($_SESSION['id'])." ".$this->getSurname($_SESSION['id'])."</a></b>". $answer_to ."<br><span class='comment_gallery_content'>". strip_tags($_POST['comment'], '<a>') . "</span></p>".$answer."</div>";
                $comment .= $temp;
                file_put_contents($path, $comment);


                $view_photo = "/gallery.php?id=" . $_GET['id'] . "&view_photo=" . $_GET['view_photo'];
                header("Location: $view_photo");
            }
        }
    }

    public function deleteCommentGalleryForm(){
        $result = '';
        if(isset($_SESSION['auth'])&&$_SESSION['status_id']==1){
            $check_comment_id = $this->checkGalleryCommentId();
            $result .= "<form method='POST' action=''><label>Удалить комментарий №: <input type='text' name='comment_id' size='5'></label>&nbsp;&nbsp;<input type='submit' name='delete_comment_gallery' value='Удалить'>".$check_comment_id."</form><br>";
        }
        return $result;
    }

    public function checkGalleryCommentId(){     //Для удаления коммента
        if(isset($_POST['comment_id'])){
            $comment_id = (int)$_POST['comment_id'];
            $id = $_GET['id'];
            require 'classes/link.php';
            $query = "SELECT * FROM auth WHERE id='$id'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            $login = $data['login'];
            $view_photo = $_GET['view_photo'];
            $path_arr = explode('.',$view_photo);
            $path = "users/".$login."/comments/".$path_arr[0].".txt";
            if(file_exists($path)) {
                $comment_layout = file_get_contents($path);
                preg_match('#<div id=.comment(?<count>[0-9]+).\sclass=.comment_gallery.>#',$comment_layout,$match);
                $count = $match['count'];
                if ($comment_id >= 1 && $comment_id <= $count) {
                    return "";
                } else return "<span class='alert'>&nbsp;&nbsp;Номер комментария должен находится в пределах от 1 до $count</span>";
            }
            else return "<span class='alert'>&nbsp;&nbsp;Пользователи еще не оставляли комментариев</span>";
        }
        else return "";
    }

    public function deleteCommentGallery(){
        if(isset($_POST['delete_comment_gallery'])){
            if($this->checkGalleryCommentId()==""){
                $view_photo = $_GET['view_photo'];
                $path_arr = explode('.',$view_photo);
                $path = $path_arr[0];
                $comment_id = $_POST['comment_id'];
                $id = $_GET['id'];
                require 'classes/link.php';
                $query = "SELECT * FROM auth WHERE id='$id'";
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                $data = mysqli_fetch_assoc($result);
                $login = $data['login'];
                $comments_layout = file_get_contents("users/".$login."/comments/".$path.".txt");
                $pattern = '#<div\sid=.comment'.$comment_id.'.\sclass=.comment_gallery.><p\sclass=.comment_gallery.><b><a\sclass=.comment_login.\shref=.profile\.php\?id=(?<login_id>[0-9]+?).>.+?</a>(?<answer_to>.*?)</b>:<br><span\sclass=.comment_gallery_content.>.+?</span></p><form\smethod=.POST.\saction=..\sclass=.comment_gallery.><input\stype=.hidden.\sname=.login.\svalue=..+?.><input\stype=.submit.\sname=.answer.\sclass=.comment_gallery_submit.\svalue=.Ответить.></form></div>#us';

                if(preg_match($pattern,$comments_layout,$match)==1){
                    require 'classes/link.php';
                    $login_id = $match['login_id'];
                    $query = "SELECT * FROM auth WHERE id='$login_id'";
                    $result = mysqli_query($link,$query) or die(mysqli_error($link));
                    $data_writer = mysqli_fetch_assoc($result);
                    $replace = "<div id='comment".$comment_id."' class='comment_gallery'><p class='comment_gallery'><b><a class='comment_login' href='profile.php?id=".$data_writer['id']."'>".$data_writer['name']." ".$data_writer['surname']."</a>".$match['answer_to']."</b>:<br><span class='comment_gallery_content'>Комментарий удален</span></p></div>";
                    $comments_layout = preg_replace($pattern,$replace,$comments_layout);
                    file_put_contents("users/".$data['login']."/comments/".$path.".txt",$comments_layout);
                    header("Location: gallery.php?id=$id&view_photo=$view_photo");
                }
                else{
                    header("Location: gallery.php?id=$id&view_photo=$view_photo");
                }

            }
        }
    }
}