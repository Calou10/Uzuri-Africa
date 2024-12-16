<?php

//print_r($_POST);
//print_r($_FILES);
error_reporting(E_ALL);
ini_set('display_errors', 1);


session_start();
//echo "Role: " . $_SESSION['role'];  // Output the role to see if it's set correctly
//echo "User ID: " . $_SESSION['user_id'];  // Output the user ID to see if it's set

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.html"); // Redirect to login if not a seller
    exit();
}

$seller_id = $_SESSION['user_id']; // The seller's ID

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


// Add Item Logic (If form is submitted)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $product_kind = $_POST['product_kind'];
    $price = $_POST['price'];
    $image = $_FILES['image'];  // Assuming you are uploading images manually or have a URL for them.

    if ($image['error'] == 0) {
        // Define the directory for storing images
        $upload_dir = 'assets/images/';
        $image_name = time() . '_' . basename($image['name']);
        $image_path = $upload_dir . $image_name;

        // Move the uploaded file to the 'uploads' directory
        if (move_uploaded_file($image['tmp_name'], $image_path)) {
            
            $stmt = $conn->prepare("INSERT INTO items (name, category, type, product_kind, image_path, price, seller_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssdi", $name, $category, $type, $product_kind, $image_path, $price, $seller_id);
        
            echo "SQL Query: INSERT INTO items (name, category, type, product_kind, image_path, price, seller_id) VALUES ('$name', '$category', '$type', '$product_kind', '$image_path', '$price', '$seller_id')";

            if ($stmt->execute()) {
                echo "Item added successfully!";
                header("Location: admin_dashboard.php"); // Refresh the page after adding
                exit();
            }else  {         
                echo "Database Insert Error: " . $stmt->error;
            }
                    
        } else {
            echo "Error: ";
        }
            
    } else {
        echo "Error: Failed to upload the image.";
     }
    
 }

 // Fetch all products from the sellerWHERE seller_id = ?");
$stmt = $conn->prepare("SELECT * FROM items"); 
//$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);


// Delete Item Logic
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $id, $seller_id);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch Item for Edit
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $id, $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_item = $result->fetch_assoc();
}

