<?php
$url = $_SERVER['REQUEST_URI'];

require 'classes/session.php';
require 'classes/page.php';
require 'classes/profile.php';
require 'classes/friends.php';
require 'classes/post.php';
require 'classes/gallery.php';
require 'classes/auth.php';
require 'classes/reg.php';
require 'classes/account.php';
require 'classes/forum.php';
require 'classes/mail.php';
require 'classes/admin.php';



if(preg_match('#^/$#',$url)==1) {
    header('Location: auth.php');
}

if(preg_match('#^/auth.php$#',$url)==1) {
    $auth = new Auth;
    $layout_auth = file_get_contents('templates/auth.php');
    $layout_auth = str_replace('{{header}}',$auth->header(),$layout_auth);
    $layout_auth = str_replace('{{footer}}',$auth->footer(),$layout_auth);
    $layout_auth = str_replace('{{authForm}}',$auth->authForm(),$layout_auth);
    $auth->auth();
    echo $layout_auth;
}

if(preg_match('#^/reg.php$#',$url,$match)==1) {
    if(!isset($_SESSION['auth'])) {
        $reg = new Reg;
        $layout_reg = file_get_contents('templates/reg.php');
        $layout_reg = str_replace('{{header}}', $reg->header(), $layout_reg);
        $layout_reg = str_replace('{{footer}}',$reg->footer(),$layout_reg);
        if (isset($_POST['submit'])) {
            $layout_reg = str_replace('{{echoLogin}}', $reg->echoLogin(), $layout_reg);
            $layout_reg = str_replace('{{echoPassword}}', $reg->echoPassword(), $layout_reg);
            $layout_reg = str_replace('{{echoPassword2}}', $reg->echoPassword2(), $layout_reg);
            $layout_reg = str_replace('{{echoName}}', $reg->echoName(), $layout_reg);
            $layout_reg = str_replace('{{echoPatronymic}}', $reg->echoPatronymic(), $layout_reg);
            $layout_reg = str_replace('{{echoSurname}}', $reg->echoSurname(), $layout_reg);
            $layout_reg = str_replace('{{echoBirth_date}}', $reg->echoBirth_date(), $layout_reg);
            $layout_reg = str_replace('{{echoEmail}}', $reg->echoEmail(), $layout_reg);
            $layout_reg = str_replace('{{checkLogin}}', $reg->checkLogin($_POST['login']), $layout_reg);
            $layout_reg = str_replace('{{checkPassword}}', $reg->checkPassword($_POST['password']), $layout_reg);
            $layout_reg = str_replace('{{checkConfirmPassword}}', $reg->checkConfirmPassword($_POST['password'], $_POST['password2']), $layout_reg);
            $layout_reg = str_replace('{{checkName}}', $reg->checkName($_POST['name']), $layout_reg);
            $layout_reg = str_replace('{{checkPatronymic}}', $reg->checkPatronymic($_POST['patronymic']), $layout_reg);
            $layout_reg = str_replace('{{checkSurname}}', $reg->checkSurname($_POST['surname']), $layout_reg);
            $layout_reg = str_replace('{{checkBirth_date}}', $reg->checkBirth_date($_POST['birth_date']), $layout_reg);
            $layout_reg = str_replace('{{checkEmail}}', $reg->checkEmail($_POST['email']), $layout_reg);
        } else {
            $layout_reg = str_replace(['{{checkLogin}}', '{{checkPassword}}', '{{checkConfirmPassword}}', '{{checkName}}', '{{checkPatronymic}}', '{{checkSurname}}', '{{checkBirth_date}}', '{{checkEmail}}',
                '{{echoLogin}}', '{{echoPassword}}', '{{echoPassword2}}', '{{echoName}}', '{{echoPatronymic}}', '{{echoSurname}}', '{{echoBirth_date}}', '{{echoEmail}}',], '', $layout_reg);
        }
        $reg->reg();
        echo $layout_reg;
    }
    else header('Location: auth.php');
}

