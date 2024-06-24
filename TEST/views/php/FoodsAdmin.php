<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Foods</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-adminshop.css">
</head>
<body>
    <header class="header" id="header">
        <div class="nav-container">
            <a class="btn-home active" href="/home/AdminView">Home</a>

    </div>
</header>
<main class="container">
    <div class="filter-section">
        <h2>Manage Foods</h2>
    </div>

    <div class="grid" id="foodsGrid">
       
    </div>
</main>


<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Food</h2>
        <form id="editForm">
            <input type="hidden" id="editId">
            <div class="form-group">
                <label for="editAliment">Aliment:</label>
                <input type="text" id="editAliment" required>
            </div>
            <div class="form-group">
                <label for="editPrice">Price:</label>
                <input type="number" step="0.01" id="editPrice" required>
            </div>
            <div class="form-group">
                <label for="editCategory">Category:</label>
                <input type="text" id="editCategory" required>
            </div>
            <div class="form-group">
                <label for="editRestrictions">Restrictions:</label>
                <textarea id="editRestrictions" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="editPerishability">Perishability:</label>
                <input type="number" id="editPerishability" required>
            </div>
            <div class="form-group">
                <label for="editValidity">Validity:</label>
                <input type="date" id="editValidity" required>
            </div>
            <div class="form-group">
                <label for="editAvailabilitySeason">Availability Season:</label>
                <input type="text" id="editAvailabilitySeason" required>
            </div>
            <div class="form-group">
                <label for="editAvailabilityRegion">Availability Region:</label>
                <input type="text" id="editAvailabilityRegion" required>
            </div>
            <div class="form-group">
                <label for="editSpecificRestaurants">Specific Restaurants:</label>
                <input type="text" id="editSpecificRestaurants" required>
            </div>
            <div class="form-group">
                <label for="editWeight">Weight:</label>
                <input type="number" id="editWeight" required>
            </div>
            <div class="form-group">
                <label for="editProtein">Protein:</label>
                <input type="number" step="0.01" id="editProtein" required>
            </div>
            <div class="form-group">
                <label for="editFiber">Fiber:</label>
                <input type="number" step="0.01" id="editFiber" required>
            </div>
            <div class="form-group">
                <label for="editCalories">Calories:</label>
                <input type="number" id="editCalories" required>
            </div>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadFoods();

        function loadFoods() {
            fetch('/api/foods')
                .then(response => response.json())
                .then(data => {
                    const foodsGrid = document.getElementById('foodsGrid');
                    foodsGrid.innerHTML = ''; 

                    data.forEach(food => {
                        const gridItem = document.createElement('div');
                        gridItem.classList.add('grid-item');

                        gridItem.innerHTML = `
                            <div class="card">
                                <img src="${food.image_url}" class="card-img" alt="${food.aliment}">
                                <div class="card-body">
                                    <h5 class="card-title">${food.aliment}</h5>
                                    <p class="card-text">Category: ${food.category}</p>
                                    <p class="card-text">Price: $${food.price}</p>
                                    <button onclick="deleteFood(${food.id})">Delete</button>
                                    <button onclick="editFood(${food.id}, '${food.aliment}', '${food.category}', ${food.price}, '${food.restrictions}', ${food.perishability}, '${food.validity}', '${food.availability_season}', '${food.availability_region}', '${food.specific_restaurants}', ${food.weight}, ${food.protein}, ${food.fiber}, ${food.calories})">Edit</button>
                                </div>
                            </div>
                        `;

                        foodsGrid.appendChild(gridItem);
                    });
                })
                .catch(error => console.error('Error fetching foods:', error));
        }

        window.deleteFood = function(id) {
            if (confirm('Are you sure you want to delete this food?')) {
                fetch('/api/foods', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadFoods();
                })
                .catch(error => {
                    console.error('Error deleting food:', error);
                });
            }
        }

        window.editFood = function(id, aliment, category, price, restrictions, perishability, validity, availabilitySeason, availabilityRegion, specificRestaurants, weight, protein, fiber, calories) {
            document.getElementById('editId').value = id;
            document.getElementById('editAliment').value = aliment;
            document.getElementById('editPrice').value = price;
            document.getElementById('editCategory').value = category;
            document.getElementById('editRestrictions').value = restrictions;
            document.getElementById('editPerishability').value = perishability;
            document.getElementById('editValidity').value = validity;
            document.getElementById('editAvailabilitySeason').value = availabilitySeason;
            document.getElementById('editAvailabilityRegion').value = availabilityRegion;
            document.getElementById('editSpecificRestaurants').value = specificRestaurants;
            document.getElementById('editWeight').value = weight;
            document.getElementById('editProtein').value = protein;
            document.getElementById('editFiber').value = fiber;
            document.getElementById('editCalories').value = calories;
            document.getElementById('editModal').style.display = 'block';
        }

        document.getElementById('editForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const id = document.getElementById('editId').value;
            const aliment = document.getElementById('editAliment').value;
            const category = document.getElementById('editCategory').value;
            const price = document.getElementById('editPrice').value;
            const restrictions = document.getElementById('editRestrictions').value;
            const perishability = document.getElementById('editPerishability').value;
            const validity = document.getElementById('editValidity').value;
            const availabilitySeason = document.getElementById('editAvailabilitySeason').value;
            const availabilityRegion = document.getElementById('editAvailabilityRegion').value;
            const specificRestaurants = document.getElementById('editSpecificRestaurants').value;
            const weight = document.getElementById('editWeight').value;
            const protein = document.getElementById('editProtein').value;
            const fiber = document.getElementById('editFiber').value;
            const calories = document.getElementById('editCalories').value;

            fetch('/api/foods', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id,
                    aliment: aliment,
                    category: category,
                    price: price,
                    restrictions: restrictions,
                    perishability: perishability,
                    validity: validity,
                    availability_season: availabilitySeason,
                    availability_region: availabilityRegion,
                    specific_restaurants: specificRestaurants,
                    weight: weight,
                    protein: protein,
                    fiber: fiber,
                    calories: calories
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                document.getElementById('editModal').style.display = 'none';
                loadFoods(); 
            })
            .catch(error => {
                console.error('Error editing food:', error);
            });
        });

        // inchidere modal la click 
        document.querySelector('.modal .close').onclick = function() {
            document.getElementById('editModal').style.display = 'none';
        }

        // inchidere modal cand apas pe altceva
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    });
</script>
</body>
</html>