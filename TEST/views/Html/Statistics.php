<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-statistics.css">
    <style>
        /* Additional CSS styles for improved layout */
        .specific-foods {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
            margin-top: 40px;
        }

        .specific-food {
            flex-basis: calc(25% - 20px);
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .interesting-statistics {
            margin-top: 20px;
            text-align: center;
        }

        .interesting-statistics h2 {
            font-size: 1.2em;
            color: #282828;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .client-status-box,
        #foods-list-container {
            width: 100%;
            max-width: 600px; /* Adjust width as needed */
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            text-align: center;
            box-sizing: border-box; /* Ensure padding and border width are included in the width */
        }

        /* Media queries for responsiveness */
        @media screen and (max-width: 600px) {
            .specific-food {
                flex-basis: calc(50% - 20px);
            }
        }

        @media (max-width: 768px) {
            .header {
                font-size: 0.8em;
            }
        }

        @media (max-width: 480px) {
            .header {
                font-size: 0.6em;
            }
        }
    </style>
</head>
<body>
    <header class="header" id="header">
        <div class="topnav">
            <a href="/home/homeL" style="margin-right: auto;">Home</a>
            <a href="/home/shop" style="float: right;">Shop</a>
        </div>
    </header>
    <main class="container mt-5">
        <h1>Statistics</h1>
        <div id="client-status" class="client-status">
            <h2>Client Status</h2>
            <div class="client-status-box">
                <p id="bmi-category"></p>
                <p id="weight-suggestion"></p>
            </div>
        </div>
        <div id="recommended-foods" class="recommended-foods">
            <h2>Recommended Foods</h2>
            <div id="foods-list-container">
                <h3 id="foods-list-title"></h3>
                <ul id="foods-list"></ul>
            </div>
        </div>
        <div id="specific-foods" class="specific-foods">
            <h2>Interesting Statistics</h2>
            <div id="most-expensive" class="specific-food">
                <h3>Most Expensive Food</h3>
                <p id="most-expensive-food"></p>
            </div>
            <div id="highest-calories" class="specific-food">
                <h3>Highest Calories Food</h3>
                <p id="highest-calories-food"></p>
            </div>
            <div id="lowest-calories" class="specific-food">
                <h3>Lowest Calories Food</h3>
                <p id="lowest-calories-food"></p>
            </div>
            <div id="highest-protein" class="specific-food">
                <h3>Highest Protein Food</h3>
                <p id="highest-protein-food"></p>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to fetch specific food data
            function fetchSpecificFoodData(action, elementId, additionalAttribute) {
                fetch(`/CuPO/WEB/TEST/controllers/FoodController.php?action=${action}`, {
                    method: 'GET',
                    credentials: 'include'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const food = data.data;

                        // Ensure data fields are present before accessing
                        if ('name' in food && additionalAttribute in food) {
                            document.getElementById(elementId).textContent = `${food.name} - ${additionalAttribute}: ${food[additionalAttribute]}`;
                        } else {
                            console.error('Error: Incomplete data received from server');
                            document.getElementById(elementId).textContent = 'Data unavailable';
                        }
                    } else {
                        console.error(`Error fetching ${action} food:`, data.message);
                        document.getElementById(elementId).textContent = 'Failed to fetch data';
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    document.getElementById(elementId).textContent = 'Fetch error';
                });
            }

            // Fetch user statistics
            fetch('/CuPO/WEB/TEST/controllers/StatisticsController.php', {
                method: 'GET',
                credentials: 'include'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const { height_cm, weight_kg, bmi, bmi_category } = data.data;

                    // Display BMI information
                    const bmiCategoryElement = document.getElementById('bmi-category');
                    bmiCategoryElement.textContent = `BMI Category: ${bmi_category}`;

                    // Color coding BMI category
                    switch (bmi_category.toLowerCase()) {
                        case 'underweight':
                            bmiCategoryElement.style.color = 'blue';
                            break;
                        case 'normal weight':
                            bmiCategoryElement.style.color = 'green';
                            break;
                        case 'overweight':
                            bmiCategoryElement.style.color = 'orange';
                            break;
                        case 'obese':
                            bmiCategoryElement.style.color = 'red';
                            break;
                        default:
                            bmiCategoryElement.style.color = 'black';
                    }

                    const weightSuggestionElement = document.getElementById('weight-suggestion');
                    let foodsCategory;
                    switch (bmi_category.toLowerCase()) {
                        case 'underweight':
                            weightSuggestionElement.textContent = `You need to gain weight. Gain: ${parseFloat((Math.max(0, 18.5 - bmi) * height_cm * height_cm / 10000).toFixed(2))} kg`;
                            foodsCategory = 'gainWeight';
                            break;
                        case 'normal weight':
                            weightSuggestionElement.textContent = 'Your weight is within the healthy range.';
                            foodsCategory = 'maintainWeight';
                            break;
                        case 'overweight':
                            weightSuggestionElement.textContent = `You need to lose weight. Lose: ${parseFloat((Math.max(0, bmi - 24.9) * height_cm * height_cm / 10000).toFixed(2))} kg`;
                            foodsCategory = 'loseWeight';
                            break;
                        case 'obese':
                            weightSuggestionElement.textContent = `You need to lose weight for health reasons. Lose: ${parseFloat((Math.max(0, bmi - 29.9) * height_cm * height_cm / 10000).toFixed(2))} kg`;
                            foodsCategory = 'loseWeight';
                            break;
                        default:
                            weightSuggestionElement.textContent = '';
                    }

                    // Fetch specific food data for each type
                    fetchSpecificFoodData('most_expensive', 'most-expensive-food', 'price');
                    fetchSpecificFoodData('highest_calories', 'highest-calories-food', 'calories');
                    fetchSpecificFoodData('lowest_calories', 'lowest-calories-food', 'calories');
                    fetchSpecificFoodData('highest_protein', 'highest-protein-food', 'protein');

                    // Fetch recommended foods
                    fetch('/CuPO/WEB/TEST/controllers/FoodController.php', {
                        method: 'GET',
                        credentials: 'include'
                    })
                    .then(response => response.json())
                    .then(foodData => {
                        if (foodData.status === 'success') {
                            const foodsListElement = document.getElementById('foods-list');
                            const foodsListTitleElement = document.getElementById('foods-list-title');
                            foodsListElement.innerHTML = ''; // Clear existing list items

                            let foodsToShow;
                            switch (foodsCategory) {
                                case 'gainWeight':
                                    foodsListTitleElement.textContent = 'Foods to Help Gain Weight';
                                    foodsToShow = foodData.data.filter(food => food.calories > 250).slice(0, 10);
                                    break;
                                case 'maintainWeight':
                                    foodsListTitleElement.textContent = 'Foods to Maintain Weight';
                                    foodsToShow = foodData.data.filter(food => food.calories >= 150 && food.calories <= 250).slice(0, 10);
                                    break;
                                case 'loseWeight':
                                    foodsListTitleElement.textContent = 'Foods to Help Lose Weight';
                                    foodsToShow = foodData.data.filter(food => food.calories < 150).slice(0, 10);
                                    break;
                                default:
                                    foodsListTitleElement.textContent = 'Recommended Foods';
                                    foodsToShow = foodData.data.slice(0, 10);
                                    break;
                            }

                            foodsToShow.forEach(food => {
                                const li = document.createElement('li');
                                if (foodsCategory === 'gainWeight') {
                                    // Display additional details for gainWeight category
                                    li.innerHTML = `<strong>${food.name}</strong> - Price: ${food.price}, Protein: ${food.protein}g, Fiber: ${food.fiber}g, Calories: ${food.calories}`;
                                } else {
                                    // Display default details for other categories
                                    li.innerHTML = `<strong>${food.name}</strong> - Calories: ${food.calories}`;
                                }
                                foodsListElement.appendChild(li);
                            });
                        } else {
                            console.error('Error fetching recommended foods:', foodData.message);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Failed to load recommended foods. Please try again later.');
                    });
                } else {
                    console.error('Error fetching user statistics:', data.message);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Failed to load user statistics. Please try again later.');
            });
        });
    </script>
</body>
</html>