if(preg_match('#^/profile.php\?id=[0-9]+?$#',$url)==1 || preg_match('#^/profile.php\?id=[0-9]+?&post$#',$url)==1 || preg_match('#^/profile.php\?id=[0-9]+?&post&article$#',$url)==1
    || preg_match('#^/profile.php\?id=[0-9]+?&post&text$#',$url)==1 || preg_match('#^/profile.php\?id=[0-9]+?&post&link$#',$url)==1  || preg_match('#^/profile.php\?id=[0-9]+?&post&photo$#',$url)==1
    || preg_match('#^/profile.php\?id=[0-9]+?&post&upload_photo_filename=.+?$#',$url)==1 || preg_match('#^/profile.php\?id=[0-9]+?&post&upload_video_filename=.+?&width=[0-9]+?&height=[0-9]+?$#',$url)==1
    || preg_match('#^/profile.php\?id=[0-9]+?&post_id=[0-9]+?&comments$#',$url)==1){
    if(isset($_SESSION['auth'])) {
        $profile = new Post;
        $layout = file_get_contents('templates/profile.php');
        if(isset($_GET['post'])){
            $layout = str_replace('{{postDiv}}', file_get_contents('templates/post_div.php'), $layout);
        }
        if(isset($_GET['post_id']) && isset($_GET['comments'])){
            $layout = str_replace('{{postDiv}}', file_get_contents('templates/post_comments_div.php'), $layout);
        }
        else $layout = str_replace('{{postDiv}}', '', $layout);
        $layout = str_replace('{{header}}', $profile->header(), $layout);
        $layout = str_replace('{{footer}}',$profile->footer(),$layout);
        $layout = str_replace('{{login}}', $profile->login(), $layout);
        $layout = str_replace('{{name}}', $profile->name(), $layout);
        $layout = str_replace('{{patronymic}}', $profile->patronymic(), $layout);
        $layout = str_replace('{{surname}}', $profile->surname(), $layout);
        $layout = str_replace('{{age}}', $profile->age(), $layout);
        $layout = str_replace('{{email}}', $profile->email(), $layout);
        $layout = str_replace('{{writeMessage}}', $profile->writeMessage(), $layout);
        $layout = str_replace('{{gag}}', $profile->gag(), $layout);
        $layout = str_replace('{{profilePhoto}}', $profile->profilePhoto(), $layout);
        $layout = str_replace('{{getId}}', $profile->getId(), $layout);
        $layout = str_replace('{{profilePostLink}}', $profile->profilePostLink(), $layout);
        $layout = str_replace('{{profilePostLink2}}', $profile->profilePostLink2(), $layout);
        $layout = str_replace('{{postScreen}}', $profile->postScreen(), $layout);
        $layout = str_replace('{{writePost}}', $profile->writePost(), $layout);
        $layout = str_replace('{{postResult}}', $profile->putInConstructor(), $layout);
        $layout = str_replace('{{postFeed}}', $profile->postFeed(), $layout);
        $layout = str_replace('{{activities}}', $profile->activities(), $layout);
        $layout = str_replace('{{friendship}}', $profile->friendship(), $layout);
        $layout = str_replace('{{friendshipOfferList}}', $profile->friendshipOfferList(), $layout);
        $layout = str_replace('{{thePost}}', $profile->thePost(), $layout);
        $layout = str_replace('{{postCommentsBlock}}', $profile->postCommentsBlock(), $layout);
        $layout = str_replace('{{deleteCommentPostForm}}', $profile->deleteCommentPostForm(), $layout);
        $profile->putGag();
        $profile->offGag();
        $profile->clearConstructor();
        $profile->publicPost();
        $profile->deletePost();
        $profile->offerFriendship();
        $profile->addresseeFriendshipAnswer();
        $profile->setLike();
        $profile->sendPostComment();
        $profile->deleteCommentPost();
        echo $layout;
    }
    else header('Location: auth.php');
}

