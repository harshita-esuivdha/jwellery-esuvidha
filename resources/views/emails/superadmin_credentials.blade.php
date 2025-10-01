<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Superadmin Account Created</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #f7f9fc;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
        p {
            line-height: 1.6;
            margin-bottom: 15px;
        }
        a.button {
            display: inline-block;
            background-color: #007bff;
            color: #fff !important;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 10px;
        }
        .credentials {
            background-color: #f1f5f9;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .credentials p {
            margin: 5px 0;
        }
        .footer {
            font-size: 0.9em;
            color: #666;
            margin-top: 25px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello {{ $companyName }},</h2>

        <p>Your Admin account has been created successfully. You can log in using the credentials below:</p>

        <div class="credentials">
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Password:</strong> {{ $password }}</p>
            <p><strong>Expiry Date:</strong> {{ \Carbon\Carbon::parse($expiryDate)->format('d M, Y') }}</p>
        </div>

        <p>Click the button below to log in to your account:</p>
        <a href="{{ url('https://esuvidhatech.in/esuvidha-billing/login') }}" class="button">Login Now</a>

        <p class="footer">If you did not request this account or have any questions, please contact our support team.</p>

        <p class="footer">Thank you,<br>Fastzeal Team</p>
    </div>
</body>
</html>
