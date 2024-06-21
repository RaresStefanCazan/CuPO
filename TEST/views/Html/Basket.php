<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Basket</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-basket.css">
</head>

<body>
    <header class="header text-center py-4" id="header">
        <div class="topnav">
            <a href="/home/homeL">Home</a>
            <a class="active" href="/home/shop">Shop</a>
        </div>
    </header>
    <main class="container mt-5">
        <h1>My Basket</h1>
        <div class="grid" id="basket-items">
            <!-- Items will be loaded here via JavaScript -->
        </div>
    </main>

    <script>
      function loadBasketItems(listId) {
    console.log(`Fetching items for list ID: ${listId}`);
    fetch(`/CuPO/WEB/TEST/controllers/view_basket.php?list_id=${listId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            const basketItemsContainer = document.getElementById('basket-items');
            basketItemsContainer.innerHTML = '';

            if (data.length === 0) {
                basketItemsContainer.innerHTML = '<p>Your basket is empty.</p>';
            } else {
                data.forEach(item => {
                    const itemElement = document.createElement('div');
                    itemElement.classList.add('grid-item');
                    itemElement.innerHTML = `
                        <div class="card">
                            <img src="${item.image_url}" class="card-img" alt="${item.aliment}">
                            <div class="card-body">
                                <h5 class="card-title">${item.aliment}</h5>
                                <p class="card-text">Category: ${item.category}</p>
                                <p class="card-text">Price: $${item.price}</p>
                                <p class="card-text">Restrictions: ${item.restrictions}</p>
                                <p class="card-text">Perishability: ${item.perishability} days</p>
                                <p class="card-text">Validity: ${item.validity}</p>
                                <p class="card-text">Season: ${item.availability_season}</p>
                                <p class="card-text">Region: ${item.availability_region}</p>
                                <p class="card-text">Restaurants: ${item.specific_restaurants}</p>
                                <button onclick="removeFromCart(${item.id}, ${listId})" class="btn btn-danger">Remove</button>
                            </div>
                        </div>
                    `;
                    basketItemsContainer.appendChild(itemElement);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching basket items:', error);
            alert('Error fetching basket items: ' + error.message);
        });
}

function removeFromCart(foodId, listId) {
    fetch('/CuPO/WEB/TEST/controllers/remove_from_basket.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ food_id: foodId, list_id: listId })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        loadBasketItems(listId);
    })
    .catch(error => console.error('Error:', error));
}

// Load basket items when the page is loaded
window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    const listId = urlParams.get('list_id');
    if (listId) {
        loadBasketItems(listId);
    } else {
        console.error('List ID is required');
        alert('List ID is required');
    }
};

    </script>
</body>

</html>
