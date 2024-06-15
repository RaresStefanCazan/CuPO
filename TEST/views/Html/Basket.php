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
        function loadBasketItems() {
            fetch('/CuPO/WEB/TEST/controllers/view_basket.php')
            .then(response => response.json())
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
                                    <button onclick="removeFromCart(${item.id})" class="btn btn-danger">Remove</button>
                                </div>
                            </div>
                        `;
                        basketItemsContainer.appendChild(itemElement);
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function removeFromCart(foodId) {
            fetch('/CuPO/WEB/TEST/controllers/remove_from_basket.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ food_id: foodId })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                loadBasketItems();
            })
            .catch(error => console.error('Error:', error));
        }

        // Load basket items when the page is loaded
        window.onload = loadBasketItems;

    // Script pentru a ascunde și a afișa bara de navigare la scroll
         let lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
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
