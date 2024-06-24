<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Page</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-Info1.css">
</head>
<body>
    <header class="header" id="header">
        <div class="home-container">
            <h1><a href="homeL">Home</a></h1>
        </div>
    </header>
    <main>
        <section class="info-section">
            <h2>About Us</h2>
            <p>Welcome to our server! We are dedicated to developing a web application that exposes a REST API utilizing data from Open Food Facts. Our goal is to manage culinary preferences, including categories, prices, ingredients, perishability, and more. The application supports creating shopping lists and user administration, while generating statistics exportable in open formats like CSV and PDF.</p>
        </section>
        <section class="info-section">
            <h2>Contact Us</h2>
            <p>Email: contactrares@yahoo.com</p>
            <p>Phone: 123-456-7890</p>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Info Page. All rights reserved.</p>
    </footer>
    <script>
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
