<?php
session_start();
if(isset($_SESSION['exit'])){
    echo $_SESSION['exit'];
    unset($_SESSION['exit']);
}
if(isset($_SESSION['reg'])){
    echo $_SESSION['reg'];
    unset($_SESSION['reg']);
}
if(isset($_SESSION['flash'])){
    echo $_SESSION['flash'];
    unset($_SESSION['flash']);
}
if(isset($_SESSION['personal_data_change'])){
    echo $_SESSION['personal_data_change'];
    unset($_SESSION['personal_data_change']);
}
if(isset($_SESSION['delete_user'])){
    echo $_SESSION['delete_user'];
    unset($_SESSION['delete_user']);
}
if(isset($_SESSION['change_status'])){
    echo $_SESSION['change_status'];
    unset($_SESSION['change_status']);
}
if(isset($_SESSION['delete_account'])){
    echo $_SESSION['delete_account'];
    unset($_SESSION['delete_account']);
}
if(isset($_SESSION['post_constructor'])){
    $_SESSION['constructor'] .= $_SESSION['post_constructor'];
    unset($_SESSION['post_constructor']);
}

if (isset($_SESSION['upload']))
{
    printf('<b>%s</b>', $_SESSION['upload']);
    unset($_SESSION['upload']);
}
