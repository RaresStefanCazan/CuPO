<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-adminusers1.css">
</head>
<body>
    <header class="header" id="header">
        <div class="nav-container">
            <a class="btn-home active" href="/home/homeL">Home</a>
        </div>
    </header>
    <main class="container mt-5">
        <div class="filter-section">
            <h2>Manage Users</h2>
        </div>

        <div class="grid" id="usersGrid">
            <!-- Items will be populated here by JavaScript -->
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();

            function loadUsers() {
                fetch('/api/users')
                    .then(response => response.json())
                    .then(data => {
                        const usersGrid = document.getElementById('usersGrid');
                        usersGrid.innerHTML = ''; // Clear the grid

                        data.forEach(user => {
                            const gridItem = document.createElement('div');
                            gridItem.classList.add('grid-item');

                            gridItem.innerHTML = `
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">${user.user}</h5>
                                        <p class="card-text">Email: ${user.user}</p>
                                        <p class="card-text">Role: ${user.role}</p>
                                    </div>
                                </div>
                            `;

                            usersGrid.appendChild(gridItem);
                        });
                    })
                    .catch(error => console.error('Error fetching users:', error));
            }
        });
    </script>
</body>
</html>
