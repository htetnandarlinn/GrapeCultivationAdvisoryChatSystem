<?php
$baseUrl = 'http://localhost/GrapeCultivationAdvisoryChatSystem/public';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verified Successfully</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        @keyframes customPulse {
            0%, 100% { transform: scale(1); opacity: 0.4; }
            50% { transform: scale(1.1); opacity: 0.1; }
        }
        .pulse-ring {
            animation: customPulse 3s infinite ease-in-out;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col antialiased">

    <div class="flex-1 flex items-center justify-center p-6 w-full">
        
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 max-w-md w-full p-8 text-center border border-slate-100/80 animate__animated animate__fadeInUp animate__fast">
            
            <div class="relative flex items-center justify-center w-24 h-24 mx-auto mb-6">
                <div class="absolute inset-0 bg-green-100 rounded-full pulse-ring"></div>
                <div class="absolute w-20 h-20 bg-green-50 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-green-700 animate__animated animate__jackInTheBox animate__delay-1s" 
                         fill="none" 
                         stroke="currentColor" 
                         viewBox="0 0 24 24" 
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-slate-800 tracking-tight mb-2 animate__animated animate__fadeIn animate__delay-1s">
                Email Verified Successfully!
            </h2>
            
            <p class="text-slate-500 text-sm sm:text-base mb-8 leading-relaxed animate__animated animate__fadeIn animate__delay-1s">
                Your account has been activated. You can now access all the features of the Grape Cultivation Advisory Chat System.
            </p>

            <div class="animate__animated animate__fadeInUp animate__delay-1s">
                <a href="<?= $baseUrl ?>/login" 
                   class="inline-flex items-center justify-center w-full px-6 py-3.5 text-sm font-semibold text-white bg-green-700 hover:bg-green-800 active:bg-green-900 rounded-xl transition-all duration-200 shadow-md shadow-green-700/10 hover:shadow-green-700/20 hover:-translate-y-0.5 active:translate-y-0">
                    Login to Your Account
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>

        </div>
    </div>

</body>
</html>