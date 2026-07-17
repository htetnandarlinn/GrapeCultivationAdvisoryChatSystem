<?php
$currentUser = $_SESSION['user'] ?? [
    'username' => 'User',
    'role' => 'User',
    'avatar' => null,
];
// $initials = strtoupper(substr($currentUser['username'], 0, 2));
// $greeting = ['title' => 'Welcome back, ' . htmlspecialchars($currentUser['username']) . '! 👏', 'sub' => "Here's what's happening in the system today."];
?>
<header class="fixed top-0 right-0 left-0 md:left-72 h-16 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-4 sm:px-8 shadow-sm z-40">
    <div class="flex items-center gap-4">
        <!-- Left Section -->
        <div class="flex items-center gap-2 sm:gap-3">

            <!-- Sidebar Toggle Icon (mobile only) -->
            <button id="mobileSidebarToggle"
                class="md:hidden p-2 rounded-xl text-[#15803D] hover:bg-[#15803D]/10 hover:text-[#15803D] transition-all flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-[#15803D]/30"
                aria-label="Toggle sidebar" aria-expanded="false" aria-controls="app-sidebar">
                <span class="text-xl leading-none">☰</span>
            </button>

            <h2 class="text-sm sm:text-base lg:text-lg font-black text-[#0b1325] tracking-tight leading-none whitespace-nowrap">
                 Dashboard
            </h2>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-4">
        <div class="relative" id="notifContainer">
            <button id="notifBell" class="relative p-2 rounded-xl text-slate-500 hover:bg-slate-50 transition active:scale-95">
                <span id="notifDot" class="absolute top-2 right-2 w-1.5 h-1.5 bg-[#15803D] rounded-full ring-2 ring-white hidden"></span>
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
                <div class="border-t border-slate-50 px-4 py-2 text-center">
                    <a href="<?= BASE_URL ?>/notifications" id="viewAllNotif" class="text-[10px] font-semibold text-slate-400 hover:text-slate-600">View all notifications</a>
                </div>
            </div>
        </div>

        <div class="relative" id="profileDropdownContainer">
            <button id="profileDropdownTrigger" class="flex items-center gap-2 sm:gap-3 p-1 sm:pr-2 rounded-xl hover:bg-slate-50 transition-colors duration-150 focus:outline-none">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 text-[#15803D] font-bold text-xs sm:text-sm flex items-center justify-center border border-emerald-100/30 uppercase shrink-0">
                    <?php if (!empty($currentUser['avatar'])): ?>
                        <img src="<?= BASE_URL . htmlspecialchars($currentUser['avatar']) ?>" alt="Profile" class="w-full h-full object-cover rounded-xl">
                    <?php else: ?>
                        
                    <?php endif; ?>
                </div>
                <div class="hidden sm:block text-left leading-none">
                    <span class="text-sm font-bold text-slate-800 block flex items-center gap-1">
                        <?= htmlspecialchars($currentUser['username']) ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" id="dropdownArrow">
                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span class="text-[10px] text-[#15803D] font-bold tracking-wide uppercase mt-0.5 block"><?= htmlspecialchars($currentUser['role']) ?></span>
                </div>
            </button>

            <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100/80 p-2 z-50 opacity-0 scale-95 transition-all duration-200 origin-top-right">
                <div class="px-4 py-2.5 border-b border-slate-50 text-left">
                    <span class="block text-[11px] font-semibold text-slate-400 tracking-wide uppercase">Signed in as</span>
                    <span class="block text-sm font-bold text-slate-700 truncate"><?= htmlspecialchars($currentUser['username']) ?></span>
                </div>
                <div class="py-1">
                    <a href="<?= BASE_URL ?>/profile" class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 rounded-xl transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        My Profile Details
                    </a>
                    <a href="<?= BASE_URL ?>/logout" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50/50 rounded-xl transition-colors mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout System
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
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
                <a href="${BASE}${n.link || '/notifications'}" data-id="${n.id}" class="notif-item flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition border-b border-slate-50 last:border-0 ${n.is_read ? '' : 'bg-emerald-50/40'}">
                    <div class="w-2 h-2 rounded-full mt-1.5 shrink-0 ${n.is_read ? 'bg-slate-200' : 'bg-emerald-500'}"></div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-slate-700 leading-relaxed ${n.is_read ? 'font-normal' : 'font-semibold'}">${escapeHtml(n.message)}</p>
                        <span class="text-[10px] text-slate-400 mt-0.5 block">${n.time_ago}</span>
                    </div>
                </a>
            `).join('');
            // Mark individual notification as read on click (decrement count only, no navigation)
            notifList.querySelectorAll('.notif-item').forEach(el => {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    if (!id) return;
                    fetch(BASE + '/notifications/mark-read', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'id=' + id,
                    })
                    .then(() => fetchNotifCount())
                    .finally(() => {
                        this.classList.remove('bg-emerald-50/40');
                        this.querySelector('.w-2')?.classList.remove('bg-emerald-500');
                        this.querySelector('.w-2')?.classList.add('bg-slate-200');
                        this.querySelector('p')?.classList.remove('font-semibold');
                    });
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

    const viewAllBtn = document.getElementById('viewAllNotif');
    if (viewAllBtn) {
        viewAllBtn.addEventListener('click', () => {
            notifDropdown.classList.add('hidden');
            window.location.href = BASE + '/notifications';
        });
    }
}

// Poll for new notifications every 10s
if (typeof BASE !== 'undefined') {
    fetchNotifCount();
    setInterval(fetchNotifCount, 10000);
}
</script>
