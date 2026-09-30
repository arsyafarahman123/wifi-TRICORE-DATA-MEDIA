<!-- TRICORE DATA MEDIA - Virtual Assistant Chatbot Widget -->
<div id="tricore-chatbot" class="fixed bottom-5 right-5 z-50 flex flex-col items-end font-sans">

    <!-- Floating Action Buttons Row -->
    <div class="flex items-center gap-2.5">
        <!-- Direct WhatsApp "Hubungi Kami" Floating Button -->
        <a href="https://wa.me/6282138413292?text=Halo%20TRICORE%20DATA%20MEDIA,%20saya%20tertarik%20dengan%20layanan%20WiFi%20Fiber%20Optic%20Purwokerto.%20Mohon%20informasinya." 
           target="_blank" 
           rel="noopener noreferrer"
           title="Hubungi CS via WhatsApp"
           class="group relative flex items-center gap-2 px-3.5 py-3 rounded-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold shadow-xl shadow-emerald-500/40 hover:scale-105 active:scale-95 transition-all duration-300">
            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            <span class="text-xs tracking-wide hidden sm:inline">Hubungi Kami</span>
        </a>

        <!-- Chat Launcher Floating Button -->
        <button id="chatbot-toggle-btn"
                type="button"
                aria-label="Buka Chatbot TRICORE Assistant"
                class="group relative flex items-center gap-3 px-4 py-3.5 rounded-full bg-gradient-to-r from-cyan-500 via-blue-600 to-emerald-500 text-slate-950 font-bold shadow-2xl shadow-cyan-500/40 hover:shadow-cyan-400/60 hover:scale-105 active:scale-95 transition-all duration-300">
            
            <!-- Ambient Glow Ripple -->
            <span class="absolute -inset-1 rounded-full bg-gradient-to-r from-cyan-400 to-emerald-400 opacity-60 blur-md group-hover:opacity-100 transition duration-500 -z-10 animate-pulse"></span>
            
            <!-- Bot Icon / Avatar -->
            <div class="relative w-9 h-9 rounded-full bg-[#090d16] flex items-center justify-center text-cyan-400 shadow-inner">
                <svg id="chat-icon-open" class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <svg id="chat-icon-close" class="w-5 h-5 hidden transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <!-- Online status green dot -->
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-[#090d16]"></span>
            </div>

            <!-- Button Label -->
            <div class="text-left leading-tight pr-1">
                <div class="text-[13px] font-extrabold tracking-wide text-white drop-shadow-sm flex items-center gap-1.5">
                    <span>Tanya Tricore Bot</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-ping"></span>
                </div>
                <div class="text-[10px] font-medium text-cyan-100 opacity-90">Bantuan 24/7 & Cek Tagihan</div>
            </div>
        </button>
    </div>

    <!-- Chat Box Window -->
    <div id="chatbot-window"
         class="hidden flex flex-col w-[360px] sm:w-[410px] h-[580px] max-h-[85vh] mb-3 bg-[#0c121e]/95 backdrop-blur-xl border border-cyan-500/30 rounded-2xl shadow-2xl shadow-cyan-950/90 overflow-hidden transition-all duration-300 origin-bottom-right">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3.5 bg-gradient-to-r from-slate-900 via-slate-800 to-cyan-950/80 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="relative w-9 h-9 rounded-xl bg-gradient-to-tr from-cyan-500 to-emerald-500 p-0.5 shadow-md">
                    <div class="w-full h-full bg-[#090d16] rounded-[10px] flex items-center justify-center text-cyan-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-[#090d16]"></span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-1.5 leading-tight">
                        TRICORE Assistant
                        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">AI Verified</span>
                    </h3>
                    <p class="text-[11px] text-emerald-400 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Asisten ISP Aktif 24 Jam
                    </p>
                </div>
            </div>

            <!-- Action Controls -->
            <div class="flex items-center gap-1">
                <!-- Reset Chat -->
                <button type="button"
                        id="chatbot-reset-btn"
                        title="Reset Percakapan"
                        class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
                <!-- Close Button -->
                <button type="button"
                        id="chatbot-close-btn"
                        title="Tutup Chat"
                        class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Quick Suggestions Horizontal Scroll Bar -->
        <div class="px-3 py-2 bg-slate-950/80 border-b border-white/5 overflow-x-auto whitespace-nowrap flex gap-1.5 text-[11px] no-scrollbar">
            <button type="button" data-prompt="Cek Tagihan" class="chatbot-pill-btn shrink-0 px-2.5 py-1 rounded-full bg-slate-800 hover:bg-cyan-900/60 text-slate-300 hover:text-cyan-300 border border-white/10 hover:border-cyan-500/40 transition">
                💳 Cek Tagihan
            </button>
            <button type="button" data-prompt="Lampu LOS merah" class="chatbot-pill-btn shrink-0 px-2.5 py-1 rounded-full bg-slate-800 hover:bg-rose-900/60 text-slate-300 hover:text-rose-300 border border-white/10 hover:border-rose-500/40 transition">
                🔴 LOS Merah
            </button>
            <button type="button" data-prompt="Ping game tinggi" class="chatbot-pill-btn shrink-0 px-2.5 py-1 rounded-full bg-slate-800 hover:bg-amber-900/60 text-slate-300 hover:text-amber-300 border border-white/10 hover:border-amber-500/40 transition">
                🎮 Ping / RTO
            </button>
            <button type="button" data-prompt="Cara ganti password wifi" class="chatbot-pill-btn shrink-0 px-2.5 py-1 rounded-full bg-slate-800 hover:bg-emerald-900/60 text-slate-300 hover:text-emerald-300 border border-white/10 hover:border-emerald-500/40 transition">
                🔑 Ganti Password
            </button>
            <button type="button" data-prompt="Daftar paket internet" class="chatbot-pill-btn shrink-0 px-2.5 py-1 rounded-full bg-slate-800 hover:bg-blue-900/60 text-slate-300 hover:text-blue-300 border border-white/10 hover:border-blue-500/40 transition">
                ⚡ Paket WiFi
            </button>
        </div>

        <!-- Message Body Container -->
        <div id="chatbot-messages" class="flex-1 p-4 overflow-y-auto space-y-3.5 text-xs sm:text-sm scroll-smooth bg-[#080d17]/70">
            <!-- Messages inserted dynamically via JS -->
        </div>

        <!-- Typing Indicator -->
        <div id="chatbot-typing" class="hidden px-4 py-2 bg-[#080d17]/90 flex items-center gap-2 text-slate-400 text-xs border-t border-white/5">
            <div class="flex gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-bounce" style="animation-delay: 0s;"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-bounce" style="animation-delay: 0.15s;"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-bounce" style="animation-delay: 0.3s;"></span>
            </div>
            <span class="text-[11px] text-cyan-300">TRICORE Bot sedang merespon...</span>
        </div>

        <!-- Input Footer Form -->
        <form id="chatbot-form" class="p-3 bg-slate-900/95 border-t border-white/10 flex items-center gap-2">
            @csrf
            <input type="text"
                   id="chatbot-input"
                   autocomplete="off"
                   placeholder="Ketik kendala WiFi atau ID (TDM-2601)..."
                   class="flex-1 bg-slate-950 text-white placeholder-slate-500 text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-white/10 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
            <button type="submit"
                    id="chatbot-send-btn"
                    aria-label="Kirim Pesan"
                    class="p-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold hover:brightness-110 active:scale-95 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-md shadow-cyan-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('chatbot-toggle-btn');
    const closeBtn = document.getElementById('chatbot-close-btn');
    const resetBtn = document.getElementById('chatbot-reset-btn');
    const chatWindow = document.getElementById('chatbot-window');
    const iconOpen = document.getElementById('chat-icon-open');
    const iconClose = document.getElementById('chat-icon-close');
    const messagesContainer = document.getElementById('chatbot-messages');
    const typingIndicator = document.getElementById('chatbot-typing');
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('chatbot-input');
    const pillButtons = document.querySelectorAll('.chatbot-pill-btn');
    const endpoint = "{{ route('chatbot.message') }}";
    const csrfToken = "{{ csrf_token() }}";

    let chatHistory = [];
    let isOpen = false;

    // Toggle Chat Window
    function toggleChat() {
        isOpen = !isOpen;
        if (isOpen) {
            chatWindow.classList.remove('hidden');
            iconOpen.classList.add('hidden');
            iconClose.classList.remove('hidden');
            input.focus();
            if (chatHistory.length === 0) {
                sendIntent('menu');
            } else {
                scrollToBottom();
            }
        } else {
            chatWindow.classList.add('hidden');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
        }
    }

    if (toggleBtn) toggleBtn.addEventListener('click', toggleChat);
    if (closeBtn) closeBtn.addEventListener('click', toggleChat);

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            chatHistory = [];
            messagesContainer.innerHTML = '';
            sessionStorage.removeItem('tricore_chat_history');
            sendIntent('menu');
        });
    }

    // Scroll to bottom helper
    function scrollToBottom() {
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 50);
    }

    // Parse Markdown to HTML
    function formatMarkdown(text) {
        if (!text) return '';
        let escaped = text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        // Bold **text**
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-white">$1</strong>');
        // Italic *text* or _text_
        escaped = escaped.replace(/_(.*?)_/g, '<em class="italic text-slate-300">$1</em>');
        escaped = escaped.replace(/\*(.*?)\*/g, '<em class="italic text-slate-300">$1</em>');
        // Inline code `code`
        escaped = escaped.replace(/`([^`]+)`/g, '<code class="px-1.5 py-0.5 rounded bg-slate-800 text-cyan-300 font-mono text-[11px] border border-cyan-500/20">$1</code>');
        // Line breaks
        escaped = escaped.replace(/\n/g, '<br/>');

        return escaped;
    }

    // Append Message to UI
    function appendMessage(sender, data) {
        const isBot = sender === 'bot';
        const msgWrapper = document.createElement('div');
        msgWrapper.className = `flex gap-2.5 ${isBot ? 'items-start' : 'items-end justify-end'}`;

        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        if (isBot) {
            let contentHtml = `
                <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-cyan-500 to-emerald-500 p-0.5 shrink-0 mt-0.5 shadow-md">
                    <div class="w-full h-full bg-[#090d16] rounded-[6px] flex items-center justify-center text-cyan-400 text-[10px] font-bold">
                        ⚡
                    </div>
                </div>
                <div class="flex-1 max-w-[88%] space-y-2">
                    <div class="bg-slate-800/95 border border-white/10 text-slate-200 p-3.5 rounded-2xl rounded-tl-sm text-xs leading-relaxed shadow-xl">
                        <div>${formatMarkdown(data.text || data)}</div>
                        <div class="flex items-center justify-between mt-2 pt-1.5 border-t border-white/5 text-[10px] text-slate-400">
                            <span class="text-emerald-400 flex items-center gap-1 font-medium">✓ Terverifikasi SOP</span>
                            <span>${time}</span>
                        </div>
                    </div>`;

            // Render Quick Replies
            if (data.quick_replies && data.quick_replies.length > 0) {
                contentHtml += `<div class="flex flex-wrap gap-1.5 pt-1">`;
                data.quick_replies.forEach((btn) => {
                    if (btn.url) {
                        contentHtml += `
                            <a href="${btn.url}" target="${btn.url.startsWith('http') ? '_blank' : '_self'}"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-medium bg-cyan-950/80 hover:bg-cyan-900 text-cyan-300 border border-cyan-500/30 hover:border-cyan-400 transition transform active:scale-95 shadow-sm">
                                ${btn.label}
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>`;
                    } else {
                        contentHtml += `
                            <button type="button"
                                    data-intent="${btn.intent || ''}"
                                    data-label="${btn.label}"
                                    class="chatbot-quick-reply-btn inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-medium bg-slate-800/95 hover:bg-slate-700 text-slate-200 border border-white/10 hover:border-cyan-400 hover:text-cyan-300 transition transform active:scale-95 shadow-sm">
                                ${btn.label}
                            </button>`;
                    }
                });
                contentHtml += `</div>`;
            }

            contentHtml += `</div>`;
            msgWrapper.innerHTML = contentHtml;
        } else {
            msgWrapper.innerHTML = `
                <div class="max-w-[80%] bg-gradient-to-r from-cyan-600 to-blue-600 text-white p-3 rounded-2xl rounded-tr-sm text-xs leading-relaxed shadow-lg">
                    <div>${formatMarkdown(data.text || data)}</div>
                    <span class="block text-[10px] text-cyan-200 mt-1 text-right">${time}</span>
                </div>`;
        }

        messagesContainer.appendChild(msgWrapper);
        scrollToBottom();

        // Attach event listener for quick reply buttons
        msgWrapper.querySelectorAll('.chatbot-quick-reply-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const intent = this.getAttribute('data-intent');
                const label = this.getAttribute('data-label');
                sendIntent(intent, label);
            });
        });
    }

    // Send Intent to backend
    function sendIntent(intentName, displayText) {
        if (displayText) {
            appendMessage('user', { text: displayText });
        }
        sendToBot({ intent: intentName });
    }

    // Send message to bot API
    async function sendToBot(payload) {
        showTyping(true);
        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                throw new Error('Gagal menghubungi server');
            }

            const data = await response.json();
            setTimeout(() => {
                showTyping(false);
                appendMessage('bot', data);
            }, 350);
        } catch (error) {
            showTyping(false);
            appendMessage('bot', {
                text: "⚠️ Terjadi gangguan koneksi. Silakan hubungi CS WhatsApp kami di +62 821-3841-3292 atau coba sesaat lagi.",
                quick_replies: [
                    { label: '💬 Chat WhatsApp CS', intent: 'contact_cs' },
                    { label: '🔙 Menu Utama', intent: 'menu' }
                ]
            });
        }
    }

    function showTyping(show) {
        if (show) {
            typingIndicator.classList.remove('hidden');
            scrollToBottom();
        } else {
            typingIndicator.classList.add('hidden');
        }
    }

    // Quick Pill buttons handler
    pillButtons.forEach(pill => {
        pill.addEventListener('click', function () {
            const promptText = this.getAttribute('data-prompt');
            appendMessage('user', { text: promptText });
            sendToBot({ message: promptText });
        });
    });

    // Handle Form Submit
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;

        appendMessage('user', { text: text });
        input.value = '';
        sendToBot({ message: text });
    });
});
</script>
