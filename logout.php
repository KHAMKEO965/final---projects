<?php
/**
 * logout.php - ອອກຈາກລະບົບ (ສະບັບມີການຢືນຢັນ ພ້ອມຮູບພາບ ແລະ ຄຳຄົມ)
 */

session_start();

// ຖ້າເປັນ POST request (ມາຈາກການຄລິກຢືນຢັນ)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_logout'])) {
    // ລຶບຂໍ້ມູນ Session ທັງໝົດ
    $_SESSION = array();
    
    // ລຶບ Session Cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // ທຳລາຍ Session
    session_destroy();
    
    // ນຳທາງກັບໄປໜ້າ Login
    header('Location: login.php?message=logged_out');
    exit();
}

// ຖ້າບໍ່ແມ່ນ POST request, ສະແດງໜ້າຢືນຢັນ
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຢືນຢັນການອອກຈາກລະບົບ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Noto Sans Lao', sans-serif;
        }
        
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }
        
        .split-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }
        
        /* ດ້ານຊ້າຍ - ຮູບພາບ ແລະ ຄຳຄົມ */
        .left-side {
            flex: 1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .left-side img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.85;
        }
        
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
        }
        
        .quote-container {
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
            padding: 40px;
            max-width: 500px;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .quote-icon {
            font-size: 50px;
            margin-bottom: 20px;
            opacity: 0.9;
        }
        
        .quote-text {
            font-size: 28px;
            font-weight: 500;
            line-height: 1.4;
            margin-bottom: 25px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .quote-author {
            font-size: 16px;
            opacity: 0.9;
            font-style: italic;
        }
        
        /* ດ້ານຂວາ - ຟອມຢືນຢັນ */
        .right-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            padding: 20px;
        }
        
        .logout-card {
            border-radius: 20px;
            text-align: center;
            padding: 50px 40px;
            background: white;
            max-width: 450px;
            width: 100%;
            animation: fadeInRight 0.8s ease-out;
        }
        
        .logout-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        
        .logout-card h3 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }
        
        .logout-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
            padding: 12px 35px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 50px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
            background: linear-gradient(135deg, #c82333, #bd2130);
        }
        
        .btn-cancel {
            background: #6c757d;
            border: none;
            padding: 12px 35px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 50px;
            margin-left: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
            background: #5a6268;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @media (max-width: 768px) {
            .split-container {
                flex-direction: column;
            }
            .left-side {
                display: none;
            }
            .right-side {
                flex: 1;
            }
            .logout-card {
                padding: 30px 20px;
            }
        }
        
        .btn i {
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="split-container">
        <!-- ດ້ານຊ້າຍ: ຮູບພາບ ແລະ ຄຳຄົມ -->
        <div class="left-side">
            <img src="https://images.unsplash.com/photo-1529074963764-98f45c47344b?auto=format&fit=crop&w=1600&q=80" alt="Background">
            <div class="overlay"></div>
            <div class="quote-container">
                <div class="quote-icon">
                    <i class="fas fa-quote-right"></i>
                </div>
                <div class="quote-text">
                    ""ຄວາມຮັກທີ່ແທ້ຈິງບໍ່ໄດ້ມາຈາກການຫວງຫ້າມ ແຕ່ມາຈາກຄວາມເຂົ້າໃຈ", 
                </div>
                <div class="quote-author">
                    — By khamkeo
                </div>
            </div>
        </div>
        
        <!-- ດ້ານຂວາ: ຟອມຢືນຢັນການອອກລະບົບ -->
        <div class="right-side">
            <div class="logout-card">
                <div class="logout-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <h3>ຢືນຢັນການອອກຈາກລະບົບ</h3>
                <p>
                    ທ່ານກຳລັງຈະອອກຈາກລະບົບ. <br>
                    ກະລຸນາຢືນຢັນວ່າທ່ານຕ້ອງການອອກຈາກລະບົບແທ້ ຫຼື ບໍ່?
                </p>
                <form method="POST" action="">
                    <input type="hidden" name="confirm_logout" value="1">
                    <button type="submit" class="btn btn-logout text-white">
                        <i class="fas fa-sign-out-alt"></i> ອອກຈາກລະບົບ
                    </button>
                    <button type="button" class="btn btn-cancel text-white" onclick="window.location.href='dashboard.php'">
                        <i class="fas fa-times"></i> ຍົກເລີກ
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>