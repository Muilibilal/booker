<?php
session_start();

if (!isset($_SESSION['admin-verify'])) {
    header("Location: login.php");
    exit();
}
