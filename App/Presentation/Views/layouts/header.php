<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght=400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS Script -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Centralizing custom config instead of using style.css -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        grapeGreen: '#2e7d32',
                        grapeDark: '#1a1a1a'
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Smooth entrance animation for our registration/login cards */
        @keyframes formReveal {
            from { opacity: 0; transform: scale(0.98) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-form-card {
            animation: formReveal 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="bg-white text-grapeDark antialiased">

<!-- Top navigation bar track -->
<nav class="flex items-center justify-between px-5 py-3.5 md:px-[60px] md:py-3.5 bg-white shadow-[0_1px_6px_rgba(0,0,0,0.07)] sticky top-0 z-[100]">

    <!-- Brand Logo Element Frame -->
    <div class="flex items-center gap-3">
        <div class="w-[60px] h-[60px] flex items-center justify-center shrink-0">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" 
                 alt="Grape Cultivation Advisory Chat System Logo"
                 class="w-full h-full object-contain">
        </div>

        <div class="text-xs font-bold leading-[1.3] text-grapeDark">
            Grape Cultivation<br>
            <span class="block font-normal text-[#555]">Advisory Chat System</span>
        </div>
    </div>

    <!-- Desktop Menu Links (Hidden on mobile view, drops in at 900px/md variant) -->
    <div class="hidden md:flex items-center gap-9">
        <a href="<?= BASE_URL ?>/" class="no-underline text-grapeGreen font-semibold text-sm">Home</a>
        <a href="#" class="no-underline text-[#555] font-medium text-sm hover:text-grapeGreen hover:font-semibold transition-colors">Articles</a>
        <a href="#" class="no-underline text-[#555] font-medium text-sm hover:text-grapeGreen hover:font-semibold transition-colors">About</a>
        <a href="#" class="no-underline text-[#555] font-medium text-sm hover:text-grapeGreen hover:font-semibold transition-colors">Contact</a>
    </div>

    <!-- Right Interaction Control Block -->
    <div class="flex items-center gap-2.5">
        <a href="<?= BASE_URL ?>/login" 
           class="no-underline px-[22px] py-2 border-[1.5px] border-grapeGreen text-grapeGreen bg-transparent rounded-md text-sm font-semibold transition-colors hover:bg-green-50">
            Login
        </a>

        <a href="<?= BASE_URL ?>/register" 
           class="hidden sm:inline-block no-underline px-[22px] py-2 border-0 bg-grapeGreen text-white rounded-md text-sm font-semibold transition-opacity hover:opacity-90">
            Register
        </a>

        <!-- Hamburger Mobile Menu Button Container (Toggles UI drawer below) -->
        <button class="md:hidden flex flex-col justify-center items-center w-8 h-8 gap-1 border border-gray-200 rounded p-1" onclick="toggleMobileNavbar()">
            <span class="w-5 h-0.5 bg-[#555]"></span>
            <span class="w-5 h-0.5 bg-[#555]"></span>
            <span class="w-5 h-0.5 bg-[#555]"></span>
        </button>
    </div>
</nav>

<!-- Responsive Expandable Mobile Drawer Section Dropdown -->
<div id="mobileNavigationDrawer" class="hidden md:hidden bg-white border-b border-gray-100 px-6 py-4 space-y-3.5 shadow-inner">
    <a href="<?= BASE_URL ?>/" class="block no-underline text-grapeGreen font-semibold text-sm">Home</a>
    <a href="#" class="block no-underline text-[#555] font-medium text-sm hover:text-grapeGreen">Articles</a>
    <a href="#" class="block no-underline text-[#555] font-medium text-sm hover:text-grapeGreen">About</a>
    <a href="#" class="block no-underline text-[#555] font-medium text-sm hover:text-grapeGreen">Contact</a>
    <a href="<?= BASE_URL ?>/register" class="block sm:hidden no-underline text-grapeGreen font-semibold text-sm pt-2 border-t border-gray-100">Register Account</a>
</div>

<script>
function toggleMobileNavbar() {
    const drawer = document.getElementById('mobileNavigationDrawer');
    drawer.classList.toggle('hidden');
}
</script>