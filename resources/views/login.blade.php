<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nomination Management System</title>

    <!-- Bootstrap 5.3.7 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #007cc3;
            --dark: #0a1b4d;
            --light-bg: #f3f4f6;
            --card-bg: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            overflow-x: hidden;
            background: #fff;
        }

        .main-wrapper {
            min-height: 100vh;
        }

        /* Left Panel */
        .left-panel {
            background: var(--primary);
            color: #fff;
            position: relative;
            min-height: 100vh;
            padding: 60px;
            display: flex;
            flex-direction: column;
        }

        .brand {
            font-size: 4rem;
            font-weight: 300;
            letter-spacing: -2px;
            margin-bottom: auto;
        }

        .title-section {
            margin-top: 120px;
        }

        .title-section h1 {
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1.15;
        }

        .title-section p {
            font-size: 1.5rem;
            margin-top: 30px;
            opacity: .9;
        }

        .copyright {
            margin-top: auto;
            font-size: 13px;
            opacity: .7;
        }

        /* Decorative Circles */
        .circle {
            position: absolute;
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 50%;
        }

        .circle-lg {
            width: 330px;
            height: 330px;
            right: -120px;
            top: 50%;
            transform: translateY(-50%);
        }

        .circle-sm {
            width: 110px;
            height: 110px;
            right: 80px;
            top: 55%;
        }

        /* Right Panel */
        .right-panel {
            background: #f5f6f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .login-card {
            width: 100%;
            max-width: 560px;
            background: #fff;
            border-radius: 28px;
            padding: 60px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, .06);
        }

        .login-card h2 {
            color: var(--dark);
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .login-card p {
            color: #6b7280;
            font-size: 1.2rem;
            line-height: 1.7;
        }

        .login-btn {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 16px;
            height: 72px;
            font-size: 1.4rem;
            font-weight: 700;
            width: 100%;
            margin-top: 30px;
            transition: .3s;
            box-shadow: 0 8px 20px rgba(0, 124, 195, .25);
        }

        .login-btn:hover {
            transform: translateY(-2px);
            background: #006db0;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 35px 0;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e5e7eb;
        }

        .divider span {
            padding: 0 20px;
            color: #9ca3af;
            font-weight: 700;
            letter-spacing: 4px;
            font-size: 13px;
        }

        .info-box {
            background: #f5f7fa;
            border-radius: 20px;
            padding: 30px;
            display: flex;
            gap: 18px;
        }

        .info-box i {
            font-size: 24px;
            color: #8b95a7;
        }

        .info-box p {
            margin: 0;
            font-size: 1.1rem;
            line-height: 1.8;
        }

        @media(max-width:992px) {

            .left-panel {
                min-height: auto;
                padding: 40px;
            }

            .title-section {
                margin-top: 60px;
            }

            .title-section h1 {
                font-size: 3rem;
            }

            .brand {
                font-size: 3rem;
            }

            .login-card {
                padding: 40px 30px;
            }

            .login-card h2 {
                font-size: 2.2rem;
            }
        }

        @media(max-width:768px) {

            .left-panel {
                display: none;
            }

            .right-panel {
                padding: 20px;
            }

            .login-card {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row main-wrapper">

            <!-- LEFT SIDE -->
            <div class="col-lg-7 left-panel">

                <div class="brand">
                    Infosys
                </div>

                <div class="title-section">
                    <h1>
                        Nomination<br>
                        Management<br>
                        System
                    </h1>

                    <p>Powered by Events COE</p>
                </div>

                <div class="copyright">
                    © 2026 Infosys Ltd. All rights reserved.
                </div>

                <div class="circle circle-lg"></div>
                <div class="circle circle-sm"></div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-5 right-panel">

                <div class="login-card">

                    <h2>Hello User!</h2>

                    <p>
                        Sign in with your Infosys ID to access
                        nominations, events, and your personal dashboard.
                    </p>

                    <form action="{{ route('login') }}" method="post">
                        @csrf

                        <div>
                            <input type="text" name="user_email" id="user_email" value="gaurav@nominator.com">
                            <!-- <input type="text" name="user_email" id="user_email" value="gaurav@unitspoc.com"> -->
                            <!-- <input type="text" name="user_email" id="user_email" value="gaurav@eventops.com"> -->
                            <!-- <input type="text" name="user_email" id="user_email" value="gaurav@admin.com"> -->
                        </div>
                        <div>
                            <input type="text" name="user_password" id="user_password" value="test@123">
                        </div>
                        <button class="login-btn" id="loginBtn">
                            Login with email
                            <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>

                    <div class="divider">
                        <span>SECURE SSO</span>
                    </div>

                    <div class="info-box">

                        <i class="bi bi-shield-check"></i>

                        <p>
                            Your credentials are managed by Infosys IT.
                            Password authentication occurs through your
                            organisation's identity provider to ensure
                            secure access.
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('loginBtn').addEventListener('click', function() {

            // Replace with your SSO URL
            window.location.href = '/login';

        });
    </script>

</body>

</html>