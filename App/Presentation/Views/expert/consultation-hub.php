<?php
$username = $_SESSION['user']['username'] ?? 'Expert';
$userId = (int) ($_SESSION['user']['id'] ?? 0);
$images = $images ?? [];
$lastMessages = $lastMessages ?? [];
$farmerNames = $farmerNames ?? [];
$farmerAvatars = $farmerAvatars ?? [];

function getInitials($string) {
    $words = explode(' ', preg_replace('/[^A-Za-z0-9 ]/', '', $string));
    $initials = '';
    foreach ($words as $w) {
        if (!empty($w)) $initials .= strtoupper($w[0]);
        if (strlen($initials) >= 2) break;
    }
    return !empty($initials) ? $initials : 'C';
}

$consultations = $consultations ?? [];
$consultationsData = [];
foreach ($consultations as $c) {
    $farmerId = $c->getFarmerId();
    $consultationsData[] = [
        'id' => $c->getId(),
        'title' => $c->getTitle(),
        'description' => $c->getDescription(),
        'status' => $c->getStatus()->getValue(),
        'created_at' => $c->getCreatedAt()->format('Y-m-d H:i:s'),
        'farmer_id' => $farmerId,
        'farmer_name' => $farmerNames[$farmerId] ?? null,
        'farmer_avatar' => $farmerAvatars[$farmerId] ?? null,
        'rejection_note' => $c->getRejectionNote(),
        'images' => array_map(fn($img) => $img['image_path'], $images[$c->getId()] ?? []),
    ];
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.hide-scrollbar::-webkit-scrollbar { width: 0; height: 0; }
.hide-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }
</style>
<div class="max-w-[1400px] mx-auto p-4">
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-700 flex items-center justify-between">
            <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> <?= htmlspecialchars($_SESSION['success']) ?></span>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="mb-4 p-3.5 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-700 flex items-center justify-between">
            <span class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation text-red-500"></i> <?= htmlspecialchars($_SESSION['error']) ?></span>
            <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="bg-white rounded-3xl border border-slate-200/70 shadow-sm flex h-[calc(95vh-105px)]">
        <!-- LEFT SIDEBAR -->
        <aside class="w-full md:w-[360px] border-r border-slate-100 flex flex-col shrink-0 bg-white">
            <div class="p-4 border-b border-slate-100/80">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-2">
                        <a href="<?= BASE_URL ?>/dashboard" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        </a>
                        <h2 class="text-lg font-black text-slate-800 tracking-tight">Farmer Consultations</h2>
                    </div>
                </div>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" id="search-input" placeholder="Search consultations..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-100 rounded-full text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-slate-400 text-slate-700 font-medium">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-3 hide-scrollbar">
                <span class="text-[10px] font-bold text-slate-400 tracking-wider uppercase px-2 mb-2 block">Assigned Consultations</span>
                <?php if (empty($consultations)): ?>
                <div class="p-6 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-slate-50 flex items-center justify-center text-slate-300"><i class="fa-regular fa-comment-dots text-lg"></i></div>
                    <p class="text-xs text-slate-400 font-medium mb-3">No consultations assigned yet</p>
                </div>
                <?php else: ?>
                <div id="sidebar-list" class="space-y-1">
                    <?php foreach ($consultations as $index => $c):
                        $status = $c->getStatus()->getValue();
                        $consultationImages = $images[$c->getId()] ?? [];
                        $lastMsg = $lastMessages[$c->getId()] ?? null;
                        $farmerName = $c->getFarmerId() ? ($farmerNames[$c->getFarmerId()] ?? null) : null;
                        $hasFarmer = $farmerName && $status !== 'pending';
                        $displayName = $hasFarmer ? $farmerName : $c->getTitle();

                        $themeMap = [
                            'pending' => ['bg' => 'bg-amber-500', 'dot' => 'bg-amber-400', 'badge' => 'text-amber-500 bg-amber-50', 'label' => 'Pending'],
                            'assigned' => ['bg' => 'bg-blue-500', 'dot' => 'bg-blue-500', 'badge' => 'text-blue-600 bg-blue-50', 'label' => 'Assigned'],
                            'awaiting_payment' => ['bg' => 'bg-violet-500', 'dot' => 'bg-violet-500', 'badge' => 'text-violet-600 bg-violet-50', 'label' => 'Awaiting Payment'],
                            'accepted' => ['bg' => 'bg-[#15803D]', 'dot' => 'bg-[#15803D]', 'badge' => 'text-[#15803D] bg-emerald-50', 'label' => 'Active'],
                            'rejected' => ['bg' => 'bg-slate-400', 'dot' => 'bg-slate-400', 'badge' => 'text-slate-500 bg-slate-50', 'label' => 'Closed'],
                            'expired' => ['bg' => 'bg-red-500', 'dot' => 'bg-red-500', 'badge' => 'text-red-600 bg-red-50', 'label' => 'Expired'],
                        ];
                        $theme = $themeMap[$status] ?? $themeMap['pending'];

                        $lastMsgText = '';
                        $lastMsgTime = '';
                        if ($lastMsg) {
                            $sender = ($lastMsg['sender_id'] == $userId ? 'You' : $lastMsg['sender_name']);
                            $lastMsgText = ($lastMsg['message_type'] === 'image') ? '📷 Photo' : (($lastMsg['reply_to']) ? '↪ ' . $sender . ': ' . $lastMsg['message'] : $lastMsg['message']);
                            $dt = new DateTime($lastMsg['created_at']);
                            $now = new DateTime();
                            $diff = $now->diff($dt);
                            $lastMsgTime = ($diff->days === 0 && $diff->h === 0 && $diff->i < 1) ? 'Now' : (($diff->days === 0) ? $dt->format('g:i A') : (($diff->days === 1) ? 'Yest' : $dt->format('M d')));
                        } else {
                            $lastMsgText = $c->getDescription();
                        }
                    ?>
                    <div class="sidebar-item group flex items-start p-3.5 rounded-2xl transition-all border border-transparent hover:bg-slate-50/50 cursor-pointer"
                         data-id="<?= $c->getId() ?>" data-status="<?= $c->getStatus()->getValue() ?>" onclick="selectConsultation(<?= $c->getId() ?>)">
                        <div class="relative w-11 h-11 rounded-2xl <?= $theme['bg'] ?> flex items-center justify-center text-white font-bold text-[13px] tracking-wider shrink-0 overflow-hidden shadow-sm shadow-black/5 transition-transform group-hover:scale-[1.02]">
                            <?php
                            $farmerAvatar = $c->getFarmerId() ? ($farmerAvatars[$c->getFarmerId()] ?? null) : null;
                            if ($farmerAvatar): ?>
                                <img src="<?= BASE_URL . htmlspecialchars($farmerAvatar) ?>" alt="" class="w-full h-full object-cover">
                            <?php else: ?>
                                <?= getInitials($displayName) ?>
                            <?php endif; ?>
                            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white <?= $theme['dot'] ?>"></span>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <div class="flex items-baseline justify-between gap-1.5">
                                <h3 class="text-xs font-bold text-slate-800 truncate leading-tight"><?= htmlspecialchars($displayName) ?></h3>
                                <?php if ($lastMsgTime): ?><span class="text-[9px] font-semibold text-slate-400 shrink-0 uppercase"><?= $lastMsgTime ?></span><?php endif; ?>
                            </div>
                            <p class="text-[10px] text-slate-500 font-medium truncate mt-0.5"><?= htmlspecialchars($c->getTitle()) ?></p>
                            <p class="text-[10px] text-slate-400 font-medium truncate mt-1 flex items-center gap-1">
                                <?php if ($lastMsg && $lastMsg['sender_id'] == $userId): ?><span class="text-emerald-500 font-bold">You:</span><?php endif; ?>
                                <?= htmlspecialchars(mb_strimwidth($lastMsgText, 0, 45, "...")) ?>
                            </p>
                        </div>
                        <div class="ml-2 flex flex-col items-end shrink-0">
                            <span class="status-badge text-[9px] font-bold tracking-wide uppercase px-2 py-0.5 rounded-md <?= $theme['badge'] ?>"><?= $theme['label'] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </aside>

        <!-- RIGHT MAIN PANE -->
        <main id="right-pane" class="hidden md:flex flex-1 flex-col bg-slate-50/40">
            <!-- Empty State -->
            <div id="right-empty" class="flex-1 flex flex-col items-center justify-center p-12 text-center bg-slate-50/20">
                <div class="w-16 h-16 mb-4 rounded-3xl bg-slate-100 border border-slate-200/50 flex items-center justify-center text-slate-400">
                    <i class="fa-regular fa-comment text-2xl"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-700 mb-1">Select a Consultation</h3>
                <p class="text-xs text-slate-400 max-w-sm leading-relaxed">Choose a consultation from the list to review and respond.</p>
            </div>

            <!-- Active Consultation View -->
            <div id="right-active" class="hidden flex-1 flex flex-col">
                <!-- Header -->
                <div id="right-header" class="px-4 md:px-6 py-4 border-b border-slate-100 bg-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button id="mobile-back-btn" class="md:hidden w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors" onclick="showSidebar()" title="Back">
                            <i class="fa-solid fa-arrow-left text-sm"></i>
                        </button>
                        <button onclick="openFarmerProfile()" class="flex items-center gap-3 text-left">
                            <div id="header-avatar" class="w-10 h-10 rounded-2xl flex items-center justify-center text-white font-bold text-xs tracking-wider shrink-0 shadow-sm shadow-black/5"></div>
                            <div class="ml-0">
                                <h3 id="header-title" class="text-sm font-bold text-slate-800 leading-tight"></h3>
                                <p id="header-subtitle" class="text-[10px] text-blue-500 font-semibold mt-0.5 flex items-center gap-1"></p>
                            </div>
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <span id="header-badge" class="text-[9px] font-bold tracking-wide uppercase px-2 py-0.5 rounded-md"></span>
                    </div>
                </div>

                <!-- Consultation Details (for assigned/rejected) -->
                <div id="right-details" class="hidden flex-1 overflow-y-auto p-6">
                    <div id="detail-content" class="max-w-3xl mx-auto space-y-6"></div>
                </div>

                <!-- Messages (for accepted) -->
                <div id="right-messages-wrapper" class="hidden flex-1 relative overflow-hidden">
                    <div id="right-messages" class="absolute inset-0 overflow-y-auto p-6 space-y-4 hide-scrollbar">
                        <div id="loading-msgs" class="text-center text-xs text-slate-400 py-8"><i class="fa-solid fa-spinner animate-spin"></i> Loading messages...</div>
                    </div>
                    <button id="scroll-bottom-btn" onclick="scrollToBottom(document.getElementById('right-messages'))"
                            class="absolute bottom-4 right-4 w-9 h-9 rounded-full bg-white border border-slate-200 shadow-lg flex items-center justify-center text-slate-500 hover:text-emerald-600 hover:border-emerald-200 transition-all opacity-0 pointer-events-none">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>
                </div>

                <!-- Input (only for accepted) -->
                <div id="right-input-area" class="hidden p-4 bg-white border-t border-slate-200 shrink-0">
                    <div id="right-reply-bar" class="hidden flex items-center gap-2 px-3 py-2 bg-slate-50 rounded-xl mb-2 border border-slate-200 text-xs">
                        <i class="fa-solid fa-reply text-slate-400 text-[10px]"></i>
                        <span class="flex-1 truncate font-medium text-slate-600"><span class="text-slate-400 font-normal">Replying to </span><span id="right-reply-name"></span><span id="right-reply-text" class="text-slate-400 font-normal">: </span></span>
                        <button type="button" onclick="cancelReply()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <form id="right-chat-form" class="flex items-center space-x-2">
                        <button type="button" onclick="triggerFileSelector()" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white hover:bg-slate-100 text-slate-500 transition-colors shrink-0" title="Attach Document / Prescription">
                            <i class="fa-solid fa-paperclip"></i>
                        </button>
                        <input type="text" id="right-msg-input" placeholder="Type your message..." autofocus
                               class="flex-1 h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm placeholder-slate-400">
                        <input type="file" id="right-img-input" accept="image/*" class="hidden">
                        <button type="submit" class="h-11 px-5 rounded-xl bg-[#15803D] hover:bg-green-800 text-white font-semibold flex items-center space-x-1.5 transition-colors">
                            <span class="text-sm hidden sm:inline">Send</span>
                            <i class="fa-regular fa-paper-plane text-xs"></i>
                        </button>
                    </form>
                    <div class="flex justify-between items-center px-1 mt-1.5 text-[9px] text-slate-400">
                        <span><i class="fa-solid fa-leaf text-emerald-500 mr-1"></i> Grape Cultivation Advisory</span>
                        <span id="right-conn-status">Connecting...</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Farmer Profile Modal -->
