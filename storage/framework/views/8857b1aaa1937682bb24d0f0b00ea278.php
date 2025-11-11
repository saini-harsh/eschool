<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php echo e(URL::asset('/admin/img/favicon.png')); ?>">
    <title>Login - ESchool</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap"
        rel="stylesheet">

    <!-- Login CSS -->
    <!-- <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>"> -->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #3b0c94, #4b1d9d);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            width: 100%;
            max-width: 420px;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .login-container img {
            width: 160px;
            margin-bottom: 20px;
        }

        .login-container h2 {
            margin-bottom: 25px;
            color: #1f1f1f;
            font-size: 22px;
        }

        .input-field {
            position: relative;
            margin-bottom: 20px;
        }

        .input-field input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .input-field img {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            opacity: 0.5;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .options label {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .options a {
            color: #4267FF;
            text-decoration: none;
        }

        .options a:hover {
            text-decoration: underline;
        }

        .login-btn {
            background: #4267FF;
            border: none;
            padding: 12px;
            width: 100%;
            color: white;
            font-weight: bold;
            font-size: 15px;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background 0.3s ease;
        }

        .login-btn:hover {
            background: #2c51d6;
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
        }

        .roles-grid button {
            padding: 10px 8px;
            border: none;
            background: #e4e6f0;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .roles-grid button:hover {
            background: #cdd0e0;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 25px 20px;
            }

            .login-container img {
                width: 130px;
            }

            .input-field input {
                font-size: 13px;
            }

            .roles-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="<?php echo e(asset('/admin/img/logo.png')); ?>" alt="ESchool Logo" />
        <h2>Login Details</h2>
        <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login.submit')); ?>">
            <?php echo csrf_field(); ?>

            <div class="input-field">
                <img src="https://img.icons8.com/ios-filled/50/000000/new-post.png" alt="email icon" />
                <input type="email" name="email" placeholder="Enter Email Address" required>
            </div>

            <div class="input-field">
                <img src="https://img.icons8.com/ios-filled/50/000000/lock-2.png" alt="password icon" />
                <input type="password" name="password" placeholder="Enter Password" required>
            </div>

            <!-- Role Select Dropdown -->
            <div class="input-field">
                <select name="role" required
                    style="width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #ddd; font-size: 14px;">
                    <option value="" disabled selected>Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="institution">Institution</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                </select>
            </div>

            <div class="options">
                <label><input type="checkbox" name="remember"> Remember Me</label>
                <a href="#">Forgot Password?</a>
            </div>

            <button class="login-btn" type="submit">SIGN IN</button>
        </form>

        <!-- Quick Role Buttons (optional) -->
        <div class="roles-grid">
            <button type="button" onclick="autoLogin('admin')">ADMIN</button>
            <button type="button" onclick="autoLogin('institution')">INSTITUTION</button>
            <button type="button" onclick="autoLogin('teacher')">TEACHER</button>
            <button type="button" onclick="autoLogin('student')">STUDENT</button>
        </div>
    </div>

    <script>
        function autoLogin(role) {
            let email = '';
            let password = '';
            if (role === 'admin') {
                email = 'admin@gmail.com';
                password = 'admin';
            } else if (role === 'institution') {
                email = 'greenvalley@example.com'; // example, update as needed
                password = 'school123';
            } else if (role === 'teacher') {
                email = 'rajesh.green@example.com'; // example
                password = 'teacher123';
            } else if (role === 'student') {
                email = 'rohit.gupta@example.com'; // example
                password = 'password';
            }
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = password;
            document.querySelector('select[name="role"]').value = role;
            document.querySelector('form').submit();
        }
    </script>
</body>

</html>
<?php /**PATH E:\eschool\resources\views/auth/login.blade.php ENDPATH**/ ?>