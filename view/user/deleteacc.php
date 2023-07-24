<?php
include(__DIR__ . '/../requirements/page.php');

if (isset($_COOKIE['token'])) {
    if (!$_COOKIE['token'] == "") {
        $user_query = "SELECT * FROM mythicalwebpanel_users WHERE api_key = ?";
        $stmt = mysqli_prepare($conn, $user_query);
        mysqli_stmt_bind_param($stmt, "s", $_COOKIE['token']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            $conn->query("DELETE FROM `mythicalwebpanel_users` WHERE `mythicalwebpanel_users`.`api_key` = '".$_COOKIE['token']."';");
            header('location: /auth/logout');
            exit();
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