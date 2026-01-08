<?php
session_start();

// Simulasi data pengguna (dalam implementasi nyata, ini dari database)
$users = [];

// Proses form submission
$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isLogin = isset($_POST['is_login']) && $_POST['is_login'] === 'true';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Validasi
    if (empty($email) || empty($password)) {
        $errors[] = "Mohon isi semua field yang diperlukan";
    }
    
    if (!$isLogin && $password !== $confirm_password) {
        $errors[] = "Password tidak cocok";
    }
    
    if (!$isLogin && empty($name)) {
        $errors[] = "Nama lengkap harus diisi";
    }
    
    // Jika tidak ada error, proses autentikasi
    if (empty($errors)) {
        // Simulasi proses autentikasi
        sleep(1); // Simulasi loading
        
        if ($isLogin) {
            // Simulasi login berhasil
            $_SESSION['user'] = [
                'name' => 'John Doe',
                'email' => $email
            ];
            $success_message = "Selamat datang kembali! Senang melihatmu kembali di SehatJiwa";
        } else {
            // Simulasi registrasi berhasil
            $_SESSION['user'] = [
                'name' => $name,
                'email' => $email
            ];
            $success_message = "Pendaftaran berhasil! Akun kamu berhasil dibuat. Selamat datang di SehatJiwa!";
        }
    }
}

// Tentukan apakah mode login atau register
$isLogin = !isset($_GET['mode']) || $_GET['mode'] === 'login';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SehatJiwa - <?php echo $isLogin ? 'Masuk' : 'Daftar'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --card-bg: #ffffff;
            --background: #f9fafb;
            --foreground: #111827;
            --muted: #f3f4f6;
            --muted-foreground: #6b7280;
            --border: #e5e7eb;
            --danger: #ef4444;
            --radius: 0.75rem;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f0f9ff 0%, #dcfce7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            color: var(--foreground);
        }

        .container {
            width: 100%;
            max-width: 28rem;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            text-decoration: none;
            color: var(--foreground);
        }

        .logo-icon {
            width: 2.5rem;
            height: 2.5rem;
            background-color: var(--primary);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .card-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .card-subtitle {
            color: var(--muted-foreground);
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--foreground);
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-foreground);
            width: 1.25rem;
            height: 1.25rem;
        }

        .input {
            width: 100%;
            padding: 0.75rem 0.75rem 0.75rem 2.5rem;
            background-color: var(--muted);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--foreground);
            font-size: 1rem;
            transition: all 0.2s;
        }

        .input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted-foreground);
            cursor: pointer;
            padding: 0.25rem;
        }

        .password-toggle:hover {
            color: var(--foreground);
        }

        .forgot-password {
            display: block;
            text-align: right;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--primary);
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            margin-top: 1rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn:disabled:hover {
            transform: none;
            box-shadow: none;
        }

        .toggle-mode {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        .toggle-link {
            color: var(--primary);
            font-weight: 500;
            text-decoration: none;
            margin-left: 0.25rem;
        }

        .toggle-link:hover {
            text-decoration: underline;
        }

        .back-home {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .back-link:hover {
            color: var(--foreground);
        }

        .alert {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid var(--primary);
        }

        .alert-icon {
            font-size: 1.25rem;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 640px) {
            .card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo -->
        <a href="index.php" class="logo">
            <div class="logo-icon">
                <i class="fas fa-heart" style="color: white;"></i>
            </div>
            <span class="logo-text">SehatJiwa</span>
        </a>

        <!-- Alert Messages -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle alert-icon"></i>
                <div>
                    <strong>Error</strong>
                    <?php foreach ($errors as $error): ?>
                        <p style="margin-top: 0.25rem;"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle alert-icon"></i>
                <div>
                    <strong>Berhasil!</strong>
                    <p style="margin-top: 0.25rem;"><?php echo htmlspecialchars($success_message); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Card -->
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <?php echo $isLogin ? 'Selamat Datang Kembali' : 'Buat Akun Baru'; ?>
                </h1>
                <p class="card-subtitle">
                    <?php echo $isLogin 
                        ? 'Masuk untuk melanjutkan perjalananmu' 
                        : 'Daftar untuk memulai perjalanan kesehatan mentalmu'; ?>
                </p>
            </div>

            <form method="POST" action="auth.php" id="authForm">
                <input type="hidden" name="is_login" value="<?php echo $isLogin ? 'true' : 'false'; ?>">
                
                <?php if (!$isLogin): ?>
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input 
                                type="text" 
                                name="name" 
                                placeholder="Masukkan nama lengkap" 
                                class="input"
                                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                required>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            type="email" 
                            name="email" 
                            placeholder="Masukkan email" 
                            class="input"
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            placeholder="Masukkan password" 
                            class="input"
                            required>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <?php if (!$isLogin): ?>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input 
                                type="password" 
                                name="confirm_password" 
                                id="confirmPassword"
                                placeholder="Konfirmasi password" 
                                class="input"
                                required>
                            <button type="button" class="password-toggle" id="toggleConfirmPassword">
                                <i class="fas fa-eye" id="confirmEyeIcon"></i>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($isLogin): ?>
                    <a href="#" class="forgot-password">Lupa password?</a>
                <?php endif; ?>

                <button type="submit" class="btn" id="submitBtn">
                    <?php echo $isLogin ? 'Masuk' : 'Daftar'; ?>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="toggle-mode">
                <span>
                    <?php echo $isLogin ? 'Belum punya akun?' : 'Sudah punya akun?'; ?>
                </span>
                <a href="auth.php?mode=<?php echo $isLogin ? 'register' : 'login'; ?>" class="toggle-link">
                    <?php echo $isLogin ? 'Daftar sekarang' : 'Masuk di sini'; ?>
                </a>
            </div>
        </div>

        <!-- Back to home -->
        <div class="back-home">
            <a href="index.php" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Kembali ke beranda
            </a>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Toggle confirm password visibility (if exists)
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        if (toggleConfirmPassword) {
            toggleConfirmPassword.addEventListener('click', function() {
                const confirmPasswordInput = document.getElementById('confirmPassword');
                const confirmEyeIcon = document.getElementById('confirmEyeIcon');
                
                if (confirmPasswordInput.type === 'password') {
                    confirmPasswordInput.type = 'text';
                    confirmEyeIcon.classList.remove('fa-eye');
                    confirmEyeIcon.classList.add('fa-eye-slash');
                } else {
                    confirmPasswordInput.type = 'password';
                    confirmEyeIcon.classList.remove('fa-eye-slash');
                    confirmEyeIcon.classList.add('fa-eye');
                }
            });
        }

        // Form submission loading state
        document.getElementById('authForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Memproses... <i class="fas fa-spinner fa-spin"></i>';
        });

        // Auto-hide success message after 5 seconds
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';
                setTimeout(() => {
                    successAlert.style.display = 'none';
                }, 500);
            }, 5000);
        }
    </script>
</body>
</html>