<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Page</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-shop1.css">
    <style>
        /* Additional styles can be added here if needed */
    </style>
</head>
<body>
<header class="header" id="header">
    <div class="nav-container">
        <a class="btn-home active" href="/home/homeL">Home</a>
        <!-- Search form -->
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
    <!-- Filter Section -->
    <div class="filter-section">
        <h2>Filter by Price:</h2>
        <input type="range" id="priceRange" min="0" max="100" value="50">
        <p>Max Price: <span id="priceValue">$50</span></p>
    </div>

    <!-- Grid for displaying items -->
    <div class="grid">
        <!-- PHP loop to display items -->
        <?php
        require_once __DIR__ . '/../../controllers/shop-controller.php';
        $shopController = new ShopController($conn);
        
        // Check if there's a search query
        if (isset($_GET['query'])) {
            $query = $_GET['query'];
            $foods = $shopController->searchFoods($query);
        } else {
            $foods = $shopController->getFoods();
        }

        foreach ($foods as $food) {
            echo '<div class="grid-item">';
            echo '<div class="card" onclick="showModal(' . htmlspecialchars(json_encode($food)) . ')">';
            echo '<img src="' . htmlspecialchars($food['image_url']) . '" class="card-img" alt="' . htmlspecialchars($food['aliment']) . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($food['aliment']) . '</h5>';
            echo '<p class="card-text">Category: ' . htmlspecialchars($food['category']) . '</p>';
            echo '<p class="card-text">Price: $' . htmlspecialchars($food['price']) . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
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
    let lastScrollTop = 0;
const header = document.getElementById('header');

window.addEventListener('scroll', function() {
    const st = window.pageYOffset || document.documentElement.scrollTop;
    if (st > lastScrollTop) {
        // Scroll down
        header.style.top = '-100px';
    } else {
        // Scroll up
        header.style.top = '0';
    }
    lastScrollTop = st <= 0 ? 0 : st;
}, false);
    // Function to show modal with item details
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

    // Function to close the modal
    function closeModal() {
        document.getElementById('productModal').style.display = "none";
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target == document.getElementById('productModal')) {
            closeModal();
        }
    }

    // Form submission to add item to the basket
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

    // Filter by price functionality
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');

    priceRange.addEventListener('input', function() {
        priceValue.textContent = '$' + this.value;
        // You can implement filtering logic here based on the selected price range
        // Example: Filter items with price <= this.value
    });

    // Live search functionality
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        const gridItems = document.querySelectorAll('.grid-item');

        gridItems.forEach(item => {
            const title = item.querySelector('.card-title').textContent.toLowerCase();
            if (title.includes(query)) {
                item.style.display = 'block'; // Show matching items
            } else {
                item.style.display = 'none'; // Hide non-matching items
            }
        });
    });
</script>
</body>
</html>
