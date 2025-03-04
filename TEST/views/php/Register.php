<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-Register.css">
</head>
<body>
    
    <header>
        <h1><a href="/home/home">Home</a></h1>
    </header>
    

    <div class="container">      
        <h1>Register</h1>
        <form id="register-form">
            <label for="email">Username or Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Register</button>
        </form>
        
        
        <p>Already have an account? <a href="/home/login">Login here</a>.</p>
    </div>

    <script>
        document.querySelector('#register-form').addEventListener('submit', function(event) {
            event.preventDefault();
        
            const formData = new FormData(this);
            const data = {
                email: formData.get('email'),
                password: formData.get('password')
            };
            
            console.log('Form data:', data); 
    
            fetch('/home/USER', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Response status:', response.status); 
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data); 
                if (data.message === 'Registration successful') {
                    window.location.href = '/home/login'; 
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
    
        
</body>
</html>