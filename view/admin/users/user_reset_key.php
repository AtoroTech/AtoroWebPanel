<?php
include(__DIR__ . '/../../requirements/page.php');
include(__DIR__ . '/../../requirements/admin.php');

if (isset($_GET['id']))
{ 
    if (!$_GET['id'] == "") {
        $user_query = "SELECT * FROM mythicalwebpanel_users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $user_query);
        mysqli_stmt_bind_param($stmt, "s", $_GET['id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            $user_info = $conn->query("SELECT * FROM mythicalwebpanel_users WHERE id = '" . $_GET['id'] . "'")->fetch_array();
            $email = $user_info['email'];
            $password = $user_info['password'];
            $skey = generate_key($email,$password);
            $conn->query("UPDATE `mythicalwebpanel_users` SET `api_key` = '" . $skey . "' WHERE `mythicalwebpanel_users`.`id` = " . $_GET['id'] . ";");
            $conn->close();
            header('location: /admin/users/edit?id='.$_GET['id'].'&s=We updated the user settings in the database');
        } else {
            header('location: /admin/users/view?e=Can`t find this user in the database');
            exit();
        }
    } else {
        header('location: /admin/users/view?e=Can`t find this user in the database');
        exit();
    }
} else {
    header('location: /admin/users/view');
    exit();
}
?>