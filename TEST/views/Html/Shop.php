<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Page</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-shop1.css">
</head>

<body>
    <header class="header text-center py-4" id="header">
        <div class="topnav">
            <a class="active" href="/home/homeL">Home</a>
            <input type="text" placeholder="Choose and make the best food...">
        </div>
        <div class="basket">
            <h2><a href="/home/mybasket"><img src="/CuPO/WEB/TEST/views/photo/result.png" alt="Logo"> My Basket</a></h2>
        </div>
    </header>
    <main class="container mt-5">
        <div class="grid">
            <?php
            require_once __DIR__ . '/../../controllers/shop-controller.php';
            $shopController = new ShopController($conn);
            $foods = $shopController->getFoods();

            foreach ($foods as $food) {
                echo '<div class="grid-item">';
                echo '<div class="card">';
                echo '<img src="' . htmlspecialchars($food['image_url']) . '" class="card-img" alt="' . htmlspecialchars($food['aliment']) . '">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($food['aliment']) . '</h5>';
                echo '<p class="card-text">Category: ' . htmlspecialchars($food['category']) . '</p>';
                echo '<p class="card-text">Price: $' . htmlspecialchars($food['price']) . '</p>';
                echo '<p class="card-text">Restrictions: ' . htmlspecialchars($food['restrictions']) . '</p>';
                echo '<p class="card-text">Perishability: ' . htmlspecialchars($food['perishability']) . ' days</p>';
                echo '<p class="card-text">Validity: ' . htmlspecialchars($food['validity']) . '</p>';
                echo '<p class="card-text">Season: ' . htmlspecialchars($food['availability_season']) . '</p>';
                echo '<p class="card-text">Region: ' . htmlspecialchars($food['availability_region']) . '</p>';
                echo '<p class="card-text">Restaurants: ' . htmlspecialchars($food['specific_restaurants']) . '</p>';
                echo '<form action="/CuPO/WEB/TEST/controllers/add_to_basket.php" method="POST">';
                echo '<input type="hidden" name="aliment_id" value="' . htmlspecialchars($food['id']) . '">';
                echo '<button type="submit" class="btn btn-primary">Add to My Basket</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </main>

    <script>
        function scrollToTop() {
            window.scrollTo(0, 0);
        }

        let lastScrollTop = 0;
        window.addEventListener('scroll', function () {
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
