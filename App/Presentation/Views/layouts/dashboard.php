<?php
/** @var string $content */

$role = $_SESSION['user_role'] ?? '';

$titles = [
    'admin'  => 'Dashboard',
    'farmer' => 'Farmer Dashboard',
    'expert' => 'Expert Dashboard',
];
$pageTitle = $titles[$role] ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>
<body class="bg-slate-50 min-h-screen antialiased selection:bg-[#15803D] selection:text-white">

<?php require __DIR__ . '/sidebar.php'; ?>

<div class="md:ml-72 min-h-screen flex flex-col">

    <?php require __DIR__ . '/topbar.php'; ?>

    <main class="flex-1 w-full p-6 overflow-x-hidden pt-20">
        <?= $content ?>
    </main>

</div>

</body>
</html>