// Update Item Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_item'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $category = $_POST['category'];
    $type = $_POST['type'];
    $product_kind = $_POST['product_kind'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE items SET name = ?, category = ?, type = ?, product_kind = ?, price = ? WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ssssiii", $name, $category, $type, $product_kind, $price, $id, $seller_id);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch All Products
$stmt = $conn->prepare("SELECT * FROM items WHERE seller_id = ?");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Uzuri Africa</title>
    <style>
        /* Add your previous CSS here */

        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

              
        body {
            font-family: 'Arial', sans-serif;
            background-color: #EDE6CF;
            color: #333;
            flex: 1;
            flex-direction: column;
            justify-content: flex-start;
            display: flex;
            height: 100%;
        }
        
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #068b50;
            color: white;
            padding: 20px 10px;
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

        .logout-btn {
            background-color: red;
            color: white;
            padding: 8px 10px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            margin: 8px 0;
        }

        .logout-btn:hover {
            background-color: darkred;
        }

        


              /* Form Styling */


        .form-container {
            width: 600px;
            margin: 20px 100px;
            float: left;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container input, textarea, select {
            width: 95%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;

        }

        .form-container button {
            background-color: #068b50;
            margin-left: 200px;
            color: white;
            font-size: 1.2rem;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }


        .form2-container {
            width: 900px;
            margin: 20px 50px;
            float: right;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .form2-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form2-container input, textarea, select {
            width: 95%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;

        }

        /*.form2-container button {
            background-color: #068b50;
            margin-left: 200px;
            color: white;
            font-size: 1.2rem;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }*/

        .btn-delete {
            background: red;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        
        /* Form Title */
        h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #068b50;
            text-align: center;
        }


        /* Form Input Styling */
        form input, form textarea, form select {
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            justify-content: center;
            text-align: center;
        }

        textarea {
            height: 50px;
            resize: vertical;
        }

        
        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #EDE6CF;
            padding: 20px;
            border-radius: 1px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 2px solid #dfd;
            font-size: 1.2rem;
        }

        th {
            background-color: #068b50;
            color: white;
        }

        td {
            background-color: #EDE6CF;
        }

        td a {
            text-decoration: none;
            color: #068b50;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        td a:hover {
            background-color: #f52a23;
            color: white;
        }

        
                /* Footer Styling */
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

        #update-item-form {

            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        #update-item-form input, textarea, select {
            width: 95%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;

        }

        #update-item-form button {
            background-color: #068b50;
            color: white;
            font-size: 1.2rem;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {

            header {
                flex-direction: column; /* Stack logo, h1, and nav vertically */
                align-items: center;
                text-align: center;
            }

            .logo img {
                width: 60px; /* Smaller logo for smaller screens */
                margin: 5px 0;
            }

            header h1 {
                font-size: 1rem; /* Reduced heading size for smaller screens */
                margin: 5px 0;
            }

            nav ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 8px;
            }
        
            h1 {
                font-size: 2rem;
            }
            
            h2 {
                font-size: 1.6rem;
            }

            input[type="text"], input[type="number"], select, button[type="submit"] {
                font-size: 1rem;
            }

            .form-container {
                padding: 15px;
                flex-direction: column;
                width: 300px;
            }

            .form2-container {
                padding: 15px;
                flex-direction: column;
                width: 300px;
            }

                     th, td {
                padding: 10px;
            }

            table {
                font-size: 0.9rem;
            }

            form input, form textarea {
                font-size: 1rem;
            }
        

        footer {
                font-size: 0.9rem;
                padding: 15px 5px;
                margin-top: auto;
            }
        }

            

        @media (max-width: 480px) {

            header h1 {
                font-size: 0.9rem;
            }

            nav ul li a {
                font-size: 0.8rem;
            }
        
            input[type="text"], input[type="number"], select, textarea, button[type="submit"] {
                font-size: 0.9rem;
            }

             .form-container {
                padding: 15px;
                flex-direction: column;
                width: 150px;
            }

            .form2-container {
                padding: 15px;
                flex-direction: column;
                width: 150px;
            }
        

            table {
                font-size: 0.8rem;
            }
            
            th, td {
                padding: 8px;
            }

            form input, form textarea {
                font-size: 0.9rem;
            }

            form button {
                font-size: 1rem;
            }
        

            footer {
                font-size: 0.8rem;
                padding: 10px 5px;
            }
        }

    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="logo">
            <img src="images/UZURI AFRICA2.jpg" alt="Uzuri Africa Logo">
        </div>
        <h1>Welcome to Your Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="browse.html">Browse All</a></li>
                <li><a href="displaybids.php">View Bids</a></li>
                <li><a href="admin_login.html"><?php echo htmlspecialchars($email); ?><p>(logout)</a></li>
            </ul>
        </nav>
    </header>

    <!-- Seller Dashboard -->
    

    <!-- Add Item Form -->
    <div class="container">
        <table>
            <tr>
                <td>
                    <div class="form-container">
                        <h2>Add New Item</h2>
                        <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
                            <label for="item">Item:</label>
                            <input type="text" name="name" placeholder="Item Name" required><br>
                            
                            <!-- Category Dropdown -->
                            <label for="category">Category:</label>
                            <select name="category" required>
                                <option value="rare">Select Category</option>
                                <option value="rare">Rare Items</option>
                                <option value="high-end">High-End</option>
                                <option value="regular">Regular Items</option>
                            </select><br>

                            <!-- Type Dropdown -->
                            <label for="type">Type:</label>
                            <select name="type" required>
                                <option value="all">All Purchase Types</option>
                                <option value="buy-now">Buy It Now</option>
                                <option value="negotiation">Negotiation</option>
                                <option value="best-offer">Best Offer</option>
                            </select><br>

                            <label for="product_kind">Product:</label>
                            <select name="product_kind" required>
                                <option value="all">All Product Kind</option>
                                <option value="food">Food</option>
                                <option value="clothings">Clothings</option>
                                <option value="artifacts">Artifacts</option>
                            </select><br>

                            <label for="price">Price:</label>
                            <input type="number" step="0.01" name="price" placeholder="Price" required><br>
                            <label for="image">Image:</label>
                            <input type="file" name="image" placeholder="Image URL" accept="image/*"  required><br>
                            <button type="submit" name="add_item">Add Item</button>
                        </form>
                    </div>
                </td>

                <td>
                    <div class="form2-container">
                        <h2>Remove Seller</h2>
                        <?php
                            // Fetch data from the database
                            $sql = "SELECT * FROM users";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<table id ='seller'>";
                                    echo "<tr>
                                            <form action='delete_seller.php' method='POST'>
                                                
                                                <td><input type='email' name='business_email' value='{$row["business_email"]}'></td>
                                                <td><input type='text' name='role' value='{$row["role"]}'></td>
                                                <td><input type='text' name='company_name' value='{$row["company_name"]}'></td>
                                                <td><input type='text' name='product' value='{$row["product"]}'></td>
                                                <td><input type='text' name='business_address' value='{$row["business_address"]}'></td>
                                                <td>
                                                    <button type='submit' class='btn-delete'>Delete</button>
                                                </td>
                                            </form>
                                          </tr>";
                                    echo "</table>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            }

                           
                            ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- List of Admin's Products -->
    <div class="products">
    <h2>Products</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Type</th>
                <th>Product</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td><?php echo htmlspecialchars($product['type']); ?></td>
                    <td><?php echo htmlspecialchars($product['product_kind']); ?></td>
                    <td><?php echo number_format($product['price'], 2); ?></td>
                    <td>
                        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" 
                             alt="Product Image" 
                             width="80" height="80" 
                             style="object-fit:cover;">
                    </td>
                    <td>
                        <a href="admin_dashboard.php?edit=<?php echo $product['id']; ?>">Edit</a>
                        <a href="admin_dashboard.php?delete=<?php echo $product['id']; ?>" 
                           onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

    <!-- Update Item Form (Only visible when editing an item) -->
    <!-- Update Item Form (Only visible when editing an item) -->
    <?php if (isset($_GET['edit'])): 
        $id = $_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
    ?>
    <h2>Update Item</h2>
    <form id="update-item-form" action="admin_dashboard.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>
        <select name="category" required>
            <option value="rare">Select Category</option>
            <option value="rare">Rare Items</option>
            <option value="high-end">High-End</option>
            <option value="regular">Regular Items</option>
        </select><br>
        <select name="type" required>
            <option value="all">All Purchase Types</option>
            <option value="buy-now">Buy It Now</option>
            <option value="negotiation">Negotiation</option>
            <option value="best-offer">Best Offer</option>
        </select><br>
        <select name="product_kind" required>
            <option value="all">All Product Kind</option>
            <option value="food">Food</option>
            <option value="clothings">Clothings</option>
            <option value="artifacts">Artifacts</option>
        ss</select><br>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required><br>
        <input type="file" name="image" value="<?php echo $product['image_path']; ?>" required><br>
        <button type="submit" name="update_item">Update Item</button>
    </form>
    <?php endif; ?>


    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Uzuri Africa. All rights reserved.</p>
    </footer>

