<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification | LaptopHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            border: none;
        }
        .footer {
            font-size: 0.95rem;
            color: #888;
            margin-top: 32px;
            text-align: center;
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
            <div class="title">Email Verification Required</div>
            <div class="message">
                Thank you for registering!<br>
                Before proceeding, please check your email for a verification link.<br>
                If you did not receive the email, click the button below to request another.
            </div>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                @guest
                    <div style="margin-bottom: 20px; text-align: left;">
                        <input type="email" name="email" required placeholder="Enter your email address" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
                        @error('email')
                            <div style="color: #c0392b; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                @endguest
                <button type="submit" class="button">Resend Verification Email</button>
            </form>
            <div class="footer">
                If you did not create an account, no further action is required.<br><br>
            </div>
        </div>
    </div>
</body>
</html>
