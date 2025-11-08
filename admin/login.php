<?php
include('header.php');
include('../server/connection.php');

if(isset($_SESSION['admin_logged_in'])){
    header('location:index.php');
    exit;
}

if(isset($_POST['login_btn'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT admin_id, admin_name, admin_email, admin_password FROM admins WHERE admin_email=? AND admin_password=? LIMIT 1");
    $stmt->bind_param('ss', $email, $password);

    if($stmt->execute()){
        $stmt->bind_result($admin_id, $admin_name, $admin_email, $admin_password);
        $stmt->store_result();

        if($stmt->num_rows() == 1){
            $stmt->fetch();
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_name'] = $admin_name;
            $_SESSION['admin_email'] = $admin_email;
            $_SESSION['admin_logged_in'] = true;
            header('location: index.php?login_success=logged in successfully');
        } else {
            header('location: login.php?error=could not verify your account');
        }
    } else {
        header('location: login.php?error=something went wrong');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f4f4;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-wrapper {
            display: flex;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1200px;
            min-height: 600px;
        }

        .login-form-section {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-image-section {
            flex: 1.2;
            background-image: url('images/vo.png');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .login-image-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.2);
        }

        .login-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #fb774b;
            box-shadow: 0 0 0 2px rgba(251, 119, 75, 0.2);
        }

        .form-label {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: #fff;
            padding: 0 5px;
            color: #666;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: 0;
            font-size: 14px;
            color: #fb774b;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .forgot-link {
            color: #fb774b;
            text-decoration: none;
            font-size: 14px;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: #fb774b;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background: #e65d35;
        }

        .social-login {
            margin-top: 30px;
            text-align: center;
        }

        .social-title {
            color: #666;
            margin-bottom: 20px;
        }

        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .facebook {
            background: #3b5998;
        }

        .twitter {
            background: #1da1f2;
        }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .login-wrapper {
                flex-direction: column;
                max-width: 600px;
            }

            .login-image-section {
                min-height: 300px;
            }

            .login-form-section {
                padding: 30px;
            }
        }

        @media (max-width: 576px) {
            .login-form-section {
                padding: 20px;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .form-control {
                padding: 12px 15px;
            }

            .social-buttons {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-wrapper">
        <div class="login-form-section">
            <h2 class="login-title">Welcome Back</h2>
            
            <form action="login.php" method="POST">
                <?php if(isset($_GET['error'])){ ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_GET['error']; ?>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder=" " required>
                    <label class="form-label">Email Address</label>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder=" " required>
                    <label class="form-label">Password</label>
                </div>

                <div class="remember-forgot">
                    <label class="custom-checkbox">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" name="login_btn" class="login-btn">Login</button>

                <div class="social-login">
                    <p class="social-title">Or login with</p>
                    <div class="social-buttons">
                        <a href="#" class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-btn twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <div class="login-image-section"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>