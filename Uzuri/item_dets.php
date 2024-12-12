<?php
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Access session variables
$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Uzuri Africa - Purchase Page</title>
  <style>

  * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
    body {
        font-family: Arial, sans-serif;
        background-color: #EDE6CF; /* Beige background */
        margin: 0;
        padding: 0;
        color: #333;
    }

    /* Header Styling */
    header {
        background-color: #f52a23; /* Red header */
        color: white;
        padding: 20px 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 10;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        flex-wrap: wrap; /* Ensures responsiveness on smaller screens */
    }

   .logo img {
        width: 80px; /* Set logo size */
        height: auto;
        display: block; /* Ensures no extra inline spacing */
        margin: auto 0; /* Centers the logo vertically */
    }

    header h1 {
        font-size: 2.5rem;
        flex: 1;
        text-align: center;
        margin: 0;
        color: white;
        line-height: 1.2;
    }

    nav ul {
        list-style: none;
        display: flex;
        gap: 13px;
        margin: 0;
        padding: 0;
    }

    nav ul li a {
        text-decoration: none;
        font-weight: bold;
        color: white;
        transition: color 0.3s ease;
    }

    nav ul li a:hover {
        color: #f52a23;
        }

    /* Main Content Styling */
#section1 {
    padding: 20px;
    text-align: center;
}

/* Filters Section Styling */
.filters {
    margin-bottom: 20px;
    background-color: #068b50; /* Green filters background */
    padding: 15px;
    border-radius: 10px;
    color: white;
    font-weight: bold;
}

.filters select,
.filters button {
    padding: 10px;
    margin: 5px;
    border: 1px solid #ccc; /* Light gray border */
    border-radius: 5px;
    font-size: 1em;
}

#item_dets {
  width: 1060px;
  margin-left: 20px;
}

#den_Cat{
  font-size: 1.5em;
  color:#068b50 ;
}

#den_Type{
  font-size: 1.5em;
  color:#068b50 ;
}

#den_Price{
  font-size: 1.5em;
  color:#068b50 ;
}
/* Main Section */
main {
  display: flex;
  flex-direction: column;
  gap: 20px;
  float: right;
  margin-top: 40px;
  width: 800px;
  height: 650px;
  margin-right: 20px;
}

.purchase-option {
  background: #ccc;
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.purchase-option h2 {
  font-size: 1.5em;
  color: #0056b3;
  margin-bottom: 10px;
}

.purchase-option p {
  color: #666;
  margin-bottom: 15px;
}

.purchase-option .timer {
  font-size: 0.9em;
  color: #ff4500;
  margin-bottom: 10px;
}

.purchase-option input,
.purchase-option textarea {
  width: 80%;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.purchase-option button {
  background: #0056b3;
  color: #fff;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  border-radius: 5px;
  font-size: 1em;
}

.purchase-option button:hover {
  background: #003f8a;
}

.purchase-option .buy-now {
  background: #28a745;
}

.purchase-option .buy-now:hover {
  background: #218838;
}

.purchase-option .price {
  font-size: 1.2em;
  font-weight: bold;
  margin-bottom: 10px;
}

.message-box {
  margin-top: 10px;
  font-size: 0.9em;
  color: #555;
}

/* Footer */
footer {
  text-align: center;
  margin-top: 20px;
  color: #068b50;
}

footer a {
  color: #0056b3;
  text-decoration: none;
}

footer a:hover {
  text-decoration: underline;
}

/* Media Queries for Responsiveness */
@media (max-width: 768px) {
  main {
    flex-direction: column;
  }

  .purchase-option input,
  .purchase-option textarea {
  
  }

  .purchase-option {
    padding: 15px;
  }

  header h1 {
    font-size: 1.8em;
  }

  header p {
    font-size: 1em;
  }

  table {
    flex-direction: column;
  }

  #item_dets {
  flex-direction: column;
 
}

#den{
  flex-direction: column;
}


}

@media (max-width: 480px) {
  header h1 {
    font-size: 1.5em;
  }

  header p {
    font-size: 0.9em;
  }

  .purchase-option button {
    font-size: 0.9em;
    padding: 8px 15px;
  }

  .purchase-option {
    padding: 10px;
  }

  footer {
    font-size: 0.8em;
  }
}





  </style>        