<div id="farmer-profile-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full max-h-[85vh] overflow-y-auto shadow-2xl">
        <div class="flex items-center justify-between p-5 border-b border-slate-100">
            <h3 class="text-base font-black text-slate-900">Farmer Profile</h3>
            <button onclick="closeFarmerProfile()" class="w-8 h-8 rounded-xl hover:bg-slate-100 flex items-center justify-center text-slate-400 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="p-5 flex items-center gap-4">
            <div id="modal-farmer-avatar" class="w-16 h-16 rounded-full overflow-hidden border-2 border-slate-200 bg-slate-100 flex items-center justify-center text-white font-bold text-lg shrink-0"></div>
            <div>
                <h4 id="modal-farmer-name" class="text-lg font-bold text-slate-900"></h4>
                <p class="text-sm text-slate-500">Farmer</p>
            </div>
        </div>
        <div class="px-5 pb-5">
            <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2"><i class="fa-regular fa-images text-emerald-500"></i> Shared Media</h4>
            <div id="modal-shared-images" class="grid grid-cols-3 gap-2"></div>
            <div id="modal-no-images" class="hidden text-center py-10 text-slate-400 text-sm">
                <i class="fa-regular fa-image text-3xl mb-3 block"></i>
                No images shared yet
            </div>
            <div id="modal-loading" class="text-center py-10 text-slate-400 text-sm">
                <i class="fa-solid fa-spinner animate-spin"></i> Loading images...
            </div>
        </div>
    </div>
