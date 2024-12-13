<?php
session_start();

// Check if the buyer is logged in
if (!isset($_SESSION['buyer_id'])) {
    die("ERROR: Buyer is not logged in.");
}

// Get the buyer ID from the session
$buyer_id = $_SESSION['buyer_id'];

// Access session variables
$user_id = $_SESSION['user_id'];

$email = isset($_SESSION['email']) ? $_SESSION['email'] : "Unknown";

// Database connection details
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "uzuri"; 

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("ERROR: Database connection failed: " . $conn->connect_error);
}

// Fetch cart details
$sql = "SELECT 
            cart.id AS cart_id, 
            cart.quantity, 
            cart.price, 
            items.name AS item_name, 
            items.image_path 
        FROM cart 
        JOIN items ON cart.item_id = items.id 
        WHERE cart.buyer_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("ERROR: Failed to prepare statement.");
}

$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Header Styling */
    * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #EDE6CF;
            color: #333;
            justify-content: center;
        }
        header {
            position: sticky;
            top: 0;
            background-color: #068b50;
            color: white;
            padding: 10px 20px;
            z-index: 10;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .logo img {
            width: 100px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        nav ul li a {
            text-decoration: none;
            font-weight: bold;
            color: white;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #f52a23;
        }

        .cart-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
        }

        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 20px;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-actions {
            display: flex;
            align-items: center;
        }

        .quantity-btn {
            padding: 5px 10px;
            margin: 0 5px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }

        .quantity-btn:hover {
            background: #0056b3;
        }

        .remove-btn {
            padding: 5px 10px;
            background: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }

        .remove-btn:hover {
            background: #b02a37;
        }

        .cart-total {
            margin-top: 20px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
     <header>
        <div class="logo">
            <img src="images/UZURI AFRICA2.jpg" alt="Uzuri Africa Logo">
        </div>
        <h2>My Cart</h2>
        <nav>
            <ul>
                <li><a href="buyer_dashboard.php">Home</a></li>
                <li><a href="buyer_dashboard.php">Continue Shopping</a></li>
                <li><a href="notifications.php">Notifications</a></li>
                <li><a href="view_cart.php">Cart</a></li>
                <li><a href="index.html"><?php echo htmlspecialchars($email); ?><p>(logout)</a></li>
            </ul>
        </nav>
        
    </header>

    <div class="cart-container">
        

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): 
                $total += $row['price'] * $row['quantity'];
            ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Item Image">
                    <div class="cart-item-details">
                        <h4><?php echo htmlspecialchars($row['item_name']); ?></h4>
                        <p>Price: $<?php echo number_format($row['price'], 2); ?></p>
                        <p>Quantity: <?php echo $row['quantity']; ?></p>
                    </div>
                    <div class="cart-item-actions">
                        <button class="quantity-btn" onclick="updateCart(<?php echo $row['cart_id']; ?>, 'increase')">+</button>
                        <button class="quantity-btn" onclick="updateCart(<?php echo $row['cart_id']; ?>, 'decrease')">-</button>
                        <button class="remove-btn" onclick="removeFromCart(<?php echo $row['cart_id']; ?>)">Remove</button>
                    </div>
                </div>
            <?php endwhile; ?>
            <div class="cart-total">
                Total: $<?php echo number_format($total, 2); ?>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <script>
        function updateCart(cartId, action) {
            const formData = new FormData();
            formData.append('cart_id', cartId);
            formData.append('action', action);

            fetch('update_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                if (text.trim() === 'SUCCESS') {
                    location.reload(); // Refresh the page to show updated cart
                } else {
                    alert(text);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function removeFromCart(cartId) {
            if (!confirm('Are you sure you want to remove this item from the cart?')) {
                return;
            }

            const formData = new FormData();
            formData.append('cart_id', cartId);
            formData.append('action', 'remove');

            fetch('remove_from_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                if (text.trim() === 'SUCCESS') {
                    location.reload(); // Refresh the page to show updated cart
                } else {
                    alert(text);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
