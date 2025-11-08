<?php
session_start();

include('../server/connection.php');

?>
<?php 
    if(!isset($_SESSION['admin_logged_in'])){
        header('location:index.php');
        exit();
    }

    if(isset($_GET['product_id'])){
        $product_id=$_GET['product_id'];
        $stmt=$conn->prepare("DELETE FROM products WHERE  product_id=?");
        $stmt->bind_param('i',$product_id);
        
        
        if($stmt->execute()){
            header('location:products.php?delete_success=Product has been deleted successfully');
        }
        else{
            header('location:products.php?delete_failure=COuld not delete products');
            
        }
    }

?>