</div>

<script>
const consultationsData = <?= json_encode($consultationsData) ?>;
const baseUrl = '<?= BASE_URL ?>';
const userId = <?= $userId ?>;
const userName = '<?= htmlspecialchars($username, ENT_QUOTES) ?>';
const role = 'expert';

const themeMap = {
    pending:  { bg: 'bg-amber-500',  dot: 'bg-amber-400',  label: 'Pending',  badge: 'text-amber-500 bg-amber-50' },
    assigned: { bg: 'bg-blue-500',   dot: 'bg-blue-500',   label: 'Assigned', badge: 'text-blue-600 bg-blue-50' },
    expert_accepted: { bg: 'bg-blue-500', dot: 'bg-blue-500', label: 'Accepted', badge: 'text-blue-600 bg-blue-50' },
    awaiting_payment: { bg: 'bg-violet-500', dot: 'bg-violet-500', label: 'Awaiting Payment', badge: 'text-violet-600 bg-violet-50' },
    payment_submitted: { bg: 'bg-amber-500', dot: 'bg-amber-500', label: 'Pending Review', badge: 'text-amber-600 bg-amber-50' },
    accepted: { bg: 'bg-[#15803D]',dot: 'bg-[#15803D]',label: 'Active',   badge: 'text-[#15803D] bg-emerald-50' },
    chat_started: { bg: 'bg-emerald-500',dot: 'bg-emerald-500',label: 'Active', badge: 'text-emerald-600 bg-emerald-50' },
    completed: { bg: 'bg-blue-500',  dot: 'bg-blue-500',   label: 'Completed', badge: 'text-blue-600 bg-blue-50' },
    closed: { bg: 'bg-slate-400',    dot: 'bg-slate-400',  label: 'Closed',   badge: 'text-slate-500 bg-slate-50' },
    rejected: { bg: 'bg-slate-400',  dot: 'bg-slate-400',  label: 'Closed',   badge: 'text-slate-500 bg-slate-50' },
    expired: { bg: 'bg-red-500',     dot: 'bg-red-500',    label: 'Expired',  badge: 'text-red-600 bg-red-50' },
};

let selectedId = null;
let ws = null;
let wsConnected = false;
let reconnectTimer = null;
let replyToId = null;
let lastMessageId = 0;
let pollTimer = null;

function getConsultation(id) {
    return consultationsData.find(c => c.id === id);
}

function showSidebar() {
    document.querySelector('aside').classList.remove('hidden');
    document.getElementById('right-pane').classList.add('hidden');
    document.getElementById('right-pane').classList.remove('flex');
}

function setReply(msgId, senderName, msgText) {
    replyToId = msgId;
    document.getElementById('right-reply-bar').classList.remove('hidden');
    const preview = (msgText || '').length > 50 ? msgText.substring(0, 50) + '...' : (msgText || '');
    document.getElementById('right-reply-name').textContent = senderName;
    document.getElementById('right-reply-text').textContent = ': ' + preview;
    document.getElementById('right-msg-input').focus();
}

