<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "cupo_users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Crearea tabelului foods
$createTableSQL = "CREATE TABLE IF NOT EXISTS foods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aliment VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(255) NOT NULL,
    image_url VARCHAR(255),
    restrictions TEXT,
    perishability INT,
    validity DATE,
    availability_season VARCHAR(255),
    availability_region VARCHAR(255),
    specific_restaurants VARCHAR(255)
)";

if ($conn->query($createTableSQL) === TRUE) {
    echo "Table foods created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}

// Popularea tabelului cu date de test
$insertDataSQL = "INSERT INTO foods (aliment, price, category, image_url, restrictions, perishability, validity, availability_season, availability_region, specific_restaurants) VALUES
('Apple', 5.99, 'Fruits', '/CuPO/WEB/TEST/views/images/apple.jpg', 'None', 7, '2024-06-30', 'Summer', 'North America', 'Restaurant A'),
('Coconut', 4.50, 'Fruits', '/CuPO/WEB/TEST/views/images/coconut.jpg', 'None', 14, '2024-07-15', 'All Year', 'Tropical Regions', 'Restaurant B'),
('Carrot', 2.99, 'Vegetables', '/CuPO/WEB/TEST/views/images/carrot.jpg', 'None', 10, '2024-06-25', 'Spring', 'Europe', 'Restaurant C'),
('Broccoli', 3.49, 'Vegetables', '/CuPO/WEB/TEST/views/images/broccoli.jpg', 'None', 7, '2024-06-20', 'Spring', 'Europe', 'Restaurant D'),
('Milk', 1.99, 'Dairy', '/CuPO/WEB/TEST/views/images/milk.jpg', 'Lactose Intolerant', 5, '2024-06-18', 'All Year', 'Global', 'Restaurant E'),
('Banana', 1.20, 'Fruits', '/CuPO/WEB/TEST/views/images/banana.jpg', 'None', 5, '2024-07-10', 'Summer', 'South America', 'Restaurant F'),
('Tomato', 2.50, 'Vegetables', '/CuPO/WEB/TEST/views/images/tomato.jpg', 'None', 8, '2024-06-25', 'Summer', 'Europe', 'Restaurant G'),
('Cheese', 3.99, 'Dairy', '/CuPO/WEB/TEST/views/images/cheese.jpg', 'Lactose Intolerant', 10, '2024-06-20', 'All Year', 'Global', 'Restaurant H'),
('Chicken', 6.50, 'Meat', '/CuPO/WEB/TEST/views/images/chicken.jpg', 'None', 5, '2024-06-18', 'All Year', 'North America', 'Restaurant I'),
('Beef', 7.99, 'Meat', '/CuPO/WEB/TEST/views/images/beef.jpg', 'None', 7, '2024-06-22', 'All Year', 'Global', 'Restaurant J'),
('Fish', 8.99, 'Seafood', '/CuPO/WEB/TEST/views/images/fish.jpg', 'None', 4, '2024-06-19', 'All Year', 'Global', 'Restaurant K'),
('Shrimp', 9.99, 'Seafood', '/CuPO/WEB/TEST/views/images/shrimp.jpg', 'None', 3, '2024-06-18', 'All Year', 'Global', 'Restaurant L'),
('Yogurt', 1.50, 'Dairy', '/CuPO/WEB/TEST/views/images/yogurt.jpg', 'Lactose Intolerant', 7, '2024-06-21', 'All Year', 'Global', 'Restaurant M'),
('Orange', 3.20, 'Fruits', '/CuPO/WEB/TEST/views/images/orange.jpg', 'None', 10, '2024-07-15', 'Winter', 'North America', 'Restaurant N'),
('Strawberry', 4.50, 'Fruits', '/CuPO/WEB/TEST/views/images/strawberry.jpg', 'None', 3, '2024-06-18', 'Spring', 'Europe', 'Restaurant O'),
('Blueberry', 5.00, 'Fruits', '/CuPO/WEB/TEST/views/images/blueberry.jpg', 'None', 3, '2024-06-18', 'Summer', 'North America', 'Restaurant P'),
('Pineapple', 3.75, 'Fruits', '/CuPO/WEB/TEST/views/images/pineapple.jpg', 'None', 5, '2024-06-25', 'All Year', 'Tropical Regions', 'Restaurant Q'),
('Avocado', 2.99, 'Fruits', '/CuPO/WEB/TEST/views/images/avocado.jpg', 'None', 6, '2024-06-26', 'Summer', 'North America', 'Restaurant R'),
('Egg', 2.50, 'Dairy', '/CuPO/WEB/TEST/views/images/egg.jpg', 'None', 20, '2024-07-10', 'All Year', 'Global', 'Restaurant S'),
('Butter', 2.00, 'Dairy', '/CuPO/WEB/TEST/views/images/butter.jpg', 'Lactose Intolerant', 30, '2024-07-20', 'All Year', 'Global', 'Restaurant T'),
('Lettuce', 1.99, 'Vegetables', '/CuPO/WEB/TEST/views/images/lettuce.jpg', 'None', 5, '2024-06-20', 'Spring', 'Europe', 'Restaurant U'),
('Spinach', 2.50, 'Vegetables', '/CuPO/WEB/TEST/views/images/spinach.jpg', 'None', 4, '2024-06-19', 'Spring', 'Europe', 'Restaurant V'),
('Pepper', 3.00, 'Vegetables', '/CuPO/WEB/TEST/views/images/pepper.jpg', 'None', 7, '2024-06-25', 'Summer', 'North America', 'Restaurant W'),
('Onion', 1.50, 'Vegetables', '/CuPO/WEB/TEST/views/images/onion.jpg', 'None', 10, '2024-07-10', 'All Year', 'Global', 'Restaurant X'),
('Garlic', 1.20, 'Vegetables', '/CuPO/WEB/TEST/views/images/garlic.jpg', 'None', 15, '2024-07-20', 'All Year', 'Global', 'Restaurant Y'),
('Potato', 2.00, 'Vegetables', '/CuPO/WEB/TEST/views/images/potato.jpg', 'None', 20, '2024-07-30', 'All Year', 'Global', 'Restaurant Z'),
('Zucchini', 1.75, 'Vegetables', '/CuPO/WEB/TEST/views/images/zucchini.jpg', 'None', 10, '2024-07-10', 'Summer', 'Europe', 'Restaurant AA'),
('Pumpkin', 3.25, 'Vegetables', '/CuPO/WEB/TEST/views/images/pumpkin.jpg', 'None', 30, '2024-08-10', 'Fall', 'North America', 'Restaurant BB'),
('Cabbage', 1.99, 'Vegetables', '/CuPO/WEB/TEST/views/images/cabbage.jpg', 'None', 15, '2024-07-20', 'Winter', 'Europe', 'Restaurant CC'),
('Cucumber', 2.50, 'Vegetables', '/CuPO/WEB/TEST/views/images/cucumber.jpg', 'None', 7, '2024-06-25', 'Summer', 'North America', 'Restaurant DD'),
('Radish', 1.75, 'Vegetables', '/CuPO/WEB/TEST/views/images/radish.jpg', 'None', 10, '2024-06-25', 'Spring', 'Europe', 'Restaurant EE'),
('Celery', 2.20, 'Vegetables', '/CuPO/WEB/TEST/views/images/celery.jpg', 'None', 12, '2024-07-10', 'Summer', 'North America', 'Restaurant FF'),
('Corn', 3.00, 'Vegetables', '/CuPO/WEB/TEST/views/images/corn.jpg', 'None', 5, '2024-06-20', 'Summer', 'North America', 'Restaurant GG'),
('Mushroom', 4.00, 'Vegetables', '/CuPO/WEB/TEST/views/images/mushroom.jpg', 'None', 7, '2024-06-25', 'Fall', 'Europe', 'Restaurant HH'),
('Grapes', 4.50, 'Fruits', '/CuPO/WEB/TEST/views/images/grapes.jpg', 'None', 10, '2024-07-10', 'Summer', 'Europe', 'Restaurant II'),
('Peach', 3.75, 'Fruits', '/CuPO/WEB/TEST/views/images/peach.jpg', 'None', 5, '2024-06-25', 'Summer', 'North America', 'Restaurant JJ'),
('Plum', 4.00, 'Fruits', '/CuPO/WEB/TEST/views/images/plum.jpg', 'None', 5, '2024-06-20', 'Summer', 'North America', 'Restaurant KK'),
('Cherry', 4.50, 'Fruits', '/CuPO/WEB/TEST/views/images/cherry.jpg', 'None', 5, '2024-06-25', 'Summer', 'Europe', 'Restaurant LL'),
('Lemon', 2.20, 'Fruits', '/CuPO/WEB/TEST/views/images/lemon.jpg', 'None', 10, '2024-07-15', 'Winter', 'Europe', 'Restaurant MM'),
('Lime', 2.50, 'Fruits', '/CuPO/WEB/TEST/views/images/lime.jpg', 'None', 10, '2024-07-15', 'Summer', 'North America', 'Restaurant NN'),
('Watermelon', 7.50, 'Fruits', '/CuPO/WEB/TEST/views/images/watermelon.jpg', 'None', 5, '2024-06-25', 'Summer', 'North America', 'Restaurant OO'),
('Papaya', 6.00, 'Fruits', '/CuPO/WEB/TEST/views/images/papaya.jpg', 'None', 7, '2024-06-30', 'All Year', 'Tropical Regions', 'Restaurant PP'),
('Kiwi', 5.00, 'Fruits', '/CuPO/WEB/TEST/views/images/kiwi.jpg', 'None', 7, '2024-06-30', 'Winter', 'Europe', 'Restaurant QQ'),
('Mango', 4.99, 'Fruits', '/CuPO/WEB/TEST/views/images/mango.jpg', 'None', 7, '2024-06-30', 'Summer', 'North America', 'Restaurant RR'),
('Pear', 3.75, 'Fruits', '/CuPO/WEB/TEST/views/images/pear.jpg', 'None', 7, '2024-06-30', 'Fall', 'North America', 'Restaurant SS'),
('Raspberry', 6.00, 'Fruits', '/CuPO/WEB/TEST/views/images/raspberry.jpg', 'None', 3, '2024-06-18', 'Summer', 'North America', 'Restaurant TT'),
('Blackberry', 6.50, 'Fruits', '/CuPO/WEB/TEST/views/images/blackberry.jpg', 'None', 3, '2024-06-18', 'Summer', 'Europe', 'Restaurant UU'),
('Pomegranate', 5.99, 'Fruits', '/CuPO/WEB/TEST/views/images/pomegranate.jpg', 'None', 7, '2024-06-30', 'Fall', 'North America', 'Restaurant VV')
";
if ($conn->query($insertDataSQL) === TRUE) {
    echo "Data inserted successfully\n";
} else {
    echo "Error inserting data: " . $conn->error;
}

$conn->close();
?>
