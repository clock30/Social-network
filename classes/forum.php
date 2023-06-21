<?php
class Forum extends Page{
    public function themeList(){
        require 'classes/link.php';
        $query = "SELECT * FROM themes";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
        $themesList = "<p>Список тем форума:</p><br>";
        if(!empty($data)){
            $themesList .= '<ol class="themes_list">';
            for($i=0;$i<count($data);$i++){
                $themesList .= "<li><a class='themes_list' href='/forum.php?theme=".$data[$i]['slug']."'>".$data[$i]['name']."</a></li>";
            }
            $themesList .= "</ol>";
        }
        else $themesList .= "<p>Пока не создано ни одной темы.</p>";
        return $themesList;
    }

    public function themeListAdmin(){
        require 'classes/link.php';
        $query = "SELECT * FROM themes";
        $result = mysqli_query($link,$query) or die(mysqli_error($link));
        for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
        $themesList = "<p>Список тем форума:</p>";
        if(!empty($data)){
            $themesList .= '<table class="theme_list"><tr><td>Id</td><td>Название темы</td></tr>';
            for($i=0;$i<count($data);$i++){
                $themesList .= "<tr><td>".$data[$i]['id']."</td><td><a class='themes_list' href='/forum.php?theme=".$data[$i]['slug']."'>".$data[$i]['name']."</a></td><td><a class='delete_theme' href='/forum.php?delete=".$data[$i]['id']."'>удалить тему</a></td><td>".$this->renameTheme($data[$i]['id'])."</td></tr>";
            }
            $themesList .= "</table>";
        }
        else $themesList .= "<p>Пока не создано ни одной темы.</p>";
        return $themesList;
    }

    public function deleteTheme(){
        if($_SESSION['status_id']==1&&isset($_SESSION['auth'])){

                $id = $_GET['delete'];
                require 'classes/link.php';
                $query = "SELECT * FROM themes WHERE id='$id'";
                $result = mysqli_query($link, $query) or die(mysqli_query($link));
                $data = mysqli_fetch_assoc($result);
                $slug = $data['slug'];
                $path = "comments/$slug.txt";
                if (file_exists($path)) {
                    unlink($path);
                }
                $query = "DELETE FROM themes WHERE id='$id'";
                mysqli_query($link, $query) or die(mysqli_error($link));
                header("Location: forum.php");

        }
        else header("Location: forum.php");
    }

    public function createNewTheme(){
        $create_new_theme = "<p><a class='create_new_theme' href='/forum.php?create_new_theme=true'>Создать новую тему</a></p>";
        $create_new_theme_end = "";
        if(isset($_GET['create_new_theme'])){
            $create_new_theme = "
            <form method='POST' action=''>
            <label>Название новой темы форума: <input type='text' name='theme' size='20'></label>
            <input type='submit' name='submit' value='Создать'>";
            $create_new_theme_end = "</form>";
            if(isset($_POST['submit'])){
                if($_POST['theme']!=''){
                    if($this->checkTheme($_POST['theme'])===true) {
                        $name = $_POST['theme'];
                        $slug = $this->slug($_POST['theme']);
                        require 'classes/link.php';
                        $query = "SELECT * FROM themes WHERE slug='$slug'";
                        $result = mysqli_query($link,$query) or die(mysqli_error($link));
                        for($data=[];$row=mysqli_fetch_assoc($result);$data[]=$row);
                        if(empty($data)) {
                            $query = "INSERT INTO themes SET name='$name', slug='$slug'";
                            mysqli_query($link, $query) or die(mysqli_error($link));
                            header('Location: forum.php');
                        }
                        else $create_new_theme_end = "&nbsp;&nbsp;<span class='alert'>Такая тема уже создана</span></form>";
                    }
                    else{
                        $create_new_theme_end = "&nbsp;&nbsp;<span class='alert'>Назмание темы должно содержать хотя бы одну букву или цифру, быть длиной не более 200 символов и не содержать некоторые спецсимволы</span></form>";
                    }
                }
                else $create_new_theme_end = "&nbsp;&nbsp;<span class='alert'>Введите название темы</span></form>";
            }
        }
        return $create_new_theme.$create_new_theme_end;
    }