if(preg_match('#^/feed.php\?id=[0-9]+?$#',$url)==1){
    if(isset($_SESSION['auth'])) {
        $feed = new Post;
        $layout_feed = file_get_contents('templates/feed.php');
        $layout_feed = str_replace('{{header}}', $feed->header(), $layout_feed);
        $layout_feed = str_replace('{{footer}}',$feed->footer(),$layout_feed);
        $layout_feed = str_replace('{{login}}', $feed->login(), $layout_feed);
        $layout_feed = str_replace('{{name}}', $feed->name(), $layout_feed);
        $layout_feed = str_replace('{{patronymic}}', $feed->patronymic(), $layout_feed);
        $layout_feed = str_replace('{{surname}}', $feed->surname(), $layout_feed);
        $layout_feed = str_replace('{{age}}', $feed->age(), $layout_feed);
        $layout_feed = str_replace('{{email}}', $feed->email(), $layout_feed);
        $layout_feed = str_replace('{{writeMessage}}', $feed->writeMessage(), $layout_feed);
        $layout_feed = str_replace('{{gag}}', $feed->gag(), $layout_feed);
        $layout_feed = str_replace('{{profilePhoto}}', $feed->profilePhoto(), $layout_feed);
        $layout_feed = str_replace('{{getId}}', $feed->getId(), $layout_feed);
        $layout_feed = str_replace('{{writePost}}', $feed->writePost(), $layout_feed);
        $layout_feed = str_replace('{{activities}}', $feed->activities(), $layout_feed);
        $layout_feed = str_replace('{{friendship}}', $feed->friendship(), $layout_feed);
        $layout_feed = str_replace('{{friendshipOfferList}}', $feed->friendshipOfferList(), $layout_feed);
        $feed->putGag();
        $feed->offGag();
        $feed->offerFriendship();
        $feed->addresseeFriendshipAnswer();
        $feed->setLike();
        echo $layout_feed;
    }
    else header('Location: auth.php');
}

if(preg_match('#^/profile.php\?id=[0-9]+?&post&upload_photo_filename$#',$url)==1) {
    header("Location: profile.php?id=$_SESSION[id]&post");
}

if(preg_match('#^/profile.php\?id=[0-9]+?&post&upload_video_filename$#',$url)==1) {
    header("Location: profile.php?id=$_SESSION[id]&post");
}

if(preg_match('#^/friends.php\?id=[0-9]+?$#',$url,$match)==1) {
    if(isset($_SESSION['auth'])) {
        $friends = new Friends;
        $layout_friends = file_get_contents('templates/friends.php');
        $layout_friends = str_replace('{{header}}', $friends->header(), $layout_friends);
        $layout_friends = str_replace('{{footer}}',$friends->footer(),$layout_friends);
        $layout_friends = str_replace('{{login}}', $friends->login(), $layout_friends);
        $layout_friends = str_replace('{{name}}', $friends->name(), $layout_friends);
        $layout_friends = str_replace('{{patronymic}}', $friends->patronymic(), $layout_friends);
        $layout_friends = str_replace('{{surname}}', $friends->surname(), $layout_friends);
        $layout_friends = str_replace('{{age}}', $friends->age(), $layout_friends);
        $layout_friends = str_replace('{{email}}', $friends->email(), $layout_friends);
        $layout_friends = str_replace('{{profilePhoto}}', $friends->profilePhoto(), $layout_friends);
        $layout_friends = str_replace('{{friendshipOfferList}}', $friends->friendshipOfferList(), $layout_friends);
        $layout_friends = str_replace('{{friendsList}}', $friends->friendsList(), $layout_friends);
        $layout_friends = str_replace('{{getId}}', $friends->getId(), $layout_friends);
        $friends->deleteFromFriends();
        echo $layout_friends;
    }
    else header('Location: auth.php');
}

if(preg_match('#^/scripts/uploadphotopost.php$#',$url)==1) {
    if(isset($_SESSION['auth'])){
        require 'scripts/uploadphotopost.php';
    }
    else header("Location: auth.php");
}

if(preg_match('#^/scripts/uploadvideopost.php$#',$url)==1) {
    if(isset($_SESSION['auth'])){
        require 'scripts/uploadvideopost.php';
    }
    else header("Location: auth.php");
}

