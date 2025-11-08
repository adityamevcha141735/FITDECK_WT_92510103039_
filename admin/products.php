<?php include('header.php');?>
<?php include('../server/connection.php');?>
<?php 
    if(!isset($_SESSION['admin_logged_in'])){
        header('location:login.php');
        exit();
    }

    //1.determine page no
    if(isset($_GET['page_no']) && $_GET['page_no'] != ""){
        $page_no=$_GET['page_no'];
    }else{
        $page_no = 1;
    }

    // 2.return number of products
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products");
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    //3.products per page
    $total_records_per_page = 8;
    $offset = ($page_no-1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_no_of_pages = ceil($total_records/$total_records_per_page);

    //4.get all products
    $stmt2=$conn->prepare("SELECT * FROM products LIMIT $offset,$total_records_per_page");
    $stmt2->execute();
    $products = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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

        .table-container {
            background: white;
            padding: 1.5rem;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .table thead th {
            background-color: var(--light-bg);
            color: var(--secondary-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            border-bottom: 2px solid #e3e6f0;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem;
            border-bottom: 1px solid #e3e6f0;
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

        .btn-danger {
            background-color: #e74a3b;
            border-color: #e74a3b;
        }

        .pagination {
            margin-top: 2rem;
            justify-content: center;
        }

        .page-link {
            padding: 0.5rem 0.75rem;
            color: var(--primary-color);
            background-color: #fff;
            border: 1px solid #dddfeb;
        }

        .page-link:hover {
            color: #224abe;
            background-color: var(--light-bg);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .product-image {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 0.25rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100px;
            }
            .content {
                margin-left: 100px;
            }
        }

        .alert {
            border-radius: 0.35rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="sidebar">
            <nav class="nav flex-column">
            <a href="index.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="orders.php" class="nav-link ">
                    <i class="fas fa-tachometer-alt"></i> Orders
                </a>
                
                <a href="products.php" class="nav-link active">
                    <i class="fas fa-box"></i> Products
                </a>
                <a href="add_product.php" class="nav-link">
                    <i class="fas fa-plus-circle"></i> Add Product
                </a>
                <a href="orders.php" class="nav-link">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
                <a href="account.php" class="nav-link">
                    <i class="fas fa-user"></i> Account
                </a>
                <a href="help.php" class="nav-link">
                    <i class="fas fa-question-circle"></i> Help
                </a>
            </nav>
        </div>

        <div class="content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Products Management</h1>
                <a href="add_product.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            </div>

            <?php if(isset($_GET['edit_success_message'])): ?>
                <div class="alert alert-success"><?php echo $_GET['edit_success_message']; ?></div>
            <?php endif; ?>

            <?php if(isset($_GET['edit_failure_message'])): ?>
                <div class="alert alert-danger"><?php echo $_GET['edit_failure_message']; ?></div>
            <?php endif; ?>

            <?php if(isset($_GET['delete_success'])): ?>
                <div class="alert alert-success"><?php echo $_GET['delete_success']; ?></div>
            <?php endif; ?>

            <?php if(isset($_GET['delete_failure'])): ?>
                <div class="alert alert-danger"><?php echo $_GET['delete_failure']; ?></div>
            <?php endif; ?>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Offer</th>
                                <th>Category</th>
                                <th>Color</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $product): ?>
                            <tr>
                                <td><?php echo $product['product_id']; ?></td>
                                <td>
                                    <img src="../assets/images/<?php echo $product['product_image']; ?>" 
                                         class="product-image" 
                                         alt="<?php echo $product['product_name']; ?>">
                                </td>
                                <td><?php echo $product['product_name']; ?></td>
                                <td>$<?php echo $product['product_price']; ?></td>
                                <td><?php echo $product['product_special_offer']; ?>%</td>
                                <td><?php echo $product['product_category']; ?></td>
                                <td><?php echo $product['product_color']; ?></td>
                                <td>
                                    <div class="btn-group">
                                        <!-- <a href="edit_images.php?product_id=<?php echo $product['product_id']; ?>&product_name=<?php echo $product['product_name']; ?>" 
                                           class="btn btn-secondary btn-sm">
                                            <i class="fas fa-images"></i>
                                        </a> -->
                                        <a href="edit_product.php?product_id=<?php echo $product['product_id']; ?>" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete_product.php?product_id=<?php echo $product['product_id']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination">
                        <li class="page-item <?php if($page_no <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="<?php if($page_no <= 1) echo '#'; else echo "?page_no=".($page_no-1); ?>">
                                Previous
                            </a>
                        </li>

                        <?php for($i = 1; $i <= $total_no_of_pages; $i++): ?>
                            <li class="page-item <?php if($page_no == $i) echo 'active'; ?>">
                                <a class="page-link" href="?page_no=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php if($page_no >= $total_no_of_pages) echo 'disabled'; ?>">
                            <a class="page-link" href="<?php if($page_no >= $total_no_of_pages) echo '#'; else echo "?page_no=".($page_no+1); ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150);
                }, 5000);
            });
        });
    </script>
</body>
</html>