<?php
session_start();
include 'db.php';

$error = "";

if (isset($_POST['login'])) {
    // ຮັບຄ່າ ແລະ ປ້ອງກັນ SQL Injection ເບື້ອງຕົ້ນ
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // ເຂົ້າລະຫັດ MD5 ໃຫ້ກົງກັບໃນ Database
    $password_md5 = md5($password);

    // ກວດສອບຂໍ້ມູນ
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password_md5'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
     
    } else {
        $error = "❌ ຊື່ຜູ້ໃຊ້ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ!";
    }
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Air Cargo Login</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans Lao', sans-serif; }
        .glass-effect {
            background: rgba(41, 25, 178, 0.95);
            backdrop-filter: blur(10px);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-fade { animation: fadeIn 0.8s ease-out; }
    </style>
</head>
<body class="bg-gray-100 h-screen overflow-hidden">

    <!-- Loading Overlay -->
    <div id="loading" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500"></div>
    </div>

    <div class="flex h-full">
        <!-- ສ່ວນເບື້ອງຊ້າຍ: ຄຳຄົມ ແລະ ຮູບພາບ -->
        <div class="hidden lg:flex lg:w-2/3 relative bg-blue-900 items-center justify-center p-12 text-white">
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1529074963764-98f45c47344b?auto=format&fit=crop&w=1600&q=80" 
                     class="w-full h-full object-cover opacity-40" alt="Background">
            </div>
            <div class="relative z-10 max-w-2xl">
                <h1 class="text-5xl font-bold mb-6 leading-tight">ຍິນດີຕ້ອນຮັບເຂົ້າສູ່ <br>ລະບົບບັນທຶກການຂົນສົ່ງ</h1>
                <p class="text-2xl italic font-light border-l-4 border-blue-400 pl-6">
                    "ການຂົນສົ່ງທີ່ວ່ອງໄວ ແລະ ປອດໄພ ແມ່ນຫົວໃຈຂອງທຸລະກິດພວກເຮົາ. ພວກເຮົາເຊື່ອມຕໍ່ໂລກໃຫ້ໃກ້ກັນກວ່າເກົ່າ."
                </p>
                <div class="mt-8 flex items-center gap-4">
                    <div class="h-1 w-20 bg-blue-400"></div>
                    <span class="uppercase tracking-widest text-sm">Air Cargo Solution By Khamkeo</span>
                </div>
            </div>
        </div>

    <!-- ສ່ວນເບື້ອງຂວາ: Form Login (ປັບໃຫ້ແຄບ ແລະ ນ້ອຍລົງ) -->
<div class="w-full lg:w-1/3 flex items-center justify-center p-4 bg-white lg:bg-gray-50">
    <!-- ປ່ຽນ max-w-sm ເປັນ max-w-[320px] -->
    <div class="w-full max-w-[320px] animate-fade">
        <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">

                  <div class="text-center mb-6">
                <!-- ປັບໄອຄອນໃຫ້ນ້ອຍລົງ w-12 h-12 -->
                <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full mb-3 shadow-md">
                    <i class="fas fa-plane-departure text-xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">ເຂົ້າສູ່ລະບົບ</h2>
                <p class="text-gray-500 text-xs">Air Cargo System</p>
            </div>  

                    <div id="alertMessage"></div>

                    <form id="loginForm" method="POST" action="" class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ຊື່ຜູ້ໃຊ້</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" name="username" id="username" required
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                                    placeholder="Username">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ລະຫັດຜ່ານ</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" id="password" required
                                    class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                                    placeholder="••••••••">
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center">
                                <input type="checkbox" id="rememberMe" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-600">ຈື່ຊື່ຜູ້ໃຊ້</span>
                            </label>
                        </div>

                        <button type="submit" id="loginBtn"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition-all transform hover:-translate-y-1 shadow-md">
                            <i class="fas fa-sign-in-alt mr-2"></i> ເຂົ້າສູ່ລະບົບ
                        </button>

                    
    <script>
        const loginForm = document.getElementById('loginForm');
        const loading = document.getElementById('loading');
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        // Toggle Password
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            togglePassword.querySelector('i').classList.toggle('fa-eye');
            togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // AJAX Handle
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            loading.classList.remove('hidden');
            
            const formData = new FormData(loginForm);
            try {
                const response = await fetch('', { method: 'POST', body: formData });
                const text = await response.text();

                if (text.includes('dashboard.php')) {
                    window.location.href = 'dashboard.php';
                } else {
                    showAlert('ຊື່ຜູ້ໃຊ້ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ');
                }
            } catch (err) {
                showAlert('ເກີດຂໍ້ຜິດພາດໃນການເຊື່ອມຕໍ່');
            } finally {
                loading.classList.add('hidden');
            }
        });

        function showAlert(msg) {
            const alertBox = document.getElementById('alertMessage');
            alertBox.innerHTML = `
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 text-sm animate-bounce" role="alert">
                    <p>${msg}</p>
                </div>
            `;
            setTimeout(() => alertBox.innerHTML = '', 4000);
        }

        // Remember User
        if(localStorage.getItem('saved_user')) {
            document.getElementById('username').value = localStorage.getItem('saved_user');
            document.getElementById('rememberMe').checked = true;
        }

        loginForm.addEventListener('submit', () => {
            if(document.getElementById('rememberMe').checked) {
                localStorage.setItem('saved_user', document.getElementById('username').value);
            } else {
                localStorage.removeItem('saved_user');
            }
        });
    </script>
</body>
</html>