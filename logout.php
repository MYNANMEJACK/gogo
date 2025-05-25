<?php
session_start();

// 清除所有 session 變量
$_SESSION = array();

// 刪除 session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// 銷毀 session
session_destroy();

// 刪除記住我的 cookie
setcookie('admin_remember', '', time()-3600, '/');

// 重定向到登錄頁面
header('Location: login.html');
exit;
?> 