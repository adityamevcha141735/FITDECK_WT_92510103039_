<?php include('header.php'); ?>
<?php include('../server/connection.php'); ?>
<?php 
if(!isset($_SESSION['admin_logged_in'])){
    header('location:login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #f39c12;
            --secondary-color: #2c3e50;
            --light-bg: #ecf0f1;
            --dark-text: #34495e;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
        }

        .sidebar {
            min-height: 100vh;
            width: 250px;
            background-color: var(--secondary-color);
            color: white;
            position: fixed;
            transition: all 0.3s ease-in-out;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(255, 255, 255, 0.1);
        }

        .content {
            margin-left: 250px;
            padding: 1.5rem;
        }

        h1 {
            color: var(--secondary-color);
        }

        .container {
            background: white;
            padding: 1.5rem;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        p {
            color: var(--dark-text);
            font-size: 16px;
        }

        .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.25rem;
            transition: all 0.15s ease-in-out;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100px;
            }
            .content {
                margin-left: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <?php include('sidemenu.php'); ?>
            </div>
            <!-- Main Content -->
            <div class="col-md-10 content">
                <h1 class="h2">Account</h1>
                <div class="container">
                    <p>ID: <?php echo $_SESSION['admin_id']; ?></p>
                    <p>Name: <?php echo $_SESSION['admin_name']; ?></p>
                    <p>Email: <?php echo $_SESSION['admin_email']; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
