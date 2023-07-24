<?php
include(__DIR__ . '/../requirements/page.php');

if (isset($_COOKIE['token']))
{ 
    if (!$_COOKIE['token'] == "") {
        $user_query = "SELECT * FROM mythicalwebpanel_users WHERE api_key = ?";
        $stmt = mysqli_prepare($conn, $user_query);
        mysqli_stmt_bind_param($stmt, "s", $_COOKIE['token']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            $user_info = $conn->query("SELECT * FROM mythicalwebpanel_users WHERE api_key = '" . $_COOKIE['token'] . "'")->fetch_array();
            $email = $user_info['email'];
            $password = $user_info['password'];
            $skey = generate_key($email,$password);
            $conn->query("UPDATE `mythicalwebpanel_users` SET `api_key` = '" . $skey . "' WHERE `mythicalwebpanel_users`.`api_key` = '" . $_COOKIE['token'] . "';");
            $conn->close();
            header('location: /user/profile?s=We updated the user settings in the database');
        } else {
            header('location: /user/profile?e=Can`t find this user in the database');
            exit();
        }
    } else {
        header('location: /user/profile?e=Can`t find this user in the database');
        exit();
    }
} else {
    header('location: /user/profile');
    exit();
}
?>