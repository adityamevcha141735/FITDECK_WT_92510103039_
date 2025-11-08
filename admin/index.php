<?php

include('../server/connection.php');
include('header.php');
if (!isset($_SESSION['admin_logged_in'])) {
    header('location:login.php');
    exit();
}

// Initialize variables with default values
$total_records = 0;
$completed_orders = 0;
$pending_orders = 0;
$total_revenue = 0;

try {
    // Get total orders
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM orders");
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();
    $stmt1->close();

    // Get completed orders
    $stmt2 = $conn->prepare("SELECT COUNT(*) FROM orders WHERE order_status = 'completed'");
    $stmt2->execute();
    $stmt2->bind_result($completed_orders);
    $stmt2->fetch();
    $stmt2->close();

    // Get pending orders
    $stmt3 = $conn->prepare("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'");
    $stmt3->execute();
    $stmt3->bind_result($pending_orders);
    $stmt3->fetch();
    $stmt3->close();

    // Get total revenue
    $stmt4 = $conn->prepare("SELECT COALESCE(SUM(order_cost), 0) FROM orders WHERE order_status = 'completed'");
    $stmt4->execute();
    $stmt4->bind_result($total_revenue);
    $stmt4->fetch();
    $stmt4->close();

    // Get recent orders
    $stmt5 = $conn->prepare("SELECT * FROM orders ORDER BY order_date DESC LIMIT 5");
    $stmt5->execute();
    $recent_orders = $stmt5->get_result();

    // Pagination setup
    $page_no = isset($_GET['page_no']) ? intval($_GET['page_no']) : 1;
    $total_records_per_page = 10;
    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $adjacents = "2";

    $total_no_of_pages = ceil($total_records / $total_records_per_page);

    // Get paginated orders
    $stmt7 = $conn->prepare("SELECT * FROM orders LIMIT ?, ?");
    $stmt7->bind_param("ii", $offset, $total_records_per_page);
    $stmt7->execute();
    $orders = $stmt7->get_result();

} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
}
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
            min-height: 100vh;
        }

        /* Top Navbar Styles */
        .top-navbar {
            background: white;
            padding: 1rem;
            margin-left: 250px;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Sidebar Styles */
        .sidebar {
            min-height: 100vh;
            width: 250px;
            background-color: var(--secondary-color);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            transition: all 0.3s ease-in-out;
            z-index: 1040;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: var(--primary-color);
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        /* Main Content */
        .content {
            margin-left: 250px;
            margin-top: 50px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Dashboard Cards */
        .dashboard-cards {
            display: -webkit-box;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2.5rem;
            margin-bottom: 3rem;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.15);
        }

        .card-stats {
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
            color: white;
        }

        .card-stats i {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .card-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Table Styles */
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
            background-color: var(--light-bg);
            color: var(--secondary-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            border-bottom: 2px solid #e3e6f0;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(243, 156, 18, 0.05);
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem;
            border-bottom: 1px solid #e3e6f0;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.35em 1em;
            border-radius: 50rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-completed {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }

        .status-pending {
            background-color: rgba(241, 196, 15, 0.1);
            color: var(--warning-color);
        }

        /* Buttons */
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
            transform: translateY(-2px);
        }

        /* Pagination */
        .pagination {
            margin-top: 2rem;
            justify-content: center;
            gap: 0.5rem;
        }

        .page-link {
            padding: 0.5rem 1rem;
            color: var(--primary-color);
            background-color: white;
            border: 1px solid #dddfeb;
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

        .table-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
            margin: 1rem 0;
            overflow: hidden;
        }

        /* Table Header Styles */
        .table-header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e3e6f0;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table-title {
            margin: 0;
            font-size: 1.25rem;
        }

        .table-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Table Scroll Wrapper */
        .table-scroll-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Basic Table Styles */
        .table {
            margin: 0;
            width: 100%;
        }

        /* Column Specific Widths */
        .col-id {
            min-width: 80px;
        }

        .col-status {
            min-width: 100px;
        }

        .col-customer {
            min-width: 200px;
        }

        .col-date {
            min-width: 150px;
        }

        .col-amount {
            min-width: 100px;
        }

        .col-actions {
            min-width: 60px;
        }

        /* Customer Info Styles */
        .customer-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .customer-details {
            display: flex;
            flex-direction: column;
        }

        .customer-phone {
            font-size: 0.875rem;
            color: #6c757d;
        }

        /* Date Info Styles */
        .date-info {
            display: flex;
            flex-direction: column;
        }

        .time {
            font-size: 0.875rem;
            color: #6c757d;
        }

        /* Status Badge Styles */
        .status-badge {
            padding: 0.35em 0.8em;
            border-radius: 50rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Table Section Styles */
        .table-section {
            margin: 1rem 0;
        }

        /* Toggle Button Styles */
        .toggle-table {
            width: 100%;
            text-align: left;
            padding: 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .toggle-table:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .toggle-table i {
            transition: transform 0.3s ease;
        }

        .toggle-table[aria-expanded="true"] i {
            transform: rotate(180deg);
        }

        /* Collapse Animation */
        .collapse {
            transition: all 0.3s ease-out;
        }

        .collapse:not(.show) {
            display: none;
        }

        .collapsing {
            height: 0;
            overflow: hidden;
            transition: height 0.35s ease;
        }

        /* Table Container Animation */
        .table-container {
            transform-origin: top;
            animation: slideDown 0.3s ease-out forwards;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .toggle-table {
                padding: 0.75rem;
            }

            .button-text {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .table-section {
                margin: 0.5rem 0;
            }

            .toggle-table {
                padding: 0.5rem;
            }
        }

        @media (max-width: 400px) {
            .table-container {
                margin: 0.5rem 0;
                border-radius: 0;
            }

            .table-header-wrapper {
                padding: 0.75rem;
            }

            .table-title {
                font-size: 1.1rem;
            }

            .table-actions {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .form-select,
            .btn {
                width: 100%;
                max-width: none;
            }
        }

        /* Scrollbar Styles */
        .table-scroll-wrapper::-webkit-scrollbar {
            height: 6px;
        }

        .table-scroll-wrapper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .table-scroll-wrapper::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        .table-scroll-wrapper::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h4 class="mb-0 text-dark">Admin Dashboard</h4>
                </div>

                <!-- Admin Profile -->
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle text-dark" type="button" id="adminDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle fa-lg me-2"></i>
                        Admin
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i
                                    class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Sidebar -->
        <div class="sidebar">
            <nav class="nav flex-column">
                <a href="?view=dashboard"
                    class="nav-link <?php echo !isset($_GET['view']) || $_GET['view'] == 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="orders.php"
                    class="nav-link <?php echo isset($_GET['view']) && $_GET['view'] == 'orders' ? 'active' : ''; ?>">
                    <i class="fas fa-box"></i> Orders
                </a>
                <a href="products.php" class="nav-link">
                    <i class="fas fa-tags"></i> Products
                </a>
                <a href="account.php" class="nav-link">
                    <i class="fas fa-user"></i> Account
                </a>
                <a href="add_product.php" class="nav-link">
                    <i class="fas fa-plus-circle"></i> Add Product
                </a>
                <a href="help.php" class="nav-link">
                    <i class="fas fa-question-circle"></i> Help
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Dashboard View -->
            <div id="dashboardView"
                class="dashboard-view <?php echo !isset($_GET['view']) || $_GET['view'] == 'dashboard' ? 'active' : ''; ?>">
                <!-- Stats Cards -->
                <div class="dashboard-cards">
                    <div class="card">
                        <div class="card-stats text-primary">
                            <div>
                                <h5 class="card-title">Total Orders</h5>
                                <h3 class="mb-0 counter"><?php echo $total_records ?? 0; ?></h3>
                                <small class="text-white-50"><br></small>
                            </div>
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-stats text-success">
                            <div>
                                <h5 class="card-title">Completed Orders</h5>
                                <h3 class="mb-0 counter"><?php echo $completed_orders ?? 0; ?></h3>
                                <small class="text-white-50"><br></small>
                            </div>
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-stats text-warning">
                            <div>
                                <h5 class="card-title">Pending Orders</h5>
                                <h3 class="mb-0 counter"><?php echo $pending_orders ?? 0; ?></h3>
                                <small class="text-white-50"><br></small>
                            </div>
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-stats text-info">
                            <div>
                                <h5 class="card-title">Total Revenue</h5>
                                <h3 class="mb-0">$<span
                                        class="counter"><?php echo number_format($total_revenue ?? 0, 2); ?></span></h3>
                                <small class="text-white-50">+8.32% from last week</small>
                            </div>
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <!-- Table Container with Toggle Button -->
                <div class="table-section">
                    <button class="btn btn-primary toggle-table mb-3" type="button" data-bs-toggle="collapse"
                        data-bs-target="#ordersTable" aria-expanded="false" aria-controls="ordersTable">
                        <i class="fas fa-table me-2"></i>
                        <span class="button-text">Show Recent Orders</span>
                    </button>

                    <div class="collapse" id="ordersTable">
                        <div class="table-container">
                            <!-- Header Section -->
                            <div class="table-header-wrapper">
                                <h4 class="table-title">Recent Orders</h4>
                                <div class="table-actions">
                                    <select class="form-select form-select-sm">
                                        <option>All Orders</option>
                                        <option>Completed</option>
                                        <option>Pending</option>
                                    </select>
                                    <a href="?view=orders" class="btn btn-primary btn-sm">View All</a>
                                </div>
                            </div>

                            <!-- Table Wrapper -->
                            <div class="table-scroll-wrapper">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="col-id">Order ID</th>
                                            <th class="col-status">Status</th>
                                            <th class="col-customer">Customer</th>
                                            <th class="col-date">Date</th>
                                            <th class="col-amount">Amount</th>
                                            <th class="col-actions">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($order = $recent_orders->fetch_assoc()): ?>
                                            <tr class="align-middle">
                                                <td class="col-id">#<?php echo $order['order_id']; ?></td>
                                                <td class="col-status">
                                                    <span
                                                        class="status-badge <?php echo $order['order_status'] == 'completed' ? 'status-completed' : 'status-pending'; ?>">
                                                        <?php echo ucfirst($order['order_status']); ?>
                                                    </span>
                                                </td>
                                                <td class="col-customer">
                                                    <div class="customer-info">
                                                        <div class="avatar">
                                                            <i class="fas fa-user-circle text-secondary"></i>
                                                        </div>
                                                        <div class="customer-details">
                                                            <span class="customer-id">User
                                                                #<?php echo $order['user_id']; ?></span>
                                                            <span
                                                                class="customer-phone"><?php echo $order['user_phone']; ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-date">
                                                    <div class="date-info">
                                                        <span
                                                            class="date"><?php echo date('M d, Y', strtotime($order['order_date'])); ?></span>
                                                        <span
                                                            class="time"><?php echo date('h:i A', strtotime($order['order_date'])); ?></span>
                                                    </div>
                                                </td>
                                                <td class="col-amount">
                                                    <span
                                                        class="amount">$<?php echo number_format($order['order_cost'], 2); ?></span>
                                                </td>
                                                <td class="col-actions">
                                                    <div class="dropdown">
                                                        <button class="btn btn-link dropdown-toggle"
                                                            data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="fas fa-eye me-2"></i>View Details</a></li>
                                                            <li><a class="dropdown-item" href="#"><i
                                                                        class="fas fa-edit me-2"></i>Edit Order</a></li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item text-danger" href="#"><i
                                                                        class="fas fa-trash me-2"></i>Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders View -->
    <div id="ordersView"
        class="orders-view <?php echo isset($_GET['view']) && $_GET['view'] == 'orders' ? 'active' : ''; ?>">
        <!-- Orders content here -->
    </div>
    </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this order?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Handle view switching
            const urlParams = new URLSearchParams(window.location.search);
            const view = urlParams.get('view');

            const dashboardView = document.getElementById('dashboardView');
            const ordersView = document.getElementById('ordersView');

            if (view === 'orders') {
                dashboardView.classList.remove('active');
                ordersView.classList.add('active');
            } else {
                dashboardView.classList.add('active');
                ordersView.classList.remove('active');
            }

            // Counter animation
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseFloat(counter.innerText);
                const increment = target / 200;
                let current = 0;

                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.innerText = current.toFixed(2);
                        setTimeout(updateCounter, 1);
                    } else {
                        counter.innerText = target;
                    }
                };

                updateCounter();
            });

            // Auto dismiss alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150);
                }, 5000);
            });
        });

        // Delete confirmation
        function confirmDelete(orderId) {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const confirmBtn = document.getElementById('confirmDeleteBtn');

            confirmBtn.onclick = function () {
                window.location.href = `delete_order.php?order_id=${orderId}`;
            };

            modal.show();
        }
    </script>
</body>

</html>