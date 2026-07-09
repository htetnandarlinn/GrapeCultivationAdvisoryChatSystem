<?php
$user = $_SESSION['user'] ?? null;
$userRole = $_SESSION['user_role'] ?? '';
$isLoggedIn = $user !== null;
$username = $user['username'] ?? '';
$avatar = $user['avatar'] ?? '';
?>
<nav class="flex items-center justify-between px-5 py-3.5 md:px-[60px] md:py-3.5 bg-white shadow-[0_1px_6px_rgba(0,0,0,0.07)] sticky top-0 z-[100]">
    <div class="flex items-center gap-3">
        <div class="w-[60px] h-[60px] flex items-center justify-center shrink-0">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Grape Cultivation Advisory Chat System Logo" class="w-full h-full object-contain">
        </div>
        <div class="text-xs font-bold leading-[1.3] text-grapeDark">
            Grape Cultivation<br>
            <span class="block font-normal text-[#555]">Advisory Chat System</span>
        </div>
    </div>
    <div class="hidden md:flex items-center gap-9">
        <a href="<?= BASE_URL ?>/" class="no-underline text-grapeGreen font-semibold text-sm">Home</a>
        <a href="<?= BASE_URL ?>/articles" class="no-underline text-[#555] font-medium text-sm hover:text-grapeGreen hover:font-semibold transition-colors">Articles</a>
        <?php if ($isLoggedIn && $userRole === 'farmer'): ?>
        <a href="<?= BASE_URL ?>/consultations" class="no-underline text-[#555] font-medium text-sm hover:text-grapeGreen hover:font-semibold transition-colors">My Consultations</a>
        <?php endif; ?>
        <a href="#" class="no-underline text-[#555] font-medium text-sm hover:text-grapeGreen hover:font-semibold transition-colors">About</a>
        <a href="#" class="no-underline text-[#555] font-medium text-sm hover:text-grapeGreen hover:font-semibold transition-colors">Contact</a>
    </div>
    <div class="flex items-center gap-2.5">
        <?php if ($isLoggedIn): ?>
            <?php if ($userRole === 'expert'): ?>
                <a href="<?= BASE_URL ?>/dashboard" class="no-underline p-2 text-[#555] hover:text-grapeGreen transition-colors" title="Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                </a>
            <?php endif; ?>
            <div class="relative" id="notifContainer">
                <button id="notifBell" class="relative p-2 text-[#555] hover:text-grapeGreen transition-colors" title="Notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span id="notifBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-1 hidden"></span>
                </button>
                <div id="notifDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100/80 z-50 opacity-0 scale-95 transition-all duration-200 origin-top-right overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-50 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wide">Notifications</span>
                        <button id="markAllReadBtn" class="text-[10px] font-semibold text-emerald-600 hover:text-emerald-700 hidden">Mark all read</button>
                    </div>
                    <div id="notifList" class="max-h-80 overflow-y-auto">
                        <div class="px-4 py-6 text-center text-xs text-slate-400">Loading...</div>
                    </div>
                </div>
            </div>
            <div class="relative" id="userMenu">
                <button onclick="toggleUserDropdown()" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-200">
                    <div class="w-7 h-7 rounded-full bg-grapeGreen text-white flex items-center justify-center text-xs font-bold">
                        <?= strtoupper(substr($username, 0, 1)) ?>
                    </div>
                    <span class="text-sm font-semibold text-slate-700 hidden sm:inline"><?= htmlspecialchars($username) ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50">
                    <div class="px-4 py-2 border-b border-slate-50">
                        <p class="text-xs font-bold text-slate-900"><?= htmlspecialchars($username) ?></p>
                        <p class="text-[10px] text-slate-400 capitalize"><?= htmlspecialchars($userRole) ?></p>
                    </div>
                    <a href="<?= BASE_URL ?>/profile" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Profile
                    </a>

                    <?php if ($userRole !== 'admin'): ?>
                        <a href="<?= BASE_URL ?>/dashboard" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                            Dashboard
                        </a>
                    <?php endif; ?>
                    <div class="border-t border-slate-50 mt-1 pt-1">
                        <a href="<?= BASE_URL ?>/logout" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-red-500 hover:bg-red-50 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php
            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            if (defined('BASE_URL') && BASE_URL !== '' && str_starts_with($currentPath, BASE_URL)) {
                $currentPath = substr($currentPath, strlen(BASE_URL)) ?: '/';
            }
            $hideLogin = preg_match('#^/(login|register|forgot-password|reset-password)(\?|$)#', $currentPath);
            ?>
            <?php if (!$hideLogin): ?>
                <a href="<?= BASE_URL ?>/login" class="no-underline px-[22px] py-2 border-[1.5px] border-grapeGreen text-grapeGreen bg-transparent rounded-md text-sm font-semibold transition-colors hover:bg-green-50">Login</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/register" class="hidden sm:inline-block no-underline px-[22px] py-2 border-0 bg-grapeGreen text-white rounded-md text-sm font-semibold transition-opacity hover:opacity-90">Register</a>
        <?php endif; ?>
        <button class="md:hidden flex flex-col justify-center items-center w-8 h-8 gap-1 border border-gray-200 rounded p-1" onclick="toggleMobileNavbar()">
            <span class="w-5 h-0.5 bg-[#555]"></span>
            <span class="w-5 h-0.5 bg-[#555]"></span>
            <span class="w-5 h-0.5 bg-[#555]"></span>
        </button>
    </div>
