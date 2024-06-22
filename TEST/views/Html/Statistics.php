<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
    <link rel="stylesheet" href="/CuPO/WEB/TEST/views/Style-CSS/styles-statistics2.css">
</head>
<body>
    <header class="header" id="header">
        <div class="topnav">
            <a href="/home/homeL">Home</a>
            <a href="/home/shop">Shop</a>
        </div>
    </header>
    <main class="container mt-5">
        <h1>Statistics</h1>
        <div id="client-status" class="client-status">
            <h2>Client Status</h2>
            <div class="client-status-box">
                <p id="bmi-category"></p>
                <p id="weight-suggestion"></p>
            </div>
        </div>
    </main>

    <script>
        function getColorForBmiCategory(category) {
            switch(category.toLowerCase()) {
                case 'obese':
                    return 'red';
                case 'overweight':
                    return 'orange';
                case 'underweight':
                    return 'blue';
                case 'normal weight':
                    return 'green';
                default:
                    return 'black';
            }
        }

        function calculateWeightSuggestion(height, weight, bmiCategory) {
            const heightInMeters = height / 100; // Assuming height is in cm
            const minNormalWeight = 18.5 * heightInMeters * heightInMeters;
            const maxNormalWeight = 24.9 * heightInMeters * heightInMeters;

            let suggestion = '';

            if (bmiCategory.toLowerCase() === 'underweight') {
                const weightToGain = minNormalWeight - weight;
                suggestion = `You need to gain at least ${weightToGain.toFixed(1)} kg to reach a normal weight.`;
            } else if (bmiCategory.toLowerCase() === 'overweight' || bmiCategory.toLowerCase() === 'obese') {
                const weightToLose = weight - maxNormalWeight;
                suggestion = `You need to lose at least ${weightToLose.toFixed(1)} kg to reach a normal weight.`;
            } else if (bmiCategory.toLowerCase() === 'normal weight') {
                suggestion = 'You have a normal weight. Keep maintaining it!';
            }

            return suggestion;
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetch('/home/user-con')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        const stats = data.data;
                        const bmiCategory = stats.bmi_category;
                        const color = getColorForBmiCategory(bmiCategory);
                        const suggestion = calculateWeightSuggestion(stats.height_cm, stats.weight_kg, bmiCategory);

                        document.getElementById('bmi-category').innerHTML = `
                            <span style="color: ${color};">${bmiCategory}</span>
                        `;
                        document.getElementById('weight-suggestion').innerHTML = suggestion;
                    } else {
                        console.error('Error message from server:', data.message);
                        document.getElementById('statistics').innerHTML = `
                            <p>${data.message}</p>
                        `;
                        document.getElementById('bmi-category').innerHTML = `
                            <p>${data.message}</p>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    document.getElementById('statistics').innerHTML = `
                        <p>Failed to load statistics. Please try again later.</p>
                    `;
                    document.getElementById('bmi-category').innerHTML = `
                        <p>Failed to load BMI category. Please try again later.</p>
                    `;
                });
        });
    </script>
</body>
</html>
