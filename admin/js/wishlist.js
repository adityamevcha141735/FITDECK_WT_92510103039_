$(document).ready(function () {
    // Add to Wishlist Button Click Event
    $(document).on("click", ".add-to-wishlist", function (e) {
      e.preventDefault();
  
      const button = $(this);
      const productId = button.data("product-id");
      const productName = button.data("product-name");
      const productPrice = button.data("product-price");
      const productImage = button.data("product-image");
  
      // Send AJAX request
      $.ajax({
        url: "add_to_wishlist.php",
        type: "POST",
        data: {
          product_id: productId,
          product_name: productName,
          product_price: productPrice,
          product_image: productImage
        },
        success: function (response) {
          try {
            const data = JSON.parse(response);
            if (data.status === "success") {
              button.html('<i class="fas fa-heart"></i> Added to Wishlist').prop("disabled", true);
              alert("Product added to wishlist!");
            } else if (data.status === "exists") {
              button.html('<i class="fas fa-heart"></i> Already in Wishlist').prop("disabled", true);
              alert("This product is already in your wishlist.");
            } else if (data.status === "error" && data.message === "Please login first") {
              window.location.href = "login.php";
            } else {
              alert(data.message);
            }
          } catch (error) {
            console.error("Error parsing JSON response:", error);
            alert("An error occurred. Please check the console for details.");
          }
        },
        error: function (xhr, status, error) {
          console.error("AJAX error:", xhr.responseText);
          alert("An error occurred while adding the product to the wishlist.");
        }
      });
    });
  });
  