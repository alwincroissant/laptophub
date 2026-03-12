<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email | LaptopHub</title>
    <style>
        body {
            background: #f5f1ea;
            font-family: 'Libre Franklin', Arial, sans-serif;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 620px;
            margin: 40px auto;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.09);
            padding: 0 0 40px 0;
            border: 1px solid #e5e0d8;
        }
        .header {
            padding: 32px 0 12px 0;
            text-align: center;
        }
        .brand {
            font-family: 'Playfair Display', serif;
            font-size: 2.1rem;
            color: #c0392b;
            font-weight: 700;
            letter-spacing: -1px;
        }
        .brand span {
            color: #1a3a5c;
        }
        .welcome {
            font-size: 1.1rem;
            color: #222;
            margin-bottom: 0.5rem;
        }
        .content {
            padding: 0 48px;
            text-align: center;
        }
        .title {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 18px 0 10px 0;
            color: #c0392b;
            font-family: 'Playfair Display', serif;
        }
        .message {
            font-size: 1.05rem;
            margin-bottom: 24px;
            color: #222;
        }
        .button {
            display: inline-block;
            background: linear-gradient(90deg, #c0392b 0%, #1a3a5c 100%);
            color: #fff !important;
            font-weight: 600;
            font-size: 1.08rem;
            padding: 14px 36px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 18px;
            box-shadow: 0 2px 8px rgba(192,57,43,0.08);
            letter-spacing: .03em;
        }
        .footer {
            font-size: 0.95rem;
            color: #888;
            margin-top: 32px;
            text-align: center;
        }
        .fallback-url-block {
            margin: 22px auto 0 auto;
            max-width: 98%;
            background: #f5f1ea;
            border-radius: 8px;
            padding: 14px 16px;
            word-break: break-all;
            font-size: 1.04rem;
            color: #1a3a5c;
            text-align: left;
            border: 1px solid #e0e0e0;
        }
        .url {
            word-break: break-all;
            color: #1a3a5c;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="brand">Laptop<span>Hub</span></div>
            <div class="welcome">Welcome to LaptopHub!</div>
        </div>
        <div class="content">
            <div class="title">Verify Your Email Address</div>
            <div class="message">
                Thank you for registering with <b>LaptopHub</b>.<br>
                To complete your registration, please verify your email address by clicking the button below:
            </div>
            <a href="{{ $actionUrl }}" class="button">Verify Email</a>
            <div class="footer">
                If you did not create an account, no further action is required.<br><br>
            </div>
            <div style="margin-top:10px;text-align:center;">
                <span style="font-size:0.97rem;color:#888;">If you're having trouble clicking the "Verify Email" button, copy and paste the URL below into your web browser:</span>
                <div class="fallback-url-block">{{ $actionUrl }}</div>
            </div>
        </div>
    </div>
</body>
</html>