</head>
<body>
     <header>
        <div class="logo">
            <img src="images/UZURI AFRICA2.jpg" alt="Uzuri Africa Logo">
        </div>
        <nav>
            <ul>
                <li><a href="buyer_dashboard.php">Home</a></li>
                <li><a href="notifications.html">Notifications</a></li>
                <li><a href="cart.html">Cart</a></li>
                <li><a href="index.html"><?php echo htmlspecialchars($email); ?><p>(logout)</a></li>
            </ul>
        </nav>
        
    </header>

    <div class="items-grid" id="itemsGrid">
            <!-- Items will be dynamically loaded here -->
        </div>
    </div>

  <table>
    <tr>
      <td>
        <form id="item_dets">
            <?php
            
            $conn = new mysqli("localhost", "root", "", "uzuri");

            // Check the database connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get the item ID from the URL
            $item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

            // Fetch the item details from the database
            $stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $item = $result->fetch_assoc();
                // Display item details
                echo "<table style = 'width: 500px; float: left; margin-left:40px'>";
                echo "<h1>" . htmlspecialchars($item['name']) . "</h1>";
                echo "<img src='./" . htmlspecialchars($item['image_path']) . "' alt='" . htmlspecialchars($item['name']) . "'>";
                echo "<p><h2 id='den_Cat'>Category: " . htmlspecialchars($item['category']) . "</p></h2>";
                echo "<p><h2 id='den_Type'>Type: " . htmlspecialchars($item['type']) . "</p></h2>";
                echo "<p><h2 id='den_Price'>Price: €" . htmlspecialchars($item['price']) . "</p></h2>";
                echo "</table>";
            } else {
                echo "<p>Item not found.</p>";
            }

            $conn->close();
            ?>

        </form>
      </td>
      <td>
        <main>
      <!-- Best Offer Section -->
      <section class="purchase-option">
        <div id="bidOffer">
          <h2>Best Offer</h2>
          <p>Bid on items and let us handle the bidding for you!</p>
          <div class="timer">Time Left: <span id="best-offer-timer"></span></div>
          <input type="number" id="bid-amount" placeholder="Enter your max bid (€)">
          <button id="place-bid">Place Bid</button>
        </div>
      </section>

      <!-- Seller-Customer Transaction Section -->
      <section class="purchase-option">
        <div id="negotiate">
          <h2>Seller-Customer Transaction</h2>
          <p>Negotiate directly with the seller for the best price.</p>
          <textarea id="offer" placeholder="Enter your offer (€)" rows="3"></textarea>
          <button id="send-offer">Send Offer</button>
          <div class="message-box"></div>

          <!-- Hidden buttons for accepting/rejecting counteroffer -->
          <div id="accept-reject-buttons" style="display: none;">
            <button id="accept-counteroffer">Accept Counteroffer</button>
            <button id="reject-counteroffer">Reject Counteroffer</button>
          </div>

          <!-- Hidden payment button -->
           <button id="add2cart" style="display: none;">Add to Cart</button>
        </div>

      </section>

      <!-- Buy It Now Section -->
      <section class="purchase-option">
        <div id="instantBuy">

          <h2>Buy It Now</h2>
          <p>Purchase instantly at the listed price!</p>
          <div class="price">Price: €<?php echo htmlspecialchars($item['price']); ?></div>
          <form action="cart.php" method="POST" id="buy-now-form">
            <!-- Include hidden inputs to pass item details -->
            <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['id']); ?>">
            <input type="hidden" name="item_name" value="<?php echo htmlspecialchars($item['name']); ?>">
            <input type="hidden" name="item_price" value="<?php echo htmlspecialchars($item['price']); ?>">
            <button type="submit" class="buy-now">Buy Now</button>
          </form>
         </div> 
      </section>
    </main>
      </td>
    </tr>
    
  </table>
  <footer>
    <p>© 2024 Uzuri Africa. <a href="#">Contact Us</a></p>
  </footer>
</body>
</html>



<script>

//TIMER GENERATION

function startCountdown(deadline, elementId) {
  const timerElement = document.getElementById(elementId);

  function updateTimer() {
    const now = new Date().getTime();
    const timeLeft = new Date(deadline).getTime() - now;

    if (timeLeft < 0) {
      timerElement.textContent = "Auction ended";
      clearInterval(interval);
      return;
    }

    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

    timerElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
  }

  const interval = setInterval(updateTimer, 1000);
  updateTimer();
}

