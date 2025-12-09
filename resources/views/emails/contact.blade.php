<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #3b82f6;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
        }
        .info-row {
            margin: 15px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #3b82f6;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            display: inline-block;
            width: 100px;
        }
        .message-box {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Message from Contact Form</h2>
        
        <div class="info-row">
            <span class="info-label">Name:</span>
            <span>{{ $name }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span>{{ $email }}</span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Subject:</span>
            <span>{{ $subject }}</span>
        </div>
        
        <div class="message-box">
            <strong>Message:</strong><br>
            <p style="white-space: pre-wrap;">{{ $messageContent }}</p>
        </div>
        
        <div class="footer">
            <p>This email was sent from the portfolio website of Muzaki Abdullah Irsyad</p>
            <p>Please reply directly to: {{ $email }}</p>
        </div>
    </div>
</body>
</html>