</nav>

<div id="mobileNavigationDrawer" class="hidden md:hidden bg-white border-b border-gray-100 px-6 py-4 space-y-3.5 shadow-inner">
    <a href="<?= BASE_URL ?>/" class="block no-underline text-grapeGreen font-semibold text-sm">Home</a>
    <a href="<?= BASE_URL ?>/articles" class="block no-underline text-[#555] font-medium text-sm hover:text-grapeGreen">Articles</a>
    <?php if ($isLoggedIn): ?>
        <?php if ($userRole === 'farmer'): ?>
        <a href="<?= BASE_URL ?>/consultations" class="block no-underline text-[#555] font-medium text-sm hover:text-grapeGreen">My Consultations</a>
        <a href="<?= BASE_URL ?>/consultation/create" class="block no-underline text-grapeGreen font-semibold text-sm hover:text-grapeGreen">+ New Consultation</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/profile" class="block no-underline text-[#555] font-medium text-sm hover:text-grapeGreen">Profile</a>
        <a href="<?= BASE_URL ?>/logout" class="block no-underline text-red-500 font-medium text-sm hover:text-red-600 pt-2 border-t border-gray-100">Logout</a>
    <?php else: ?>
        <a href="#" class="block no-underline text-[#555] font-medium text-sm hover:text-grapeGreen">About</a>
        <a href="#" class="block no-underline text-[#555] font-medium text-sm hover:text-grapeGreen">Contact</a>
        <a href="<?= BASE_URL ?>/register" class="block sm:hidden no-underline text-grapeGreen font-semibold text-sm pt-2 border-t border-gray-100">Register Account</a>
    <?php endif; ?>
</div>

<script>
function toggleMobileNavbar() {
    const drawer = document.getElementById('mobileNavigationDrawer');
    drawer.classList.toggle('hidden');
}

function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    const menu = document.getElementById('userMenu');
    const dropdown = document.getElementById('userDropdown');
    if (menu && dropdown && !menu.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});

// --- Notification System ---
const notifBell = document.getElementById('notifBell');
const notifBadge = document.getElementById('notifBadge');
const notifDropdown = document.getElementById('notifDropdown');
const notifList = document.getElementById('notifList');
const markAllBtn = document.getElementById('markAllReadBtn');
const BASE = '<?= BASE_URL ?>';

function fetchNotifCount() {
    fetch(BASE + '/notifications/unread-count')
        .then(r => r.json())
        .then(d => {
            if (d.count > 0) {
                notifBadge.textContent = d.count;
                notifBadge.classList.remove('hidden');
            } else {
                notifBadge.classList.add('hidden');
            }
        })
        .catch(() => {});
}

