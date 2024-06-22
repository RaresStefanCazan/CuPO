<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-user2.css">
</head>
<body>
    <header class="header" id="header">
        <div class="container">
            <div class="header-content1">
                <h1><a href="/home/homeL">Home</a></h1>
            </div>
        </div>
    </header>

    <main>
        <section class="profile-section">
            <h2>User Profile</h2>

            <form id="personal-info-form">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="weight_kg">Weight (kg):</label>
                <input type="number" id="weight_kg" name="weight_kg" required>

                <label for="height_cm">Height (cm):</label>
                <input type="number" id="height_cm" name="height_cm" required>

                <label for="gender">Gender:</label>
                <select id="gender" name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>

                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone">

                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="3"></textarea>

                <label for="budget_per_week">Budget per week:</label>
                <input type="number" id="budget_per_week" name="budget_per_week">

                <button type="submit" class="btn btn-primary btn-lg">Save</button>
            </form>
        </section>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    fetch('/home/Profile', {
        method: 'GET',
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const profile = data.data;

            // Populate form fields
            document.getElementById('first_name').value = profile.first_name || '';
            document.getElementById('last_name').value = profile.last_name || '';
            document.getElementById('email').value = profile.user || '';
            document.getElementById('weight_kg').value = profile.weight_kg || '';
            document.getElementById('height_cm').value = profile.height_cm || '';
            document.getElementById('gender').value = profile.gender || '';
            document.getElementById('phone').value = profile.phone || '';
            document.getElementById('address').value = profile.address || '';
            document.getElementById('budget_per_week').value = profile.budget_per_week || '';
        } else {
            console.error('Error message from server:', data.message);
            alert('Failed to load profile: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Failed to load profile. Please try again later.');
    });
});

document.getElementById('personal-info-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    const jsonData = {
        first_name: formData.get('first_name'),
        last_name: formData.get('last_name'),
        weight_kg: formData.get('weight_kg'),
        height_cm: formData.get('height_cm'),
        gender: formData.get('gender'),
        email: formData.get('email'),
        phone: formData.get('phone'),
        address: formData.get('address'),
        budget_per_week: formData.get('budget_per_week')
    };

    fetch('/home/Profile', {
        method: 'PUT',
        body: JSON.stringify(jsonData),
        headers: {
            'Content-Type': 'application/json'
        },
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Profile updated successfully');
        } else {
            console.error('Error message from server:', data.message);
            alert('Failed to update profile: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Failed to update profile. Please try again later.');
    });
});


    </script>
</body>
</html>
