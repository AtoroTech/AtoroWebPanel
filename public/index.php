<?php
try {
    require("../vendor/autoload.php");
} catch (Exception $e) {
    die('Woopps this looks like your packages are broken or you installed the wrong version of McStaffX please check the docs error: "<code>ROUTER-CNI</code>"');
}
$router = new \Router\Router();
$router->add('/', function () {
    require("../include/main.php");
    require("../view/index.php");
});

$router->add('/auth/login', function () {
    require("../include/main.php");
    require("../view/auth/login.php");
});

$router->add('/dashboard', function () {
    require("../include/main.php");
    require("../view/dashboard.php");
});

$router->add('/help-center/tos', function () {
    require("../include/main.php");
    require("../view/legal/termsofservice.php");
});

$router->add('/help-center/pp', function () {
    require("../include/main.php");
    require("../view/legal/privacypolicy.php");
});

$router->add('/help-center', function () {
    require("../include/main.php");
    require("../view/help-center.php");
});

$router->add('/auth/logout', function () {
    require("../include/main.php");
    require("../functions/logout.php");
});

$router->add("/e/critical", function () {
    require("../view/errors/critical.php");
});

$router->add("/e/404", function () {
    require("../include/main.php");
    require("../view/errors/404.php");
});

$router->add("/user/profile",function () {
    require("../include/main.php");
    require("../view/user/profile.php");
});

$router->add("/user/security/delete_account",function () {
    require("../include/main.php");
    require("../view/user/deleteacc.php");
});

$router->add("/user/security/resetkey",function () {
    require("../include/main.php");
    require("../view/user/resetkey.php");
});

$router->add("/user/security/resetpwd",function () {
    require("../include/main.php");
    require("../view/user/resetpwd.php");
});

$router->add("/blank",function () {
    require("../include/main.php");
    require("../view/blank.php");
});

$router->add("/admin/users/view",function () {
    require("../include/main.php");
    require("../view/admin/users/view_users.php");
});

$router->add("/admin/users/edit",function () {
    require("../include/main.php");
    require("../view/admin/users/edit_user.php");
});

$router->add("/admin/users/new",function () {
    require("../include/main.php");
    require("../view/admin/users/new_user.php");
});

$router->add("/admin/users/delete",function () {
    require("../include/main.php");
    require("../view/admin/users/delete_user.php");
});

$router->add("/admin/users/security/resetkey",function() {
    require("../include/main.php");
    require("../view/admin/users/user_reset_key.php");
});

$router->add("/admin/users/security/resetpwd",function() {
    require("../include/main.php");
    require("../view/admin/users/user_reset_password.php");
});

$router->add("/e/401", function () {
    require("../include/main.php");
    require("../view/errors/401.php");
});

$router->add("/e/maintenance", function () {
    require("../include/main.php");
    require("../view/errors/maintenance.php");
});

$router->add("/(.*)", function () {
    require("../include/main.php");
    require("../view/errors/404.php");
});

$router->route();
?>