function fetchNotifList() {
    notifList.innerHTML = '<div class="px-4 py-6 text-center text-xs text-slate-400">Loading...</div>';
    fetch(BASE + '/notifications/list')
        .then(r => r.json())
        .then(notifs => {
            if (!notifs.length) {
                notifList.innerHTML = '<div class="px-4 py-6 text-center text-xs text-slate-400">No notifications yet</div>';
                markAllBtn.classList.add('hidden');
                return;
            }
            markAllBtn.classList.remove('hidden');
            notifList.innerHTML = notifs.map(n => `
                <a href="${BASE}${n.link || '#'}" data-id="${n.id}" class="notif-item flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition border-b border-slate-50 last:border-0 ${n.is_read ? '' : 'bg-emerald-50/40'}">
                    <div class="w-2 h-2 rounded-full mt-1.5 shrink-0 ${n.is_read ? 'bg-slate-200' : 'bg-emerald-500'}"></div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-slate-700 leading-relaxed ${n.is_read ? 'font-normal' : 'font-semibold'}">${escapeHtml(n.message)}</p>
                        <span class="text-[10px] text-slate-400 mt-0.5 block">${n.time_ago}</span>
                    </div>
                </a>
            `).join('');
            // Mark individual notification as read on click
            notifList.querySelectorAll('.notif-item').forEach(el => {
                el.addEventListener('click', function(e) {
                    const id = this.dataset.id;
                    if (id) {
                        fetch(BASE + '/notifications/mark-read', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'id=' + id,
                            keepalive: true,
                        });
                        this.classList.remove('bg-emerald-50/40');
                        this.querySelector('.w-2')?.classList.remove('bg-emerald-500');
                        this.querySelector('.w-2')?.classList.add('bg-slate-200');
                        this.querySelector('p')?.classList.remove('font-semibold');
                        // Decrement badge
                        const cur = parseInt(notifBadge.textContent, 10);
                        if (cur > 1) {
                            notifBadge.textContent = cur - 1;
                        } else {
                            notifBadge.classList.add('hidden');
                        }
                    }
                });
            });
        })
        .catch(() => {
            notifList.innerHTML = '<div class="px-4 py-6 text-center text-xs text-red-400">Failed to load</div>';
        });
}

function escapeHtml(text) {
    const d = document.createElement('div');
    d.textContent = text;
    return d.innerHTML;
}

if (notifBell) {
    notifBell.addEventListener('click', (e) => {
        e.stopPropagation();
        const isHidden = notifDropdown.classList.contains('hidden');
        if (isHidden) {
            fetchNotifList();
            notifDropdown.classList.remove('hidden');
            setTimeout(() => {
                notifDropdown.classList.remove('opacity-0', 'scale-95');
                notifDropdown.classList.add('opacity-100', 'scale-100');
            }, 10);
        } else {
            notifDropdown.classList.add('opacity-0', 'scale-95');
            notifDropdown.classList.remove('opacity-100', 'scale-100');
            setTimeout(() => notifDropdown.classList.add('hidden'), 200);
        }
    });

    document.addEventListener('click', (e) => {
        const container = document.getElementById('notifContainer');
        if (container && !container.contains(e.target)) {
            notifDropdown.classList.add('opacity-0', 'scale-95');
            notifDropdown.classList.remove('opacity-100', 'scale-100');
            setTimeout(() => notifDropdown.classList.add('hidden'), 200);
        }
    });

    if (markAllBtn) {
        markAllBtn.addEventListener('click', () => {
            fetch(BASE + '/notifications/mark-all-read', { method: 'POST' })
                .then(() => {
                    fetchNotifCount();
                    notifList.querySelectorAll('.bg-emerald-50\\/40').forEach(el => {
                        el.classList.remove('bg-emerald-50/40');
                        el.querySelector('.w-2')?.classList.remove('bg-emerald-500');
                        el.querySelector('.w-2')?.classList.add('bg-slate-200');
                        el.querySelector('p')?.classList.remove('font-semibold');
                    });
                    markAllBtn.classList.add('hidden');
                });
        });
    }
}

if (typeof BASE !== 'undefined') {
    fetchNotifCount();
    setInterval(fetchNotifCount, 10000);
}
</script>
