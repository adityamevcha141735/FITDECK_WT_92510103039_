<?php include('header.php'); ?>
<?php include('../server/connection.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        /* Sidebar styling */
        .sidebar {
            background-color: #f8f9fa;
            height: 100vh; /* Full viewport height */
            border-right: 1px solid #ddd;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: #000;
            padding: 10px 20px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #e9ecef;
        }

        /* Main content */
        .content {
            padding: 20px;
        }

        /* Help Section */
        .help-section {
            margin-top: 50px;
            text-align: center;
        }
        .help-section h2 {
            font-size: 2rem;
            margin-bottom: 20px;
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
                <div class="help-section">
                    <h2>Help</h2>
                    <p>Please contact <strong>admin@gmail.com</strong></p>
                    <p>Please call <strong>12345678</strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
