<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Page</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-shop1.css">
</head>
<body>
    <header class="header" id="header">
        <div class="nav-container">
            <a class="btn-home active" href="/home/homeL">Home</a>
            <form id="searchForm" action="/home/search" method="GET" class="topnav-search">
                <input type="text" name="query" placeholder="Search for food..." class="search-input" id="searchInput">
            </form>
            <div class="basket">
                <a href="/home/mybasket">
                    <img src="/CuPO/WEB/TEST/views/photo/result.png" alt="Basket" class="basket-icon"> My Basket
                </a>
            </div>
        </div>
    </header>
    <main class="container mt-5">
        <div class="filter-section">
            <h2>Filter by Price:</h2>
            <input type="range" id="priceRange" min="0" max="100" value="50">
            <p>Max Price: <span id="priceValue">$50</span></p>
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
            fetch('/CuPO/WEB/TEST/controllers/shop-controller.php')
                .then(response => response.json())
                .then(data => {
                    const foodsGrid = document.getElementById('foodsGrid');
                    foodsGrid.innerHTML = ''; // Clear the grid

                    data.forEach(food => {
                        const gridItem = document.createElement('div');
                        gridItem.classList.add('grid-item');

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

                        foodsGrid.appendChild(gridItem);
                    });
                })
                .catch(error => console.error('Error fetching foods:', error));
        });

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

        document.getElementById('addToCartForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const foodId = document.getElementById('foodId').value;
            const quantity = document.getElementById('quantity').value;
            fetch('/CuPO/WEB/TEST/controllers/add_to_basket.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ food_id: foodId, quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                closeModal();
            })
            .catch(error => console.error('Error:', error));
        });

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

        const priceRange = document.getElementById('priceRange');
        const priceValue = document.getElementById('priceValue');
        priceRange.addEventListener('input', function() {
            priceValue.textContent = '$' + this.value;
        });

        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            const gridItems = document.querySelectorAll('.grid-item');

            gridItems.forEach(item => {
                const title = item.querySelector('.card-title').textContent.toLowerCase();
                if (title.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
