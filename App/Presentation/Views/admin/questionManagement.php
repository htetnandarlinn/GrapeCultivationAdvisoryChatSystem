<div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-2xl font-bold text-gray-800 mb-6">
        Question Management
    </h2>

    <div class="overflow-x-auto">

        <table class="min-w-full border border-gray-200">

            <thead class="bg-green-700 text-white">

            <tr>
                <th class="px-4 py-3 border">ID</th>
                <th class="px-4 py-3 border">Farmer</th>
                <th class="px-4 py-3 border">Category</th>
                <th class="px-4 py-3 border">Title</th>
                <th class="px-4 py-3 border">Status</th>
                <th class="px-4 py-3 border">Expert</th>
                <th class="px-4 py-3 border">Created</th>
                <th class="px-4 py-3 border">Action</th>
            </tr>

            </thead>

            <tbody>

            <?php if (!empty($questions)): ?>

                <?php foreach ($questions as $question): ?>

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3 border">
                            <?= $question['question_id'] ?>
                        </td>

                        <td class="px-4 py-3 border">
                            <?= htmlspecialchars($question['farmer_name']) ?>
                        </td>

                        <td class="px-4 py-3 border">
                            <?= htmlspecialchars($question['category_name']) ?>
                        </td>

                        <td class="px-4 py-3 border">
                            <?= htmlspecialchars($question['title']) ?>
                        </td>

                        <td class="px-4 py-3 border">
                            <?= htmlspecialchars($question['status_name']) ?>
                        </td>

                        <td class="px-4 py-3 border">
                            <?= htmlspecialchars($question['expert_name'] ?? '-') ?>
                        </td>

                        <td class="px-4 py-3 border">
                            <?= htmlspecialchars($question['created_at']) ?>
                        </td>

                        <td class="px-4 py-3 border">

                            <a href="<?= BASE_URL ?>/admin/questions/view?id=<?= $question['question_id'] ?>"
                               class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">

                                View

                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="8"
                        class="text-center py-6 text-gray-500">

                        No questions found.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>