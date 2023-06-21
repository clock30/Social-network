<?php
class Friends extends Profile{
    public function friendsList()
    {
        require 'classes/link.php';
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
        $exit_data = "";
        if(!empty($friends_id)){
            $query = "SELECT id, login, name, surname, profile_photo FROM auth WHERE id IN (".implode(',',$friends_id).")";
            $result = mysqli_query($link, $query) or die($link);
            for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;
            if (!empty($data)) {
                foreach ($data as $item) {
                    if($item['profile_photo'] === null || $item['profile_photo'] == ''){
                        $img = "img/photo_profile.jpg";
                    }
                    else $img = "users/" . $this->getlogin($item['id']) . "/icons/" . $item['profile_photo'];
                    $exit_data .= "<div class='friends_item'><a href='profile.php?id=" . $item['id'] . "'><img class='friends_item' alt='friends' src='".$img."'></a>
                <a class='friends_item' href='profile.php?id=" . $item['id'] . "'>" . $item['name'] . " " . $item['surname'] . "</a>
                <form method='POST' action='' class='delete_from_friends'><input type='hidden' name='delete_from_friends_id1' value='".$_SESSION['id']."'>
                <input type='hidden' name='delete_from_friends_id2' value='".$item['id']."'><input type='submit' name='delete_from_friends' value='Удалить из друзей'></form></div>";
                }
            }
            else $exit_data .= "";
            $exit_data .= "<div class='clear'></div>";
        }
        return $exit_data;
    }

    public function deleteFromFriends(){
        if(isset($_POST['delete_from_friends'])){
            require 'classes/link.php';
            $query = "DELETE FROM friendship WHERE (addressee_friendship='$_POST[delete_from_friends_id1]' AND sender_friendship='$_POST[delete_from_friends_id2]') OR (addressee_friendship='$_POST[delete_from_friends_id2]' AND sender_friendship='$_POST[delete_from_friends_id1]')";
            mysqli_query($link,$query) or die(mysqli_error($link));
            header("Location: friends.php?id=$_SESSION[id]");
        }
        else return "";
    }
}