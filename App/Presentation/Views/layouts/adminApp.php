<?php /** @var string $content */ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>
<body class="bg-slate-50 min-h-screen flex antialiased selection:bg-[#15803D] selection:text-white lg:pl-72">

<div class="flex w-full min-h-screen">

    <?php require __DIR__ . '/adminSidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0">
        
        <?php require __DIR__ . '/adminTopbar.php'; ?>

     <main class="flex-1 w-full p-6 overflow-x-hidden">
        <?= $content ?>
    </main>
    </div>

</div>

</body>
</html>
