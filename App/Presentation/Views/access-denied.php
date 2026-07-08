<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f6fbf7; color: #1f2937; display: grid; place-items: center; min-height: 100vh; }
        .card { background: white; padding: 2rem 2.5rem; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); max-width: 560px; text-align: center; }
        .badge { display: inline-block; padding: 0.4rem 0.8rem; border-radius: 999px; background: #dcfce7; color: #166534; font-weight: bold; margin-bottom: 1rem; }
        h1 { margin: 0 0 0.75rem; font-size: 1.75rem; }
        p { margin: 0 0 1.25rem; line-height: 1.6; }
        a { display: inline-block; text-decoration: none; padding: 0.7rem 1.2rem; border-radius: 0.75rem; background: #15803d; color: white; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <div class="badge">Access denied</div>
        <h1>You're not allowed here</h1>
        <p><?= htmlspecialchars($message ?? 'You do not have permission to access this page.') ?></p>
        <a href="<?= BASE_URL ?? '/' ?>">Go back home</a>
    </div>
</body>
</html>
