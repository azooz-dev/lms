<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Platform</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4f46e5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Our Platform!</h1>
    </div>
    <div class="content">
        <h2>Hello {{ $instructor->name }},</h2>
        <p>Thank you for registering as an instructor on our platform. We're excited to have you join our community of educators!</p>
        
        <p>Your account is currently pending approval. Once approved by our admin team, you'll be able to:</p>
        <ul>
            <li>Create and publish courses</li>
            <li>Engage with students</li>
            <li>Track your earnings</li>
            <li>Build your teaching profile</li>
        </ul>
        
        <p>In the meantime, you can start preparing your course content and materials.</p>
        
        <p>If you have any questions, feel free to contact our support team.</p>
        
        <p>Best regards,<br>The Team</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} All rights reserved.</p>
    </div>
</body>
</html>