// Initialize the timer
document.addEventListener("DOMContentLoaded", () => {
  startCountdown("2024-12-15T16:59:00", "best-offer-timer");
});


//INPUT VALIDATION
document.querySelector("button#place-bid").addEventListener("click", () => {
  const bid = document.querySelector("input#bid-amount").value;

  if (isNaN(bid) || bid <= 0) {
    alert("Please enter a valid bid amount!");
  } else {
    alert(`Your bid of €${bid} has been placed.`);
  }
});


//CHOOSE WHICH PURCHASE OPTION TO DISPLAY

document.addEventListener("DOMContentLoaded", () => {

  //Extract item details

  //const itemType = document.getElementById("den_Type").textContent.trim().toLowerCase();

  const itemType = document.getElementById("den_Type").textContent.replace("Type: ", "").trim();

  // Reference the sections

  const bestOfferSection = document.getElementById("bidOffer");
  const sellerCustomerSection = document.getElementById("negotiate");
  const buyNowSection = document.getElementById("instantBuy");

  //Toggle visibility based on the type
  if (itemType === "negotiation"){
      bestOfferSection.style.display = "none"; //hide best option
      buyNowSection.style.display = "none"; //hide buy now
      sellerCustomerSection.style.display = "block";
  } else if (itemType === "buy-now"){
      bestOfferSection.style.display = "none"; //hide best option
      buyNowSection.style.display = "block"; //hide buy now
      sellerCustomerSection.style.display = "none";
  
   } else if (itemType === "best-offer"){
      bestOfferSection.style.display = "block"; //hide best option
      buyNowSection.style.display = "none"; //hide buy now
      sellerCustomerSection.style.display = "none";

  }

});

//PLACING BID

document.querySelector("button#place-bid").addEventListener("click", () => {
  const bidAmount = document.querySelector("input#bid-amount").value;
  const buyerId = <?php echo json_encode($user_id); ?>;
  const productId = <?php echo json_encode($item_id); ?>;

  // Validate input
  if (isNaN(bidAmount) || bidAmount <= 0) {
    alert("Please enter a valid bid amount!");
    return;
  }

  // Send the bid to the PHP backend
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "placebid.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      // Handle the response from the PHP backend
      alert(xhr.responseText);  // Display success or error message from PHP
    }
  };

  // Send the POST request with the necessary data
  xhr.send(`buyer_id=${buyerId}&product_id=${productId}&bid_amount=${bidAmount}`);
});


//REAL TIME SELLER-BUYER NEGOTIATION

let originalOffer = 0;

document.querySelector("button#send-offer").addEventListener("click", () => {
  const offerInput = document.querySelector("textarea").value;
  const messageBox = document.querySelector(".message-box");
  let offerValue = parseFloat(offerInput);

  // Validate input
  if (isNaN(offerValue) || offerValue <= 0) {
    alert("Please enter a valid offer.");
    return;
  }

  // Set the original offer only once
  if (originalOffer === 0) {
    originalOffer = offerValue;
  }

  const percentIncrease = 0.7;
  let counteroffer = offerValue + (offerValue * percentIncrease);

  messageBox.innerHTML = `<p>Your Offer: €${offerValue.toFixed(2)}</p>`;

  // Send the offer to PHP using AJAX
  const buyerId = 1; // Replace with actual buyer ID
  const sellerId = 2; // Replace with actual seller ID

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "offersave.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      console.log(xhr.responseText); // Handle response if needed
    }
  };
  xhr.send(`buyer_id=${buyerId}&seller_id=${sellerId}&original_offer=${offerValue}&counter_offer=${counteroffer}`);

  // Check if the counteroffer meets the seller's acceptance threshold
  const sellerAcceptanceThreshold = 1.2 * originalOffer; // 20% above the original offer

  if (offerValue >= sellerAcceptanceThreshold) {
    messageBox.innerHTML += `<p>Seller accepts your offer: €${offerValue.toFixed(2)}.</p>`;
    showAddToCart(offerValue);
  } else {
    messageBox.innerHTML += `<p>Seller Counteroffer: €${counteroffer.toFixed(2)}</p>`;
    messageBox.innerHTML += `<p>Seller did not accept your offer. Accept counteroffer or make a new offer.</p>`;

    document.getElementById("accept-reject-buttons").style.display = "block";

    //accepting the counteroffer

    document.getElementById("accept-counteroffer").addEventListener('click', () => {
      messageBox.innerHTML += `<p>You have accepted the seller's counteroffer: €${counteroffer.toFixed(2)}.</p>`;
      document.getElementById("accept-reject-buttons").style.display = "none"; // Hide Accept/Reject buttons
      showAddToCart(counteroffer);


      // Update offer status to 'accepted'
      const xhr2 = new XMLHttpRequest();
      xhr2.open("POST", "update_offer_status.php", true);
      xhr2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr2.send(`offer_id=${offerId}&status=accepted`);
    });
    

    //handle the reject button
    document.getElementById("reject-counteroffer").addEventListener('click', () => {
      messageBox.innerHTML += `<p>You rejected the seller's counteroffer. You can make a new offer.</p>`;
      document.querySelector("textarea").value = '';
      document.getElementById("accept-reject-buttons").style.display = "none"; // Hide the buttons after rejection

      // Update offer status to 'rejected'
      const xhr2 = new XMLHttpRequest();
      xhr2.open("POST", "update_offer_status.php", true);
      xhr2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr2.send(`offer_id=${offerId}&status=rejected`);
    });
  }

  document.querySelector("textarea").value = '';  // Clear input for new offer
});


