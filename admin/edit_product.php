<?php include('header.php'); ?>
<?php include('../server/connection.php'); ?>

<?php
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $products = $stmt->get_result();
} elseif (isset($_POST['edit_btn'])) {
    $product_id = $_POST['product_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $offer = $_POST['offer'];
    $color = $_POST['color'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];


    // Image upload logic
    $target_dir = "assets/images/";

    // Check if new images are uploaded
    $image1 = $_FILES['product_image']['name'] ? $_FILES['product_image']['name'] : $_POST['existing_image1'];
    $image2 = $_FILES['product_image2']['name'] ? $_FILES['product_image2']['name'] : $_POST['existing_image2'];
    $image3 = $_FILES['product_image3']['name'] ? $_FILES['product_image3']['name'] : $_POST['existing_image3'];

    // Move uploaded images to the folder
    if ($_FILES['product_image']['name']) {
        move_uploaded_file($_FILES['product_image']['tmp_name'], $target_dir . $image1);
    }
    if ($_FILES['product_image2']['name']) {
        move_uploaded_file($_FILES['product_image2']['tmp_name'], $target_dir . $image2);
    }
    if ($_FILES['product_image3']['name']) {
        move_uploaded_file($_FILES['product_image3']['tmp_name'], $target_dir . $image3);
    }

    // Update product details
    $stmt = $conn->prepare("UPDATE products SET 
        product_name=?, product_description=?, product_price=?, product_special_offer=?, 
        product_color=?, product_category=?,product_subcategory=?, product_image=?, product_image2=?, product_image3=? 
        WHERE product_id=?");

    $stmt->bind_param('ssssssssssi', $title, $description, $price, $offer, $color, $category,$subcategory, $image1, $image2, $image3, $product_id);

    if ($stmt->execute()) {
        header('location:products.php?edit_success_message=Product has been updated successfully.');
    } else {
        header('location:products.php?edit_failure_message=Error occurred, try again');
    }
} else {
    header('location:products.php');
    exit;
}
?>

<div class="container-fluid">
    <div class="row" style="min-height:1000px;">
        <?php include('sidemenu.php'); ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pd-2 md-3">
                <h1 class="h2">Dashboard</h1>
            </div>

            <h2>Edit Product</h2>
            <div class="mx-auto container">
                <form id="edit-form" method="POST" action="edit_product.php" enctype="multipart/form-data">
                    <p style="color:red"><?php if (isset($_GET['error'])) { echo $_GET['error']; } ?></p>

                    <?php foreach ($products as $product) { ?>
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

                        <div class="form-group mt-2">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" value="<?php echo $product['product_name']; ?>" required>
                        </div>

                        <div class="form-group mt-2">
                            <label>Description</label>
                            <input type="text" class="form-control" name="description" value="<?php echo $product['product_description']; ?>" required>
                        </div>

                        <div class="form-group mt-2">
                            <label>Price</label>
                            <input type="text" class="form-control" name="price" value="<?php echo $product['product_price']; ?>" required>
                        </div>
                        <div class="form-group mt-2">
                          <label>Category</label>
                          <select name="category" class="form-select" required>
                              <?php
                              // Fetch categories from the database
                              $category_query = "SELECT * FROM categories";
                              $category_result = $conn->query($category_query);

                              while ($row = $category_result->fetch_assoc()) {
                                  $selected = ($product['product_category'] == $row['category_name']) ? "selected" : "";
                                  echo "<option value='{$row['category_name']}' $selected>{$row['category_name']}</option>";
                              }
                              ?>
                          </select>
                      </div>

                        <div class="form-group mt-2">
                          <label>SubCategory</label>
                          <select name="subcategory" class="form-select" required>
                              <?php
                              // Fetch categories from the database
                              $subcategory_query = "SELECT * FROM subcategories";
                              $subcategory_result = $conn->query($subcategory_query);

                              while ($row = $subcategory_result->fetch_assoc()) {
                                  $selected = ($product['product_subcategory'] == $row['subcategory_name']) ? "selected" : "";
                                  echo "<option value='{$row['subcategory_name']}' $selected>{$row['subcategory_name']}</option>";
                              }
                              ?>
                          </select>
                      </div>

                        <div class="form-group mt-2">
                            <label>Color</label>
                            <input type="text" class="form-control" name="color" value="<?php echo $product['product_color']; ?>" required>
                        </div>

                        <div class="form-group mt-2">
                            <label>Special Offer</label>
                            <input type="text" class="form-control" name="offer" value="<?php echo $product['product_special_offer']; ?>" required>
                        </div>

                        <!-- Product Image Upload -->
                        <div class="form-group mt-2">
                            <label>Product Image 1</label>
                            <input type="file" class="form-control" name="product_image">
                            <input type="hidden" name="existing_image1" value="<?php echo $product['product_image']; ?>">
                            <img src="../assets/images/<?php echo $product['product_image']; ?>" width="100">
                        </div>

                        <div class="form-group mt-2">
                            <label>Product Image 2</label>
                            <input type="file" class="form-control" name="product_image2">
                            <input type="hidden" name="existing_image2" value="<?php echo $product['product_image2']; ?>">
                            <img src="../assets/images/<?php echo $product['product_image2']; ?>" width="100">
                        </div>

                        <div class="form-group mt-2">
                            <label>Product Image 3</label>
                            <input type="file" class="form-control" name="product_image3">
                            <input type="hidden" name="existing_image3" value="<?php echo $product['product_image3']; ?>">
                            <img src="../assets/images/<?php echo $product['product_image3']; ?>" width="100">
                        </div>

                        <div class="form-group mt-2">
                            <input type="submit" class="btn btn-primary" name="edit_btn" value="Edit">
                        </div>
                    <?php } ?>
                </form>
            </div>
        </main>
    </div>
</div>
