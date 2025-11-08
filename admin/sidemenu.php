  <!-- Sidebar -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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

  </style>
</head>
<body>
  


  <div class="sidebar">
            <!-- <a href="index.php">Dashboard</a>
            <a href="index.php">Orders</a>
            <a href="products.php">Products</a>
            <a href="account.php">Account</a>
            <a href="add_product.php">Add new product</a>
            <a href="help.php">Help</a> -->
            <div class="sidebar">
            <nav class="nav flex-column">
            <a href="index.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="orders.php" class="nav-link ">
                    <i class="fas fa-tachometer-alt"></i> Orders
                </a>
                
                <a href="products.php" class="nav-link ">
                    <i class="fas fa-box"></i> Products
                </a>
                <a href="add_product.php" class="nav-link">
                    <i class="fas fa-plus-circle"></i> Add Product
                </a>
                
                <a href="account.php" class="nav-link">
                    <i class="fas fa-user"></i> Account
                </a>
                <a href="help.php" class="nav-link">
                    <i class="fas fa-question-circle"></i> Help
                </a>
            </nav>
        </div>
          </div>
</body>
</html>