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

// Adăugarea noilor coloane în tabela foods
$alterTableSQL = "ALTER TABLE foods
    ADD COLUMN gramaj INT,
    ADD COLUMN proteine DECIMAL(5, 2),
    ADD COLUMN fibre DECIMAL(5, 2),
    ADD COLUMN kcal INT";



if ($conn->query($createTableSQL) === TRUE) {
    echo "Table foods created successfully\n";
} else {
    echo "Error creating table: " . $conn->error;
}
if ($conn->query($alterTableSQL) === TRUE) {
    echo "Columns added successfully\n";
} else {
    echo "Error adding columns: " . $conn->error;
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

// Popularea noilor coloane cu date reale
$updateDataSQL = "
UPDATE foods SET gramaj = 182, proteine = 0.3, fibre = 4.4, kcal = 95 WHERE aliment = 'Apple';
UPDATE foods SET gramaj = 400, proteine = 2.0, fibre = 9.0, kcal = 354 WHERE aliment = 'Coconut';
UPDATE foods SET gramaj = 61, proteine = 0.9, fibre = 2.8, kcal = 25 WHERE aliment = 'Carrot';
UPDATE foods SET gramaj = 91, proteine = 2.5, fibre = 2.4, kcal = 31 WHERE aliment = 'Broccoli';
UPDATE foods SET gramaj = 244, proteine = 8.0, fibre = 0.0, kcal = 122 WHERE aliment = 'Milk';
UPDATE foods SET gramaj = 118, proteine = 1.3, fibre = 3.1, kcal = 105 WHERE aliment = 'Banana';
UPDATE foods SET gramaj = 182, proteine = 0.9, fibre = 1.5, kcal = 22 WHERE aliment = 'Tomato';
UPDATE foods SET gramaj = 28, proteine = 7.0, fibre = 0.0, kcal = 110 WHERE aliment = 'Cheese';
UPDATE foods SET gramaj = 174, proteine = 38.0, fibre = 0.0, kcal = 335 WHERE aliment = 'Chicken';
UPDATE foods SET gramaj = 85, proteine = 26.0, fibre = 0.0, kcal = 213 WHERE aliment = 'Beef';
UPDATE foods SET gramaj = 85, proteine = 19.0, fibre = 0.0, kcal = 206 WHERE aliment = 'Fish';
UPDATE foods SET gramaj = 85, proteine = 18.0, fibre = 0.0, kcal = 84 WHERE aliment = 'Shrimp';
UPDATE foods SET gramaj = 245, proteine = 9.0, fibre = 0.0, kcal = 154 WHERE aliment = 'Yogurt';
UPDATE foods SET gramaj = 131, proteine = 1.2, fibre = 3.1, kcal = 62 WHERE aliment = 'Orange';
UPDATE foods SET gramaj = 152, proteine = 1.1, fibre = 2.9, kcal = 49 WHERE aliment = 'Strawberry';
UPDATE foods SET gramaj = 148, proteine = 1.1, fibre = 3.6, kcal = 84 WHERE aliment = 'Blueberry';
UPDATE foods SET gramaj = 165, proteine = 0.9, fibre = 2.3, kcal = 83 WHERE aliment = 'Pineapple';
UPDATE foods SET gramaj = 150, proteine = 3.0, fibre = 10.0, kcal = 240 WHERE aliment = 'Avocado';
UPDATE foods SET gramaj = 50, proteine = 6.0, fibre = 0.0, kcal = 70 WHERE aliment = 'Egg';
UPDATE foods SET gramaj = 14, proteine = 0.1, fibre = 0.0, kcal = 102 WHERE aliment = 'Butter';
UPDATE foods SET gramaj = 36, proteine = 0.5, fibre = 1.0, kcal = 5 WHERE aliment = 'Lettuce';
UPDATE foods SET gramaj = 30, proteine = 2.9, fibre = 0.8, kcal = 7 WHERE aliment = 'Spinach';
UPDATE foods SET gramaj = 70, proteine = 1.0, fibre = 1.5, kcal = 20 WHERE aliment = 'Pepper';
UPDATE foods SET gramaj = 110, proteine = 1.1, fibre = 3.0, kcal = 44 WHERE aliment = 'Onion';
UPDATE foods SET gramaj = 30, proteine = 0.9, fibre = 2.8, kcal = 41 WHERE aliment = 'Garlic';
UPDATE foods SET gramaj = 213, proteine = 2.0, fibre = 3.8, kcal = 163 WHERE aliment = 'Potato';
UPDATE foods SET gramaj = 118, proteine = 1.2, fibre = 1.1, kcal = 21 WHERE aliment = 'Zucchini';
UPDATE foods SET gramaj = 250, proteine = 1.0, fibre = 0.5, kcal = 30 WHERE aliment = 'Pumpkin';
UPDATE foods SET gramaj = 89, proteine = 1.5, fibre = 2.2, kcal = 22 WHERE aliment = 'Cabbage';
UPDATE foods SET gramaj = 104, proteine = 1.2, fibre = 1.5, kcal = 16 WHERE aliment = 'Cucumber';
UPDATE foods SET gramaj = 116, proteine = 0.7, fibre = 1.6, kcal = 19 WHERE aliment = 'Radish';
UPDATE foods SET gramaj = 101, proteine = 0.9, fibre = 1.6, kcal = 16 WHERE aliment = 'Celery';
UPDATE foods SET gramaj = 166, proteine = 3.6, fibre = 2.6, kcal = 123 WHERE aliment = 'Corn';
UPDATE foods SET gramaj = 96, proteine = 3.1, fibre = 1.4, kcal = 22 WHERE aliment = 'Mushroom';
UPDATE foods SET gramaj = 151, proteine = 0.7, fibre = 0.9, kcal = 62 WHERE aliment = 'Grapes';
UPDATE foods SET gramaj = 150, proteine = 1.0, fibre = 2.3, kcal = 59 WHERE aliment = 'Peach';
UPDATE foods SET gramaj = 150, proteine = 0.5, fibre = 1.0, kcal = 46 WHERE aliment = 'Plum';
UPDATE foods SET gramaj = 138, proteine = 1.0, fibre = 2.0, kcal = 87 WHERE aliment = 'Cherry';
UPDATE foods SET gramaj = 84, proteine = 1.1, fibre = 2.4, kcal = 24 WHERE aliment = 'Lemon';
UPDATE foods SET gramaj = 67, proteine = 0.5, fibre = 1.9, kcal = 20 WHERE aliment = 'Lime';
UPDATE foods SET gramaj = 2800, proteine = 2.0, fibre = 0.5, kcal = 80 WHERE aliment = 'Watermelon';
UPDATE foods SET gramaj = 500, proteine = 1.0, fibre = 2.0, kcal = 215 WHERE aliment = 'Papaya';
UPDATE foods SET gramaj = 69, proteine = 1.1, fibre = 2.1, kcal = 42 WHERE aliment = 'Kiwi';
UPDATE foods SET gramaj = 207, proteine = 1.1, fibre = 2.6, kcal = 201 WHERE aliment = 'Mango';
UPDATE foods SET gramaj = 178, proteine = 0.5, fibre = 6.0, kcal = 102 WHERE aliment = 'Pear';
UPDATE foods SET gramaj = 123, proteine = 1.5, fibre = 8.0, kcal = 53 WHERE aliment = 'Raspberry';
UPDATE foods SET gramaj = 144, proteine = 1.0, fibre = 8.0, kcal = 62 WHERE aliment = 'Blackberry';
UPDATE foods SET gramaj = 282, proteine = 5.0, fibre = 7.0, kcal = 234 WHERE aliment = 'Pomegranate';
";

if ($conn->multi_query($updateDataSQL) === TRUE) {
    echo "Data updated successfully\n";
} else {
    echo "Error updating data: " . $conn->error;
}

$conn->close();
?>
