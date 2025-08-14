<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .success-container {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h1 {
            color: #4CAF50;
        }
        p {
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h1>Registration Successful!</h1>
        <p>Thank you for registering with Sabily.</p>
        <p>You will be redirected to our stocks platform shortly.</p>
    </div>
    
    <script>
        setTimeout(function() {
            window.location.href = "https://stocks.sabily.info";
        }, 5000);
    </script>
</body>
</html>