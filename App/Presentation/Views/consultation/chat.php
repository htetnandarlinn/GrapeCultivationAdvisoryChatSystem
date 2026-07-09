<?php
$user = $_SESSION['user'] ?? [];
$userId = (int) ($user['id'] ?? 0);
$userName = $user['username'] ?? 'Farmer';
$consultationId = $consultation->getId();
$consultationTitle = htmlspecialchars($consultation->getTitle());
$images = $images ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Chat - Grape Cultivation Advisory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        .message-bubble { max-width: 100%; overflow-wrap: break-word; }
        .chat-image img { max-width: 260px; max-height: 300px; border-radius: 0.75rem; object-fit: cover; display: block; }
        .reply-btn { opacity: 0; transition: opacity 0.15s; }
        .msg-group:hover .reply-btn { opacity: 1; }
        body { background: #f8fafc; margin: 0; }
    </style>
</head>
<body class="bg-slate-50">

<div class="h-screen flex flex-col bg-slate-50 overflow-hidden">
    <!-- Premium Header -->
    <header class="h-16 border-b border-slate-200 bg-white px-4 md:px-6 flex items-center justify-between shrink-0 shadow-sm z-10">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-green-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                <i class="fa-solid fa-seedling text-lg"></i>
            </div>
            <div>
                <h1 class="text-sm font-bold text-slate-900"><?= $consultationTitle ?></h1>
                <p class="text-[10px] text-slate-500 font-medium">Grape Cultivation Consultation</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <div class="hidden sm:flex items-center space-x-2 bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-full text-[10px] font-semibold">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Live Consultation</span>
            </div>
            <a href="<?= BASE_URL ?>/consultations" class="h-9 w-9 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </a>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden">
        <!-- Main Chat Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Messages -->
            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4 bg-slate-50/50">
                <div id="messages-container" class="space-y-4"></div>
                <div id="loading-messages" class="text-center text-xs text-slate-400 py-8">
                    <i class="fa-solid fa-spinner animate-spin"></i> Loading messages...
                </div>
            </div>

            <!-- Typing Indicator -->
            <div id="typing-indicator" class="hidden px-6 py-2 bg-white border-t border-slate-100 flex items-center space-x-2">
                <span class="flex space-x-1">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></span>
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay:0.2s"></span>
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay:0.4s"></span>
                </span>
                <span class="text-xs text-slate-400">Expert is typing...</span>
            </div>

            <!-- Reply Bar -->
            <div id="reply-bar" class="hidden px-4 py-2 bg-emerald-50 border-t border-emerald-200 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2 text-xs text-emerald-700 min-w-0">
                    <i class="fa-solid fa-reply"></i>
                    <span class="whitespace-nowrap">Replying to <strong id="reply-to-name"></strong></span>
                    <span class="text-emerald-400 shrink-0">·</span>
                    <span id="reply-to-preview" class="text-emerald-500 truncate min-w-0"></span>
                </div>
                <button type="button" id="cancel-reply-btn" class="text-emerald-400 hover:text-emerald-600 transition-colors shrink-0 ml-2">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Input -->
            <div class="p-4 bg-white border-t border-slate-200 shrink-0">
                <form id="chat-form" class="flex items-center space-x-2">
                    <input type="text" id="message-input" placeholder="Type your message about the grape issue..." autofocus
                           class="flex-1 h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm placeholder-slate-400">
                    <input type="file" id="image-input" accept="image/*" class="hidden">
                    <button type="button" id="image-btn" class="h-11 w-11 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-400 hover:text-emerald-600 transition-colors" title="Send image">
                        <i class="fa-regular fa-image text-sm"></i>
                    </button>
                    <button type="submit" id="send-btn" class="h-11 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold flex items-center space-x-1.5 transition-colors disabled:opacity-50">
                        <span class="text-sm hidden sm:inline">Send</span>
                        <i class="fa-regular fa-paper-plane text-xs"></i>
                    </button>
                </form>
                <div class="flex justify-between items-center px-1 mt-1.5 text-[9px] text-slate-400">
                    <span><i class="fa-solid fa-leaf text-emerald-500 mr-1"></i> Grape Cultivation Advisory</span>
                    <span id="connection-status">Connecting...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const consultationId = <?= $consultationId ?>;
    const userId = <?= $userId ?>;
    const userName = '<?= htmlspecialchars($userName, ENT_QUOTES) ?>';
    const role = 'farmer';
    const baseUrl = '<?= BASE_URL ?>';

    let ws = null;
    let wsConnected = false;
    let reconnectTimer = null;
    let currentReplyTo = null;

    function connectWebSocket() {
        const wsUrl = `ws://localhost:8080?consultation_id=${consultationId}&user_id=${userId}&role=${role}`;
        ws = new WebSocket(wsUrl);

        ws.onopen = function() {
            wsConnected = true;
            document.getElementById('connection-status').innerHTML = '<i class="fa-solid fa-circle text-emerald-500 mr-1 text-[6px]"></i> Connected';
            if (reconnectTimer) { clearTimeout(reconnectTimer); reconnectTimer = null; }
        };

        ws.onmessage = function(event) {
            const data = JSON.parse(event.data);
            if (data.type === 'system') {
                appendSystemMessage(data.message);
            } else if (parseInt(data.sender_id) !== userId) {
                const replyInfo = data.reply_to ? { message: data.reply_to_message, sender: data.reply_to_sender } : null;
                if (data.message_type === 'image') {
                    appendImage(data.message, data.sender_name, false, data.created_at, null, replyInfo, data.caption);
                } else {
                    appendMessage(data.message, data.sender_name, false, data.created_at, null, replyInfo);
                }
            }
        };

        ws.onclose = function() {
            wsConnected = false;
            document.getElementById('connection-status').innerHTML = '<i class="fa-solid fa-circle text-amber-500 mr-1 text-[6px]"></i> Reconnecting...';
            if (!reconnectTimer) reconnectTimer = setTimeout(connectWebSocket, 3000);
        };

        ws.onerror = function() {
            wsConnected = false;
            document.getElementById('connection-status').innerHTML = '<i class="fa-solid fa-circle text-slate-300 mr-1 text-[6px]"></i> Offline (HTTP mode)';
        };
    }

    function buildReplyHtml(replyInfo) {
        if (!replyInfo) return '';
        const preview = replyInfo.message.length > 80 ? replyInfo.message.substring(0, 80) + '...' : replyInfo.message;
        return `
            <div class="border-l-2 ${replyInfo.isMineReply ? 'border-emerald-300' : 'border-slate-300'} pl-2 mb-1">
                <div class="text-[10px] ${replyInfo.isMineReply ? 'text-emerald-200' : 'text-slate-500'} font-medium">Replying to ${escapeHtml(replyInfo.sender)}</div>
                <div class="text-[10px] ${replyInfo.isMineReply ? 'text-emerald-200/70' : 'text-slate-400'} truncate max-w-[220px]">${escapeHtml(preview)}</div>
            </div>
        `;
    }

    function appendMessage(text, senderName, isMine, timestamp, messageId, replyInfo) {
        const container = document.getElementById('messages-container');
        const time = timestamp ? new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : 'Just now';
        const replyHtml = buildReplyHtml(replyInfo);
        const idAttr = messageId ? `data-msg-id="${messageId}"` : '';

        const div = document.createElement('div');
        if (isMine) {
            div.className = 'flex justify-end mb-4 msg-group';
            div.innerHTML = `
                <div class="max-w-[80%]" ${idAttr}>
                    ${replyHtml}
                    <div class="bg-emerald-600 text-white px-4 py-2.5 rounded-2xl rounded-tr-none shadow-sm text-sm w-fit max-w-full break-words ml-auto">
                        <p class="leading-relaxed">${escapeHtml(text)}</p>
                    </div>
                    <span class="text-[9px] text-slate-400 mt-1 block text-right">
                        ${time}
                        ${messageId ? `<button class="reply-btn text-[9px] text-slate-400 hover:text-emerald-600 transition-colors ml-2" onclick="startReply(${messageId},'${escapeHtml(senderName)}')"><i class="fa-solid fa-reply"></i></button>` : ''}
                    </span>
                </div>
            `;
        } else {
            div.className = 'flex justify-start mb-4 msg-group';
            div.innerHTML = `
                <div class="max-w-[80%]" ${idAttr}>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center text-white text-[8px] font-bold">E</div>
                        <span class="text-[10px] font-semibold text-slate-500">${escapeHtml(senderName)}</span>
                    </div>
                    ${replyHtml}
                    <div class="bg-white text-slate-800 px-4 py-2.5 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 text-sm w-fit max-w-full break-words">
                        <p class="leading-relaxed">${escapeHtml(text)}</p>
                    </div>
                    <span class="text-[9px] text-slate-400 mt-1">
                        ${time}
                        ${messageId ? `<button class="reply-btn text-[9px] text-slate-400 hover:text-emerald-600 transition-colors ml-2" onclick="startReply(${messageId},'${escapeHtml(senderName)}')"><i class="fa-solid fa-reply"></i></button>` : ''}
                    </span>
                </div>
            `;
        }
        container.appendChild(div);
        scrollToBottom();
    }

    function appendImage(src, senderName, isMine, timestamp, messageId, replyInfo, caption) {
        const container = document.getElementById('messages-container');
        const time = timestamp ? new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : 'Just now';
        const imgUrl = src.startsWith('data:') ? src : baseUrl + '/' + src;
        const replyHtml = buildReplyHtml(replyInfo);
        const idAttr = messageId ? `data-msg-id="${messageId}"` : '';
        const captionHtml = caption ? `<p class="text-sm px-1 pt-1.5 pb-0.5 leading-relaxed ${isMine ? 'text-white' : 'text-slate-800'}">${escapeHtml(caption)}</p>` : '';

        const div = document.createElement('div');
        if (isMine) {
            div.className = 'flex justify-end mb-4 msg-group';
            div.innerHTML = `
                <div class="max-w-[80%]" ${idAttr}>
                    ${replyHtml}
                    <div class="bg-emerald-600 px-2 pt-2 pb-1 rounded-2xl rounded-tr-none shadow-sm w-fit max-w-full ml-auto chat-image">
                        <img src="${imgUrl}" alt="Sent image" class="rounded-xl">${captionHtml}
                    </div>
                    <span class="text-[9px] text-slate-400 mt-1 block text-right">
                        ${time}
                        ${messageId ? `<button class="reply-btn text-[9px] text-slate-400 hover:text-emerald-600 transition-colors ml-2" onclick="startReply(${messageId},'${escapeHtml(senderName)}')"><i class="fa-solid fa-reply"></i></button>` : ''}
                    </span>
                </div>
            `;
        } else {
            div.className = 'flex justify-start mb-4 msg-group';
            div.innerHTML = `
                <div class="max-w-[80%]" ${idAttr}>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center text-white text-[8px] font-bold">E</div>
                        <span class="text-[10px] font-semibold text-slate-500">${escapeHtml(senderName)}</span>
                    </div>
                    ${replyHtml}
                    <div class="bg-white px-2 pt-2 pb-1 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 w-fit max-w-full chat-image">
                        <img src="${imgUrl}" alt="Sent image" class="rounded-xl">${captionHtml}
                    </div>
                    <span class="text-[9px] text-slate-400 mt-1">
                        ${time}
                        ${messageId ? `<button class="reply-btn text-[9px] text-slate-400 hover:text-emerald-600 transition-colors ml-2" onclick="startReply(${messageId},'${escapeHtml(senderName)}')"><i class="fa-solid fa-reply"></i></button>` : ''}
                    </span>
                </div>
            `;
        }
        container.appendChild(div);
        scrollToBottom();
    }

    function appendSystemMessage(text) {
        const container = document.getElementById('messages-container');
        const div = document.createElement('div');
        div.className = 'flex justify-center my-2';
        div.innerHTML = `<span class="text-[9px] text-slate-400 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">${escapeHtml(text)}</span>`;
        container.appendChild(div);
        scrollToBottom();
    }

    function startReply(messageId, senderName) {
        const el = document.querySelector(`[data-msg-id="${messageId}"]`);
        if (!el) return;
        const textEl = el.querySelector('p, img');
        const messageText = textEl ? (textEl.tagName === 'IMG' ? '(Image)' : textEl.textContent) : '';
        currentReplyTo = { id: messageId, message: messageText, sender: senderName };
        document.getElementById('reply-to-name').textContent = senderName;
        document.getElementById('reply-to-preview').textContent = messageText.length > 60 ? messageText.substring(0, 60) + '...' : messageText;
        document.getElementById('reply-bar').classList.remove('hidden');
        document.getElementById('message-input').focus();
    }

    function cancelReply() {
        currentReplyTo = null;
        document.getElementById('reply-bar').classList.add('hidden');
    }

    function scrollToBottom() {
        const chat = document.getElementById('chat-messages');
        chat.scrollTop = chat.scrollHeight;
    }

    function escapeHtml(text) {
        if (text == null) return '';
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    document.getElementById('image-btn').addEventListener('click', function() {
        document.getElementById('image-input').click();
    });

    document.getElementById('cancel-reply-btn').addEventListener('click', cancelReply);

    fetch(`${baseUrl}/chat/history?consultation_id=${consultationId}`)
        .then(res => res.json())
        .then(messages => {
            document.getElementById('loading-messages').classList.add('hidden');
            messages.forEach(m => {
                const isMine = parseInt(m.sender_id) === userId;
                const replyInfo = m.reply_to ? { message: m.reply_to_message, sender: m.reply_to_sender, isMineReply: isMine } : null;
                if (m.message_type === 'system') {
                    appendSystemMessage(m.message);
                } else if (m.message_type === 'image') {
                    appendImage(m.message, m.sender_name, isMine, m.created_at, m.message_id, replyInfo, m.caption);
                } else {
                    appendMessage(m.message, m.sender_name, isMine, m.created_at, m.message_id, replyInfo);
                }
            });
            scrollToBottom();
        })
        .catch(() => {
            document.getElementById('loading-messages').innerHTML = 'Failed to load messages.';
        });

    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('message-input');
        const text = input.value.trim();
        const fileInput = document.getElementById('image-input');
        const file = fileInput.files[0];

        if (!text && !file) return;

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                appendImage(e.target.result, userName, true, null, null, currentReplyTo ? { message: currentReplyTo.message, sender: currentReplyTo.sender, isMineReply: true } : null, text || null);
            };
            reader.readAsDataURL(file);
        } else {
            appendMessage(text, userName, true, null, null, currentReplyTo ? { message: currentReplyTo.message, sender: currentReplyTo.sender, isMineReply: true } : null);
        }

        const formData = new FormData();
        formData.append('consultation_id', consultationId);
        if (file) formData.append('image', file);
        if (!file && text) formData.append('message', text);
        if (file && text) formData.append('caption', text);
        if (currentReplyTo) formData.append('reply_to', currentReplyTo.id);

        fetch(`${baseUrl}/chat/send`, { method: 'POST', body: formData })
            .then(res => { if (!res.ok) throw new Error('HTTP ' + res.status); return res.json(); })
            .then(data => {
                console.log('Message saved, id:', data.id);
                if (ws && ws.readyState === WebSocket.OPEN) {
                    ws.send(JSON.stringify({
                        message: data.message,
                        message_type: data.message_type || (file ? 'image' : 'text'),
                        image_path: file ? data.message : null,
                        reply_to: data.reply_to,
                        reply_to_message: data.reply_to_message,
                        reply_to_sender: data.reply_to_sender,
                        caption: text && file ? text : null,
                        sender_name: userName
                    }));
                }
            })
            .catch(err => console.error('Persist failed:', err));

        input.value = '';
        fileInput.value = '';
        cancelReply();
    });

    connectWebSocket();
</script>
</body>
</html>
