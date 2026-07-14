<?php
$user = $_SESSION['user'] ?? null;
$userRole = $_SESSION['user_role'] ?? '';
$isLoggedIn = $user !== null;
$username = $user['username'] ?? '';
$avatar = $user['avatar'] ?? '';

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = rtrim(BASE_URL, '/');
$route = str_replace($basePath, '', $currentPath);
$route = $route ?: '/';

function navClass(string $targetRoute, string $currentRoute): string {
    if ($targetRoute === $currentRoute) {
        return 'text-grapeGreen font-semibold';
    }
    return 'text-[#555] font-medium hover:text-grapeGreen hover:font-semibold';
}
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
        <a href="<?= BASE_URL ?>/" class="no-underline text-sm transition-colors <?= navClass('/', $route) ?>">Home</a>
        <a href="<?= BASE_URL ?>/articles" class="no-underline text-sm transition-colors <?= navClass('/articles', $route) ?>">Articles</a>
        <?php if ($isLoggedIn && $userRole === 'farmer'): ?>
        <a href="<?= BASE_URL ?>/consultations" class="no-underline text-sm transition-colors <?= navClass('/consultations', $route) ?>">My Consultations</a>
        <a href="<?= BASE_URL ?>/payment/history" class="no-underline text-sm transition-colors <?= str_starts_with($route, '/invoice') ? 'text-grapeGreen font-semibold' : navClass('/payment/history', $route) ?>">Payments</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/about" class="no-underline text-sm transition-colors <?= navClass('/about', $route) ?>">About Us</a>
        <a href="<?= BASE_URL ?>/contact" class="no-underline text-sm transition-colors <?= navClass('/contact', $route) ?>">Contact Us</a>
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
            <div class="relative" id="profileDropdownContainer">
                <button id="profileDropdownTrigger" class="flex items-center gap-2 sm:gap-3 p-1 sm:pr-2 rounded-xl hover:bg-slate-50 transition-colors duration-150 focus:outline-none">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 text-grapeGreen font-bold text-xs sm:text-sm flex items-center justify-center border border-emerald-100/30 uppercase shrink-0">
                        <?php if (!empty($avatar)): ?>
                            <img src="<?= BASE_URL . htmlspecialchars($avatar) ?>" alt="Profile" class="w-full h-full object-cover rounded-xl">
                        <?php else: ?>
                            <?= strtoupper(substr($username, 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <div class="hidden sm:block text-left leading-none">
                        <span class="text-sm font-bold text-slate-800 block flex items-center gap-1">
                            <?= htmlspecialchars($username) ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" id="dropdownArrow">
                                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span class="text-[10px] text-grapeGreen font-bold tracking-wide uppercase mt-0.5 block"><?= htmlspecialchars($userRole) ?></span>
                    </div>
                </button>

                <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100/80 p-2 z-50 opacity-0 scale-95 transition-all duration-200 origin-top-right">
                    <div class="px-4 py-2.5 border-b border-slate-50 text-left">
                        <span class="block text-[11px] font-semibold text-slate-400 tracking-wide uppercase">Signed in as</span>
                        <span class="block text-sm font-bold text-slate-700 truncate"><?= htmlspecialchars($username) ?></span>
                    </div>
                    <div class="py-1">
                        <a href="<?= BASE_URL ?>/<?= $userRole === 'farmer' ? 'my-profile' : 'profile' ?>" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-xl transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            My Profile Details
                        </a>
                        <?php if ($userRole !== 'farmer'): ?>
                        <a href="<?= BASE_URL ?>/dashboard" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-xl transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                            Dashboard
                        </a>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>/logout" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50/50 rounded-xl transition-colors mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout System
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
    <a href="<?= BASE_URL ?>/" class="block no-underline text-sm <?= navClass('/', $route) ?>">Home</a>
    <a href="<?= BASE_URL ?>/articles" class="block no-underline text-sm <?= navClass('/articles', $route) ?>">Articles</a>
    <?php if ($isLoggedIn): ?>
        <?php if ($userRole === 'farmer'): ?>
        <a href="<?= BASE_URL ?>/consultations" class="block no-underline text-sm <?= navClass('/consultations', $route) ?>">My Consultations</a>
        <a href="<?= BASE_URL ?>/payment/history" class="block no-underline text-sm <?= navClass('/payment/history', $route) ?>">Payment History</a>
        <a href="<?= BASE_URL ?>/consultation/create" class="block no-underline text-grapeGreen font-semibold text-sm hover:text-grapeGreen">+ New Consultation</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/<?= $userRole === 'farmer' ? 'my-profile' : 'profile' ?>" class="block no-underline text-sm <?= navClass('/' . ($userRole === 'farmer' ? 'my-profile' : 'profile'), $route) ?>">Profile</a>
        <a href="<?= BASE_URL ?>/logout" class="block no-underline text-red-500 font-medium text-sm hover:text-red-600 pt-2 border-t border-gray-100">Logout</a>
        <div class="pt-2 border-t border-gray-100 space-y-3.5">
            <a href="<?= BASE_URL ?>/about" class="block no-underline text-sm <?= navClass('/about', $route) ?>">About Us</a>
            <a href="<?= BASE_URL ?>/contact" class="block no-underline text-sm <?= navClass('/contact', $route) ?>">Contact Us</a>
        </div>
    <?php else: ?>
        <a href="<?= BASE_URL ?>/register" class="block sm:hidden no-underline text-grapeGreen font-semibold text-sm pt-2 border-t border-gray-100">Register Account</a>
        <div class="pt-2 border-t border-gray-100 space-y-3.5">
            <a href="<?= BASE_URL ?>/about" class="block no-underline text-sm <?= navClass('/about', $route) ?>">About Us</a>
            <a href="<?= BASE_URL ?>/contact" class="block no-underline text-sm <?= navClass('/contact', $route) ?>">Contact Us</a>
        </div>
    <?php endif; ?>
</div>

<script>
function toggleMobileNavbar() {
    const drawer = document.getElementById('mobileNavigationDrawer');
    drawer.classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('profileDropdownContainer');
    const trigger = document.getElementById('profileDropdownTrigger');
    const menu = document.getElementById('profileDropdownMenu');
    const arrow = document.getElementById('dropdownArrow');

    if (!trigger || !menu) return;

    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        const isHidden = menu.classList.contains('hidden');
        if (isHidden) {
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('opacity-0', 'scale-95');
                menu.classList.add('opacity-100', 'scale-100');
                if (arrow) arrow.classList.add('rotate-180');
            }, 10);
        } else {
            closeMenu();
        }
    });

    document.addEventListener('click', (e) => {
        if (container && !container.contains(e.target)) {
            closeMenu();
        }
    });

    function closeMenu() {
        menu.classList.remove('opacity-100', 'scale-100');
        menu.classList.add('opacity-0', 'scale-95');
        if (arrow) arrow.classList.remove('rotate-180');
        setTimeout(() => {
            menu.classList.add('hidden');
        }, 200);
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
                    e.preventDefault();
                    const id = this.dataset.id;
                    const href = this.href;
                    if (id) {
                        fetch(BASE + '/notifications/mark-read', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'id=' + id,
                            keepalive: true,
                        }).finally(() => {
                            window.location.href = href;
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