// Function to handle adding to cart
function showAddToCart(agreedPrice) {
  const messageBox = document.querySelector(".message-box");

  // Show the "Add to Cart" button
  const addToCartButton = document.getElementById("add2cart");
  addToCartButton.style.display = "block";

  // Attach event listener to the "Add to Cart" button
  addToCartButton.onclick = () => {
    // Simulate adding the agreed price to the cart
    messageBox.innerHTML += `<p>Adding item to cart at the agreed price of €${agreedPrice.toFixed(2)}...</p>`;

    // Simulate a delay for cart update
    setTimeout(() => {
      messageBox.innerHTML += `<p>Item successfully added to your cart!</p>`;
    }, 2000);

    // Hide the button after adding to the cart
    addToCartButton.style.display = "none";
  };
}


// Buy Now section

/*document.querySelector("button.buy-now").addEventListener("click", () => {
  const messageBox = document.querySelector(".message-box");

  // Assuming you have an item ID and price
  const itemId = 1;  // Replace with the actual item ID
  const price = 450;  // Price for the item
  const quantity = 1;  // Quantity (default is 1)

  // Send AJAX request to add the item to the cart
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "addtocart.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      const response = JSON.parse(xhr.responseText);
      if (response.success) {
        messageBox.innerHTML += `<p>Item has been added to your cart at €${price}!</p>`;
      } else {
        messageBox.innerHTML += `<p>Failed to add item to cart: ${response.message}</p>`;
      }
    }
  };
  xhr.send(`item_id=${itemId}&price=${price}&quantity=${quantity}`);
});*/


//using json
/*document.querySelector("button.buy-now").addEventListener("click", (e) => {
  e.preventDefault(); // Prevent the default form submission

  const itemDetails = {
    item_id: document.querySelector("input[name='item_id']").value,
    item_name: document.querySelector("input[name='item_name']").value,
    item_price: document.querySelector("input[name='item_price']").value,
  };

  // Send AJAX request
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "addtocart.php", true);
  xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      alert("Item added to cart successfully!");
    }
  };
  xhr.send(JSON.stringify(itemDetails));
});*/


    document.addEventListener("DOMContentLoaded", () => {
        document.querySelector("button.buy-now").addEventListener("click", (e) => {
          e.preventDefault(); // Prevent form submission

                const itemDetails = {
                    item_id: document.querySelector("input[name='item_id']").value,
                    item_name: document.querySelector("input[name='item_name']").value,
                    item_price: document.querySelector("input[name='item_price']").value,
                    user_id: document.querySelector("input[name='user_id']").value,
                };

                // Send AJAX request
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "addtocart.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message);
                    }
                };

                const params = new URLSearchParams(itemDetails).toString();
                xhr.send(params);
            });
        });



</script>