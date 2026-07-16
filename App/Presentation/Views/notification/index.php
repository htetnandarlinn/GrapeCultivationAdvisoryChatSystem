<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Notifications</h2>
        <p class="text-xs text-slate-500 mt-1">All your recent notifications in one place.</p>
    </div>

    <?php if (empty($notifications)): ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
        </div>
        <p class="text-sm font-semibold text-slate-600">No notifications yet</p>
        <p class="text-xs text-slate-400 mt-0.5">You're all caught up!</p>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-24">Status</th>
                        <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Message</th>
                        <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider w-28">Date</th>
                    </tr>
                </thead>
            </table>
            <div class="max-h-[400px] overflow-y-auto">
                <table class="w-full text-sm">
                    <tbody>
                        <?php foreach ($notifications as $notif): ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors <?= !$notif->isRead() ? 'bg-emerald-50/30' : '' ?>">
                            <td class="px-4 py-3 w-24">
                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase <?= !$notif->isRead() ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>"><?= !$notif->isRead() ? 'Unread' : 'Read' ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <?php
                                $notifHref = '#';
                                if ($notif->getType() === 'profile_update') {
                                    $viewerRole = $_SESSION['user_role'] ?? '';
                                    $notifHref = $viewerRole === 'farmer'
                                        ? BASE_URL . '/my-profile'
                                        : BASE_URL . '/notifications';
                                } elseif ($notif->getLink()) {
                                    $notifHref = BASE_URL . $notif->getLink();
                                }
                                ?>
                                <a href="<?= $notifHref ?>" class="flex items-center gap-3 group">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 <?= !$notif->isRead() ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>">
                                        <?php if ($notif->getType() === 'consultation_assigned'): ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <?php elseif ($notif->getType() === 'profile_updated'): ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <?php elseif ($notif->getType() === 'new_message' || $notif->getType() === 'message_received'): ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                                        <?php elseif ($notif->getType() === 'article_published'): ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                        <?php else: ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                        <?php endif; ?>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs <?= !$notif->isRead() ? 'font-bold text-slate-800' : 'text-slate-600' ?> group-hover:text-emerald-700 transition-colors"><?= htmlspecialchars($notif->getMessage()) ?></p>
                                    </div>
                                </a>
                            </td>
                            <td class="px-4 py-3 w-28">
                                <span class="text-[10px] text-slate-400"><?= $notif->getCreatedAt()->format('M d, Y h:i A') ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>
