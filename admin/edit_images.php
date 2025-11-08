<?php include('header.php');?>
<?php include('../server/connection.php');?>
<?php 
if(isset($_GET['product_id'])){
    $product_id=$_GET['product_id'];
    $product_name=$_GET['product_name'];
}else{
    header('location:products.php');
}

?>



<div class="container-fluid">
    <div class="row" style="min-height:1000px;">
        <?php include('sidemenu.php'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

        
    <style>
         body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
    }

    /* Container for sidebar and content */
    .container {
      display: flex;
      height: 100vh; /* Full viewport height */
    }

    /* Sidebar styling */
    .sidebar {
      width: 200px;
      background-color: #f8f9fa;
      padding-top: 20px;
      border-right: 1px solid #ccc;
      display: flex;
      flex-direction: column;
    }

    .sidebar a {
      display: block;
      padding: 10px 20px;
      color: #000;
      text-decoration: none;
    }

    .sidebar a:hover {
      background-color: #ddd;
    }

    /* Content area styling */
    .content {
      flex: 1; /* Take up the remaining horizontal space */
      padding: 20px;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      justify-content: center; /* Vertically center the form */
      align-items: center; /* Horizontally center the form */
    }

    /* Form styling */
    form {
      max-width: 700px;
      width: 100%;
      height: 70%;
      background: #fff;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    form h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    form input,
    form select,
    form button,
    form textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    form button {
      background-color: #007bff;
      color: #fff;
      border: none;
      cursor: pointer;
      padding: 10px 15px;
    }

    form button:hover {
      background-color: #0056b3;
    }
    </style>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pd-2 md-3">
                <h1 class="h2"> Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">

                    </div>
                </div>
            </div>
<br>
            <h2>Update product images</h2>
            <div class="table-responsive">


            <div class="mx-auto container">
            <form id="create-form" enctype="multipart/form-data" method="POST" action="update_images.php">
              <p style="color:red" ><?php if(isset($_GET['error'])){ echo $_GET['error'];}?></p>
                <div class="form-group mt-2">
                    <input type="hidden" name="product_id" value="<?php echo $product_id;?>">
                    <input type="hidden" name="product_name" value="<?php echo $product_name;?>">

                  
          
                <div class="form-group mt-2">
                    <label>Image 1</label>
                    <input type="file" class="form-control"  id="image1"name="image1" placeholder="Image 1" required>
                </div>
                <div class="form-group mt-2">
                    <label>Image 2</label>
                    <input type="file" class="form-control" id="image2"name="image2" placeholder="Image 2" required>
                </div>
                <div class="form-group mt-2">
                    <label>Image 3</label>
                    <input type="file" class="form-control"  id="image3"name="image3" placeholder="Image 3" required>
                </div>
                <div class="form-group mt-2">
                    <label>Image 4</label>
                    <input type="file" class="form-control" id="image4"name="image4" placeholder="Image 4" required>
                </div>
              

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="update_images" value="Update">
                </div>
             
            </form>
        </div>
            </div>
        </main>


    </div>
</div>