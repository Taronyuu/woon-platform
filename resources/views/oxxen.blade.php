<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oxxen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #09090b;
            color: #fafafa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .glow {
            position: fixed;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        main {
            text-align: center;
            position: relative;
            z-index: 1;
            max-width: 500px;
            width: 100%;
        }

        h1 {
            font-size: 48px;
            font-weight: 600;
            letter-spacing: -2px;
            margin-bottom: 16px;
        }

        .slogan {
            color: #71717a;
            font-size: 18px;
            font-weight: 300;
            margin-bottom: 48px;
        }

        .signup-box {
            display: flex;
            background: #18181b;
            border: 1px solid #27272a;
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .signup-box:focus-within {
            border-color: #6366f1;
        }

        .signup-box input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            padding: 16px 20px;
            font-size: 15px;
            color: #fafafa;
            font-family: inherit;
        }

        .signup-box input::placeholder {
            color: #52525b;
        }

        .signup-box button {
            background: #6366f1;
            color: white;
            border: none;
            padding: 16px 24px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.2s;
        }

        .signup-box button:hover {
            background: #4f46e5;
        }

        .success {
            color: #22c55e;
            font-size: 15px;
        }

        .error {
            color: #ef4444;
            font-size: 13px;
            margin-top: 12px;
        }

        .side-text {
            position: fixed;
            font-size: 400px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.04);
            letter-spacing: -6px;
            pointer-events: none;
            user-select: none;
            writing-mode: vertical-rl;
        }

        .side-text.left {
            left: 40px;
            top: 50%;
            transform: translateY(-50%) rotate(180deg);
        }

        .side-text.right {
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
        }

        @media (max-width: 768px) {
            .side-text {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="glow"></div>
    <span class="side-text left">SEEK</span>
    <span class="side-text right">FIND</span>
    <main>
        <h1>Oxxen</h1>
        <p class="slogan">Your next home hides in plain sight.</p>
        @if(session('success'))
            <p class="success">You're on the list.</p>
        @else
            <form class="signup-box" action="{{ route('notify.store') }}" method="POST">
                @csrf
                <input type="email" name="email" placeholder="Enter your email..." required>
                <button type="submit">Notify me</button>
            </form>
            @error('email')
                <p class="error">{{ $message === 'The email has already been taken.' ? 'Already on the list.' : $message }}</p>
            @enderror
        @endif
    </main>
</body>
</html>
