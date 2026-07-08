<section class="min-h-screen flex items-center bg-gradient-to-br from-green-50 to-white px-6">

    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        <!-- LEFT -->
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900 leading-tight mb-4">
                <?= $title ?>
            </h1>

            <p class="text-gray-600 text-lg">
                <?= $subtitle ?>
            </p>
        </div>

        <!-- RIGHT -->
        <div>
            <?= $content ?? '' ?>
        </div>

    </div>

</section>