    public function renameTheme($theme_id){
        $rename_theme = "<a class='rename_theme' href='/forum.php?rename=".$theme_id."'>переименовать тему</a>";
        $rename_theme_end = "";
        require 'classes/link.php';
        if(isset($_GET['rename'])&&$_GET['rename']==$theme_id) {
            $id = $theme_id;
            $query = "SELECT * FROM themes WHERE id='$id'";
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            $old_slug = $data['slug'];
            $rename_theme = "<form method='POST' action=''><input type='hidden' name='theme_id' value='".$id."'><label>Введите новое название темы: <input type='text' name='new_name' size='50'>&nbsp;&nbsp;<input type='submit' name='new_theme_name' value='Переименовать'></label>";
            $rename_theme_end = "</form>";
            if(isset($_POST['new_theme_name'])){
                if(isset($_POST['theme_id'])&&isset($_POST['new_name'])){
                    $new_name = $_POST['new_name'];
                    if($this->checkTheme($new_name)===true) {
                        $new_slug = $this->slug($new_name);
                        $query = "SELECT * FROM themes WHERE slug='$new_slug'";
                        $result = mysqli_query($link,$query) or die(mysqli_error($link));
                        $data = mysqli_fetch_assoc($result);
                        if(empty($data)) {
                            $query = "UPDATE themes SET name='$new_name', slug='$new_slug' WHERE id='$id'";
                            mysqli_query($link, $query) or die(mysqli_error($link));
                            $old_path = 'comments/' . $old_slug . '.txt';
                            $new_path = 'comments/' . $new_slug . '.txt';
                            if (file_exists($old_path)) {
                                $comments = file_get_contents($old_path);
                                file_put_contents($new_path, $comments);
                                unlink($old_path);
                            }
                            header('Location: forum.php');
                        }
                        else $rename_theme_end = "&nbsp;&nbsp;<span class='alert'>Такая тема уже создана</span></form>";
                    }
                    else $rename_theme_end = "&nbsp;&nbsp;<span class='alert'>Назмание темы должно содержать хотя бы одну букву или цифру, быть длиной не более 200 символов и не содержать некоторые спецсимволы</span></form>";
                }
                else $rename_theme_end = "&nbsp;&nbsp;<span class='alert'>Введите название темы</span></form>";
            }
        }
        return $rename_theme.$rename_theme_end;
    }

    public function checkTheme($theme){
        if(preg_match('#^[А-ЯЁа-яё\sA-Za-z0-9-_,.!?%"()^/|$*+@№&<>:;=]{1,200}$#u',$theme)+preg_match('#[А-ЯЁа-яёA-Za-z0-9]{1,200}#u',$theme)==2){
            return true;
        }
        else return false;
    }

