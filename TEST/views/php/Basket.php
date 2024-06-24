<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Basket</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-basket1.css">
</head>
<body>
    <header class="header text-center py-4" id="header">
        <div class="topnav">
            <a href="/home/homeL">Home</a>
            <a class="active" href="/home/shop">Shop</a>
            <a href="/home/Lists">Lists</a>
        </div>
    </header>
    <main class="container mt-5">
        <h1>My Basket</h1>
        <div class="grid" id="basket-items">
            
        </div>
        <hr> 
        <div id="total-sum" class="text-center">
            
        </div>
    </main>

    <script>        
    function getCookie(name) {
        let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) {
            return match[2];
        } else {
            return null;
        }
    }

    function loadBasketItems(listId) {
        const userLists = JSON.parse(localStorage.getItem('userLists'));
        if (!userLists.includes(parseInt(listId))) {
            alert('You do not have access to this list.');
            return;
        }

        console.log(`Fetching items for list ID: ${listId}`);
        fetch(`/CuPO/WEB/TEST/controllers/TestBasket.php?list_id=${listId}`)
            .then(response => {
                console.log('Fetch response:', response);
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Fetched data:', data);
                const basketItemsContainer = document.getElementById('basket-items');
                basketItemsContainer.innerHTML = '';

                if (data.length === 0) {
                    basketItemsContainer.innerHTML = '<p>Your basket is empty.</p>';
                } else {
                    let totalSum = 0; 

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
                                    <p class="card-text">Quantity: ${item.quantity}</p>
                                    <p class="card-text">Total Price: $${item.total_price.toFixed(2)}</p>
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

                        totalSum += item.total_price; 
                    });

                    
                    const totalSumElement = document.getElementById('total-sum');
                    totalSumElement.innerHTML = `<p>Total Sum: $${totalSum.toFixed(2)}</p>`;
                }
            })
            .catch(error => {
                console.error('Error fetching basket items:', error);
                alert('Error fetching basket items: ' + error.message);
            });
    }

    function removeFromCart(foodId, listId) {
        fetch('/home/Basket', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ food_id: foodId, list_id: listId })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Remove response:', data);
            alert(data.message);
            loadBasketItems(listId);
        })
        .catch(error => console.error('Error:', error));
    }

    window.onload = function() {
        const listId = getCookie('currentListId');
        console.log('Current list ID:', listId);
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
