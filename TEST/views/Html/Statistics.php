<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-statistics1.css">
</head>
<body>
    <header class="header" id="header">
        <div class="topnav">
            <div class="nav-container">
                <a href="/home/homeL" class="btn-home nav-center">Home</a>
                <a href="/home/shop" class="btn-home nav-left">Shop</a>
            </div>
        </div>
    </header>
    <main class="container mt-5">
        <h1>Statistics</h1>
        <div id="client-status" class="client-status">
            <h2>Client Status</h2>
            <p id="bmi-category"></p>
            <p id="weight-suggestion"></p>
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
        <button id="export-pdf">Export to PDF</button>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exportPdfButton = document.getElementById('export-pdf');
            exportPdfButton.addEventListener('click', function() {
                // Try to get email from cookie
                let email = getCookie('user_email');
                
                // If email is not found in cookie, get it from URL parameter
                if (!email) {
                    const urlParams = new URLSearchParams(window.location.search);
                    email = urlParams.get('email');
                }
                
                // If email is still not available, prompt user to enter it
                if (!email) {
                    email = prompt('Enter your email:');
                }
                
                // Proceed with exporting PDF
                if (email) {
                    window.location.href = `/CuPO/WEB/generate_pdf.php?email=${encodeURIComponent(email)}`;
                } else {
                    alert('Email not provided. Cannot export PDF.');
                }
            });

            // Function to get cookie value by name
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
            }
        });

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
        
        fetch('/CuPO/WEB/TEST/controllers/StatisticsController.php', {
            method: 'GET',
            credentials: 'include'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const { height_cm, weight_kg, bmi, bmi_category } = data.data;
                const bmiCategoryElement = document.getElementById('bmi-category');
                bmiCategoryElement.textContent = `${bmi_category}`;
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
                fetchSpecificFoodData('most_expensive', 'most-expensive-food', 'price');
                fetchSpecificFoodData('highest_calories', 'highest-calories-food', 'calories');
                fetchSpecificFoodData('lowest_calories', 'lowest-calories-food', 'calories');
                fetchSpecificFoodData('highest_protein', 'highest-protein-food', 'protein');
                fetch('/CuPO/WEB/TEST/controllers/FoodController.php', {
                    method: 'GET',
                    credentials: 'include'
                })
                .then(response => response.json())
                .then(foodData => {
                    if (foodData.status === 'success') {
                        const foodsListElement = document.getElementById('foods-list');
                        const foodsListTitleElement = document.getElementById('foods-list-title');
                        foodsListElement.innerHTML = ''; 
                        let foodsToShow;
                        foodsListTitleElement.textContent = foodsCategory === 'gainWeight' ? 'Foods to Help Gain Weight' :
                            foodsCategory === 'maintainWeight' ? 'Foods to Maintain Weight' :
                            'Foods to Help Lose Weight';
                        foodsToShow = foodsCategory === 'gainWeight' ? foodData.data.filter(food => food.calories > 250).slice(0, 10) :
                            foodsCategory === 'maintainWeight' ? foodData.data.filter(food => food.calories >= 150 && food.calories <= 250).slice(0, 10) :
                            foodData.data.filter(food => food.calories < 150).slice(0, 10);
                        foodsToShow.forEach(food => {
                            const li = document.createElement('li');
                            li.innerHTML = `<strong>${food.name}</strong> - Price: ${food.price}, Protein: ${food.protein}g, Fiber: ${food.fiber}g, Calories: ${food.calories}`;
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

        let lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            const st = window.pageYOffset || document.documentElement.scrollTop;
            console.log(`Scroll position: ${st}`);
            if (st > lastScrollTop) {
                header.style.top = '-100px';
            } else {
                header.style.top = '0';
            }
            lastScrollTop = st <= 0 ? 0 : st; // For Mobile or negative scrolling
        }, false);
    </script>
</body>
</html>
