<?php
include('../server/connection.php');

// Fetch all categories
$categories_query = "SELECT * FROM categories ORDER BY category_name";
$categories_result = $conn->query($categories_query);

// Fetch all items
$items_query = "SELECT * FROM item ORDER BY item_name";
$items_result = $conn->query($items_query);

// Handle new category submission
if(isset($_POST['add_category'])) {
    $category_name = trim($_POST['new_category_name']);

    if (!empty($category_name)) {
        // Check if category already exists
        $check_query = $conn->prepare("SELECT category_id FROM categories WHERE category_name = ?");
        $check_query->bind_param("s", $category_name);
        $check_query->execute();
        $check_query->store_result();

        if ($check_query->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
            $stmt->bind_param("s", $category_name);
            $stmt->execute();
        } else {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?error=Category already exists');
            exit();
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Handle new subcategory submission
if(isset($_POST['add_subcategory'])) {
    $subcategory_name = trim($_POST['new_subcategory_name']);
    $category_id = $_POST['category_id'];

    if (!empty($subcategory_name)) {
        // Check if subcategory already exists under the same category
        $check_query = $conn->prepare("SELECT subcategory_id FROM subcategories WHERE subcategory_name = ? AND category_id = ?");
        $check_query->bind_param("si", $subcategory_name, $category_id);
        $check_query->execute();
        $check_query->store_result();

        if ($check_query->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO subcategories (subcategory_name, category_id) VALUES (?, ?)");
            $stmt->bind_param("si", $subcategory_name, $category_id);
            $stmt->execute();
        } else {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?error=Subcategory already exists');
            exit();
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Handle new item submission
if(isset($_POST['add_item'])) {
    $item_name = trim($_POST['new_item_name']);

    if (!empty($item_name)) {
        $check_query = $conn->prepare("SELECT id FROM item WHERE item_name = ?");
        $check_query->bind_param("s", $item_name);
        $check_query->execute();
        $check_query->store_result();

        if ($check_query->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO item (item_name) VALUES (?)");
            $stmt->bind_param("s", $item_name);
            $stmt->execute();
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Handle product submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_product'])) {
    try {
        $product_name = trim($_POST['product_name']);
        $product_description = trim($_POST['product_description']);
        $product_price = $_POST['product_price'];
        $product_special_offer = $_POST['product_special_offer'];
        $category_id = $_POST['category_id'];
        $product_color = trim($_POST['product_color']);
        $subcategory_id = $_POST['subcategory_id'];
        $item_id = $_POST['item_id'];



        // Validate special offer percentage
        if ($product_special_offer < 0 || $product_special_offer > 100) {
            throw new Exception("Special offer percentage must be between 0 and 100.");
        }

        // Handle multiple image uploads
        $target_dir = "../assets/images/";
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        $images = ['product_image', 'product_image2', 'product_image3', 'product_image4'];
        $uploaded_images = [];

        foreach ($images as $index => $img) {
            if (!empty($_FILES[$img]['name'])) {
                $file_ext = strtolower(pathinfo($_FILES[$img]['name'], PATHINFO_EXTENSION));

                if (!in_array($file_ext, $allowed_types)) {
                    throw new Exception("Invalid image type. Only JPG, JPEG, PNG, and GIF are allowed.");
                }

                $image_name = time() . "_{$index}_" . $_FILES[$img]['name'];
                move_uploaded_file($_FILES[$img]['tmp_name'], $target_dir . $image_name);
                $uploaded_images[$img] = $image_name;
            } else {
                $uploaded_images[$img] = NULL; // If image is optional
            }
        }


        if (empty($category_id)) {
            throw new Exception("Category ID is required.");
        }
        

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO products (
            product_name,
            product_description,
            product_price,
            product_image,
            product_image2,
            product_image3,
            product_image4,
            product_special_offer,
            product_color,
    
            category_id,
            subcategory_id,
            item_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");

        $stmt->bind_param(
            "ssdssssisiii",
            $product_name,
            $product_description,
            $product_price,
            $uploaded_images['product_image'],
            $uploaded_images['product_image2'],
            $uploaded_images['product_image3'],
            $uploaded_images['product_image4'],
            $product_special_offer,
            $product_color,
          
            $category_id,
            $subcategory_id,
            $item_id
        );

        if ($stmt->execute()) {
            header('location: products.php?message=Product created successfully');
        } else {
            throw new Exception("Failed to create product");
        }
    } catch (Exception $e) {
        header('location: add_product.php?error=' . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 0.75rem;
        }

        .form-control:focus {
            border-color: #ff0066;
            box-shadow: 0 0 0 0.2rem rgba(255,0,102,0.25);
        }

        .btn-primary {
            background-color: #ff0066;
            border-color: #ff0066;
            padding: 0.75rem 2rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #e5005c;
            border-color: #e5005c;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            width: 50%;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h2 class="text-center mb-4">Add New Product</h2>
                    
                    <!-- Error Messages -->
                    <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_GET['error']; ?></div>
                    <?php endif; ?>

                    <!-- Category Management Buttons -->
                    <div class="mb-4">
                        <button type="button" class="btn btn-secondary" onclick="showModal('categoryModal')">
                            Add New Category
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="showModal('subcategoryModal')">
                            Add New Subcategory
                        </button>

                        <button type="button" class="btn btn-secondary" onclick="showModal('itemModal')">Add Item</button>

  
                    </div>

                    <!-- Product Form -->
                    <form id="create-form" enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="product_name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="product_description" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Price</label>
                            <input type="number" class="form-control" name="product_price" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Special Offer (%)</label>
                            <input type="number" class="form-control" name="product_special_offer" min="0" max="100">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select" required>
                                <?php
                                $categories_result = $conn->query("SELECT * FROM categories ORDER BY category_name");
                                while ($category = $categories_result->fetch_assoc()) {
                                    echo "<option value='" . $category['category_id'] . "'>" 
                                        . htmlspecialchars($category['category_name']) . "</option>";
                                }
                                ?>
                            </select>

                        </div>

                        <div class="form-group">
                            <label class="form-label">Item </label>
                            <select name="item_id" class="form-select" required>
                                <?php
                                $items_result = $conn->query("SELECT * FROM item ORDER BY item_name");
                                while ($item = $items_result->fetch_assoc()) {
                                    echo "<option value='" . $item['item_id'] . "'>" 
                                        . htmlspecialchars($item['item_name']) . "</option>";
                                }
                                ?>
                            </select>

                        </div>


                        <div class="form-group">
                            <label class="form-label">Color</label>
                            <input type="text" class="form-control" name="product_color" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Type</label>
                            <input type="text" class="form-control" name="type" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Subcategory</label>
                            <select name="subcategory_id" class="form-select" required>
                                <?php
                                $subcategories_query = "SELECT * FROM subcategories ORDER BY subcategory_name";
                                $subcategories_result = $conn->query($subcategories_query);
                                while ($subcategory = $subcategories_result->fetch_assoc()) {
                                    echo "<option value='" . $subcategory['subcategory_id'] . "'>" 
                                         . htmlspecialchars($subcategory['subcategory_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Product Images</label>
                            <div class="mb-3">
                                <label class="form-label">Main Image</label>
                                <input type="file" class="form-control" name="product_image" accept="image/*" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Additional Image 1</label>
                                <input type="file" class="form-control" name="product_image2" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Additional Image 2</label>
                                <input type="file" class="form-control" name="product_image3" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Additional Image 3</label>
                                <input type="file" class="form-control" name="product_image4" accept="image/*">
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="create_product" class="btn btn-primary">
                                Add Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div id="categoryModal" class="modal">
        <div class="modal-content">
            <h3>Add New Category</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="new_category_name" class="form-control" required>
                </div>
                <button type="submit" name="add_category" class="btn btn-primary mt-3">Add Category</button>
                <button type="button" class="btn btn-secondary mt-3" onclick="hideModal('categoryModal')">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Subcategory Modal -->
    <div id="subcategoryModal" class="modal">
        <div class="modal-content">
            <h3>Add New Subcategory</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Parent Category</label>
                    <select name="category_id" class="form-select" required>
                        <?php
                        $categories_result = $conn->query($categories_query);
                        while ($category = $categories_result->fetch_assoc()) {
                            echo "<option value='" . $category['category_id'] . "'>" 
                                 . htmlspecialchars($category['category_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label>Subcategory Name</label>
                    <input type="text" name="new_subcategory_name" class="form-control" required>
                </div>
                <button type="submit" name="add_subcategory" class="btn btn-primary mt-3">Add Subcategory</button>
                <button type="button" class="btn btn-secondary mt-3" onclick="hideModal('subcategoryModal')">Cancel</button>
            </form>
        </div>
    </div>


    <!-- Item Modal -->
    <div id="itemModal" class="modal">
        <div class="modal-content">
            <h3>Add New Item</h3>
            <form method="POST">
            <div class="form-group">
                    <label>Parent Category</label>
                    <select name="category_id" class="form-select" required>
                        <?php
                        $categories_result = $conn->query($categories_query);
                        while ($category = $categories_result->fetch_assoc()) {
                            echo "<option value='" . $category['category_id'] . "'>" 
                                 . htmlspecialchars($category['category_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Parent sub Category</label>
                    <select name="subcategory_id" class="form-select" required>
                        <?php
                        $subcategories_result = $conn->query($subcategories_query);
                        while ($subcategory = $subcategories_result->fetch_assoc()) {
                            echo "<option value='" . $subcategory['subcategory_id'] . "'>" 
                                 . htmlspecialchars($subcategory['subcategory_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Item Name</label>
                    <input type="text" name="new_item_name" class="form-control" required>
                </div>
                <button type="submit" name="add_item" class="btn btn-primary mt-3">Add Item</button>
                <button type="button" class="btn btn-secondary mt-3" onclick="hideModal('itemModal')">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function showModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function hideModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html> 