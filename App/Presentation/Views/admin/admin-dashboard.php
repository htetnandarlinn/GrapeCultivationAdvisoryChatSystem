<?php
$activities = $activities ?? [];
$farmerCount = $farmerCount ?? 0;
$expertCount = $expertCount ?? 0;

$roleDotColors = [
    'ADMIN' => 'bg-red-500',
    'EXPERT' => 'bg-blue-500',
    'FARMER' => 'bg-green-500',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .stagger-item { opacity: 0; animation: fadeInUp 0.6s ease-out forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen">

<main class="max-w-7xl mx-auto px-4 py-8 sm:px-8">
    <header class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-200 mb-8 animate__animated animate__fadeInDown">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-[#15803D]">Admin Dashboard</p>
                <h1 class="text-3xl font-black text-slate-900">Platform Insights</h1>
            </div>
            <div class="flex gap-3">
                <div class="px-4 py-2 rounded-full bg-slate-50 border border-slate-200 text-sm font-medium">
                    Date: <?= date('M d, Y') ?>
                </div>
            </div>
        </div>
    </header>

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <?php 
        $metrics = [
            ['Farmers', $farmerCount, 'bg-emerald-100 text-[#15803D]', 'delay-1'],
            ['Experts', $expertCount, 'bg-emerald-100 text-[#15803D]', 'delay-2'],
            ['Questions', '356', 'bg-indigo-100 text-indigo-700', 'delay-3'],
            ['Chats', '245', 'bg-amber-100 text-amber-700', 'delay-4'],
            ['Articles', '42', 'bg-blue-100 text-blue-700', 'delay-5'],
        ];
        foreach ($metrics as $m): ?>
            <div class="stagger-item <?= $m[3] ?> bg-white p-6 rounded-[28px] border border-slate-200 shadow-sm hover:shadow-md transition">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400"><?= $m[0] ?></p>
                <p class="text-3xl font-black text-slate-900 mt-2"><?= $m[1] ?></p>
                <div class="w-8 h-1 <?= $m[2] ?> mt-4 rounded-full"></div>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2 bg-white rounded-[32px] p-8 border border-slate-200">
            <h2 class="text-xl font-bold mb-6">Recent Activity</h2>
            <div class="space-y-4">
                <?php if (empty($activities)): ?>
                    <p class="text-sm text-slate-400 text-center py-8">No recent activity.</p>
                <?php else: ?>
                    <?php foreach ($activities as $act): 
                        $role = $act['user_role'] ?? '';
                        $dotClass = $roleDotColors[strtoupper($role)] ?? 'bg-gray-400';
                    ?>
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-[#15803D]/20 transition">
                            <span class="w-3 h-3 rounded-full <?= $dotClass ?>"></span>
                            <p class="text-sm font-medium text-slate-700 flex-grow"><?= htmlspecialchars($act['activity'] ?? '') ?></p>
                            <span class="text-xs text-slate-400 font-mono"><?= date('h:i A', strtotime($act['created_at'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <aside class="bg-slate-900 rounded-[32px] p-8 text-white">
            <h2 class="text-xl font-bold">Quick Access</h2>
            <p class="text-slate-400 text-sm mt-2 mb-6">Manage system wide configurations.</p>
            <button id="actionBtn" onclick="handleAction()" 
                    class="w-full bg-[#15803D] hover:bg-[#166534] py-4 rounded-2xl font-bold transition-all active:scale-95">
                Take Action
            </button>
        </aside>
    </section>
</main>

<script>
    function handleAction() {
        alert('Action triggered!');
    }
</script>
</body>
</html>
