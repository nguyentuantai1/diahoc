<?php

// Kiểm tra trang hiện tại để tải nội dung
$page = isset($_GET['page']) ? $_GET['page'] : 'home'; // Trang mặc định là home

// Bao gồm view tương ứng với trang
switch($page) {
    case 'home':
        include('view/home.php');
        break;

    case 'login':
        include('view/login.php');
        break;

    case 'admin':
        include('view/admin.php');
        break;
            
    case 'review':
        include('view/review.php');
        break;
        
    case 'register':
        include('view/register.php');
        break;
    
    default:
        include('view/home.php');
}


?>
