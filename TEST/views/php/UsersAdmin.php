<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-adminshop.css">
</head>
<body>
    <header class="header" id="header">
        <div class="nav-container">
            <a class="btn-home active" href="/home/AdminView">Home</a>
           
        </div>
    </header>
    <main class="container">
        <div class="filter-section">
            <h2>Manage Users</h2>
        </div>
        <div class="grid" id="usersGrid">
        </div>
    </main>
    
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit User</h2>
            <form id="editUserForm">
                <input type="hidden" id="editUserId">
                <div class="form-group">
                    <label for="editFirstName">First Name:</label>
                    <input type="text" id="editFirstName" required>
                </div>
                <div class="form-group">
                    <label for="editLastName">Last Name:</label>
                    <input type="text" id="editLastName" required>
                </div>
                <div class="form-group">
                    <label for="editEmail">Email:</label>
                    <input type="email" id="editEmail" required>
                </div>
                <div class="form-group">
                    <label for="editWeight">Weight (kg):</label>
                    <input type="number" id="editWeight" required>
                </div>
                <div class="form-group">
                    <label for="editHeight">Height (cm):</label>
                    <input type="number" id="editHeight" required>
                </div>
                <div class="form-group">
                    <label for="editGender">Gender:</label>
                    <select id="editGender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editRole">Role:</label>
                    <input type="text" id="editRole" required>
                </div>
                <div class="form-group">
                    <label for="editPhone">Phone:</label>
                    <input type="tel" id="editPhone">
                </div>
                <div class="form-group">
                    <label for="editAddress">Address:</label>
                    <textarea id="editAddress" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="editBudget">Budget per week:</label>
                    <input type="number" id="editBudget">
                </div>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();

            function loadUsers() {
                fetch('/api/users')
                    .then(response => response.json())
                    .then(data => {
                        const usersGrid = document.getElementById('usersGrid');
                        usersGrid.innerHTML = ''; // sterge grid

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
                                        <button onclick="deleteUser(${user.id})">Delete</button>
                                        <button onclick="editUser(${user.id}, '${user.first_name}', '${user.last_name}', '${user.user}', ${user.weight_kg}, ${user.height_cm}, '${user.gender}', '${user.role}', '${user.phone}', '${user.address}', ${user.budget_per_week})">Edit</button>
                                    </div>
                                </div>
                            `;

                            usersGrid.appendChild(gridItem);
                        });
                    })
                    .catch(error => console.error('Error fetching users:', error));
            }

            window.deleteUser = function(id) {
                if (confirm('Are you sure you want to delete this user?')) {
                    fetch('/api/users', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        loadUsers(); // face reload dupa delete
                    })
                    .catch(error => {
                        console.error('Error deleting user:', error);
                    });
                }
            }

            window.editUser = function(id, first_name, last_name, email, weight_kg, height_cm, gender, role, phone, address, budget_per_week) {
                document.getElementById('editUserId').value = id;
                document.getElementById('editFirstName').value = first_name;
                document.getElementById('editLastName').value = last_name;
                document.getElementById('editEmail').value = email;
                document.getElementById('editWeight').value = weight_kg;
                document.getElementById('editHeight').value = height_cm;
                document.getElementById('editGender').value = gender;
                document.getElementById('editRole').value = role;
                document.getElementById('editPhone').value = phone;
                document.getElementById('editAddress').value = address;
                document.getElementById('editBudget').value = budget_per_week;
                document.getElementById('editUserModal').style.display = 'block';
            }

            document.getElementById('editUserForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const id = document.getElementById('editUserId').value;
                const first_name = document.getElementById('editFirstName').value;
                const last_name = document.getElementById('editLastName').value;
                const email = document.getElementById('editEmail').value;
                const weight_kg = document.getElementById('editWeight').value;
                const height_cm = document.getElementById('editHeight').value;
                const gender = document.getElementById('editGender').value;
                const role = document.getElementById('editRole').value;
                const phone = document.getElementById('editPhone').value;
                const address = document.getElementById('editAddress').value;
                const budget_per_week = document.getElementById('editBudget').value;

                fetch('/api/users', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        first_name: first_name,
                        last_name: last_name,
                        email: email,
                        weight_kg: weight_kg,
                        height_cm: height_cm,
                        gender: gender,
                        role: role,
                        phone: phone,
                        address: address,
                        budget_per_week: budget_per_week
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    document.getElementById('editUserModal').style.display = 'none';
                    loadUsers(); // reload
                })
                .catch(error => {
                    console.error('Error editing user:', error);
                });
            });

            // butonul close sa se inchida modalul
            document.querySelector('.modal .close').onclick = function() {
                document.getElementById('editUserModal').style.display = 'none';
            }

            // sa se inchida modalul cand se apasa pe langa
            window.onclick = function(event) {
                const modal = document.getElementById('editUserModal');
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
