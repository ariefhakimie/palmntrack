<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PalmSecure | Authentication Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4a7c59;  /* Palm green */
            --secondary: #8cb369; /* Light palm green */
            --accent: #DAA520;   /* Golden palm fruit */
            --dark: #2d3436;
            --light: #f5f6fa;
            --container-bg: #ffffff; /* White for consistency */
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background-color: var(--light);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(74, 124, 89, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(140, 179, 105, 0.1) 0%, transparent 20%);
            animation: gradientFlow 15s ease infinite;
        }

        @keyframes gradientFlow {
            0% { background-position: 10% 20%, 90% 80%; }
            50% { background-position: 20% 30%, 80% 70%; }
            100% { background-position: 10% 20%, 90% 80%; }
        }

        .login-container {
            width: 100%;
            max-width: 450px; /* Narrower for a focused login experience */
            background: var(--container-bg);
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(0,0,0,0.08);
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.15;
            background-image: 
                radial-gradient(circle at 20% 30%, var(--accent) 0%, transparent 15%),
                radial-gradient(circle at 80% 70%, var(--accent) 0%, transparent 15%);
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: float 4s ease-in-out infinite;
        }

        .logo svg {
            width: 50px;
            height: 50px;
            fill: var(--primary);
        }

        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .login-form {
            padding: 2rem;
        }

        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: var(--dark);
            font-weight: 500;
        }

        .input-field {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }

        .input-field:focus {
            border-color: var(--primary);
            background-color: white;
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 124, 89, 0.15);
        }

        .input-field::placeholder {
            color: #bdbdbd;
            font-weight: 400;
        }

        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 2.75rem;
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .remember-me input {
            margin-right: 0.5rem;
            accent-color: var(--primary);
            width: 1rem;
            height: 1rem;
            cursor: pointer;
        }

        .remember-me label {
            cursor: pointer;
            user-select: none;
        }

        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .forgot-password:hover {
            background: rgba(74, 124, 89, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(74, 124, 89, 0.25);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(74, 124, 89, 0.35);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn:disabled {
            background: var(--success);
            box-shadow: 0 5px 15px rgba(0, 184, 148, 0.3);
            cursor: not-allowed;
        }

        .footer-note {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #6c757d;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(-1deg); }
            50% { transform: translateY(-5px) rotate(1deg); }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .login-container {
                margin: 1rem;
            }

            .login-form {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .subtitle {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="header-pattern"></div>
            <div class="logo">
                <svg viewBox="0 0 100 100">
                    <!-- Palm tree trunk -->
                    <path d="M50 20 L45 80 L55 80 Z" fill="#8B4513" />
                    <!-- Palm leaves -->
                    <path d="M50 20 Q30 10 30 30 Q35 25 50 20" fill="#4a7c59" />
                    <path d="M50 20 Q40 5 45 5 Q50 10 50 20" fill="#4a7c59" />
                    <path d="M50 20 Q60 5 55 5 Q50 10 50 20" fill="#4a7c59" />
                    <path d="M50 20 Q70 10 70 30 Q65 25 50 20" fill="#4a7c59" />
                    <path d="M50 20 Q55 5 60 5 Q50 10 50 20" fill="#4a7c59" />
                    <!-- Palm fruits -->
                    <circle cx="45" cy="50" r="3" fill="#DAA520" />
                    <circle cx="55" cy="55" r="3" fill="#DAA520" />
                    <circle cx="48" cy="60" r="3" fill="#DAA520" />
                </svg>
            </div>
            <h1>Palm & Track</h1>
            <p class="subtitle">Commodity, Machinery, And Equipment Inventory Application</p>
        </div>

        <div class="login-form">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" required autofocus class="input-field" placeholder="Enter your email">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required class="input-field" placeholder="Enter your password">
                    <span class="password-toggle">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                        </svg>
                    </span>
                </div>


                <button type="submit" class="login-btn">Log in</button>

                <div class="footer-note">
                    &copy; {{ date('Y') }} Palm & Track. All rights reserved.
                </div>
            </form>
        </div>
    </div>

    <script>
        // Password toggle functionality
        const passwordToggle = document.querySelector('.password-toggle');
        const passwordInput = document.getElementById('password');

        passwordToggle.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            passwordToggle.innerHTML = type === 'password' ?
                `<svg viewBox="0 0 24 24">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                </svg>` :
                `<svg viewBox="0 0 24 24">
                    <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>
                </svg>`;
        });

        // Form submission handling
        const form = document.querySelector('form');
        const loginBtn = document.querySelector('.login-btn');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            loginBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Authenticating...';
            loginBtn.disabled = true;
            setTimeout(() => {
                form.submit(); // Submit the form after animation
            }, 1500);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>