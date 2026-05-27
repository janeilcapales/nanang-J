<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Restaurant Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f0a04;
        }

        /* Left panel - decorative */
        .login-left {
            flex: 1;
            background:
                radial-gradient(ellipse at 30% 70%, rgba(249,115,22,0.25) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 20%, rgba(245,158,11,0.15) 0%, transparent 50%),
                #1a1008;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 1px 1px, rgba(249,115,22,0.08) 1px, transparent 0);
            background-size: 32px 32px;
        }

        .brand-logo {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #F97316, #F59E0B);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 36px;
            margin-bottom: 24px;
            position: relative;
            box-shadow: 0 8px 32px rgba(249,115,22,0.4);
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            color: #fff;
            text-align: center;
            line-height: 1.1;
        }

        .brand-sub {
            color: #a89070;
            font-size: 13px;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 10px;
            text-align: center;
        }

        .divider-line {
            width: 48px;
            height: 2px;
            background: linear-gradient(90deg, #F97316, #F59E0B);
            margin: 24px auto;
            border-radius: 2px;
        }

        .tagline {
            color: #7a6248;
            font-size: 14px;
            text-align: center;
            max-width: 280px;
            line-height: 1.7;
        }

        /* Right panel - form */
        .login-right {
            width: 480px;
            background: #faf8f5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
        }

        .login-box {
            width: 100%;
        }

        .login-heading {
            font-family: 'Playfair Display', serif;
            font-size: 30px;
            color: #1a1008;
            margin-bottom: 6px;
        }

        .login-sub {
            color: #888;
            font-size: 14px;
            margin-bottom: 36px;
        }

        .field-group { margin-bottom: 20px; }

        .field-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #bbb;
            font-size: 15px;
        }

        .field-input {
            width: 100%;
            padding: 13px 14px 13px 42px;
            border: 1.5px solid #e0d5c8;
            border-radius: 11px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            background: white;
            color: #1a1008;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .field-input:focus {
            outline: none;
            border-color: #F97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.12);
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 28px;
        }

        .remember-row input[type="checkbox"] {
            accent-color: #F97316;
            width: 16px; height: 16px;
        }

        .remember-row label {
            font-size: 13px;
            color: #666;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #F97316, #EA580C);
            color: white;
            border: none;
            border-radius: 11px;
            font-size: 15px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.5px;
            transition: all 0.2s;
            box-shadow: 0 4px 16px rgba(249,115,22,0.3);
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #EA580C, #C2410C);
            box-shadow: 0 6px 24px rgba(249,115,22,0.45);
            transform: translateY(-1px);
        }

        .alert-error {
            background: #FFF1F2;
            border: 1px solid #FECDD3;
            border-radius: 10px;
            padding: 12px 16px;
            color: #E11D48;
            font-size: 13px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-right { width: 100%; padding: 40px 32px; }
        }
    </style>
</head>
<body>

<div class="login-left">
    <div class="brand-logo">🍽️</div>
    <div class="brand-name">La Mesa</div>
    <div class="brand-sub">Restaurant System</div>
    <div class="divider-line"></div>
    <p class="tagline">Manage your restaurant with elegance — reservations, dishes, and transactions in one place.</p>
</div>

<div class="login-right">
    <div class="login-box">
        <h2 class="login-heading">Welcome back</h2>
        <p class="login-sub">Sign in to your account to continue</p>

        @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="field-group">
                <label class="field-label">Username / Email</label>
                <div class="field-wrap">
                    <span class="field-icon">✉️</span>
                    <input type="text" name="email" class="field-input"
                           placeholder="Enter your email" value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="field-group">
                <label class="field-label">Password</label>
                <div class="field-wrap">
                    <span class="field-icon">🔒</span>
                    <input type="password" name="password" class="field-input"
                           placeholder="Enter your password" required>
                </div>
            </div>

            <div class="remember-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Keep me signed in</label>
            </div>

            <button type="submit" class="login-btn">Sign In →</button>
        </form>
    </div>
</div>

</body>
</html>