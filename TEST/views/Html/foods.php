<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foods</title>
    <link rel="stylesheet" href="../Style-CSS/style.css">
</head>
<body>
    <h1>Foods</h1>
    <div class="food-container">
        <?php
        require_once __DIR__ . '/../../model/database.php';
        require_once __DIR__ . '/../../controllers/foods-controller.php';
        
        $foodsController = new FoodsController($conn);
        $foods = $foodsController->getFoods();

        foreach ($foods as $food) {
            echo '<div class="food-item">';
            echo '<h2>' . $food['category'] . '</h2>';
            echo '<p>Price: $' . $food['price'] . '</p>';
            echo '<p>Ingredients: ' . $food['ingredients'] . '</p>';
            echo '<img src="' . $food['image_url'] . '" alt="' . $food['category'] . '">';
            echo '<p>Restrictions: ' . $food['restrictions'] . '</p>';
            echo '<p>Perishability: ' . $food['perishability'] . ' days</p>';
            echo '<p>Validity: ' . $food['validity'] . '</p>';
            echo '<p>Season: ' . $food['availability_season'] . '</p>';
            echo '<p>Region: ' . $food['availability_region'] . '</p>';
            echo '<p>Restaurants: ' . $food['specific_restaurants'] . '</p>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>
