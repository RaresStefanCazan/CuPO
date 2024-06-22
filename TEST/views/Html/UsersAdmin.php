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
<
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
                                        <p class="card-text"><strong>Email:</strong> ${user.user}</p>
                                        <p class="card-text"><strong>Role:</strong> ${user.role}</p>
                                        <p class="card-text"><strong>First Name:</strong> ${user.first_name}</p>
                                        <p class="card-text"><strong>Last Name:</strong> ${user.last_name}</p>
                                        <p class="card-text"><strong>Weight (kg):</strong> ${user.weight_kg}</p>
                                        <p class="card-text"><strong>Height (cm):</strong> ${user.height_cm}</p>
                                        <p class="card-text"><strong>Gender:</strong> ${user.gender}</p>
                                        <p class="card-text"><strong>Phone:</strong> ${user.phone}</p>
                                        <p class="card-text"><strong>Address:</strong> ${user.address}</p>
                                        <p class="card-text"><strong>Budget per week:</strong> ${user.budget_per_week}</p>
                                    </div>
                                </div>
                            `;

                            usersGrid.appendChild(gridItem);
                        });
                    })
                    .catch(error => console.error('Error fetching users:', error));
            }
        });
    </script>*/
</body>
</html>