</body>
    
</html>

<script>
    // Add event listener to validate the form before submission
    document.querySelector('form').addEventListener('submit', function(event) {
        let itemName = document.querySelector('input[name="name"]').value;
        let description = document.querySelector('textarea[name="description"]').value;
        let price = document.querySelector('input[name="price"]').value;
        let image = document.querySelector('input[name="image"]').value;

        // Check if all required fields are filled
        if (!itemName || !description || !price || !image) {
            alert('Please fill in all required fields.');
            event.preventDefault(); // Prevent form submission
            return false;
        }

        // Optional: Validate price to ensure it's a valid number
        if (isNaN(price) || price <= 0) {
            alert('Please enter a valid price.');
            event.preventDefault(); // Prevent form submission
            return false;
        }
        
        return true; // Proceed with form submission if everything is valid
    });


        // Function to confirm deletion of an item
    document.querySelectorAll('a[href*="delete"]').forEach(deleteLink => {
        deleteLink.addEventListener('click', function(e) {
            if (!confirm("Are you sure you want to delete this item?")) {
                e.preventDefault();
            }
        });
    });

    // Show/Hide Update Form when Edit is clicked
    document.querySelectorAll('a[href*="edit"]').forEach(editLink => {
        editLink.addEventListener('click', function(e) {
            const updateForm = document.querySelector('#update-item-form');
            updateForm.style.display = 'block';
            window.scrollTo(0, updateForm.offsetTop); // Scroll to the update form
        });
    });

</script>