document.addEventListener("DOMContentLoaded", () => {
    const categoryFilter = document.getElementById("categoryFilter");
    const typeFilter = document.getElementById("typeFilter");
    const productFilter = document.getElementById("productFilter");
    const itemsGrid = document.getElementById("itemsGrid");

    // Fetch items and update the grid
    const fetchItems = () => {
        const category = categoryFilter.value;
        const type = typeFilter.value;
        const product_kind = productFilter.value;

        // Send a GET request to PHP script to fetch filtered HTML content
        fetch(`fetch_viewitemdets.php?category=${category}&type=${type}&product_kind=${product_kind}`)
            .then(response => response.text())  // Get the HTML content instead of JSON
            .then(data => {
                itemsGrid.innerHTML = data;  // Insert the fetched HTML into the grid
            })
            .catch(error => {
                itemsGrid.innerHTML = "<p>Error loading items. Please try again later.</p>";
                console.error("Error:", error);
            });
    };

    // Event listeners for the filters
    categoryFilter.addEventListener("change", fetchItems);
    typeFilter.addEventListener("change", fetchItems);
    productFilter.addEventListener("change", fetchItems);

    // Initial fetch when the page loads
    fetchItems();
});