if(preg_match('#^/gallery.php\?id=[0-9]+?$#',$url,$match)==1||preg_match('#^/gallery.php\?id=[0-9]+?&view_photo=[\w.]+?$#',$url,$match)==1){
    if(isset($_SESSION['auth'])) {
        $gallery = new Gallery;
        $layout_gallery = file_get_contents('templates/gallery.php');
        $layout_gallery = str_replace('{{header}}', $gallery->header(), $layout_gallery);
        $layout_gallery = str_replace('{{footer}}',$gallery->footer(),$layout_gallery);
        $layout_gallery = str_replace('{{login}}', $gallery->login(), $layout_gallery);
        $layout_gallery = str_replace('{{name}}', $gallery->name(), $layout_gallery);
        $layout_gallery = str_replace('{{patronymic}}', $gallery->patronymic(), $layout_gallery);
        $layout_gallery = str_replace('{{surname}}', $gallery->surname(), $layout_gallery);
        $layout_gallery = str_replace('{{photos}}', $gallery->getPhotos(), $layout_gallery);
        $layout_gallery = str_replace('{{uploadPhotosForm}}', $gallery->uploadPhotoForm(), $layout_gallery);
        $layout_gallery = str_replace('{{galleryBack}}', $gallery->galleryBack(), $layout_gallery);
        $layout_gallery = str_replace('{{galleryDiv}}', $gallery->galleryDiv(), $layout_gallery);
        $layout_gallery = str_replace('{{getPreviousPhoto}}', $gallery->getPreviousPhoto(), $layout_gallery);
        $layout_gallery = str_replace('{{getCurrentPhoto}}', $gallery->getCurrentPhoto(), $layout_gallery);
        $layout_gallery = str_replace('{{getNextPhoto}}', $gallery->getNextPhoto(), $layout_gallery);
        $layout_gallery = str_replace('{{getGalleryLogin}}', $gallery->getGalleryLogin(), $layout_gallery);
        $layout_gallery = str_replace('{{profileGalleryLink}}', $gallery->profileGalleryLink(), $layout_gallery);
        $layout_gallery = str_replace('{{commentsGallery}}', $gallery->commentsGallery(), $layout_gallery);
        $layout_gallery = str_replace('{{commentGallery}}', $gallery->commentGallery(), $layout_gallery);
        $layout_gallery = str_replace('{{deleteCommentGalleryForm}}', $gallery->deleteCommentGalleryForm(), $layout_gallery);
        $layout_gallery = str_replace('{{getId}}', $gallery->getId(), $layout_gallery);
        $gallery->sendCommentGallery();
        $gallery->deleteCommentGallery();
        echo $layout_gallery;
    }
    else header('Location: auth.php');
}

if(preg_match('#^/scripts/upload.php$#',$url)==1) {
    if(isset($_SESSION['auth'])){
        require 'scripts/upload.php';
    }
    else header("Location: auth.php");
}

if(preg_match('#^/gallery\.php\?id=[0-9]+?&makemain=[a-z0-9]+?\.[a-z]+$#',$url)==1) {
    $gallery = new Gallery;
    $gallery->makeMain();
}

if(preg_match('#^/gallery\.php\?id=[0-9]+?&delete_photo=\w+?\.[a-z]+$#',$url)==1) {
    $gallery = new Gallery;
    $gallery->deletePhoto();
}

if(preg_match('#^/exit.php$#',$url,$match)==1){
    if(isset($_SESSION['auth'])) {
        $exit = new Page;
        $layout_exit = file_get_contents('templates/exit.php');
        $layout_exit = str_replace('{{getLogin}}',$exit->getlogin($_SESSION['id']),$layout_exit);
        $layout_exit = str_replace('{{header}}',$exit->header(),$layout_exit);
        $layout_exit = str_replace('{{footer}}',$exit->footer(),$layout_exit);
        $exit->exitAuth();
        echo $layout_exit;
    }
    else header('Location: auth.php');
}

if(preg_match('#^/users.php$#',$url,$match)==1) {
    if(isset($_SESSION['auth'])) {
        $users = new Page;
        $layout_users = file_get_contents('templates/users.php');
        $layout_users = str_replace('{{header}}', $users->header(), $layout_users);
        $layout_users = str_replace('{{footer}}',$users->footer(),$layout_users);
        $layout_users = str_replace('{{getUsers}}', $users->getUsers(), $layout_users);
        $layout_users = str_replace('{{searchResult}}', $users->searchUsers(), $layout_users);
        $layout_users = str_replace('{{echoLogin}}', $users->echoLogin(), $layout_users);
        $layout_users = str_replace('{{echoName}}', $users->echoName(), $layout_users);
        $layout_users = str_replace('{{echoSurname}}', $users->echoSurname(), $layout_users);
        $layout_users = str_replace('{{echoPatronymic}}', $users->echoPatronymic(), $layout_users);
        $layout_users = str_replace('{{echoBirth_date}}', $users->echoBirth_date(), $layout_users);
        $users->searchUsers();
        echo $layout_users;
    }
    else header('Location: auth.php');
}