function cancelReply() {
    replyToId = null;
    document.getElementById('right-reply-bar').classList.add('hidden');
}

function selectConsultation(id) {
    selectedId = id;

    document.querySelectorAll('.sidebar-item').forEach(el => {
        const isActive = parseInt(el.dataset.id) === id;
        el.className = 'sidebar-item group flex items-start p-3.5 rounded-2xl transition-all cursor-pointer ' +
            (isActive ? 'bg-slate-50/90 border border-slate-100/50' : 'border border-transparent hover:bg-slate-50/50');
    });

    const data = getConsultation(id);
    if (!data) return;

    document.getElementById('right-empty').classList.add('hidden');
    document.getElementById('right-active').classList.remove('hidden');

    if (window.innerWidth < 768) {
        document.querySelector('aside').classList.add('hidden');
        document.getElementById('right-pane').classList.remove('hidden');
        document.getElementById('right-pane').classList.add('flex');
    }

    cancelReply();

    const theme = themeMap[data.status] || themeMap.pending;
    const hasFarmer = data.farmer_name && data.status !== 'pending';
    const displayName = hasFarmer ? data.farmer_name : data.title;
    const avatar = document.getElementById('header-avatar');
    avatar.className = 'w-10 h-10 rounded-2xl ' + theme.bg + ' flex items-center justify-center text-white font-bold text-xs tracking-wider shrink-0 overflow-hidden shadow-sm shadow-black/5';
    if (data.farmer_avatar) {
        avatar.innerHTML = '<img src="' + baseUrl + data.farmer_avatar + '" alt="" class="w-full h-full object-cover">';
    } else {
        avatar.textContent = getInitialsFn(displayName);
    }

    document.getElementById('header-title').textContent = displayName;
    document.getElementById('header-subtitle').innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-[#15803D] animate-pulse"></span> ${hasFarmer ? 'Farmer - ' : ''}${theme.label} Consultation`;

    const badge = document.getElementById('header-badge');
    badge.className = 'text-[9px] font-bold tracking-wide uppercase px-2 py-0.5 rounded-md ' + theme.badge;
    badge.textContent = theme.label;

    const detailsEl = document.getElementById('right-details');
    const messagesWrapper = document.getElementById('right-messages-wrapper');
    const inputArea = document.getElementById('right-input-area');

    detailsEl.classList.add('hidden');
    messagesWrapper.classList.add('hidden');
    inputArea.classList.add('hidden');

    if (ws) { ws.close(); ws = null; }
    stopPolling();
    lastMessageId = 0;

    if (data.status === 'accepted' || data.status === 'chat_started') {
        messagesWrapper.classList.remove('hidden');
        inputArea.classList.remove('hidden');
        connectWebSocket(id);
        loadMessages(id);
    } else {
        detailsEl.classList.remove('hidden');
        renderConsultationDetails(data);
    }
}

function renderConsultationDetails(data) {
    const container = document.getElementById('detail-content');
    const theme = themeMap[data.status] || themeMap.pending;
    const farmerDisplay = data.farmer_name || 'Farmer';
    const farmerAvatarHtml = data.farmer_avatar
        ? `<img src="${baseUrl}${data.farmer_avatar}" alt="" class="w-10 h-10 rounded-xl object-cover">`
        : `<div class="w-10 h-10 rounded-xl bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-sm">${getInitialsFn(farmerDisplay)}</div>`;

    const imagesHtml = (data.images && data.images.length > 0)
        ? `<div class="px-6 py-4 border-t border-slate-100">
             <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Attached Images</p>
             <div class="flex gap-2 flex-wrap">${data.images.map(img => `<a href="${baseUrl}/${img}" target="_blank"><img src="${baseUrl}/${img}" class="w-24 h-24 rounded-xl object-cover border border-slate-200 hover:opacity-90 transition-opacity" alt="Consultation image"></a>`).join('')}</div>
           </div>`
        : '';

    let html = `
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-50 flex items-center gap-3">
                ${farmerAvatarHtml}
                <div>
                    <h3 class="text-sm font-bold text-slate-800">${escapeHtml(farmerDisplay)}</h3>
                    <p class="text-[10px] text-slate-400">${escapeHtml(data.title)} &middot; ${new Date(data.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
                </div>
            </div>
            <div class="px-6 py-4">
                <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">${escapeHtml(data.description)}</p>
            </div>
            ${imagesHtml}
        </div>
    `;

    if (data.status === 'assigned') {
        html += `
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 bg-slate-50 space-y-3">
                    <p class="text-xs font-semibold text-slate-600">Do you want to accept this consultation?</p>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="acceptConsultation(this, ${data.id})" class="px-5 py-2.5 bg-[#15803D] text-white text-xs font-bold rounded-xl hover:bg-green-800 transition-colors">
                            Accept Consultation
                        </button>
                        <button type="button" onclick="toggleRejectForm()" class="px-5 py-2.5 bg-red-50 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-colors">
                            Reject
                        </button>
                    </div>
                    <div id="reject-form-container" class="hidden">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Rejection Note</label>
                                <textarea id="rejection-note-input" rows="3" required
                                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all resize-y"
                                          placeholder="Explain why you cannot take this consultation..."></textarea>
                            </div>
                            <button onclick="rejectConsultation(this, ${data.id})" class="px-5 py-2.5 bg-red-600 text-white text-xs font-bold rounded-xl hover:bg-red-700 transition-colors">
                                Submit Rejection
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (data.status === 'rejected' && data.rejection_note) {
        html += `
            <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 bg-red-50">
                    <h3 class="text-xs font-bold text-red-600 uppercase mb-1">Rejection Note</h3>
                    <p class="text-sm text-red-700">${escapeHtml(data.rejection_note)}</p>
                </div>
            </div>
        `;
    }

    container.innerHTML = html;
}

function toggleRejectForm() {
    const el = document.getElementById('reject-form-container');
    if (el) el.classList.toggle('hidden');
}

function showAcceptError(msg) {
    const container = document.getElementById('detail-content');
    if (!container) return;
    const errEl = document.createElement('div');
    errEl.className = 'p-3.5 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-700 flex items-center gap-2 mt-4';
    errEl.innerHTML = '<i class="fa-solid fa-circle-exclamation text-red-500"></i> ' + escapeHtml(msg);
    container.prepend(errEl);
    setTimeout(() => { if (errEl.parentNode) errEl.parentNode.removeChild(errEl); }, 8000);
    const btn = container.querySelector('button[onclick^="acceptConsultation"]');
    if (btn) { btn.disabled = false; btn.textContent = 'Accept Consultation'; }
}

function acceptConsultation(btn, id) {
    btn.disabled = true;
    btn.textContent = 'Accepting...';
    fetch(`${baseUrl}/expert/consultations/accept`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: 'consultation_id=' + id,
    })
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(res => {
        if (res.success) {
            updateConsultationStatus(id, 'awaiting_payment');
            selectConsultation(id);
        } else {
            showAcceptError(res.error || 'Failed to accept consultation.');
            btn.disabled = false;
            btn.textContent = 'Accept Consultation';
        }
    })
    .catch(err => {
        showAcceptError('Network or server error: ' + err.message);
        btn.disabled = false;
        btn.textContent = 'Accept Consultation';
    });
}

function rejectConsultation(btn, id) {
    const note = document.getElementById('rejection-note-input');
    if (!note || !note.value.trim()) return;
    btn.disabled = true;
    btn.textContent = 'Submitting...';
    fetch(`${baseUrl}/expert/consultations/reject`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: 'consultation_id=' + id + '&rejection_note=' + encodeURIComponent(note.value.trim()),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            updateConsultationStatus(id, 'rejected');
            selectConsultation(id);
        } else {
            btn.disabled = false;
            btn.textContent = 'Submit Rejection';
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.textContent = 'Submit Rejection';
    });
}

function updateConsultationStatus(id, newStatus) {
    const item = document.querySelector('.sidebar-item[data-id="' + id + '"]');
    if (item) {
        item.setAttribute('data-status', newStatus);
        const badge = item.querySelector('.status-badge');
        if (badge) {
            const labels = { pending: 'Pending', assigned: 'Assigned', awaiting_payment: 'Awaiting Payment', payment_submitted: 'Pending Review', accepted: 'Active', chat_started: 'Active', completed: 'Completed', closed: 'Closed', rejected: 'Closed', expired: 'Expired' };
            badge.textContent = labels[newStatus] || newStatus;
        }
    }
    const cons = consultationsData.find(c => c.id === id);
    if (cons) cons.status = newStatus;
}

function loadMessages(id) {
    const container = document.getElementById('right-messages');
    container.innerHTML = '<div id="loading-msgs" class="text-center text-xs text-slate-400 py-8"><i class="fa-solid fa-spinner animate-spin"></i> Loading messages...</div>';

    container.onscroll = null;
    container.onscroll = function() { checkScroll(container); };

    fetch(`${baseUrl}/chat/history?consultation_id=${id}`)
        .then(res => res.json())
        .then(messages => {
            container.innerHTML = '';
            messages.forEach(m => {
                const isMine = parseInt(m.sender_id) === userId;
                const replyInfo = m.reply_to ? { message: m.reply_to_message, sender: m.reply_to_sender, isMineReply: isMine } : null;
                const msgId = m.message_id;
                if (m.message_id > lastMessageId) lastMessageId = m.message_id;
                if (m.message_type === 'system') {
                    appendSystemMessage(m.message, container);
                } else if (m.message_type === 'image') {
                    appendImage(m.image_path || m.message, m.sender_name, isMine, m.created_at, container, replyInfo, msgId, m.caption);
                } else {
                    appendMessage(m.message, m.sender_name, isMine, m.created_at, container, replyInfo, msgId);
                }
            });
            scrollToBottom(container);
        })
        .catch(() => {
            container.innerHTML = '<div class="text-center text-xs text-slate-400 py-8">Failed to load messages.</div>';
        });
}

function startPolling(cid) {
    if (pollTimer) return;
    pollTimer = setInterval(() => {
        if (wsConnected) return;
        fetch(`${baseUrl}/chat/history?consultation_id=${cid}`)
            .then(res => res.json())
            .then(messages => {
                const container = document.getElementById('right-messages');
                let hasNew = false;
                messages.forEach(m => {
                    if (m.message_id && m.message_id > lastMessageId) {
                        if (m.message_id > lastMessageId) lastMessageId = m.message_id;
                        if (parseInt(m.sender_id) === userId) return;
                        const replyInfo = m.reply_to ? { message: m.reply_to_message, sender: m.reply_to_sender } : null;
                    if (m.message_type === 'system') {
                        appendSystemMessage(m.message, container);
                    } else if (m.message_type === 'image') {
                        appendImage(m.image_path || m.message, m.sender_name, false, m.created_at, container, replyInfo, m.message_id, m.caption);
                    } else {
                        appendMessage(m.message, m.sender_name, false, m.created_at, container, replyInfo, m.message_id);
                    }
                        hasNew = true;
                    }
                });
                if (hasNew && isNearBottom) scrollToBottom(container);
            })
            .catch(() => {});
    }, 3000);
}

function stopPolling() {
    if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
}

function connectWebSocket(cid) {
    stopPolling();
    const host = window.location.hostname || 'localhost';
    const wsUrl = `ws://${host}:8080?consultation_id=${cid}&user_id=${userId}&role=${role}`;
    ws = new WebSocket(wsUrl);

    ws.onopen = function() {
        wsConnected = true;
        document.getElementById('right-conn-status').innerHTML = '<i class="fa-solid fa-circle text-emerald-500 mr-1 text-[6px]"></i> Connected';
        if (reconnectTimer) { clearTimeout(reconnectTimer); reconnectTimer = null; }
    };

    ws.onmessage = function(event) {
        const data = JSON.parse(event.data);
        const container = document.getElementById('right-messages');
        if (data.type === 'status_update') {
            handleStatusUpdate(data.consultation_id, data.status);
            return;
        }
        if (data.message_id && data.message_id > lastMessageId) lastMessageId = data.message_id;
        if (data.type === 'system') {
            appendSystemMessage(data.message, container);
        } else if (parseInt(data.sender_id) !== userId) {
            const replyInfo = data.reply_to ? { message: data.reply_to_message, sender: data.reply_to_sender } : null;
            if (data.message_type === 'image') {
                appendImage(data.message, data.sender_name, false, data.created_at, container, replyInfo, data.message_id, data.caption);
            } else {
                appendMessage(data.message, data.sender_name, false, data.created_at, container, replyInfo, data.message_id);
            }
        }
    };

    ws.onclose = function() {
        wsConnected = false;
        document.getElementById('right-conn-status').innerHTML = '<i class="fa-solid fa-circle text-amber-500 mr-1 text-[6px]"></i> Polling...';
        if (selectedId && !reconnectTimer) reconnectTimer = setTimeout(() => connectWebSocket(selectedId), 5000);
        if (selectedId) startPolling(selectedId);
    };

    ws.onerror = function() {
        wsConnected = false;
        document.getElementById('right-conn-status').innerHTML = '<i class="fa-solid fa-circle text-slate-300 mr-1 text-[6px]"></i> Offline (polling)';
        if (selectedId) startPolling(selectedId);
    };
}

function appendMessage(text, senderName, isMine, timestamp, container, replyInfo, msgId) {
    const time = timestamp ? new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : 'Just now';
    const replyHtml = replyInfo ? buildReplyHtml(replyInfo, isMine) : '';
    const div = document.createElement('div');
    const replyBtn = msgId ? `<button class="reply-btn text-[9px] text-slate-400 hover:text-emerald-600 transition-colors opacity-0 group-hover:opacity-100" data-msg-id="${msgId}" data-sender="${escapeHtml(senderName)}"><i class="fa-solid fa-reply"></i></button>` : '';
    if (isMine) {
        div.className = 'flex justify-end mb-4';
        div.innerHTML = `<div class="max-w-[75%] group">${replyHtml}<div class="bg-[#15803D] text-white px-4 py-2.5 rounded-2xl rounded-tr-none shadow-sm text-sm w-fit max-w-full break-words ml-auto"><p class="leading-relaxed">${escapeHtml(text)}</p></div><div class="flex justify-end gap-2 mt-0.5">${replyBtn}<span class="text-[9px] text-slate-400">${time}</span></div></div>`;
    } else {
        div.className = 'flex justify-start mb-4';
        div.innerHTML = `<div class="max-w-[75%] group">${replyHtml}<div class="bg-white text-slate-800 px-4 py-2.5 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 text-sm w-fit max-w-full break-words"><p class="leading-relaxed">${escapeHtml(text)}</p></div><div class="flex gap-2 mt-0.5">${replyBtn}<span class="text-[9px] text-slate-400">${time}</span></div></div>`;
    }
    container.appendChild(div);
    if (isNearBottom) scrollToBottom(container);
}

function appendImage(src, senderName, isMine, timestamp, container, replyInfo, msgId, caption) {
    const time = timestamp ? new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : 'Just now';
    const imgUrl = src.startsWith('data:') ? src : baseUrl + '/' + src;
    const replyHtml = replyInfo ? buildReplyHtml(replyInfo, isMine) : '';
    const replyBtn = msgId ? `<button class="reply-btn text-[9px] text-slate-400 hover:text-emerald-600 transition-colors opacity-0 group-hover:opacity-100" data-msg-id="${msgId}" data-sender="${escapeHtml(senderName)}"><i class="fa-solid fa-reply"></i></button>` : '';
    const captionHtml = caption ? `<p class="text-sm px-1 pt-1.5 pb-0.5 leading-relaxed ${isMine ? 'text-white' : 'text-slate-800'}">${escapeHtml(caption)}</p>` : '';
    const div = document.createElement('div');
    if (isMine) {
        div.className = 'flex justify-end mb-4';
        div.innerHTML = `<div class="max-w-[75%] group">${replyHtml}<div class="bg-[#15803D] px-2 pt-2 pb-1 rounded-2xl rounded-tr-none shadow-sm w-fit max-w-full ml-auto chat-image"><img src="${imgUrl}" alt="Sent image" class="max-w-[260px] max-h-[300px] rounded-xl object-cover block">${captionHtml}</div><div class="flex justify-end gap-2 mt-0.5">${replyBtn}<span class="text-[9px] text-slate-400">${time}</span></div></div>`;
    } else {
        div.className = 'flex justify-start mb-4';
        div.innerHTML = `<div class="max-w-[75%] group">${replyHtml}<div class="bg-white px-2 pt-2 pb-1 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 w-fit max-w-full chat-image"><img src="${imgUrl}" alt="Sent image" class="max-w-[260px] max-h-[300px] rounded-xl object-cover block">${captionHtml}</div><div class="flex gap-2 mt-0.5">${replyBtn}<span class="text-[9px] text-slate-400">${time}</span></div></div>`;
    }
    container.appendChild(div);
    if (isNearBottom) scrollToBottom(container);
}

function appendSystemMessage(text, container) {
    const div = document.createElement('div');
    div.className = 'flex justify-center my-2';
    div.innerHTML = `<span class="text-[9px] text-slate-400 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">${escapeHtml(text)}</span>`;
    container.appendChild(div);
    if (isNearBottom) scrollToBottom(container);
}

function buildReplyHtml(replyInfo, isMine) {
    if (!replyInfo) return '';
    const preview = (replyInfo.message || '').length > 80 ? replyInfo.message.substring(0, 80) + '...' : (replyInfo.message || '');
    return `<div class="border-l-2 ${isMine ? 'border-emerald-300' : 'border-slate-300'} pl-2 mb-1"><div class="text-[10px] ${isMine ? 'text-emerald-200' : 'text-slate-500'} font-medium">Replying to ${escapeHtml(replyInfo.sender)}</div><div class="text-[10px] ${isMine ? 'text-emerald-200/70' : 'text-slate-400'} truncate max-w-[220px]">${escapeHtml(preview)}</div></div>`;
}

let isNearBottom = true;

function scrollToBottom(container) {
    container.scrollTop = container.scrollHeight;
    isNearBottom = true;
    document.getElementById('scroll-bottom-btn').classList.add('opacity-0', 'pointer-events-none');
}

function checkScroll(container) {
    const threshold = 120;
    const distFromBottom = container.scrollHeight - container.scrollTop - container.clientHeight;
    isNearBottom = distFromBottom < threshold;
    const btn = document.getElementById('scroll-bottom-btn');
    if (isNearBottom) {
        btn.classList.add('opacity-0', 'pointer-events-none');
    } else {
        btn.classList.remove('opacity-0', 'pointer-events-none');
    }
}

function escapeHtml(text) { if (text == null) return ''; const d = document.createElement('div'); d.textContent = text; return d.innerHTML; }
function getInitialsFn(str) { const m = str.match(/[A-Za-z0-9]/g); return m ? m.slice(0,2).join('').toUpperCase() : 'C'; }

function addReplyBtnToMsg(container, msgId, senderName) {
    if (!container || !msgId) return;
    const outer = container.lastElementChild;
    if (!outer) return;
    const group = outer.querySelector('.group');
    if (!group) return;
    const timeRow = group.children[group.children.length - 1];
    if (!timeRow || timeRow.querySelector('.reply-btn')) return;
    const btn = document.createElement('button');
    btn.className = 'reply-btn text-[9px] text-slate-400 hover:text-emerald-600 transition-colors opacity-0 group-hover:opacity-100';
    btn.dataset.msgId = msgId;
    btn.dataset.sender = senderName;
    btn.innerHTML = '<i class="fa-solid fa-reply"></i>';
    timeRow.insertBefore(btn, timeRow.firstChild);
}

document.getElementById('right-messages').addEventListener('click', function(e) {
    const btn = e.target.closest('.reply-btn');
    if (!btn) return;
    const msgId = parseInt(btn.dataset.msgId);
    const sender = btn.dataset.sender;
    const msgEl = btn.closest('.group');
    const textEl = msgEl?.querySelector('.chat-image img') ? '📷 Photo' : msgEl?.querySelector('p')?.textContent || '';
    setReply(msgId, sender, textEl);
});

document.getElementById('right-chat-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('right-msg-input');
    const text = input.value.trim();
    const fileInput = document.getElementById('right-img-input');
    const file = fileInput.files[0];
    if (!text && !file) return;
    if (!selectedId) return;

    const container = document.getElementById('right-messages');
    const savedReplyToId = replyToId;
    cancelReply();

    if (text && file) {
        const reader = new FileReader();
        reader.onload = function(ev) { appendImage(ev.target.result, userName, true, null, container, null, null, text); };
        reader.readAsDataURL(file);

        const formData = new FormData();
        formData.append('consultation_id', selectedId);
        formData.append('image', file);
        formData.append('caption', text);
        if (savedReplyToId) formData.append('reply_to', savedReplyToId);

        fetch(`${baseUrl}/chat/send`, { method: 'POST', body: formData })
            .then(res => { if (!res.ok) throw new Error('HTTP ' + res.status); return res.json(); })
            .then(data => {
                addReplyBtnToMsg(container, data.id, userName);
                if (ws && ws.readyState === WebSocket.OPEN) {
                    ws.send(JSON.stringify({
                        message_id: data.id,
                        message: data.message,
                        message_type: 'image',
                        image_path: data.message,
                        reply_to: savedReplyToId,
                        reply_to_message: data.reply_to_message,
                        reply_to_sender: data.reply_to_sender,
                        caption: text,
                        sender_name: userName
                    }));
                }
            })
            .catch(err => console.error('Persist failed:', err));
    } else if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) { appendImage(ev.target.result, userName, true, null, container); };
        reader.readAsDataURL(file);

        const formData = new FormData();
        formData.append('consultation_id', selectedId);
        formData.append('image', file);
        if (savedReplyToId) formData.append('reply_to', savedReplyToId);

        fetch(`${baseUrl}/chat/send`, { method: 'POST', body: formData })
            .then(res => { if (!res.ok) throw new Error('HTTP ' + res.status); return res.json(); })
            .then(data => {
                addReplyBtnToMsg(container, data.id, userName);
                if (ws && ws.readyState === WebSocket.OPEN) {
                    ws.send(JSON.stringify({
                        message_id: data.id,
                        message: data.message,
                        message_type: 'image',
                        image_path: data.message,
                        reply_to: savedReplyToId,
                        reply_to_message: data.reply_to_message,
                        reply_to_sender: data.reply_to_sender,
                        caption: null,
                        sender_name: userName
                    }));
                }
            })
            .catch(err => console.error('Persist failed:', err));
    } else {
        const replyInfo = savedReplyToId ? { sender: document.getElementById('right-reply-name').textContent, message: text } : null;
        appendMessage(text, userName, true, null, container, replyInfo);

        const formData = new FormData();
        formData.append('consultation_id', selectedId);
        formData.append('message', text);
        if (savedReplyToId) formData.append('reply_to', savedReplyToId);

        fetch(`${baseUrl}/chat/send`, { method: 'POST', body: formData })
            .then(res => { if (!res.ok) throw new Error('HTTP ' + res.status); return res.json(); })
            .then(data => {
                addReplyBtnToMsg(container, data.id, userName);
                if (ws && ws.readyState === WebSocket.OPEN) {
                    ws.send(JSON.stringify({
                        message_id: data.id,
                        message: data.message,
                        message_type: 'text',
                        reply_to: savedReplyToId,
                        reply_to_message: data.reply_to_message,
                        reply_to_sender: data.reply_to_sender,
                        sender_name: userName
                    }));
                }
            })
            .catch(err => console.error('Persist failed:', err));
    }

    input.value = '';
    fileInput.value = '';
});