    public function slug($str){
            return strtr($str,[
                'а' => 'a',
                'б' => 'b',
                'в' => 'v',
                'г' => 'g',
                'д' => 'd',
                'е' => 'e',
                'ё' => 'yo',
                'ж' => 'zh',
                'з' => 'z',
                'и' => 'i',
                'й' => 'y',
                'к' => 'k',
                'л' => 'l',
                'м' => 'm',
                'н' => 'n',
                'о' => 'o',
                'п' => 'p',
                'р' => 'r',
                'с' => 's',
                'т' => 't',
                'у' => 'u',
                'ф' => 'f',
                'х' => 'h',
                'ц' => 'ts',
                'ч' => 'ch',
                'ш' => 'sh',
                'щ' => 'sch',
                'ъ' => '',
                'ы' => 'y',
                'ь' => '',
                'э' => 'e',
                'ю' => 'yu',
                'я' => 'ya',
                'А' => 'a',
                'Б' => 'b',
                'В' => 'v',
                'Г' => 'g',
                'Д' => 'd',
                'Е' => 'e',
                'Ё' => 'yo',
                'Ж' => 'zh',
                'З' => 'z',
                'И' => 'i',
                'Й' => 'y',
                'К' => 'k',
                'Л' => 'l',
                'М' => 'm',
                'Н' => 'n',
                'О' => 'o',
                'П' => 'p',
                'Р' => 'r',
                'С' => 's',
                'Т' => 't',
                'У' => 'u',
                'Ф' => 'f',
                'Х' => 'h',
                'Ц' => 'ts',
                'Ч' => 'ch',
                'Ш' => 'sh',
                'Щ' => 'sch',
                'Ъ' => '',
                'Ы' => 'y',
                'Ь' => '',
                'Э' => 'e',
                'Ю' => 'yu',
                'Я' => 'ya',
                ' ' => '-',
                '/' => '',
                '|' => '',
                '=' => '',
                '+' => '',
                '*' => '',
                '-' => '',
                '_' => '_',
                '!' => '',
                '@' => '',
                '"' => '',
                '#' => '',
                '№' => '',
                '$' => '',
                ';' => '',
                '%' => '',
                '^' => '',
                ':' => '',
                '?' => '',
                '(' => '',
                ')' => '',
                ',' => '',
                '.' => '',
                '~' => '',
                '>' => '',
                '<' => '',
            ]);
    }

    public function theme(){
        if(isset($_GET['theme'])){
            $slug = $_GET['theme'];
            require 'classes/link.php';
            $query = "SELECT * FROM themes WHERE slug='$slug'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            $theme = $data['name'];
            if(!empty($data)){
                return $theme;
            }
            else header('Location: forum.php');
        }
        else return "";
    }

    public function themeSlug(){
        if(isset($_GET['theme'])){
            $slug = $_GET['theme'];
            require 'classes/link.php';
            $query = "SELECT * FROM themes WHERE slug='$slug'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            $theme = $data['slug'];
            return $theme;
        }
        else return "";
    }

    public function comment(){
        if(isset($_POST['hidden'])&&isset($_POST['login'])&&isset($_POST['comment_number'])){
            $comment_head = "Вы отвечаете на <b>Комментарий № ".$_POST['comment_number']."</b> от пользователя <b>".$_POST['login']."</b>:<br>".$_POST['hidden'];
            $hidden = $_POST['hidden'];
        }
        else {
            $comment_head = "Оставьте Ваш комментарий:<br>";
            $hidden = '';
        }
        if(isset($_POST['comment'])){
            $check_comment = $this->checkComment($_POST['comment']);
            $check_gag = $this->checkGag();
        }
        else {
            $check_comment = '';
            $check_gag = '';
        }

        $result = "<form method='POST' action=''><input type='hidden' name='hidden' value='".$hidden."'><label>".$comment_head."<textarea name='comment' cols='80' rows='8'>".$this->echoComment()."</textarea></label><br>".$check_comment.$check_gag."<br><input type='submit' name='submit'></form><br>";
        return $result;
    }

    public function echoComment(){
        /*if(isset($_POST['hidden'])){
            require 'classes/link.php';
            $id = $_SESSION['id'];
            $query = "SELECT * FROM auth WHERE id='$id'";
            $result = mysqli_query($link,$query) or die(mysqli_error($link));
            $data = mysqli_fetch_assoc($result);
            $login = $data['login'];
            return $_POST['hidden'];
        }*/
        if(isset($_POST['comment'])){
            return $_POST['comment'];
        }
        else return "";
    }

    public function comments(){
        $path = "comments/".$this->themeSlug().".txt";
        if(file_exists($path)){
            $result = file_get_contents($path);
        }
        else $result = "<p>Комментариев на эту тему еще не было</p>";
        return $result;
    }