if(preg_match('#^/mail.php\?id=[0-9]+$#',$url)==1 || preg_match('#^/mail.php\?id=[0-9]+?&addressee=[0-9]+?$#',$url)==1) {
    if (isset($_SESSION['auth'])) {
        $mail = new Mail($_SESSION['id']);
        $layout_mail = file_get_contents('templates/mail.php');
        $layout_mail = str_replace('{{getLogin}}',$mail->getlogin($_SESSION['id']),$layout_mail);
        $layout_mail = str_replace('{{getName}}',$mail->getName($_SESSION['id']),$layout_mail);
        $layout_mail = str_replace('{{getSurname}}',$mail->getSurname($_SESSION['id']),$layout_mail);
        $layout_mail = str_replace('{{header}}',$mail->header(),$layout_mail);
        $layout_mail = str_replace('{{footer}}',$mail->footer(),$layout_mail);
        $layout_mail = str_replace('{{allDialogsView}}',$mail->allDialogsView(),$layout_mail);
        $layout_mail = str_replace('{{messageForm}}',$mail->messageForm(),$layout_mail);
        $layout_mail = str_replace('{{viewDialogsWithOtherUser}}',$mail->viewDialogsWithOtherUser(),$layout_mail);
        $mail->dialog();
        echo $layout_mail;
    }
    else header('Location: auth.php');
}

if(preg_match('#^/delete_conv.php\?id=[0-9]+?&conv_id=[0-9]+?$#',$url)==1) {
    if (isset($_SESSION['auth'])) {
        $mail = new Mail($_SESSION['id']);
        $mail->deleteDialog();
    }
    else header('Location: auth.php');
}

if(preg_match('#^/account.php$#',$url,$match)==1) {
    if(isset($_SESSION['auth'])) {
        $account = new Account;
        $layout_account = file_get_contents('templates/account.php');
        $layout_account = str_replace('{{header}}', $account->header(), $layout_account);
        $layout_account = str_replace('{{footer}}',$account->footer(),$layout_account);
        $layout_account = str_replace('{{login}}', $account->echoLogin(), $layout_account);

        if (isset($_POST['submit'])) {
            $layout_account = str_replace(['{{echoName}}', '{{checkName}}', '{{echoPatronymic}}', '{{checkPatronymic}}', '{{echoSurname}}', '{{checkSurname}}', '{{echoBirth_date}}', '{{checkBirth_date}}', '{{echoEmail}}', '{{checkEmail}}',],
                [$account->echoName(), $account->checkName($_POST['name']), $account->echoPatronymic(), $account->checkPatronymic($_POST['patronymic']), $account->echoSurname(), $account->checkSurname($_POST['surname']), $account->echoBirth_date(), $account->checkBirth_date($_POST['birth_date']), $account->echoEmail(), $account->checkEmail($_POST['email']),], $layout_account);
        } else $layout_account = str_replace(['{{echoName}}', '{{checkName}}', '{{echoPatronymic}}', '{{checkPatronymic}}', '{{echoSurname}}', '{{checkSurname}}', '{{echoBirth_date}}', '{{checkBirth_date}}', '{{echoEmail}}', '{{checkEmail}}',],
            [$account->echoName(), '', $account->echoPatronymic(), '', $account->echoSurname(), '', $account->echoBirth_date(), '', $account->echoEmail(), '',], $layout_account);
        $account->account();
        echo $layout_account;
    }
    else header('Location: auth.php');
}

