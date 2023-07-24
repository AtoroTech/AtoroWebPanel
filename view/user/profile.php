<?php
include(__DIR__ . '/../requirements/page.php');
include(__DIR__ . '/../../include/php-csrf.php');
$csrf = new CSRF();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($csrf->validate('profile-form')) {
        if (isset($_POST['edit_user'])) {
            $userdb = $conn->query("SELECT * FROM mythicalwebpanel_users WHERE api_key = '" . $_COOKIE['token'] . "'")->fetch_array();
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
            $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $avatar = mysqli_real_escape_string($conn, $_POST['avatar']);
            if (!$username == "" || $firstName == "" || $lastName == "" || $email == "" || $avatar == "") {
                if (!$userdb['username'] == $username || !$email == $userdb['email']) {
                    $check_query = "SELECT * FROM mythicalwebpanel_users WHERE username = '$username' OR email = '$email'";
                    $result = mysqli_query($conn, $check_query);
                    if (mysqli_num_rows($result) > 0) {
                        header('location: /user/profile?e=Username or email already exists. Please choose a different one');
                        exit();
                    }
                } else {
                    $conn->query("UPDATE `mythicalwebpanel_users` SET `username` = '" . $username . "' WHERE `mythicalwebpanel_users`.`api_key` = '" . $_COOKIE['token'] . "';");
                    $conn->query("UPDATE `mythicalwebpanel_users` SET `first_name` = '" . $firstName . "' WHERE `mythicalwebpanel_users`.`api_key` = '" . $_COOKIE['token'] . "';");
                    $conn->query("UPDATE `mythicalwebpanel_users` SET `last_name` = '" . $lastName . "' WHERE `mythicalwebpanel_users`.`api_key` = '" . $_COOKIE['token'] . "';");
                    $conn->query("UPDATE `mythicalwebpanel_users` SET `avatar` = '" . $avatar . "' WHERE `mythicalwebpanel_users`.`api_key` = '" . $_COOKIE['token'] . "';");
                    $conn->query("UPDATE `mythicalwebpanel_users` SET `email` = '" . $email . "' WHERE `mythicalwebpanel_users`.`api_key` = '" . $_COOKIE['token'] . "';");
                    $conn->close();
                    header('location: /user/profile?s=We updated the user settings in the database');
                }
            } else {
                header('location: /user/profile?e=Please fill in all the info');
                exit();
            }
        }
    } else {
        header('location: /user/profile?e=CSRF Verification Failed');
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-semi-dark"
    data-assets-path="<?= $appURL ?>/assets/" data-template="vertical-menu-template">

<head>
    <?php include(__DIR__ . '/../requirements/head.php'); ?>
    <title>
        <?= $settings['name'] ?> | Users
    </title>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include(__DIR__ . '/../components/sidebar.php') ?>
            <div class="layout-page">
                <?php include(__DIR__ . '/../components/navbar.php') ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin / Users /</span> Edit</h4>
                        <?php
                        if (isset($_GET['e'])) {
                            ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <?= $_GET['e'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                        if (isset($_GET['s'])) {
                            ?>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <?= $_GET['s'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-pills flex-column flex-md-row mb-4">
                                    <li class="nav-item">
                                        <a href="/user/profile" class="nav-link active"><i
                                                class="ti-xs ti ti-users me-1"></i> Account</a>
                                    </li>
                                    <!--<li class="nav-item">
                      <a class="nav-link" href="pages-account-settings-billing.html"
                        ><i class="ti-xs ti ti-file-description me-1"></i> Billing & Plans</a
                      >
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="pages-account-settings-notifications.html"
                        ><i class="ti-xs ti ti-bell me-1"></i> Notifications</a
                      >
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="pages-account-settings-connections.html"
                        ><i class="ti-xs ti ti-link me-1"></i> Connections</a
                      >
                    </li>-->
                                </ul>
                                <div class="card mb-4">
                                    <h5 class="card-header">Profile Details</h5>
                                    <!-- Account -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                                            <img src="<?= $userdb['avatar'] ?>" alt="user-avatar"
                                                class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                                        </div>
                                    </div>
                                    <hr class="my-0" />
                                    <div class="card-body">
                                        <form action="/user/profile" method="POST">
                                            <div class="row">
                                                <div class="mb-3 col-md-6">
                                                    <label for="username" class="form-label">Username</label>
                                                    <input class="form-control" type="text" id="username"
                                                        name="username" value="<?= $userdb['username'] ?>"
                                                        placeholder="jhondoe" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="firstName" class="form-label">First Name</label>
                                                    <input class="form-control" type="text" id="firstName"
                                                        name="firstName" value="<?= $userdb['first_name'] ?>"
                                                        autofocus />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="lastName" class="form-label">Last Name</label>
                                                    <input class="form-control" type="text" name="lastName"
                                                        id="lastName" value="<?= $userdb['last_name'] ?>" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="email" class="form-label">E-mail</label>
                                                    <input class="form-control" type="email" id="email" name="email"
                                                        value="<?= $userdb['email'] ?>"
                                                        placeholder="john.doe@example.com" />
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="avatar" class="form-label">Avatar</label>
                                                    <input class="form-control" type="text" id="avatar" name="avatar"
                                                        value="<?= $userdb['avatar'] ?>" />
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label for="avatar" class="form-label">Secret Key</label><br>
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#viewkey" class="btn btn-primary btn-sm me-2"
                                                        value="true">View secret key</button>
                                                </div>
                                            </div>
                                            <?= $csrf->input('profile-form'); ?>

                                            <div class="mt-2">
                                                <button type="submit" name="edit_user" class="btn btn-primary me-2"
                                                    value="true">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <h5 class="card-header">Danger Zone</h5>
                                    <div class="card-body">
                                        <div class="mb-3 col-12 mb-0">
                                            <div class="alert alert-warning">
                                                <h5 class="alert-heading mb-1">Make sure you read what the button does!
                                                </h5>
                                                <p class="mb-0">Once you press a button, there is no going back. Please
                                                    be certain.</p>
                                            </div>
                                        </div>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#resetPwd"
                                            class="btn btn-danger deactivate-account">Reset Password</button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#resetKey"
                                            class="btn btn-danger deactivate-account">Reset Secret Key</button>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#deleteacc"
                                            class="btn btn-danger deactivate-account">Delete Account</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="viewkey" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                            <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                    <div class="text-center mb-4">
                                        <h3 class="mb-2">View secret key</h3>
                                        <p class="text-muted">Here is your secret key that can be used to access our
                                            client API and this is your login security token, so make sure not to share
                                            it!
                                        </p>
                                        <code><?= $userdb['api_key'] ?></code>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                            aria-label="Close">Cancel </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="deleteacc" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                            <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                    <div class="text-center mb-4">
                                        <h3 class="mb-2">Delete this user?</h3>
                                        <p class="text-muted">When you choose to delete this user, please be aware that
                                            all associated user data will be permanently wiped. This action is
                                            irreversible, so proceed with caution!
                                        </p>
                                    </div>
                                    <form method="GET" action="/user/security/delete_account" class="row g-3">
                                        <div class="col-12 text-center">
                                            <button type="submit" name="key" value="<?= $_COOKIE['token'] ?>"
                                                class="btn btn-danger me-sm-3 me-1">Delete user</button>
                                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                                aria-label="Close">Cancel </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="resetKey" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                            <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                    <div class="text-center mb-4">
                                        <h3 class="mb-2">Reset user secret key?</h3>
                                        <p class="text-muted">After updating the key, the user will have to login again.
                                        </p>
                                    </div>
                                    <form method="GET" action="/user/security/resetkey" class="row g-3">
                                        <div class="col-12 text-center">
                                            <button type="submit" name="key" value="<?= $_COOKIE['token'] ?>"
                                                class="btn btn-danger me-sm-3 me-1">Reset key</button>
                                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                                aria-label="Close">Cancel </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="resetPwd" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                            <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                    <div class="text-center mb-4">
                                        <h3 class="mb-2">Reset user password?</h3>
                                        <p class="text-muted">After updating the key, the user will stay logged in!!</p>
                                    </div>
                                    <form method="GET" action="/user/security/resetpwd" class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label" for="resetPwd">New Password</label>
                                            <input type="password" id="pwd" name="pwd" class="form-control"
                                                placeholder="" required />
                                        </div>
                                        <div class="col-12 text-center">
                                            <button type="submit" name="key" value="<?= $_COOKIE['token'] ?>"
                                                class="btn btn-danger me-sm-3 me-1">Reset password</button>
                                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                                aria-label="Close">Cancel </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include(__DIR__ . '/../components/footer.php') ?>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>
    <?php include(__DIR__ . '/../requirements/footer.php') ?>
    <!-- Page JS -->
    <script src="<?= $appURL ?>/assets/js/pages-account-settings-account.js"></script>
</body>

</html>