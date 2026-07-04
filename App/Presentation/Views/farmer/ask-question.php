<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'FARMER') {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';

unset($_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ask Question</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">

<div class="max-w-3xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold mb-6">Ask an Expert</h1>

    <?php if ($success): ?>
        <div class="mb-6 bg-emerald-100 text-emerald-800 p-4 rounded">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="mb-6 bg-red-100 text-red-800 p-4 rounded">
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST"
          action="<?= BASE_URL ?>/farmer/ask-question"
          enctype="multipart/form-data"
          class="bg-white p-8 rounded shadow space-y-6">

        <div>
            <label class="font-semibold">Title</label>
            <input name="title" required class="w-full border px-4 py-2 rounded">
        </div>

        <div>
            <label class="font-semibold">Category</label>
            <select name="category_id" required class="w-full border px-4 py-2 rounded">
                <option value="">Select</option>
                <option value="22">Disease</option>
                <option value="23">Fertilizer</option>
                <option value="24">Irrigation</option>
                <option value="25">Pruning</option>
            </select>
        </div>

        <div>
            <label class="font-semibold">Description</label>
            <textarea name="description" rows="4" required class="w-full border px-4 py-2 rounded"></textarea>
        </div>

        <div>
            <label class="font-semibold">Image (optional)</label>
            <input type="file" name="image" accept="image/*">
        </div>

        <button class="w-full bg-emerald-600 text-white py-3 rounded">
            Submit Question
        </button>
    </form>
</div>

</body>
</html>