<?php

class Post extends Profile{
    public function profilePostLink(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $profile_post_link = "profile.php?id=$id";
            return $profile_post_link;
        }
        else return "";
    }

    public function profilePostLink2(){
        if(isset($_GET['id']) && isset($_GET['post_id']) && isset($_GET['comments'])){
            $id = $_GET['id'];
            $post_id = $_GET['post_id'];
            $profile_post_link2 = "profile.php?id=$id#post$post_id";
            return $profile_post_link2;
        }
        else return "";
    }

    public function writePost(){
        $exit_data = "";
        if(isset($_GET['id']) && $_SESSION['id']==$_GET['id']){
            $exit_data .= "<a class='write_post' href='profile.php?id=".$this->getId()."&post'>Написать заметку</a>";
        }
        return $exit_data;
    }

    public function postScreen(){
        $exit_data = "";
        /*if(isset($_POST['add_link'])&&isset($_POST['hidden_article'])){
            $add_link = $_POST['hidden_article']." <a class='post' href='".$_POST['add_link_target']."'>".$_POST['add_link_name']."</a> ";
            $exit_data .= "<form method='post' action=''><label>Новый абзац:<textarea class='post_screen' name='post_article'>".$add_link."</textarea></label><input type='submit' name='add_link_submit' value='Добавить ссылку в абзац'>&nbsp;&nbsp;<input type='submit' name='post_article_submit' value='Записать в конструктор'></form>";
        }
        elseif(isset($_POST['add_link'])&&isset($_POST['hidden_text'])){
            $add_link = $_POST['hidden_text']." <a class='post' href='".$_POST['add_link_target']."'>".$_POST['add_link_name']."</a> ";
            $exit_data .= "<form method='post' action=''><label>Текстовый блок:<textarea class='post_screen' name='post_text'>".$add_link."</textarea></label><input type='submit' name='add_link_submit' value='Добавить ссылку в текстовый блок'>&nbsp;&nbsp;<input type='submit' name='post_text_submit' value='Записать в конструктор'></form>";
        }
        else*/ $add_link = "";
        if(isset($_POST['head'])){
            $exit_data .= "<form method='POST' action=''><label>Заголовок: <input class='post_screen' type='text' name='post_head'></label>&nbsp;&nbsp;<input type='submit' name='post_head_submit' value='Добавить в предварительный просмотр'></form>";
        }
        if(isset($_POST['subhead'])){
            $exit_data .= "<form method='POST' action=''><label>Подзаголовок: <input class='post_screen' type='text' name='post_subhead'></label>&nbsp;&nbsp;<input type='submit' name='post_subhead_submit' value='Добавить в предварительный просмотр'></form>";
        }
        if(isset($_POST['text'])){
            $exit_data .= "<form method='POST' action=''><label>Текстовый блок:<textarea class='post_screen' name='post_text'></textarea></label><input type='submit' name='post_text_submit' value='Добавить в предварительный просмотр'></form>";
        }
        if(isset($_POST['link'])){
            $exit_data .= "<form method='POST' action=''><label>Название ссылки: <input class='post_screen' type='text' name='post_link_name'></label><br><br><label>Адрес ссылки: <input class='post_screen' type='text' name='post_link_target'></label><br><br><input type='submit' name='post_link_submit' value='Добавить в предварительный просмотр'></form>";
        }
        if(isset($_POST['photo'])){
            $exit_data .= "<form class='upload_photo' method='POST' action='/scripts/uploadphotopost.php' enctype='multipart/form-data'>
        <label>Загрузить фото <input type='file' name='uploadedFile' value='Загрузить фото'></label>&nbsp;&nbsp;<input type='submit' name='uploadBtn' value='Добавить в предварительный просмотр'>
    </form>";
        }
        if(isset($_POST['video'])){
            $exit_data .= "<form class='upload_photo' method='POST' action='/scripts/uploadvideopost.php' enctype='multipart/form-data'>
        <label>Загрузить видео <input type='file' name='uploadedFile' value='Загрузить видео'></label>&nbsp;&nbsp;<input type='submit' name='uploadBtn' value='Добавить в предварительный просмотр'>
    </form>";
        }
        return $exit_data;
    }

    /*public function addLinkToArticle(){
        $exit_data = "";
        if(isset($_POST['add_link_submit']) && isset($_POST['post_article'])){
            $exit_data .= "<form style='margin: 20px 0 10px;' method='post' action=''><input type='hidden' name='hidden_article' value='".htmlspecialchars($_POST['post_article'])."'><label>Название ссылки: <input class='post_screen' type='text' name='add_link_name'></label><br><br><label>Адрес ссылки: <input class='post_screen' type='text' name='add_link_target'></label><br><br><input type='submit' name='add_link' value='Добавить в абзац'></form>";
        }
        if(isset($_POST['add_link_submit']) && isset($_POST['post_text'])){
            $exit_data .= "<form style='margin: 20px 0 10px;' method='post' action=''><input type='hidden' name='hidden_text' value='".htmlspecialchars($_POST['post_text'])."'><label>Название ссылки: <input class='post_screen' type='text' name='add_link_name'></label><br><br><label>Адрес ссылки: <input class='post_screen' type='text' name='add_link_target'></label><br><br><input type='submit' name='add_link' value='Добавить в текстовый блок'></form>";
        }
        return $exit_data;
    }*/

    public function putInConstructor(){
        if(isset($_POST['clear_constructor'])){
            if(file_exists('users/'.$this->getlogin($_SESSION['id']).'/temp/constructor.txt')){
                unlink('users/'.$this->getlogin($_SESSION['id']).'/temp/constructor.txt');
            }
            $exit_data = "";
        }
        else{
            $file = "";
            if(file_exists('users/'.$this->getlogin($_SESSION['id']).'/temp/constructor.txt')){
                $file .= file_get_contents('users/'.$this->getlogin($_SESSION['id']).'/temp/constructor.txt');
            }
            if(isset($_POST['post_head_submit'])){
                if($file == ""){
                    $file .= "<h1 class='new_post'>".strip_tags($_POST['post_head'])."</h1>";
                }
                else $file .= "<h1 class='post'>".strip_tags($_POST['post_head'])."</h1>";
            }
            if(isset($_POST['post_subhead_submit'])){
                if($file == ""){
                    $file .= "<h2 class='new_post'>".strip_tags($_POST['post_subhead'])."</h2>";
                }
                else $file .= "<h2 class='post'>".strip_tags($_POST['post_subhead'])."</h2>";
            }
            if(isset($_POST['post_text_submit'])){
                $file .= "<span class='post'>".strip_tags($_POST['post_text'])."</span> ";
            }
            if(isset($_POST['post_link_submit'])){
                $file .= "<a class='post' target='_blank' href='".strip_tags($_POST['post_link_target'])."'>".strip_tags($_POST['post_link_name'])."</a> ";
            }
            if(isset($_POST['article'])){
                $file .= "<div class='article'></div>";
            }
            if(isset($_POST['br'])){
                $file .= "<br>";
            }
            if(isset($_POST['hr'])){
                $file .= "<hr class='post'>";
            }
            if(isset($_GET['upload_photo_filename'])){
                $file .= "<img class='post' alt='photo' src='/users/".$this->getLogin($_SESSION['id'])."/posts/photos/temp/".$_GET['upload_photo_filename']."'>";
            }
            if(isset($_GET['upload_video_filename'])){
                $file .= "<div class='post_video'><video width='".$_GET['width']."' height='".$_GET['height']."' controls='controls' src='/users/".$this->getLogin($_SESSION['id'])."/posts/videos/".$_GET['upload_video_filename']."'></video></div>";
            }
            if(is_dir('users/'.$this->getlogin($_SESSION['id']))===false) {
                mkdir('users/' . $this->getlogin($_SESSION['id']));
            }
            if(is_dir('users/'.$this->getlogin($_SESSION['id']).'/temp')===false) {
                mkdir('users/' . $this->getlogin($_SESSION['id']) . '/temp');
            }
            file_put_contents('users/'.$this->getlogin($_SESSION['id']).'/temp/constructor.txt',$file);
            if(file_exists('users/'.$this->getlogin($_SESSION['id']).'/temp/constructor.txt')){
                $exit_data = file_get_contents('users/'.$this->getlogin($_SESSION['id']).'/temp/constructor.txt');
            }
            else $exit_data = "";
        }
        if(isset($_GET['upload_video_filename']) || isset($_GET['upload_photo_filename']) || isset($_POST['hr']) || isset($_POST['br']) || isset($_POST['article']) || isset($_POST['post_link_submit']) || isset($_POST['post_text_submit']) || isset($_POST['post_subhead_submit']) || isset($_POST['post_head_submit'])){
            header("Location: /profile.php?id=$_SESSION[id]&post");
        }
        return $exit_data;
    }
    public function clearConstructor(){
        if(isset($_POST['clear_constructor'])) {
            if (is_file("users/" . $this->getLogin($_SESSION['id']) . "/temp/constructor.txt") === true){
                unlink("users/" . $this->getLogin($_SESSION['id']) . "/temp/constructor.txt");
            }
            if(is_dir("users/".$this->getLogin($_SESSION['id'])."/posts/photos/temp") === true){
                $dir = scandir("users/".$this->getLogin($_SESSION['id'])."/posts/photos/temp");
                $dir = array_diff($dir,['.','..']);
                foreach($dir as $item){
                    unlink("users/".$this->getLogin($_SESSION['id'])."/posts/photos/temp/".$item);
                }
                unlink("users/".$this->getLogin($_SESSION['id'])."/posts/photos/temp");
            }
            header("Location: /profile.php?id=$_SESSION[id]&post&upload_photo_filename");
        }
        else return "";
    }

    public function changePath($text){
        $old_path = '#<img\sclass=.post.\salt=.photo.\ssrc=./users/'.$this->getlogin($_SESSION['id']).'/posts/photos/temp/.+?\.\w+?.>#su';
            if(preg_match($old_path,$text,$match)==1) {
                $var = $match[0];
                $change = preg_replace('#temp/#', '', $var);
                $text = preg_replace('#'.$match[0].'#', $change, $text);
                if(preg_match($old_path,$text,$match)==1) {
                    $text = $this->changePath($text);
                }
            }
        return $text;
    }

    public function publicPost(){
        if(isset($_POST['public_post'])){
            require 'classes/link.php';
            if(file_exists("users/" . $this->getLogin($_SESSION['id']) . "/temp/constructor.txt")) {
                $var = file_get_contents("users/" . $this->getLogin($_SESSION['id']) . "/temp/constructor.txt");
                $text = $this->changePath($var);
                $text = mysqli_real_escape_string($link, $text);
                $head = mysqli_real_escape_string($link,"<p class='post_content'><b><a class='post_author_source' href='profile.php?id=$_SESSION[id]'>".$this->getName($_SESSION['id'])." ".$this->getSurname($_SESSION['id'])."</a></b> поделился(ась) заметкой<br>".date('Y-m-d H:i:s',time())."</p>");
                $query = "INSERT INTO posts SET user_id='$_SESSION[id]', text='$text', head='$head'";
                mysqli_query($link, $query) or die(mysqli_error($link));
                unlink("users/" . $this->getLogin($_SESSION['id']) . "/temp/constructor.txt"); //Удаляет конструктор поста
                if(is_dir("users/".$this->getLogin($_SESSION['id']) . "/posts/photos/temp")) {
                    $dir = array_diff(scandir("users/" . $this->getLogin($_SESSION['id']) . "/posts/photos/temp"), ['.', '..']);
                    foreach ($dir as $item) {  //Перемещает файлы
                        rename("users/" . $this->getLogin($_SESSION['id']) . "/posts/photos/temp/" . $item, "users/" . $this->getLogin($_SESSION['id']) . "/posts/photos/" . $item);
                    }
                }
                header("Location: /profile.php?id=$_SESSION[id]");
            }
            else header("Location: /profile.php?id=$_SESSION[id]&post");
        }
        else return "";
    }

    public function postView($is_like,$id,$user_id,$name,$surname,$date,$head,$text){
        if($is_like!=0){
            $h = "<p class='post_content'><b><a class='post_author' href='profile.php?id=$user_id'>$name $surname</a></b> считает классным:<br>$date</p><hr class='post_content'>$head<div class='article'></div>";
        }
        else $h = "<p class='post_content'><b><a class='post_author' href='profile.php?id=$user_id'>$name $surname</a></b> поделился(ась) заметкой<br>$date</p><hr class='post_content'>";
        $exit_data = "<div id='post$id' class='post_content'>$h$text<div class='clear'></div>" . $this->deletePostView($_SESSION['id'], $user_id, $id) . $this->likesView($_SESSION['id'], $user_id, $id) . $this->postCommentButton($_GET['id'],$id)."<div class='clear'></div></div>";
        return $exit_data;
    }

    public function activities(){
        $exit_data = '';
        if($_GET['id'] == $_SESSION['id']) {
            require 'classes/link.php';
            $query = "SELECT * FROM posts WHERE user_id=$_GET[id] ORDER BY id DESC";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;
            if (!empty($data)) {
                foreach($data as $item) {
                    $query = "SELECT name, surname FROM auth WHERE id='$item[user_id]'";
                    $result = mysqli_query($link, $query) or die(mysqli_error($link));
                    $name_surname = mysqli_fetch_assoc($result);
                    $exit_data .= $this->postView($item['is_like'],$item['id'],$item['user_id'],$name_surname['name'],$name_surname['surname'],$item['date'],$item['head'],$item['text']);
                }
            }
        }
        return $exit_data;
    }

    public function postFeed(){
        $exit_data = '';
        require 'classes/link.php';
        if($_GET['id'] == $_SESSION['id']) {
            $query = "SELECT * FROM friendship WHERE addressee_friendship='$_SESSION[id]' OR sender_friendship='$_SESSION[id]'";
            $result = mysqli_query($link, $query) or die($link);
            for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
            $friends_id = [];
            foreach ($data as $item){
                if($item['addressee_friendship']!=$_SESSION['id']){
                    $friends_id[] = $item['addressee_friendship'];
                }
                if($item['sender_friendship']!=$_SESSION['id']){
                    $friends_id[] = $item['sender_friendship'];
                }
            }
            $friends_id[] = $_SESSION['id'];
            if(!empty($friends_id)){
                $query = "SELECT * FROM posts WHERE user_id IN (".implode(',',$friends_id).") ORDER BY id DESC";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                for ($data_posts = []; $row = mysqli_fetch_assoc($result); $data_posts[] = $row);
                if (!empty($data_posts)) {
                    foreach ($data_posts as $item) {
                        $query = "SELECT name, surname FROM auth WHERE id='$item[user_id]'";
                        $result = mysqli_query($link, $query) or die(mysqli_error($link));
                        $name_surname=mysqli_fetch_assoc($result);
                        $exit_data .= $this->postView($item['is_like'],$item['id'],$item['user_id'],$name_surname['name'],$name_surname['surname'],$item['date'],$item['head'],$item['text']);
                    }
                }
            }
        }
        if($_GET['id'] != $_SESSION['id']){
            $query = "SELECT * FROM posts WHERE user_id='$_GET[id]' ORDER BY id DESC";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            for ($data_posts = []; $row = mysqli_fetch_assoc($result); $data_posts[] = $row);
            if (!empty($data_posts)) {
                foreach ($data_posts as $item) {
                    $query = "SELECT name, surname FROM auth WHERE id='$item[user_id]'";
                    $result = mysqli_query($link, $query) or die(mysqli_error($link));
                    $name_surname=mysqli_fetch_assoc($result);
                    $exit_data .= $this->postView($item['is_like'],$item['id'],$item['user_id'],$name_surname['name'],$name_surname['surname'],$item['date'],$item['head'],$item['text']);
                }
            }
        }
        return $exit_data;
    }

    public function deletePostView($id,$poster_id,$post_id){
        $exit_data = "";
        if ($id == $poster_id) {
            $exit_data .= "<form class='post_content' method='POST' action=''><input type='hidden' name='post_id' value='$post_id'><input class='post_content' type='submit' name='delete_post' value='Удалить заметку'></form>";
        }
        return $exit_data;
    }

    public function likesView($liker_id,$poster_id,$post_id){
        $exit_data = "";
        require 'classes/link.php';

        $query = "SELECT * FROM posts WHERE id='$post_id'";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        $post = mysqli_fetch_assoc($result);
        if($post['is_like']!=0){
            $post = $post['is_like'];
            $query = "SELECT * FROM post_likes WHERE post_id='$post'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
        }
        else{
            $post = $post_id;
            $query = "SELECT * FROM post_likes WHERE post_id='$post'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
        }

        $exit_data .= "<form class='post_like' method='POST' action=''><input type='hidden' name='post' value='$post'><input type='hidden' name='post_id' value='$post_id'><input type='hidden' name='poster_id' value='$poster_id'><input type='hidden' name='liker_id' value='$liker_id'><input type='hidden' name='time' value='".date('Y-m-d H:i:s',time())."'><input class='post_like' type='submit' name='post_like' value='Класс!'><span class='likes_count'>".count($data)."</span></form>";

        return $exit_data;
    }

    public function postCommentButton($user_id,$post_id){
        //$exit_data = "<form class='post_comment_button' method='GET' action=''><input type='hidden' name='post_id' value='$post_id'><input class='post_comment_button' type='submit' name='post_comments' value='Комментарии'></form>";
        require 'classes/link.php';
        $query = "SELECT * FROM posts WHERE id='$post_id'";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        $data = mysqli_fetch_assoc($result);
        if($data['is_like']!=0){
            $filename = $data['is_like'];
        }
        else $filename = $post_id;
        if(is_file("post_comments/$filename.txt")){
            $comments = file_get_contents("post_comments/$filename.txt");
            preg_match('#<div id=.comment(?<comment_number>[0-9]+).\sclass=.comment_post.>#',$comments,$match);
            if(!empty($match)){
                $comments_count = $match['comment_number'];
            }
            else $comments_count = 0;
        }
        else $comments_count = 0;

        $exit_data = "<a class='post_comment_button' href='profile.php?id=$user_id&post_id=$post_id&comments'>Комментарии</a>";
        $exit_data .= "<span class='post_comment_button'>$comments_count</span>";
        return $exit_data;
    }

    public function setLike(){
        if(isset($_POST['post_like'])){
            if($_POST['poster_id'] != $_SESSION['id']){
                require 'classes/link.php';
                $query = "SELECT * FROM post_likes WHERE post_id='$_POST[post]' AND liker_id='$_POST[liker_id]'";
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                $data = mysqli_fetch_assoc($result);
                if(empty($data)) {
                    $query = "INSERT INTO post_likes SET post_id='$_POST[post]', liker_id='$_POST[liker_id]', time='$_POST[time]'";
                    mysqli_query($link, $query) or die(mysqli_error($link));
                    $query = "SELECT * FROM posts WHERE id='$_POST[post_id]'";
                    $result = mysqli_query($link, $query) or die(mysqli_error($link));
                    $post = mysqli_fetch_assoc($result);
                    $head = mysqli_real_escape_string($link,$post['head']);
                    $text = mysqli_real_escape_string($link, $post['text']);
                    if($post['is_like']==0){
                        $source = $_POST['post_id'];
                    }
                    else $source = $post['is_like'];
                    $query = "INSERT INTO posts SET user_id='$_SESSION[id]', text='$text', head='$head', is_like='$source'";
                    mysqli_query($link, $query) or die(mysqli_error($link));
                    header("Location: $_SERVER[REQUEST_URI]#post$_POST[post_id]");
                }
            }
            else{
                //$_SESSION['post_like'] = "<p>Вы не можете сами себе поставить <b>Класс!</b></p>";
                header("Location: $_SERVER[REQUEST_URI]#post$_POST[post_id]");
            }
        }
        else return "";
    }

    public function thePost(){
        if(isset($_GET['post_id']) && isset($_GET['comments'])) {
            require 'classes/link.php';
            $query = "SELECT * FROM posts WHERE id='$_GET[post_id]'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $post_data = mysqli_fetch_assoc($result);
            $query = "SELECT name, surname FROM auth WHERE id='$post_data[user_id]'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $name_surname = mysqli_fetch_assoc($result);
            $exit_data = $this->postView($post_data['is_like'], $post_data['id'], $post_data['user_id'], $name_surname['name'], $name_surname['surname'], $post_data['date'], $post_data['head'], $post_data['text']);
            return $exit_data;
        }
        else return "";
    }

    public function echoPostComment(){
        if(isset($_POST['post_comments_text'])){
            return $_POST['post_comments_text'];
        }
        else return "";
    }

    public function postCommentsBlock(){
        $exit_data = "";
        if(isset($_GET['post_id']) && isset($_GET['comments'])) {
            if(isset($_POST['post_comment_answer']) && isset($_POST['login'])){
                $post_comment_head = "Вы отвечаете на комментарий пользователя $_POST[login]";
                $answer = "ответил(а) $_POST[login]:";
            }
            else {
                $post_comment_head = "Оставьте Ваш комментарий";
                $answer = ":";
            }
            if(isset($_POST['post_comments'])&&isset($_POST['post_comments_text'])){
                $check_comment = $this->checkComment($_POST['post_comments_text']);
                $check_gag = $this->checkGag();
            }
            else {
                $check_comment = '';
                $check_gag = '';
            }
            $exit_data .= "<div class='post_comments_block'><p class='post_comments_block'>$post_comment_head</p><form class='post_comments_block' method='POST' action=''><input type='hidden' name='answer_to' value='$answer'><input type='hidden' name='post_id' value='$_GET[post_id]'>
            <input type='hidden' name='writer_id' value='$_SESSION[id]'><textarea class='post_comments_block' cols='50' rows='3' name='post_comments_text'>".$this->echoPostComment()."</textarea>".$check_comment.$check_gag."<input class='post_comments_block' type='submit' name='post_comments' value='Отправить'></form></div><p><b>Комментарии:</b></p><div class='article'></div>";
            require 'classes/link.php';
            $query = "SELECT * FROM posts WHERE id='$_GET[post_id]'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            if($data['is_like']!=0){
                $filename = $data['is_like'];
            }
            else $filename = $_GET['post_id'];
            if (is_file("post_comments/$filename.txt")) {
                $exit_data .= file_get_contents("post_comments/$filename.txt");
            }
            else {
                $exit_data .= "<div class='article'></div><p style='font-style: italic;'>Комментариев еще не было</p><div class='article'></div>";
            }
        }
        return $exit_data;
    }

    public function sendPostComment(){
        if(isset($_POST['post_comments']) && isset($_POST['post_id']) && isset($_POST['writer_id']) && isset($_POST['post_comments_text'])){
            if($this->checkComment($_POST['post_comments_text'])==''&&$this->checkGag()==''){
                require 'classes/link.php';
                $query = "SELECT * FROM posts WHERE id='$_POST[post_id]'";
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                $data = mysqli_fetch_assoc($result);
                if($data['is_like']!=0){
                    $filename = $data['is_like'];
                }
                else $filename = $_POST['post_id'];

                if(is_file("post_comments/$filename.txt")===true){
                    $var = file_get_contents("post_comments/$filename.txt");
                    preg_match('#<div id=.comment(?<comment_number>[0-9]+).\sclass=.comment_post.>#',$var,$match);
                    if(!empty($match)){
                        $comment_number = $match['comment_number'] + 1;
                    }
                    else $comment_number = 1;
                }
                else{
                    $var = "";
                    $comment_number = 1;
                }
                $answer_to = $_POST['answer_to'];
                $answer = "<form method='POST' action='' class='comment_post'><input type='hidden' name='login' value='".$this->getName($_SESSION['id'])." ".$this->getSurname($_SESSION['id'])."'><input type='submit' name='post_comment_answer' class='post_comment_answer_submit' value='Ответить'></form>";
                $post_comments = "<div id='comment" . $comment_number . "' class='comment_post'><p class='comment_post'><b><a class='post_comment_login' href='profile.php?id=" . $_POST['writer_id'] . "'>".$this->getName($_SESSION['id'])." ".$this->getSurname($_SESSION['id'])."</a></b> ". $answer_to ."<br><span class='comment_post_content'>". strip_tags($_POST['post_comments_text'], '<a>') . "</span></p>".$answer."</div>";
                $post_comments .= $var;
                if(is_dir('post_comments')===false){
                    mkdir('post_comments');
                }
                file_put_contents("post_comments/$filename.txt",$post_comments);
                header("Location: $_SERVER[REQUEST_URI]");
            }
        }
        else return "";
    }

    public function deletePost(){
        if(isset($_POST['delete_post']) && isset($_POST['post_id'])){
            require 'classes/link.php';
            $query = "SELECT * FROM posts WHERE id='$_POST[post_id]'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            $img_tag_pattern = '#<img\sclass=.post.\salt=.photo.\ssrc=./users/'.$this->getlogin($_SESSION['id']).'/posts/photos/(.+?\.\w+?).>#su';
            if(preg_match_all($img_tag_pattern,$data['text'],$match)>=1){
                foreach($match[1] as $item){
                    unlink('users/'.$this->getlogin($_SESSION['id']).'/posts/photos/'.$item);
                }
            }
            $video_tag_pattern = '#<div\sclass=.post_video.><video\swidth=.[0-9]+?.\sheight=.[0-9]+?.\scontrols=.controls.\ssrc=./users/'.$this->getlogin($_SESSION['id']).'/posts/videos/(.+?\.\w+?).></video></div>#su';
            if(preg_match_all($video_tag_pattern,$data['text'],$match)>=1){
                foreach($match[1] as $item){
                    unlink('users/'.$this->getlogin($_SESSION['id']).'/posts/videos/'.$item);
                }
            }
            $query = "DELETE FROM posts WHERE id='$_POST[post_id]'";
            mysqli_query($link,$query) or die(mysqli_error($link));

            if($data['is_like']!=0){
                $query = "SELECT * FROM posts WHERE id='$data[is_like]'";
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                $source = mysqli_fetch_assoc($result);
                if(empty($source)){
                    $query = "SELECT * FROM posts WHERE is_like='$data[is_like]'";
                    $result = mysqli_query($link,$query) or die(mysqli_error($link));
                    $var = mysqli_fetch_assoc($result);
                    if(empty($var)){
                        if(is_file("post_comments/$data[is_like].txt")){
                            unlink("post_comments/$data[is_like].txt");
                        }
                    }
                }
                $query = "DELETE FROM post_likes WHERE post_id='$data[is_like]' AND liker_id='$data[user_id]'";
                mysqli_query($link, $query) or die(mysqli_error($link));
            }
            else{
                $query = "SELECT * FROM posts WHERE is_like='$_POST[post_id]'";
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                $var = mysqli_fetch_assoc($result);
                if(empty($var)){
                    if(is_file("post_comments/$_POST[post_id].txt")){
                        unlink("post_comments/$_POST[post_id].txt");
                    }
                }
            }

            //$query = "DELETE FROM post_likes WHERE post_id='$_POST[post_id]'";
            //mysqli_query($link, $query) or die(mysqli_error($link));

            header("Location: /profile.php?id=$_SESSION[id]");
        }
        else return "";
    }

    public function deleteCommentPostForm(){
        $result = '';
        if(isset($_SESSION['auth'])&&$_SESSION['status_id']==1){
            $check_comment_id = $this->checkPostCommentId();
            $result .= "<form method='POST' action=''><label>Удалить комментарий №: <input type='text' name='post_comment_id' size='5'></label>&nbsp;&nbsp;<input type='submit' name='delete_comment_post' value='Удалить'>".$check_comment_id."</form><br>";
        }
        return $result;
    }

    public function checkPostCommentId(){     //Для удаления коммента
        if(isset($_POST['post_comment_id'])){
            $comment_id = (int)$_POST['post_comment_id'];
            $post_id = $_GET['post_id'];
            require 'classes/link.php';
            $query = "SELECT * FROM posts WHERE id='$post_id'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            if($data['is_like'] != 0){
                $post_id = $data['is_like'];
            }
            $path = "post_comments/".$post_id.".txt";
            if(file_exists($path)) {
                $comment_layout = file_get_contents($path);
                preg_match('#<div id=.comment(?<comment_number>[0-9]+).\sclass=.comment_post.>#',$comment_layout,$match);
                $count = $match['comment_number'];
                if ($comment_id >= 1 && $comment_id <= $count) {
                    return "";
                } else return "<span class='alert'>&nbsp;&nbsp;Номер комментария должен находится в пределах от 1 до $count</span>";
            }
            else return "<span class='alert'>&nbsp;&nbsp;Пользователи еще не оставляли комментариев</span>";
        }
        else return "";
    }

    public function deleteCommentPost(){
        if(isset($_POST['delete_comment_post'])){
            if($this->checkPostCommentId()==""){
                $post_id = $_GET['post_id'];
                require 'classes/link.php';
                $query = "SELECT * FROM posts WHERE id='$post_id'";
                $result = mysqli_query($link,$query) or die(mysqli_error($link));
                $data = mysqli_fetch_assoc($result);
                if($data['is_like'] != 0){
                    $post_id = $data['is_like'];
                }
                $comments_layout = file_get_contents("post_comments/".$post_id.".txt");
                $pattern = '#<div\sid=.comment'.$_POST['post_comment_id'].'.\sclass=.comment_post.><p\sclass=.comment_post.><b><a\sclass=.post_comment_login.\shref=.profile\.php\?id=(?<login_id>[0-9]+?).>.+?</a></b>(?<answer_to>.*?)<br><span\sclass=.comment_post_content.>.+?</span></p><form\smethod=.POST.\saction=..\sclass=.comment_post.><input\stype=.hidden.\sname=.login.\svalue=..+?.><input\stype=.submit.\sname=.post_comment_answer.\sclass=.post_comment_answer_submit.\svalue=.Ответить.></form></div>#us';

                if(preg_match($pattern,$comments_layout,$match)==1){
                    $login_id = $match['login_id'];
                    $query = "SELECT * FROM auth WHERE id='$login_id'";
                    $result = mysqli_query($link,$query) or die(mysqli_error($link));
                    $data_writer = mysqli_fetch_assoc($result);
                    $replace = "<div id='comment".$_POST['post_comment_id']."' class='comment_post'><p class='comment_post'><b><a class='post_comment_login' href='profile.php?id=".$match['login_id']."'>".$data_writer['name']." ".$data_writer['surname']."</a></b>".$match['answer_to']."<br><span class='comment_post_content'>Комментарий удален</span></p></div>";
                    $comments_layout = preg_replace($pattern,$replace,$comments_layout);
                    file_put_contents("post_comments/".$post_id.".txt",$comments_layout);
                    header("Location: profile.php?id=$_GET[id]&post_id=$post_id&comments");
                }
                else{
                    header("Location: profile.php?id=$_GET[id]");
                }

            }
        }
    }


}