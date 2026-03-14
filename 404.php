<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <!-- CSS (Using Bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .error-card {
            text-align: center;
            max-width: 500px;
            padding: 40px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-top: 6px solid #d11212;
        }

        .error-code {
            font-size: 100px;
            font-weight: 800;
            color: #d11212;
            line-height: 1;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(209, 18, 18, 0.2);
        }

        .btn-home {
            background: #343a40;
            color: white;
            padding: 12px 30px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-home:hover {
            background: #d11212;
            color: white;
            transform: translateY(-3px);
        }

        .icon-broken {
            font-size: 50px;
            color: #6c757d;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="error-card animate__animated animate__fadeInUp">
        <i class="fas fa-plug icon-broken"></i>
        <div class="error-code">404</div>
        <h3 class="fw-bold mb-3">Page Not Found</h3>
        <p class="text-muted mb-4">
            Oops! It seems like the connection is broken. The page you are looking for might have been moved or deleted.
        </p>

        <div class="d-flex justify-content-center gap-3">
            <a href="home" class="btn btn-home rounded-pill"><i class="fas fa-home me-2"></i> Go Home</a>
            <a href="javascript:history.back()" class="btn btn-outline-dark rounded-pill fw-bold">Go Back</a>
        </div>
    </div>

</body>

</html>