function triggerFileSelector() {
    document.getElementById('right-img-input').click();
}

// Status polling
let statusPollTimer = null;

function handleStatusUpdate(id, newStatus) {
    const item = document.querySelector('.sidebar-item[data-id="' + id + '"]');
    if (!item) return;
    const currentStatus = item.dataset.status;
    if (currentStatus === newStatus) return;

    item.setAttribute('data-status', newStatus);
    const badge = item.querySelector('.status-badge');
    if (badge) {
        const labels = { pending: 'Pending', assigned: 'Assigned', expert_accepted: 'Accepted', awaiting_payment: 'Awaiting Payment', payment_submitted: 'Pending Review', accepted: 'Active', chat_started: 'Active', completed: 'Completed', closed: 'Closed', rejected: 'Closed', expired: 'Expired' };
        badge.textContent = labels[newStatus] || newStatus;
        const t = themeMap[newStatus] || themeMap.pending;
        badge.className = 'status-badge text-[9px] font-bold tracking-wide uppercase px-2 py-0.5 rounded-md ' + t.badge;
        const dot = item.querySelector('.sidebar-item .rounded-full.border-2');
        if (dot) dot.className = 'absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white ' + t.dot;
    }
    const cons = consultationsData.find(c => c.id === id);
    if (cons) cons.status = newStatus;
    if (selectedId === id) selectConsultation(id);
}