    public function sendComment(){
        if(isset($_POST['comment'])){
            if($this->checkComment($_POST['comment'])==''&&$this->checkGag()=='') {
                $path = "comments/" . $this->themeSlug() . ".txt";
                $id = $_SESSION['id'];
                require 'classes/link.php';
                $query = "SELECT * FROM auth WHERE id='$id'";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                $data = mysqli_fetch_assoc($result);
                $login = $data['login'];
                //$id = $data['id'];
                $date = date('Y-m-d H:i:s', time());
                if(isset($_POST['hidden'])){
                    $hidden = $_POST['hidden'];
                }
                else $hidden = '';

                if (file_exists($path)) {
                    $temp = file_get_contents($path);
                    preg_match('#<div id=.comment(?<comment_number>[0-9]+).\sclass=.comment.>#',$temp,$match);
                    $comment_number = $match['comment_number'] + 1;
                        //$comment_number = preg_match_all('#<div id=.comment[0-9]+.\sclass=.comment.><p><span class=.comment.>Комментарий\s№[0-9]+</span>\s<b>[0-9A-za-z-_]+:</b><br><span\sclass=.comment_content.>.+?</span><br><span\sclass=.comment_date.>[0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}</span></p></div>#u', $temp) + 1;  //
                    $answer_var = "answer_".$_GET['theme']."_".$comment_number;
                    $$answer_var = "<table><tr><td>".$hidden.strip_tags($_POST['comment'],'<b><a><span><br><table><tr><td>')." <b>".$login." пишет</b></td></tr></table>";
                    $answer = "<form method='POST' action=''><input type='hidden' name='hidden' value='".$$answer_var."'><input type='hidden' name='login' value='".$login."'><input type='hidden' name='comment_number' value='".$comment_number."'><input type='submit' name='answer' value='Ответить'></form>";
                    $comment = "<div id='comment" . $comment_number . "' class='comment'><p><span class='comment'>Комментарий №" . $comment_number . "</span> <span class='comment_date'>" . $date . "</span> <b><a class='comment_login' href='profile.php?id=" . $id . "'>" . $login . ":</a></b><br><span class='comment_content'>" . $hidden . strip_tags($_POST['comment'], '<a><span><b><br><table><tr><td>') . "</span></p>".$answer."</div>";
                    $comment .= $temp;
                    file_put_contents($path, $comment);
                } else {
                    $comment_number = 1;
                    $answer_var = "answer_".$_GET['theme']."_".$comment_number;
                    $$answer_var = "<table><tr><td>".$hidden.strip_tags($_POST['comment'],'<b><a><span><br><table><tr><td>')." <b>".$login." пишет</b></td></tr></table>";
                    //$$answer_var = "<b>".$login." пишет: </b>".strip_tags($_POST['comment'],'<b><a><span><br>')."<br>";
                    $answer = "<form method='POST' action=''><input type='hidden' name='hidden' value='".$$answer_var."'><input type='hidden' name='login' value='".$login."'><input type='hidden' name='comment_number' value='".$comment_number."'><input type='submit' name='answer' value='Ответить'></form>";
                    $comment = "<div id='comment" . $comment_number . "' class='comment'><p><span class='comment'>Комментарий №" . $comment_number . "</span> <span class='comment_date'>" . $date . "</span> <b><a class='comment_login' href='profile.php?id=" . $id . "'>" . $login . ":</a></b><br><span class='comment_content'>" . $hidden . strip_tags($_POST['comment'], '<a><span><b><br><table><tr><td>') . "</span></p>".$answer."</div>";
                    file_put_contents($path, $comment);
                }
                $forum = "/forum.php?theme=" . $this->themeSlug();
                header("Location: $forum");
            }
        }
    }

    public function deleteCommentForm(){
        $result = '';
        if(isset($_SESSION['auth'])&&$_SESSION['status_id']==1){
            $check_comment_id = $this->checkCommentId();
            $result .= "<form method='POST' action=''><label>Удалить комментарий №: <input type='text' name='comment_id' size='5'></label>&nbsp;&nbsp;<input type='submit' name='delete_comment' value='Удалить'>".$check_comment_id."</form><br>";
        }
        return $result;
    }

