<?php include('header.php');?>
<?php include('../server/connection.php');?>

<?php 
    // Redirect if admin is not logged in
    if(!isset($_SESSION['admin_logged_in'])){
        header('location:index.php');
        exit();
    }

    // Delete Order
// Delete Order
if(isset($_GET['delete_order_id'])){
    $order_id = $_GET['delete_order_id'];
    
    // Delete the order
    $delete_stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
    $delete_stmt->bind_param("i", $order_id);
    
    if($delete_stmt->execute()){
        // Reset the AUTO_INCREMENT after deletion to avoid gaps
        // Find the maximum order_id currently in the table
        $max_order_id_result = $conn->query("SELECT MAX(order_id) AS max_id FROM orders");
        $max_order_id = $max_order_id_result->fetch_assoc()['max_id'];
        
        // If there are no records, set AUTO_INCREMENT to 1
        if (!$max_order_id) {
            $max_order_id = 0;
        }
        
        // Set the next AUTO_INCREMENT value
        $reset_auto_increment = $conn->query("ALTER TABLE orders AUTO_INCREMENT = " . ($max_order_id + 1));

        // Redirect after successful deletion and reset
        header('Location: orders.php?order_deleted=Order Deleted Successfully');
    } else {
        // Redirect if deletion failed
        header('Location: orders.php?order_failed=Failed to Delete Order');
    }
}

    // 1. Determine page number
    $page_no = isset($_GET['page_no']) && $_GET['page_no'] != "" ? $_GET['page_no'] : 1;

    // 2. Return number of orders
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM orders");
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    // 3. Products per page
    $total_records_per_page = 10;
    $offset = ($page_no-1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";
    $total_no_of_pages = ceil($total_records/$total_records_per_page);

    // 4. Get all orders
    $stmt2 = $conn->prepare("SELECT * FROM orders LIMIT $offset, $total_records_per_page");
    $stmt2->execute();
    $orders = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #f39c12;
            --secondary-color: #2c3e50;
            --light-bg: #ecf0f1;
            --dark-text: #34495e;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f1c40f;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
            margin: 0;
            padding: 0;
        }

        .sidebar {
            min-height: 100vh;
            width: 250px;
            background-color: var(--secondary-color);
            color: white;
            position: fixed;
            padding-top: 20px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .content {
            margin-left: 250px;
            padding: 2rem;
            overflow-x: auto;
        }

        .table-container {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--secondary-color);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            border: none;
        }

        .table tbody tr:nth-child(odd) {
            background-color: rgba(236, 240, 241, 0.3);
        }

        .table tbody tr:nth-child(even) {
            background-color: white;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(243, 156, 18, 0.05);
            transform: scale(1.01);
        }

        .table td {
            vertical-align: middle;
            padding: 1rem;
            border-color: #eee;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
            transform: translateY(-2px);
        }

        .pagination {
            margin-top: 2rem;
            justify-content: center;
            gap: 0.5rem;
        }

        .page-link {
            color: var(--primary-color);
            background-color: white;
            border: 1px solid #dee2e6;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            animation: fadeOut 5s forwards;
        }

        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            border: 1px solid var(--danger-color);
        }

        @keyframes fadeOut {
            0% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100px;
            }
            .content {
                margin-left: 100px;
            }
            .table-responsive {
                overflow-x: auto;
            }
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(255, 255, 255, 0.1);
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
                <a href="orders.php" class="nav-link active">
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
        
        <div class="content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Dashboard</h3>
            </div>

            <div class="table-container">
                <h4 class="mb-4">Orders</h4>
                
                <?php if(isset($_GET['order_updated'])){?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_GET['order_updated'];?>
                    </div>
                <?php }?>

                <?php if(isset($_GET['order_failed'])){?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_GET['order_failed'];?>
                    </div>
                <?php }?>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Status</th>
                                <th>User ID</th>
                                <th>Order Date</th>
                                <th>User Phone</th>
                                <th>User Address</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order) { ?>
                            <tr>
                                <td><?php echo $order['order_id'];?></td>
                                <td><?php echo $order['payment_status'];?></td>
                                <td><?php echo $order['user_id'];?></td>
                                <td><?php echo $order['order_date'];?></td>
                                <td><?php echo $order['phone'];?></td>
                                <td><?php echo $order['address'];?></td>
                                <td><a class="btn btn-primary" href="edit_order.php?order_id=<?php echo $order['order_id'];?>">Edit</a></td>
                                <td><a class="btn btn-danger" href="?delete_order_id=<?php echo $order['order_id'];?>">Delete</a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item <?php if($page_no<=1){echo 'disabled';}?>">
                            <a class="page-link" href="<?php if($page_no <= 1){ echo '#'; } else{ echo "?page_no=".($page_no-1);}?>">Previous</a>
                        </li>

                        <li class="page-item"><a class="page-link" href="?page_no=1">1</a></li>
                        <li class="page-item"><a class="page-link" href="?page_no=2">2</a></li>

                        <?php if($page_no >= 3){ ?>
                        <li class="page-item"><a class="page-link" href="#">...</a></li>
                        <li class="page-item"><a class="page-link" href="<?php echo "?page_no=".$page_no;?>"><?php echo $page_no; ?></a></li>
                        <?php } ?>

                        <li class="page-item <?php if($page_no >= $total_no_of_pages){echo 'disabled';}?>">
                            <a class="page-link" href="<?php if($page_no >= $total_no_of_pages){ echo '#'; } else{ echo "?page_no=".($page_no+1);}?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>