function startStatusPolling() {
    if (statusPollTimer) return;
    statusPollTimer = setInterval(() => {
        const ids = Array.from(document.querySelectorAll('.sidebar-item')).map(el => el.dataset.id).filter(Boolean);
        if (ids.length === 0) return;
        fetch(baseUrl + '/consultation/status?ids=' + ids.join(','))
            .then(res => res.json())
            .then(statusMap => {
                Object.entries(statusMap).forEach(([id, newStatus]) => {
                    id = parseInt(id);
                    const item = document.querySelector('.sidebar-item[data-id="' + id + '"]');
                    if (!item || item.dataset.status === newStatus) return;
                    handleStatusUpdate(id, newStatus);
                });
            })
            .catch(function() {});
    }, 5000);
}

startStatusPolling();

document.getElementById('search-input')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.sidebar-item').forEach(el => {
        const title = el.querySelector('h3')?.textContent?.toLowerCase() || '';
        el.style.display = title.includes(q) ? '' : 'none';
    });
});

// Farmer Profile Modal
function openFarmerProfile() {
    if (!selectedId) return;
    const data = getConsultation(selectedId);
    if (!data || (!data.farmer_name && data.status === 'pending')) return;

    const modal = document.getElementById('farmer-profile-modal');
    modal.classList.remove('hidden');

    const avatarEl = document.getElementById('modal-farmer-avatar');
    const nameEl = document.getElementById('modal-farmer-name');

    if (data.farmer_avatar) {
        avatarEl.innerHTML = '<img src="' + baseUrl + data.farmer_avatar + '" alt="" class="w-full h-full object-cover">';
    } else {
        avatarEl.textContent = getInitialsFn(data.farmer_name || 'Farmer');
    }
    nameEl.textContent = data.farmer_name || 'Farmer';

    loadSharedImages(selectedId);
}

