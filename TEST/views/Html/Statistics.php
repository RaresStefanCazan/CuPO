<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-statistics.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header class="header text-center py-4" id="header">
        <div class="topnav">
            <a href="/home/homeL">Home</a>
            <a href="/home/shop">Shop</a>
            <a class="active" href="/home/statistics">Statistics</a>
            <a href="/home/metadataManagement">Metadata Management</a>
            <a href="/home/admin">Admin</a>
            <a href="/home/userInfo">Profile</a>
            <a href="/home/info">Info</a>
            <a href="/CuPO/WEB/TEST/controllers/logout.php">Logout</a>
        </div>
    </header>
    <main class="container mt-5">
        <h1>Statistics</h1>
        <div class="statistics-options">
            <button class="btn-stat" data-type="expensive">Expensive Products</button>
            <button class="btn-stat" data-type="favourites">Favourite Products</button>
            <button class="btn-stat" data-type="vegan">Vegan Products</button>
            <button class="btn-stat" data-type="lactosefree">Lactose-Free Products</button>
            <!-- Adaugă mai multe opțiuni de statistici aici -->
        </div>
        <div class="statistics-content">
            <div id="statistics-table"></div>
        </div>
    </main>

    <script>
        //aici folosesc biblioteca jquery am inclus o mai sus mai vedem daca o pastram
        
        $(document).ready(function() {
            $('.btn-stat').click(function() {
                var type = $(this).data('type');
                $.ajax({
                    url: '/CuPO/WEB/TEST/controllers/StatisticsController.php',
                    method: 'POST',
                    data: { type: type },
                    success: function(response) {
                        var table = '<table class="table">';
                        table += '<thead><tr><th>Product</th><th>Price</th></tr></thead>';
                        table += '<tbody>';
                        response.forEach(function(row) {
                            table += '<tr>';
                            table += '<td>' + row.aliment + '</td>';
                            table += '<td>' + row.price + '</td>';
                            table += '</tr>';
                        });
                        table += '</tbody>';
                        table += '</table>';
                        $('#statistics-table').html(table);
                    }
                });
            });
        });

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
