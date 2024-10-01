<?php
session_start();

if (isset($_SESSION['usuario_nome'])) {
    session_unset();

    session_destroy();

    header("Location: login.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>
