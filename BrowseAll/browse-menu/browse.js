document.addEventListener("DOMContentLoaded", () => {
    const categoryFilter = document.getElementById("categoryFilter");
    const typeFilter = document.getElementById("typeFilter");
    const itemsGrid = document.getElementById("itemsGrid");

    const fetchItems = () => {
        const category = categoryFilter.value;
        const type = typeFilter.value;

        fetch(`fetch_items.php?category=${category}&type=${type}`)
            .then(response => response.json())
            .then(data => {
                itemsGrid.innerHTML = "";
                data.forEach(item => {
                    const card = document.createElement("div");
                    card.classList.add("item-card");
                    card.innerHTML = `
                        <img src="../${item.image_path}" alt="${item.name}">
                        <h3>${item.name}</h3>
                        <p>Category: ${item.category}</p>
                        <p>Type: ${item.type}</p>
                        <p>Price: €${item.price}</p>
                    `;
                    itemsGrid.appendChild(card);
                });
            })
            .catch(error => {
                itemsGrid.innerHTML = "<p>Error loading items. Please try again later.</p>";
                console.error("Error:", error);
            });
    };

    categoryFilter.addEventListener("change", fetchItems);
    typeFilter.addEventListener("change", fetchItems);

    fetchItems();
});
