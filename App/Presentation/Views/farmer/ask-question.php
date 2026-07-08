<?php
// resources/views/farmer/ask-question.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors  = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? null;

unset($_SESSION['errors'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grape Cultivation Advisory Chat System - Ask an Expert</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E2ECE4; /* Custom background color from your second screenshot */
        }
    </style>
</head>
<body class="min-h-screen flex text-slate-800 antialiased overflow-x-hidden">

    <div class="md:hidden fixed top-0 left-0 right-0 h-16 bg-white border-b border-emerald-100 flex items-center justify-between px-4 z-50 shadow-sm">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-emerald-700 rounded-lg flex items-center justify-center text-white font-bold text-sm">G</div>
            <span class="font-bold text-emerald-900 text-sm tracking-tight">Grape Cultivation</span>
        </div>
        <button id="mobileMenuBtn" class="text-emerald-800 p-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </div>

    <div class="flex-1 flex flex-col min-w-0 pt-16 md:pt-0">

        <main class="flex-1 p-4 sm:p-6 lg:p-10 flex items-center justify-center">
            
            <div class="w-full max-w-2xl bg-white rounded-3xl p-6 sm:p-10 shadow-xl shadow-emerald-950/5 border border-white/60 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-950/10 transform translate-y-0 opacity-100 animate-fade-in-up">
                
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Ask an Expert</h3>
                    <p class="text-sm text-slate-500 mt-1">Struggling with a crop issue? Submit details directly to our experts.</p>
                </div>

                <?php if ($success): ?>
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 animate-pulse">
                        <i class="fa-solid fa-circle-check text-xl text-emerald-600"></i>
                        <span class="text-sm font-medium"><?= htmlspecialchars($success) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl">
                        <div class="flex items-center gap-3 mb-2">
                            <i class="fa-solid fa-circle-exclamation text-xl text-rose-600"></i>
                            <span class="text-sm font-bold">Please correct the following errors:</span>
                        </div>
                        <ul class="list-disc pl-8 space-y-1 text-xs font-medium">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/farmer-dashboard/ask-question" enctype="multipart/form-data" class="space-y-6">
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Category Selection <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-leaf"></i>
                            </div>
                            <select name="category_id" required class="block w-full pl-10 pr-4 py-3 bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 text-sm transition-all duration-200 appearance-none">
                                <option value="" disabled selected hidden>Select category...</option>
                                <?php if (!empty($categories) && is_array($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['label']) ?></option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No categories available</option>
                                <?php endif; ?>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            What is the main issue? <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-heading"></i>
                            </div>
                            <input type="text" name="title" required placeholder="e.g. White powdery spots..." 
                                   class="block w-full pl-10 pr-4 py-3 bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 text-sm transition-all duration-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Detailed Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" required rows="4" placeholder="Describe symptoms, crop age, and treatments applied..." 
                                  class="block w-full px-4 py-3 bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 text-sm transition-all duration-200 resize-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Image <span class="text-slate-400 font-normal">(Optional)</span>
                        </label>
                        
                        <div id="dropzone" class="border-2 border-dashed border-slate-300 hover:border-emerald-500 bg-slate-50/50 rounded-2xl p-6 transition-all duration-300 relative group flex flex-col items-center justify-center cursor-pointer min-h-[160px]">
                            <input type="file" name="image" id="fileInput" accept="image/png, image/jpeg, image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div id="dropzoneDefault" class="text-center space-y-2 pointer-events-none transition-all duration-200 group-hover:scale-105">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto shadow-xs">
                                    <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                                </div>
                                <div class="text-sm font-semibold text-slate-700">Click to upload or drag & drop</div>
                                <div class="text-xs text-slate-400">Supports PNG, JPG, WEBP (Max 5MB)</div>
                            </div>

                            <div id="dropzonePreview" class="hidden text-center w-full max-w-[200px] space-y-2 pointer-events-none">
                                <img id="previewImg" src="#" alt="Upload Preview" class="w-24 h-24 object-cover mx-auto rounded-xl border border-slate-200 shadow-xs">
                                <p id="fileName" class="text-xs font-medium text-emerald-800 truncate"></p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-800 active:scale-[0.98] text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-300 shadow-lg shadow-emerald-700/20 flex items-center justify-center gap-2 text-sm mt-4">
                        <i class="fa-solid fa-paper-plane"></i> Submit to Expert
                    </button>

                </form>

            </div>
        </main>
    </div>

    <script>
        // Responsive Sidebar Drawer Mechanics
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        function toggleMobileMenu() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarBackdrop.classList.toggle('hidden');
        }

        mobileMenuBtn.addEventListener('click', toggleMobileMenu);
        sidebarBackdrop.addEventListener('click', toggleMobileMenu);

        // Advanced Interactive Image Upload Handler
        const fileInput = document.getElementById('fileInput');
        const dropzone = document.getElementById('dropzone');
        const dropzoneDefault = document.getElementById('dropzoneDefault');
        const dropzonePreview = document.getElementById('dropzonePreview');
        const previewImg = document.getElementById('previewImg');
        const fileNameElement = document.getElementById('fileName');

        // Drag highlights
        ['dragenter', 'dragover'].forEach(eventName => {
            fileInput.addEventListener(eventName, () => dropzone.classList.add('border-emerald-600', 'bg-emerald-50/20'), false);
        });
        ['dragleave', 'drop'].forEach(eventName => {
            fileInput.addEventListener(eventName, () => dropzone.classList.remove('border-emerald-600', 'bg-emerald-50/20'), false);
        });

        // Live Preview Renderer
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                fileNameElement.textContent = file.name;
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    dropzoneDefault.classList.add('hidden');
                    dropzonePreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>