    public function checkCommentId(){
        if(isset($_POST['comment_id'])){
            $comment_id = (int)$_POST['comment_id'];
            $theme = $_GET['theme'];
            $path = "comments/" . $theme . ".txt";
            if(file_exists($path)) {
                $comment_layout = file_get_contents($path);
                preg_match('#<div id=.comment(?<count>[0-9]+).\sclass=.comment.>#',$comment_layout,$match);
                //$count = preg_match_all('#<div id=.comment[0-9]+.\sclass=.comment.><p><span class=.comment.>Комментарий\s№[0-9]+</span>\s<b>[0-9A-za-z-_]+:</b><br><span\sclass=.comment_content.>.+?</span><br><span\sclass=.comment_date.>[0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}</span></p></div>#u', $comment_layout);
                $count = $match['count'];
                if ($comment_id >= 1 && $comment_id <= $count) {
                    return "";
                } else return "<span class='alert'>&nbsp;&nbsp;Номер комментария должен находится в пределах от 1 до $count</span>";
            }
            else return "<span class='alert'>&nbsp;&nbsp;Пользователи еще не оставляли комментариев</span>";
        }
        else return "";
    }

    public function deleteComment(){
        if(isset($_POST['delete_comment'])){
            if($this->checkCommentId($_POST['comment_id'])==''){
                $theme = $_GET['theme'];
                $comment_id = $_POST['comment_id'];
                $comments_layout = file_get_contents("comments/$theme.txt");
                $pattern = '#<div id=.comment'.$comment_id.'.\sclass=.comment.><p><span class=.comment.>Комментарий\s№'.$comment_id.'</span>\s<span\sclass=.comment_date.>(?<date>[0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2})</span>\s<b><a\sclass=.comment_login.\shref=.profile.php\?id=[0-9]+?.>(?<login>[0-9A-za-z-_]+?):</a></b><br><span\sclass=.comment_content.>.+?</span></p><form\smethod=.POST.\saction=..><input\stype=.hidden.\sname=.hidden.\svalue=.+?><input\stype=.hidden.\sname=.login.\svalue=.[A-Za-z0-9-_]+?.><input\stype=.hidden.\sname=.comment_number.\svalue=.[0-9]+?.><input\stype=.submit.\sname=.answer.\svalue=.Ответить.></form></div>#us';

                if(preg_match($pattern,$comments_layout,$match)==1){
                    require 'classes/link.php';
                    $login = $match['login'];
                    $query = "SELECT * FROM auth WHERE login='$login'";
                    $result = mysqli_query($link,$query) or die(mysqli_error($link));
                    $data = mysqli_fetch_assoc($result);
                    $replace = '<div id="comment'.$comment_id.'" class="comment"><p><span class="comment">Комментарий №'.$comment_id.'</span> <span class="comment_date">'.$match['date'].'</span> <b><a class="comment_login" href="profile.php?id='.$data['id'].'">'.$match['login'].':</a></b><br><span class="comment_content">Комментарий удален '.date('Y-m-d H:i:s',time()).'</span></p></div>';
                    $comments_layout = preg_replace($pattern,$replace,$comments_layout);
                    file_put_contents("comments/$theme.txt",$comments_layout);
                    header("Location: forum.php?theme=$theme");
                }
                else{
                    /*$replace = "<div id='comment".$comment_id."' class='comment'><p><span class='comment'>Комментарий №".$comment_id."</span> <span class='comment_date'>19.10.1981</span> <b>Login:</b><br><span class='comment_content' >Комментарий не соответствует маске ".date('Y-m-d h:i:s',time())."</span></p></div>";
                    $comments_layout = preg_replace($pattern,$replace,$comments_layout);
                    file_put_contents("comments/$theme.txt",$comments_layout);*/
                    header("Location: forum.php");
                }

            }
        }
    }
}