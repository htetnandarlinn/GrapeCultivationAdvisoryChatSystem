<?php /** @var string $content */ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex w-full min-h-screen">

    <?php require __DIR__ . '/sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0">
        
        <?php require __DIR__ . '/topbar.php'; ?>

     <main class="flex-1 w-full p-6 overflow-x-hidden">
        <?= $content ?>
    </main>
    </div>

</div>

</body>
</html>