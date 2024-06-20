<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Page</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-shop2.css">
</head>
<body>
    <header class="header" id="header">
        <div class="nav-container">
            <a class="btn-home active" href="/home/homeL">Home</a>
            <form id="searchForm" action="" method="GET" class="topnav-search">
                <input type="text" name="query" placeholder="Search for food..." class="search-input" id="searchInput">
            </form>
            <div class="basket">
                <a href="/home/mybasket">
                    <img src="/CuPO/WEB/TEST/views/photo/result.png" alt="Basket" class="basket-icon"> My Basket
                </a>
            </div>
        </div>
        <div class="search-results" id="searchResults"></div>
    </header>
    <main class="container mt-5">
        <div class="filter-section">
            <h2>Filter by Price:</h2>
            <div class="sort-buttons">
                <button onclick="sortLowToHigh()">Sort by Price Low to High</button>
                <button onclick="sortHighToLow()">Sort by Price High to Low</button>
            </div>
        </div>

        <div class="filter-section">
            <h2>Filter by Category:</h2>
            <div class="sort-buttons">
                <button onclick="filterByCategory('Vegetables')">Vegetables</button>
                <button onclick="filterByCategory('Fruits')">Fruits</button>
                <button onclick="filterByCategory('Meat')">Meat</button>
                <!-- Add more buttons for other categories as needed -->
            </div>
        </div>

        <div class="grid" id="foodsGrid">
            <!-- Items will be populated here by JavaScript -->
        </div>
    </main>

    <!-- The Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle"></h2>
            <img id="modalImage" src="" alt="" class="modal-image">
            <p id="modalCategory"></p>
            <p id="modalPrice"></p>
            <p id="modalRestrictions"></p>
            <p id="modalPerishability"></p>
            <p id="modalValidity"></p>
            <p id="modalAvailabilitySeason"></p>
            <p id="modalAvailabilityRegion"></p>
            <p id="modalSpecificRestaurants"></p>
            <form id="addToCartForm">
                <label for="quantity">Quantity (kg):</label>
                <input type="number" id="quantity" name="quantity" min="0.1" step="0.1" required>
                <input type="hidden" id="foodId" name="food_id">
                <button type="submit">Add to My Basket</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchFoods(); // Fetch foods when the page loads
        });

        function fetchFoods() {
            fetch('/CuPO/WEB/TEST/controllers/shop-controller.php')
                .then(response => response.json())
                .then(data => {
                    renderFoods(data); // Initial render of foods
                })
                .catch(error => console.error('Error fetching foods:', error));
        }

        function renderFoods(data) {
            const foodsGrid = document.getElementById('foodsGrid');
            foodsGrid.innerHTML = ''; 

            data.forEach(food => {
                const gridItem = createGridItem(food); 
                foodsGrid.appendChild(gridItem);
            });
        }

        function createGridItem(food) {
            const gridItem = document.createElement('div');
            gridItem.classList.add('grid-item');
            gridItem.setAttribute('data-id', food.id);

            gridItem.innerHTML = `
                <div class="card" onclick='showModal(${JSON.stringify(food)})'>
                    <img src="${food.image_url}" class="card-img" alt="${food.aliment}">
                    <div class="card-body">
                        <h5 class="card-title">${food.aliment}</h5>
                        <p class="card-text">Category: ${food.category}</p>
                        <p class="card-text">Price: $${food.price}</p>
                    </div>
                </div>
            `;

            return gridItem;
        }

        function sortLowToHigh() {
            fetch('/CuPO/WEB/TEST/controllers/shop-controller.php?sort=low_to_high')
                .then(response => response.json())
                .then(data => {
                    renderFoods(data); // Render sorted foods
                })
                .catch(error => console.error('Error sorting foods:', error));
        }

        function sortHighToLow() {
            fetch('/CuPO/WEB/TEST/controllers/shop-controller.php?sort=high_to_low')
                .then(response => response.json())
                .then(data => {
                    renderFoods(data); // Render sorted foods
                })
                .catch(error => console.error('Error sorting foods:', error));
        }

        function filterByCategory(category) {
            fetch(`/CuPO/WEB/TEST/controllers/shop-controller.php?category=${category}`)
                .then(response => response.json())
                .then(data => {
                    renderFoods(data); // Render filtered foods by category
                })
                .catch(error => console.error(`Error filtering foods by ${category}:`, error));
        }

        function showModal(food) {
            document.getElementById('modalTitle').innerText = food.aliment;
            document.getElementById('modalImage').src = food.image_url;
            document.getElementById('modalImage').alt = food.aliment;
            document.getElementById('modalCategory').innerText = 'Category: ' + food.category;
            document.getElementById('modalPrice').innerText = 'Price: $' + food.price;
            document.getElementById('modalRestrictions').innerText = 'Restrictions: ' + (food.restrictions || 'None');
            document.getElementById('modalPerishability').innerText = 'Perishability: ' + food.perishability;
            document.getElementById('modalValidity').innerText = 'Validity: ' + food.validity;
            document.getElementById('modalAvailabilitySeason').innerText = 'Availability Season: ' + food.availability_season;
            document.getElementById('modalAvailabilityRegion').innerText = 'Availability Region: ' + food.availability_region;
            document.getElementById('modalSpecificRestaurants').innerText = 'Specific Restaurants: ' + (food.specific_restaurants || 'None');
            document.getElementById('foodId').value = food.id;
            document.getElementById('productModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('productModal').style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('productModal')) {
                closeModal();
            }
        }

        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            if (query.length < 1) {
                searchResults.innerHTML = '';
                fetchFoods();
                return;
            }

            fetch(`/CuPO/WEB/TEST/controllers/shop-controller.php?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    renderFilteredFoods(data, query);
                })
                .catch(error => console.error('Error searching foods:', error));
        });

        function renderFilteredFoods(data, query) {
            const foodsGrid = document.getElementById('foodsGrid');
            foodsGrid.innerHTML = '';

            data.forEach(food => {
                if (food.aliment.toLowerCase().includes(query)) {
                    const gridItem = createGridItem(food);
                    foodsGrid.appendChild(gridItem);
                }
            });
        }

        let lastScrollTop = 0;
        const header = document.getElementById('header');

        window.addEventListener('scroll', function() {
            const st = window.pageYOffset || document.documentElement.scrollTop;
            if (st > lastScrollTop) {
                header.style.top = '-100px';
            } else {
                header.style.top = '0';
            }
            lastScrollTop = st <= 0 ? 0 : st;
        }, false);

    </script>
</body>
</html>
