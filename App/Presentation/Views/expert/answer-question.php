<?php
/** @var App\Domain\ConsultationManagement\Entities\Question $question */
?>

<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-2xl shadow p-8">

        <h2 class="text-2xl font-bold text-green-700 mb-8">
            Answer Farmer Question
        </h2>

        <div class="space-y-6">

            <div>
                <label class="font-semibold text-gray-700">
                    Question Title
                </label>

                <div class="mt-2 p-4 rounded-lg bg-gray-100">
                    <?= htmlspecialchars($question->getTitle()) ?>
                </div>
            </div>

            <div>
                <label class="font-semibold text-gray-700">
                    Description
                </label>

                <div class="mt-2 p-4 rounded-lg bg-gray-100 whitespace-pre-line">
                    <?= htmlspecialchars($question->getDescription()) ?>
                </div>
            </div>

            <?php if ($question->getImage()): ?>

                <div>

                    <label class="font-semibold text-gray-700">
                        Uploaded Image
                    </label>

                    <img
                        src="<?= BASE_URL ?>/uploads/questions/<?= htmlspecialchars($question->getImage()) ?>"
                        class="mt-3 rounded-xl w-96 border">

                </div>

            <?php endif; ?>

            <form
                method="POST"
                action="<?= BASE_URL ?>/expert/questions/answer"
                class="space-y-6">

                <input
                    type="hidden"
                    name="question_id"
                    value="<?= $question->getQuestionId() ?>">

                <div>

                    <label class="block font-semibold mb-2">
                        Your Answer
                    </label>

                    <textarea
                        name="answer"
                        rows="8"
                        required
                        class="w-full border rounded-xl p-4 focus:outline-none focus:ring-2 focus:ring-green-600"
                        placeholder="Write your answer here..."></textarea>

                </div>

                <div class="flex gap-4">

                    <a
                        href="<?= BASE_URL ?>/expert-dashboard"
                        class="px-6 py-3 rounded-xl bg-gray-300 hover:bg-gray-400">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="px-6 py-3 rounded-xl bg-green-700 hover:bg-green-800 text-white">

                        Submit Answer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>