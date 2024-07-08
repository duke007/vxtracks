<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} else {
    if ($_SESSION['status'] == 'ADMIN') {
        header("Location: admin.php");
    } else {
        header("Location: user.php");
    }
    exit();
}
