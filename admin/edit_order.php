<?php include('header.php');?>
<?php include('../server/connection.php');?>
<?php
if(isset($_GET['order_id'])){
    $order_id=$_GET['order_id'];
    $stmt=$conn->prepare(" SELECT * FROM orders WHERE order_id=?");
    $stmt->bind_param('i',$order_id);
    $stmt->execute();
    $order = $stmt->get_result();
}else if(isset($_POST['edit_order'])){
    
    $payment_status=$_POST['payment_status'];
    $order_id=$_POST['order_id'];
    $stmt= $conn->prepare("UPDATE orders SET payment_status=? WHERE order_id=?");
    $stmt->bind_param('si',$payment_status,$order_id);

    if($stmt->execute()){
    header('location:index.php?order_updated=Order has been updated successfully.');
    }
    else{
    header('location:products.php?order_failed=Error occured , try again');

}
}
else{
    header('location:index.php');
    exit;
}

?>


<div class="container-fluid">
    <div class="row" style="min-height:1000px">
        <?php include('sidemenu.php');?>
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
      height: 80%;
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
            <h2>Edit order</h2>
            <div class="table-responsive">
                <div class="mx-auto container">
                    <form id="edit-order-form" method="POST" action="edit_order.php">
                        <?php foreach($order as $r){?>
                        <p style="color:red;"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
                        <div class="form-group my-3">
                            <label> OrderId</label>
                            <p class="my-4"><?php echo $r['order_id'];?></p>
                        </div>
                        <div class="form-group my-3">
                            <label> OrderPrice</label>
                            <p class="my-4"><?php echo $r['total_amount'];?></p>
                        </div>
                        <input type="hidden" name="order_id" value="<?php echo $r['order_id'];?>">
                        <div class="form-group my-3">
                            <label> Order Status</label>
                                <select class="form-select" required name="payment_status">
                                    <option value="not paid" <?php if( $r['payment_status']=='not paid'){echo "selected";}?> >Not paid</option>
                                    <option value="paid" <?php if( $r['payment_status']=='paid'){echo "selected";}?>>Paid</option>
                                    <option value="shipped" <?php if( $r['payment_status']=='shippped'){echo "selected";}?>>Shipped</option>
                                    <option value="delivered" <?php if( $r['payment_status']=='delivered'){echo "selected";}?>> Delivered</option>
                            </select>
                        </div>
                        <div class="form-group my-3">
                            <label>OrderDate</label>
                            <p class="my-4"><?php echo $r['order_date'];?></p>
                        </div>
                        
                        <div class="form-group my-3">
                            <input type="submit" class="btn btn-primary" name="edit_order" value="Submit">
                        </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
    </div>
</div>