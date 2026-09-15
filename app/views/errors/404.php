<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        body { margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8f9fa; color: #333; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .container { text-align: center; padding: 2rem; }
        h1 { font-size: 6rem; margin: 0; color: #dc3545; font-weight: 800; }
        h2 { font-size: 1.5rem; margin: 0.5rem 0 1rem; color: #666; }
        p { color: #888; margin-bottom: 2rem; }
        a { display: inline-block; padding: 0.75rem 2rem; background: #0d6efd; color: #fff; text-decoration: none; border-radius: 5px; font-weight: 600; }
        a:hover { background: #0b5ed7; }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you are looking for does not exist or has been moved.</p>
        <a href="<?php echo defined('BASE_URL') ? BASE_URL : '/'; ?>">Go Home</a>
    </div>
</body>
</html>
