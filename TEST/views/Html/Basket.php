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
            <a class="active" href="/home/mybasket">My Basket</a>
        </div>
    </header>
    <main class="container mt-5">
        <h1>My Basket</h1>
        <div class="grid">
            <?php
            session_start();
            require_once __DIR__ . '/../../controllers/basket-controller.php';
            $basketController = new BasketController($conn);
            $basketItems = $basketController->getBasketItems($_SESSION['basket']);

            if (empty($basketItems)) {
                echo '<p>Your basket is empty.</p>';
            } else {
                foreach ($basketItems as $item) {
                    echo '<div class="grid-item">';
                    echo '<div class="card">';
                    echo '<img src="' . htmlspecialchars($item['image_url']) . '" class="card-img" alt="' . htmlspecialchars($item['aliment']) . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($item['aliment']) . '</h5>';
                    echo '<p class="card-text">Category: ' . htmlspecialchars($item['category']) . '</p>';
                    echo '<p class="card-text">Price: $' . htmlspecialchars($item['price']) . '</p>';
                    echo '<p class="card-text">Restrictions: ' . htmlspecialchars($item['restrictions']) . '</p>';
                    echo '<p class="card-text">Perishability: ' . htmlspecialchars($item['perishability']) . ' days</p>';
                    echo '<p class="card-text">Validity: ' . htmlspecialchars($item['validity']) . '</p>';
                    echo '<p class="card-text">Season: ' . htmlspecialchars($item['availability_season']) . '</p>';
                    echo '<p class="card-text">Region: ' . htmlspecialchars($item['availability_region']) . '</p>';
                    echo '<p class="card-text">Restaurants: ' . htmlspecialchars($item['specific_restaurants']) . '</p>';
                    echo '<form action="/CuPO/WEB/TEST/controllers/remove_from_basket.php" method="POST">';
                    echo '<input type="hidden" name="aliment_id" value="' . htmlspecialchars($item['id']) . '">';
                    echo '<button type="submit" class="btn btn-danger">Remove</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
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