function closeFarmerProfile() {
    document.getElementById('farmer-profile-modal').classList.add('hidden');
}

document.getElementById('farmer-profile-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeFarmerProfile();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeFarmerProfile();
});

function loadSharedImages(consultationId) {
    const grid = document.getElementById('modal-shared-images');
    const loading = document.getElementById('modal-loading');
    const noImages = document.getElementById('modal-no-images');

    grid.innerHTML = '';
    loading.classList.remove('hidden');
    noImages.classList.add('hidden');

    fetch(baseUrl + '/chat/history?consultation_id=' + consultationId)
        .then(res => res.json())
        .then(messages => {
            loading.classList.add('hidden');
            const images = messages.filter(m => m.message_type === 'image');
            if (images.length === 0) {
                noImages.classList.remove('hidden');
                return;
            }
            images.forEach(img => {
                const imgUrl = baseUrl + '/' + img.message;
                const div = document.createElement('a');
                div.href = imgUrl;
                div.target = '_blank';
                div.className = 'aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-200 hover:opacity-90 transition-opacity';
                div.innerHTML = '<img src="' + imgUrl + '" alt="Shared image" class="w-full h-full object-cover">';
                grid.appendChild(div);
            });
        })
        .catch(() => {
            loading.classList.add('hidden');
            noImages.classList.remove('hidden');
            noImages.textContent = 'Failed to load images.';
        });
}

const urlParams = new URLSearchParams(window.location.search);
const consultationId = urlParams.get('id');
if (consultationId) {
    setTimeout(() => {
        const id = parseInt(consultationId, 10);
        const el = document.querySelector(`.sidebar-item[data-id="${consultationId}"]`);
        if (el && typeof selectConsultation === 'function') {
            selectConsultation(id);
        }
    }, 500);
}
</script>
