<?php

//print_r($_POST);
//print_r($_FILES);
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
//echo "Role: " . $_SESSION['role'];  // Output the role to see if it's set correctly
//echo "User ID: " . $_SESSION['user_id'];  // Output the user ID to see if it's set

//if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
  //  header("Location: signin.html"); // Redirect to login if not a seller
    //exit();
//}
// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.html");
    exit();
}

// Access session variables
$user_id = $_SESSION['user_id'];
//$email = $_SESSION['email'];

$buyer_id = $_SESSION['user_id']; // The seller's ID

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
    die("Database connection failed: " . $conn->connect_error);
}




// do whatever you want to do


?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items on Sale</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
        background-color: #f52a23; /* Red header */
        color: white;
        padding: 10px 10px;
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
        font-size: 1.5rem;
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


       /* .header {
            background-color: #068b50;
            color: white;
            padding: 15px;
            text-align: center;
        } */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 10px;
        }
        .item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .item img {
            max-width: 100%;
            border-radius: 5px;
        }
        .item h3 {
            margin: 10px 0;
        }
        .price {
            font-size: 18px;
            margin: 10px 0;
        }
        .original-price {
            text-decoration: line-through;
            color: gray;
        }
        .discounted-price {
            color: #e63946;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            color: white;
            background-color: #068b50;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #e63946;
        }

        
        footer {
            margin-top: auto;
            background-color: #068b50;
            color: white;
            text-align: center;
            font-size: 1rem;
            width: 100%;
            padding: 20px 30px;
            
        }

        footer a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #f52a23;
        }

        footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    

    <header>
        <div class="logo">
            <img src="images/UZURI AFRICA2.jpg" alt="Uzuri Africa Logo">
        </div>
        <h1><p>Flash Sale 2024 !!!!<p>
            <p>Grab your favorite products at discounted prices!</p>
        </h1>
        
        <nav>
            <ul>
                <li><a href="buyer_dashboard.php">Home</a></li>
                <li><a href="view_cart.php">Cart</a></li>
                <li><a href="notifications.php">Notifications</a></li>
                <li><a href="signin.html"><?php echo htmlspecialchars($email); ?><p>(logout)</a></li>
            </ul>
        </nav>
        
        
    </header>
    
    <div class="container" id="items-container">
        <!-- Items will be dynamically added here -->
    </div>
    <script>
        // List of items on sale
        const itemsOnSale = [
            { id: 37, name: "Safari Sunrise", originalPrice: 400, discountPrice: 2000, image: "assets/onsale/art3.jpeg" },
            { id: 38, name: "Safari Sunset", originalPrice: 400, discountPrice: 200, image: "assets/onsale/art2.jpeg" },
            { id: 39, name: "African Fine Dining", originalPrice: 1000, discountPrice: 200, image: "assets/onsale/fine dining.jpeg" },
            { id: 40,  name: "African Art", originalPrice: 300, discountPrice: 150, image: "assets/onsale/art.jpeg" },
            { id: 41, name: "Royal Wear", originalPrice: 800, discountPrice: 300, image: "assets/onsale/royal wear.jpeg" },
            { id: 42, name: "African Carpet", originalPrice: 300, discountPrice: 150, image: "assets/onsale/rug.jpeg" },
        ];
        // Reference to the container
        const itemsContainer = document.getElementById('items-container');

        // Function to add item to cart
        function addToCart(itemId, itemPrice, itemImage) {
            const formData = new FormData();
            formData.append('item_id', itemId);
            formData.append('item_price', itemPrice);
            formData.append('item_image', itemImage);

            fetch('add2cart4BN.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert(`Error: ${data.message}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the item to the cart.');
            });
        }

        // Populate the container with items
        itemsOnSale.forEach(item => {
            const itemElement = document.createElement('div');
            itemElement.className = 'item';
            itemElement.innerHTML = `
                <img src="${item.image}" alt="${item.name}">
                <h3>${item.name}</h3>
                <div class="price">
                    <span class="original-price">€${item.originalPrice.toFixed(2)}</span>
                    <span class="discounted-price">€${item.discountPrice.toFixed(2)}</span>
                </div>
                <button class="btn" onclick="addToCart(${item.id}, ${item.discountPrice}, '${item.image}')">Buy Now</button>
            `;
            itemsContainer.appendChild(itemElement);
        });
    </script>

    <footer class="footer">
        &copy; 2024 Uzuri Africa. All rights reserved.
    </footer>
</body>
</html>
