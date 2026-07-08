<?php /** @var string $content */ ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expert Dashboard</title>

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body class="bg-gray-100">

<?php require __DIR__.'/expertSidebar.php'; ?>

<div class="md:ml-72 min-h-screen">

    <?php require __DIR__.'/expertTopbar.php'; ?>

    <main class="pt-20 p-6">

        <?= $content ?>

    </main>

</div>

</body>
</html>