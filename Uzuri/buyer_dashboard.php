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
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "uzuri"; // Replace with your database name

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
    <title>My Dashboard - Uzuri Africa</title>
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
    main {
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

    /* Grid Styling */

    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        padding: 20px;
    }

    .item-card a {
        text-decoration: none;  /* Remove underline */
        color: inherit;  /* Inherit text color from parent */
        display: block; /* Make sure the anchor tag behaves like a block element */
    }

    .item-card {
        background-color: white;
        border: 2px solid #068b50; /* Green border for cards */
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;  /* Pointer cursor when hovering over card */
    }

    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .item-card img {
        width: 170px;
        height: 150px;
        object-fit: cover;
        border-bottom: 2px solid #068b50; /* Green accent border */
        margin-bottom: 10px;
        align-items: center;
    }

    .item-card h3 {
        color: #333;
        font-size: 1.2em;
        margin: 10px 0;
    }

    .item-card p {
        color: #555;
        font-size: 0.9em;
        margin: 5px 0;
    }

    .item-card .price {
        font-size: 1.1em;
        color: #f52a23; /* Red price text */
        font-weight: bold;
    }

    /* Button Styling */
    .filters button {
        background-color: #f52a23; /* Red button */
        color: white;
        border: none;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .filters button:hover {
        background-color: #c41f1b; /* Darker red on hover */
    }

    /* Responsiveness */
    @media (max-width: 768px) {
        .filters {
            flex-direction: column;
        }

        .items-grid {
            grid-template-columns: 1fr;
        }

        .item-card img {
            height: 200px;
        }
    }

    .item-card a {
        text-decoration: none;  /* Remove underline */
        color: inherit;  /* Inherit text color from parent */
    }

    .item-card {
        /* Your existing card styles */
        cursor: pointer;  /* Show pointer cursor on hover */
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
    <main>
        <div class="filters">
            <select id="categoryFilter">
                <option value="all">All Categories</option>
                <option value="rare">Rare Items</option>
                <option value="high-end">High-End</option>
                <option value="regular">Regular Items</option>
            </select>
            <select id="typeFilter">
                <option value="all">All Purchase Types</option>
                <option value="buy-now">Buy It Now</option>
                <option value="negotiation">Negotiation</option>
                <option value="best-offer">Best Offer</option>
            </select>
            <select id="productFilter">
                <option value="all">All Products</option>
                <option value="food">Food</option>
                <option value="clothings">Clothings</option>
                <option value="artifacts">Artifacts</option>
            </select>

            <button id="filterButton">Filter</button>
        </div>
        <div class="items-grid" id="itemsGrid">
            <!-- Items will be dynamically loaded here -->
        </div>
    </main>
    <footer class="footer">
        &copy; 2024 Uzuri Africa. All rights reserved.
    </footer>
    <script src="browse.js"></script>
</body>
</html>