if(preg_match('#^/change_pass.php$#',$url,$match)==1) {
    if(isset($_SESSION['auth'])) {
        $change_pass = new Account;
        $layout_change_pass = file_get_contents('templates/change_pass.php');
        $layout_change_pass = str_replace('{{header}}', $change_pass->header(), $layout_change_pass);
        $layout_change_pass = str_replace('{{footer}}',$change_pass->footer(),$layout_change_pass);
        $layout_change_pass = str_replace('{{Login}}', $change_pass->getLogin($_SESSION['id']), $layout_change_pass);

        if (isset($_POST['submit'])) {
            $layout_change_pass = str_replace(['{{echoLogin}}', '{{checkLogin}}', '{{checkPassword_old}}', '{{checkPassword}}', '{{checkConfirmPassword}}',],
                [$change_pass->echoLogin(), $change_pass->checkLogin($_POST['login']), $change_pass->checkPassword_old($_POST['login'],$_POST['password_old']), $change_pass->checkPassword($_POST['login'],$_POST['password_old'],$_POST['password']), $change_pass->checkConfirmPassword($_POST['login'],$_POST['password_old'],$_POST['password'],$_POST['confirm_password']),], $layout_change_pass);
        } else $layout_change_pass = str_replace(['{{echoLogin}}', '{{checkLogin}}', '{{checkPassword_old}}', '{{checkPassword}}', '{{checkConfirmPassword}}',],
            [$change_pass->echoLogin(), '', '', '', '',], $layout_change_pass);
        $change_pass->change_pass();
        echo $layout_change_pass;
    }
    else header('Location: auth.php');
}

if(preg_match('#^/delete_account.php$#',$url,$match)==1) {
    if (isset($_SESSION['auth'])) {
        $delete_account = new Account;
        $layout_delete_account = file_get_contents('templates/delete_account.php');
        $layout_delete_account = str_replace('{{getLogin}}',$delete_account->getlogin($_SESSION['id']),$layout_delete_account);
        $layout_delete_account = str_replace('{{header}}',$delete_account->header(),$layout_delete_account);
        $layout_delete_account = str_replace('{{footer}}',$delete_account->footer(),$layout_delete_account);
        $layout_delete_account = str_replace('{{echoPasswordDeleteAccount}}',$delete_account->echoPasswordDeleteAccount(),$layout_delete_account);
        if(isset($_POST['submit'])) {
            $layout_delete_account = str_replace('{{checkPasswordDeleteAccount}}', $delete_account->checkPasswordDeleteAccount($_POST['password']), $layout_delete_account);
            $layout_delete_account = str_replace('{{checkConfirmPasswordDeleteAccount}}', $delete_account->checkConfirmPasswordDeleteAccount($_POST['password'], $_POST['confirm_password']), $layout_delete_account);
        }
        else{
            $layout_delete_account = str_replace('{{checkPasswordDeleteAccount}}', '', $layout_delete_account);
            $layout_delete_account = str_replace('{{checkConfirmPasswordDeleteAccount}}', '', $layout_delete_account);
        }
        $delete_account->deleteAccount();
        echo $layout_delete_account;
    }
}

if(preg_match('#^/forum.php$#',$url)==1||preg_match('#^/forum.php\?create_new_theme=true$#',$url)==1||preg_match('#^/forum.php\?rename=[0-9]+?$#',$url)==1){
    if(isset($_SESSION['auth'])){
        $forum = new Forum;
        $layout_forum = file_get_contents('templates/forum.php');
        $layout_forum = str_replace('{{getLogin}}',$forum->getlogin($_SESSION['id']),$layout_forum);
        $layout_forum = str_replace('{{getName}}',$forum->getName($_SESSION['id']),$layout_forum);
        $layout_forum = str_replace('{{getSurname}}',$forum->getSurname($_SESSION['id']),$layout_forum);
        $layout_forum = str_replace('{{header}}',$forum->header(),$layout_forum);
        $layout_forum = str_replace('{{footer}}',$forum->footer(),$layout_forum);
        if($_SESSION['status_id']==1){
            $layout_forum = str_replace('{{createNewTheme}}',$forum->createNewTheme(),$layout_forum);
            $layout_forum = str_replace('{{themeList}}',$forum->themeListAdmin(),$layout_forum);
        }
        else {
            $layout_forum = str_replace('{{createNewTheme}}','',$layout_forum);
            $layout_forum = str_replace('{{themeList}}',$forum->themeList(),$layout_forum);
        }
        echo $layout_forum;
    }
}

