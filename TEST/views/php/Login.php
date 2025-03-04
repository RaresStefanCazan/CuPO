<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-Login1.css">
</head>
<body>

    <header>
        <h1><a href="/home/home">Home</a></h1>
    </header>

    <div class="container">
        <h1>Login</h1>
        <form id="login-form">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="/home/register">Register here</a>.</p>
    </div>

    <script>
        document.querySelector('#login-form').addEventListener('submit', function(event) {
            event.preventDefault();
    
            const formData = new FormData(this);
            const data = {
                email: formData.get('email'),
                password: formData.get('password')
            };
    
            fetch('/home/Session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.text())  // Schimba in text pt debugging
            .then(text => {
                console.log('Raw response:', text);  // Afiseaza brut
                const data = JSON.parse(text); 
                console.log('Parsed response:', data); 
                if (data.message === 'Login successful') {
                    if (data.role === 'admin') {
                        window.location.href = '/home/AdminView';
                    } else {
                        window.location.href = '/home/homeL';
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
    
    
</body>
</html>
