<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require_once ('includes/login_functions.inc.php');

    require_once ('mysql_connect.php');

    $e = $_REQUEST['email'];
    $p = $_REQUEST['pass'];


    list($check, $data) = check_login($dbc, $_REQUEST['email'], $_REQUEST['pass']);
    if ($check) {
        
        setcookie('user_id',$data['user_id'],time()+3600,'/','',0,0);
        setcookie('user_level',$data['user_level'],time()+3600,'/','',0,0);
        setcookie('first_name',$data['first_name'],time()+3600,'/','',0,0);
        setcookie('last_name',$data['last_name'],time()+3600,'/','',0,0);
        setcookie('email',$data['email'],time()+3600,'/','',0,0);

        $current = $current = explode('?', $_SERVER["HTTP_REFERER"], 2)[0];
        header("Location: ".$current);
        
    } else {
        echo "fails";
    }
} else {
    include('index.php');
}
?>