if(preg_match('#^/forum.php\?theme=[\w-]{1,200}$#',$url)==1){
    if(isset($_SESSION['auth'])){
        $forum_theme = new Forum;
        $layout_forum_theme = file_get_contents('templates/forum_theme.php');
        $layout_forum_theme = str_replace('{{getLogin}}',$forum_theme->getlogin($_SESSION['id']),$layout_forum_theme);
        $layout_forum_theme = str_replace('{{getName}}',$forum_theme->getName($_SESSION['id']),$layout_forum_theme);
        $layout_forum_theme = str_replace('{{getSurname}}',$forum_theme->getSurname($_SESSION['id']),$layout_forum_theme);
        $layout_forum_theme = str_replace('{{header}}',$forum_theme->header(),$layout_forum_theme);
        $layout_forum_theme = str_replace('{{footer}}',$forum_theme->footer(),$layout_forum_theme);
        $layout_forum_theme = str_replace('{{theme}}',$forum_theme->theme(),$layout_forum_theme);
        $layout_forum_theme = str_replace('{{comment}}',$forum_theme->comment(),$layout_forum_theme);
        $layout_forum_theme = str_replace('{{comments}}',$forum_theme->comments(),$layout_forum_theme);
        $layout_forum_theme = str_replace('{{deleteCommentForm}}',$forum_theme->deleteCommentForm(),$layout_forum_theme);
        $forum_theme->sendComment();
        $forum_theme->deleteComment();
        echo $layout_forum_theme;
    }
}

if(preg_match('#^/forum.php\?delete=[0-9]+$#',$url)==1){
    if(isset($_SESSION['auth'])){
        $forum_del_theme = new Forum;
        $forum_del_theme->deleteTheme();
    }
}

if(preg_match('#^/admin.php$#',$url,$match)==1) {
    if(isset($_SESSION['auth'])&&$_SESSION['status_id']==1){
        $admin = new Admin;
        $layout_admin = file_get_contents('templates/admin.php');
        $layout_admin = str_replace('{{getLogin}}',$admin->getlogin($_SESSION['id']),$layout_admin);
        $layout_admin = str_replace('{{header}}',$admin->header(),$layout_admin);
        $layout_admin = str_replace('{{footer}}',$admin->footer(),$layout_admin);
        $layout_admin = str_replace('{{getAdminPanel}}',$admin->getAdminPanel(),$layout_admin);
        $layout_admin = str_replace('{{getName}}',$admin->getName($_SESSION['id']),$layout_admin);
        $layout_admin = str_replace('{{getSurname}}',$admin->getSurname($_SESSION['id']),$layout_admin);
        echo $layout_admin;
    }
    else header('Location: auth.php');
}

if(preg_match('#^/change_status.php\?id=[0-9]+&status=[a-z]+$#',$url,$match)==1) {
    if(isset($_SESSION['auth'])&&$_SESSION['status_id']==1){
        $change_status = new Admin;
        $layout_change_status = file_get_contents('templates/change_status.php');
        $layout_change_status = str_replace('{{getLogin}}',$change_status->getlogin($_SESSION['id']),$layout_change_status);
        $layout_change_status = str_replace('{{header}}',$change_status->header(),$layout_change_status);
        $layout_change_status = str_replace('{{footer}}',$change_status->footer(),$layout_change_status);
        $change_status->changeStatus();
        echo $layout_change_status;
    }
    else header('Location: auth.php');
}

if(preg_match('#^/delete_user.php\?id=[0-9]+$#',$url,$match)==1) {
    if(isset($_SESSION['auth'])&&$_SESSION['status_id']==1){
        $delete_user = new Admin;
        $layout_delete_user = file_get_contents('templates/change_status.php');
        $layout_delete_user = str_replace('{{getLogin}}',$delete_user->getlogin($_SESSION['id']),$layout_delete_user);
        $layout_delete_user = str_replace('{{header}}',$delete_user->header(),$layout_delete_user);
        $layout_delete_user = str_replace('{{footer}}',$delete_user->footer(),$layout_delete_user);
        $delete_user->deleteUser();
        echo $layout_delete_user;
    }
    else header('Location: auth.php');
}