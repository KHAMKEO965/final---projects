<?php
session_start();

// ຖ້າມີ Session ຢູ່ແລ້ວ ໃຫ້ໄປທີ່ Dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'db.php';

$error = "";
$success = "";

if (isset($_POST['register'])) {
    // ຮັບຄ່າ ແລະ ປ້ອງກັນ SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    
    // ກວດສອບຂໍ້ມູນ
    if (empty($username) || empty($password) || empty($fullname)) {
        $error = "❌ ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ (ຊື່ຜູ້ໃຊ້, ລະຫັດຜ່ານ, ຊື່-ນາມສະກຸນ)";
    } elseif (strlen($username) < 3) {
        $error = "❌ ຊື່ຜູ້ໃຊ້ຕ້ອງມີຢ່າງນ້ອຍ 3 ຕົວອັກສອນ";
    } elseif (strlen($password) < 6) {
        $error = "❌ ລະຫັດຜ່ານຕ້ອງມີຢ່າງນ້ອຍ 6 ຕົວອັກສອນ";
    } elseif ($password !== $confirm_password) {
        $error = "❌ ລະຫັດຜ່ານບໍ່ກົງກັນ";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
        $error = "❌ ຮູບແບບອີເມວບໍ່ຖືກຕ້ອງ";
    } else {
        // ກວດສອບຊື່ຜູ້ໃຊ້ຊ້ຳ
        $check_query = "SELECT user_id FROM users WHERE username = '$username'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "❌ ຊື່ຜູ້ໃຊ້ນີ້ມີຢູ່ແລ້ວ";
        } else {
            // ເຂົ້າລະຫັດລະຫັດຜ່ານ (MD5)
            $password_md5 = md5($password);
            
            // ບັນທຶກຂໍ້ມູນ
            $insert_query = "INSERT INTO users (username, password, fullname, email, phone, role) 
                             VALUES ('$username', '$password_md5', '$fullname', '$email', '$phone', '$role')";
            
            if (mysqli_query($conn, $insert_query)) {
                $success = "✅ ລົງທະບຽນສຳເລັດ! ກະລຸນາເຂົ້າສູ່ລະບົບ";
                
                // ລ້າງຄ່າ Form
                $_POST = array();
            } else {
                $error = "❌ ບໍ່ສາມາດລົງທະບຽນໄດ້: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ລົງທະບຽນ - Air Cargo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans Lao', sans-serif; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        .animate-fade { animation: fadeIn 0.6s ease-out; }
        .animate-shake { animation: shake 0.5s ease-in-out; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full overflow-hidden animate-fade">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 text-white">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user-plus text-3xl"></i>
                    <div>
                        <h1 class="text-2xl font-bold">ລົງທະບຽນຜູ້ໃຊ້ໃໝ່</h1>
                        <p class="text-blue-100 text-sm">ສ້າງບັນຊີເພື່ອເຂົ້າສູ່ລະບົບ Air Cargo</p>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6">
                <!-- ສະແດງຂໍ້ຄວາມ -->
                <?php if ($error): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded animate-shake">
                    <p><i class="fas fa-exclamation-circle mr-2"></i> <?php echo $error; ?></p>
                </div>
                <?php endif; ?>

                <?php if ($success): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    <p><i class="fas fa-check-circle mr-2"></i> <?php echo $success; ?></p>
                    <a href="login.php" class="text-green-700 font-bold underline mt-2 inline-block">
                        <i class="fas fa-sign-in-alt mr-1"></i> ກົດນີ້ເພື່ອເຂົ້າສູ່ລະບົບ
                    </a>
                </div>
                <?php endif; ?>

                <form method="POST" action="" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- ຊື່ຜູ້ໃຊ້ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user text-blue-500 mr-1"></i> ຊື່ຜູ້ໃຊ້ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="username" required
                                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                                placeholder="ປ້ອນຊື່ຜູ້ໃຊ້">
                        </div>

                        <!-- ຊື່-ນາມສະກຸນ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-id-card text-blue-500 mr-1"></i> ຊື່-ນາມສະກຸນ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="fullname" required
                                value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                                placeholder="ປ້ອນຊື່ ແລະ ນາມສະກຸນ">
                        </div>

                        <!-- ລະຫັດຜ່ານ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-lock text-blue-500 mr-1"></i> ລະຫັດຜ່ານ <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                                    placeholder="ຢ່າງນ້ອຍ 6 ຕົວ">
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- ຢືນຢັນລະຫັດຜ່ານ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-check-circle text-blue-500 mr-1"></i> ຢືນຢັນລະຫັດຜ່ານ <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="confirm_password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                                placeholder="ປ້ອນລະຫັດຜ່ານອີກຄັ້ງ">
                        </div>

                        <!-- ອີເມວ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-envelope text-blue-500 mr-1"></i> ອີເມວ
                            </label>
                            <input type="email" name="email"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                                placeholder="example@email.com">
                        </div>

                        <!-- ເບີໂທ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-phone text-blue-500 mr-1"></i> ເບີໂທ
                            </label>
                            <input type="text" name="phone"
                                value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                                placeholder="020 5555 5555">
                        </div>

                        <!-- ສິດທິຜູ້ໃຊ້ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-user-tag text-blue-500 mr-1"></i> ສິດທິ
                            </label>
                            <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none">
                                <option value="user" <?php echo (isset($_POST['role']) && $_POST['role'] == 'user') ? 'selected' : ''; ?>>ຜູ້ໃຊ້ທົ່ວໄປ</option>
                                <option value="staff" <?php echo (isset($_POST['role']) && $_POST['role'] == 'staff') ? 'selected' : ''; ?>>ພະນັກງານ</option>
                                <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : ''; ?>>ຜູ້ດູແລລະບົບ</option>
                            </select>
                        </div>
                    </div>

                    <!-- ປຸ່ມສົ່ງ -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button type="submit" name="register"
                            class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-2.5 px-6 rounded-lg transition-all transform hover:-translate-y-1 shadow-md hover:shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i> ລົງທະບຽນ
                        </button>
                        <a href="login.php"
                            class="w-full sm:w-auto text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition-all">
                            <i class="fas fa-arrow-left mr-2"></i> ກັບຄືນ
                        </a>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-200 p-4 bg-gray-50 text-center">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-plane-departure text-blue-500 mr-1"></i> 
                    Air Cargo System &copy; <?php echo date('Y'); ?> 
                </p>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // ກວດສອບຄວາມຍາວລະຫັດຜ່ານແບບ Real-time
        document.querySelector('input[name="password"]').addEventListener('input', function() {
            const val = this.value;
            const indicator = document.getElementById('passwordStrength');
            if (val.length > 0 && val.length < 6) {
                this.style.borderColor = '#ef4444';
            } else if (val.length >= 6) {
                this.style.borderColor = '#22c55e';
            } else {
                this.style.borderColor = '#d1d5db';
            }
        });

        // ກວດສອບລະຫັດຜ່ານກົງກັນ
        document.querySelector('input[name="confirm_password"]').addEventListener('input', function() {
            const password = document.querySelector('input[name="password"]').value;
            const confirm = this.value;
            if (confirm.length > 0 && password !== confirm) {
                this.style.borderColor = '#ef4444';
            } else if (confirm.length > 0 && password === confirm) {
                this.style.borderColor = '#22c55e';
            } else {
                this.style.borderColor = '#d1d5db';
            }
        });
    </script>

</body>
</html>