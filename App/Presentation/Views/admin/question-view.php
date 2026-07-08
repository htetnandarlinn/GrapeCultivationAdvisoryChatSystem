<?php

if (!isset($question)) {
    echo '<div class="p-6">';
    echo '<h2 class="text-2xl font-bold">Question Not Found</h2>';
    echo '</div>';
    return;
}

?>

<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-xl shadow-lg p-8">

        <h1 class="text-3xl font-bold text-green-700 mb-8">
            Question Detail
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-semibold text-gray-500">
                    Question ID
                </label>

                <p class="mt-1 text-lg">
                    <?= $question->getQuestionId() ?>
                </p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500">
                    Farmer ID
                </label>

                <p class="mt-1 text-lg">
                    <?= $question->getFarmerId() ?>
                </p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500">
                    Category ID
                </label>

                <p class="mt-1 text-lg">
                    <?= $question->getCategoryId() ?>
                </p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-500">
                    Status
                </label>

                <p class="mt-1 text-lg">
                    <?= $question->getStatusId() ?>
                </p>
            </div>

        </div>

        <div class="mt-8">

            <label class="block text-sm font-semibold text-gray-500">
                Title
            </label>

            <p class="mt-2 text-lg font-semibold">
                <?= htmlspecialchars($question->getTitle()) ?>
            </p>

        </div>

        <div class="mt-6">

            <label class="block text-sm font-semibold text-gray-500">
                Description
            </label>

            <div class="mt-2 rounded-lg bg-gray-100 p-4">
                <?= nl2br(htmlspecialchars($question->getDescription())) ?>
            </div>

        </div>

        <?php if ($question->getImage()): ?>

            <div class="mt-6">

                <label class="block text-sm font-semibold text-gray-500 mb-3">
                    Uploaded Image
                </label>

                <img
                    src="<?= BASE_URL ?>/uploads/questions/<?= htmlspecialchars($question->getImage()) ?>"
                    class="w-80 rounded-lg border shadow"
                    alt="Question Image">

            </div>

        <?php endif; ?>

        <hr class="my-8">

        <h2 class="text-2xl font-bold text-green-700 mb-5">
            Answer Question
        </h2>

        <form
            action="<?= BASE_URL ?>/expert/questions/answer"
            method="POST"
        >

            <input
                type="hidden"
                name="question_id"
                value="<?= $question->getQuestionId() ?>"
            >

            <label class="block font-semibold mb-2">
                Expert Answer
            </label>

            <textarea
                name="answer"
                rows="8"
                class="w-full border rounded-lg p-4 focus:ring-2 focus:ring-green-500"
                placeholder="Write your answer here..."
                required><?= htmlspecialchars($question->getAnswer() ?? '') ?></textarea>

            <div class="mt-6 flex gap-3">

                <button
                    type="submit"
                    class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-lg">

                    Submit Answer

                </button>

                <a
                    href="<?= BASE_URL ?>/admin/questions"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

                    Back

                </a>

            </div>

        </form>

    </div>

</div>