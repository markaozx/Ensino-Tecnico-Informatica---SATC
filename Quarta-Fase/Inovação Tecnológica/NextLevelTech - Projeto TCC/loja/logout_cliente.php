<?php
// Sessão (para cliente) - compatível com PHP 5.3
if (function_exists('session_status')) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
} else {
    if (session_id() === '') { session_start(); }
}

unset($_SESSION['cliente_id'], $_SESSION['cliente_nome'], $_SESSION['cart']);
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'home.php';
header('Location: ' . $redirect);
exit;

