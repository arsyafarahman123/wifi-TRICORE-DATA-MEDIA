<!-- TRINET-BILL - Professional Customer Support & Helpdesk Widget -->
<div id="tricore-chatbot" class="fixed bottom-5 right-5 z-50 flex flex-col items-end font-sans">

    <!-- Floating Action Buttons Row -->
    <div class="flex items-center gap-2.5">
        <!-- Direct WhatsApp "WhatsApp CS" Floating Button -->
        <a href="https://wa.me/6282138413292?text=Halo%20TRINET-BILL%20TRICORE%20DATA%20MEDIA,%20saya%20butuh%20bantuan%20layanan%20WiFi." 
           target="_blank" 
           rel="noopener noreferrer"
           title="Hubungi CS via WhatsApp"
           class="group relative flex items-center gap-2 px-3.5 py-3 rounded-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold shadow-xl shadow-emerald-500/40 hover:scale-105 active:scale-95 transition-all duration-300">
            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            <span class="text-xs tracking-wide hidden sm:inline">WhatsApp CS</span>
        </a>

        <!-- Live Support / Chatbot Floating Button (Tampilan Lama) -->
        <button id="chatbot-toggle-btn"
                type="button"
                aria-label="Buka Live Support TRINET-BILL"
                class="group relative flex items-center gap-3 px-4 py-3.5 rounded-full bg-slate-900 border border-cyan-500/30 text-white font-bold shadow-2xl shadow-cyan-950/80 hover:border-cyan-400 hover:scale-105 active:scale-95 transition-all duration-300">
            
            <!-- Glow Accent -->
            <span class="absolute -inset-0.5 rounded-full bg-gradient-to-r from-cyan-500 to-emerald-500 opacity-40 blur-sm group-hover:opacity-75 transition duration-500 -z-10"></span>
            
            <!-- Support Headset Icon -->
            <div class="relative w-9 h-9 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-slate-950 shadow-md">
                <svg id="chat-icon-open" class="w-5 h-5 text-slate-950 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 18v-6a9 9 0 0118 0v6M3 14a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H5a2 2 0 00-2 2zm18 0a2 2 0 00-2-2h-1a2 2 0 00-2 2v3a2 2 0 002 2h1a2 2 0 002-2zM9 20h6a2 2 0 002-2v-1H7v1a2 2 0 002 2z" />
                </svg>
                <svg id="chat-icon-close" class="w-5 h-5 hidden transition-transform text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <!-- Online status dot -->
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-[#090d16]"></span>
            </div>

            <!-- Button Label -->
            <div class="text-left leading-tight pr-1">
                <div class="text-[13px] font-extrabold tracking-wide text-white drop-shadow-sm flex items-center gap-1.5">
                    <span>Live Support</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                </div>
                <div class="text-[10px] font-medium text-cyan-300">TRINET-BILL Helpdesk 24/7</div>
            </div>
        </button>
    </div>

    <!-- Chat Box Window (Ukuran Compact & Ramping 350px-360px) -->
    <div id="chatbot-window"
         style="width: 360px; max-width: calc(100vw - 28px); height: 470px; max-height: 75vh;"
         class="hidden flex flex-col mb-3 bg-[#0c121e]/98 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-2xl shadow-cyan-950/90 overflow-hidden transition-all duration-300 origin-bottom-right">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-3.5 py-2.5 bg-slate-900 border-b border-white/10">
            <div class="flex items-center gap-2">
                <div class="relative w-7 h-7 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-slate-950 shadow-md">
                    <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span class="absolute -bottom-0.5 -right-0.5 w-2 h-2 rounded-full bg-emerald-400 border border-slate-900"></span>
                </div>
                <div>
                    <h3 class="text-xs sm:text-[13px] font-bold text-white flex items-center gap-1 leading-tight">
                        AI Assistant TRINET
                        <span class="text-[9px] font-semibold px-1 py-0.2 rounded bg-emerald-500/20 text-emerald-300">Online</span>
                    </h3>
                    <p class="text-[10px] text-slate-400">
                        Helpdesk Pelanggan TRICORE
                    </p>
                </div>
            </div>

            <!-- Action Controls -->
            <div class="flex items-center gap-1">
                <!-- Reset Chat -->
                <button type="button"
                        id="chatbot-reset-btn"
                        title="Reset Percakapan"
                        class="p-1 rounded-md text-slate-400 hover:text-white hover:bg-white/10 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
                <!-- Close Button -->
                <button type="button"
                        id="chatbot-close-btn"
                        title="Tutup Chat"
                        class="p-1 rounded-md text-slate-400 hover:text-white hover:bg-white/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Quick Suggestions Horizontal Scroll Bar -->
        <div class="px-2.5 py-1.5 bg-slate-950 border-b border-white/5 overflow-x-auto whitespace-nowrap flex gap-1 text-[10px] no-scrollbar">
            <button type="button" data-intent="packages" class="chatbot-pill-btn shrink-0 inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-gradient-to-r from-cyan-500/20 to-emerald-500/20 hover:from-cyan-500/30 hover:to-emerald-500/30 text-cyan-300 border border-cyan-500/40 font-bold transition">
                <svg class="w-3.5 h-3.5 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Pilihan Paket (Rekomendasi)
            </button>
            <button type="button" data-intent="recom_start" class="chatbot-pill-btn shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-800/90 hover:bg-slate-700 text-slate-300 hover:text-white border border-white/10 transition">
                <svg class="w-3.5 h-3.5 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                Konsultasi 4 Langkah
            </button>
            <button type="button" data-prompt="Cek Tagihan" class="chatbot-pill-btn shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-800/90 hover:bg-cyan-900/60 text-slate-300 hover:text-cyan-300 border border-white/10 hover:border-cyan-500/40 transition">
                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2" stroke-width="2"/><path d="M2 10h20" stroke-width="2"/></svg>
                Cek Tagihan
            </button>
            <button type="button" data-prompt="Diagnosis Gangguan Jaringan" class="chatbot-pill-btn shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-800/90 hover:bg-purple-900/60 text-slate-300 hover:text-purple-300 border border-white/10 hover:border-purple-500/40 transition">
                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Diagnosis Pakar (RBR)
            </button>
            <button type="button" data-intent="packages" class="chatbot-pill-btn shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-800/90 hover:bg-blue-900/60 text-slate-300 hover:text-blue-300 border border-white/10 hover:border-blue-500/40 transition">
                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Pilihan Paket
            </button>
            <button type="button" data-prompt="Lampu LOS merah" class="chatbot-pill-btn shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-800/90 hover:bg-rose-900/60 text-slate-300 hover:text-rose-300 border border-white/10 hover:border-rose-500/40 transition">
                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Kendala LOS Merah
            </button>
            <button type="button" data-prompt="Coverage area" class="chatbot-pill-btn shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-800/90 hover:bg-emerald-900/60 text-slate-300 hover:text-emerald-300 border border-white/10 hover:border-emerald-500/40 transition">
                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Area Cakupan
            </button>
        </div>

        <!-- Message Body Container -->
        <div id="chatbot-messages" class="flex-1 p-3 overflow-y-auto space-y-2.5 text-xs scroll-smooth bg-[#080d17]/95">
            <!-- Messages inserted dynamically via JS -->
        </div>

        <!-- Typing Indicator -->
        <div id="chatbot-typing" class="hidden px-3 py-1.5 bg-[#080d17] flex items-center gap-1.5 text-slate-400 text-[11px] border-t border-white/5">
            <div class="flex gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-bounce" style="animation-delay: 0s;"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-bounce" style="animation-delay: 0.15s;"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-bounce" style="animation-delay: 0.3s;"></span>
            </div>
            <span class="text-[10px] text-cyan-300">Asisten sedang mengetik...</span>
        </div>

        <!-- Input Footer Form -->
        <form id="chatbot-form" class="p-2.5 bg-slate-900/95 border-t border-white/10 flex items-center gap-1.5">
            <input type="text"
                   id="chatbot-input"
                   autocomplete="off"
                   placeholder="Ketik pertanyaan atau nomor ID..."
                   class="flex-1 bg-slate-950 text-white placeholder-slate-500 text-xs px-3 py-2 rounded-xl border border-white/10 focus:outline-none focus:border-cyan-400 transition" />
            <button type="submit"
                    id="chatbot-send-btn"
                    aria-label="Kirim Pesan"
                    class="p-2 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold hover:brightness-110 active:scale-95 transition disabled:opacity-50 shadow">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let isOpen = false;
    let chatHistory = [];
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
    const endpoint = "/chatbot/message";
    const csrfToken = "{{ csrf_token() }}";

    // Global trigger for Recommendation System from any button on page
    window.triggerChatbotRecommendation = function() {
        if (!isOpen) {
            toggleChat();
        }
        sendIntent('packages', 'Pilihan Paket Rekomendasi');
    };

    // Global trigger to open Chatbot Assistant directly
    window.triggerChatbotOpen = function() {
        if (!isOpen) {
            toggleChat();
        }
    };

    // Toggle Chat Window
    function toggleChat() {
        isOpen = !isOpen;
        if (isOpen) {
            chatWindow.classList.remove('hidden');
            iconOpen.classList.add('hidden');
            iconClose.classList.remove('hidden');
            input.focus();
            if (chatHistory.length === 0) {
                sendIntent('packages');
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
            sessionStorage.removeItem('trinet_chat_history');
            sendIntent('packages');
        });
    }

    function scrollToBottom() {
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 50);
    }

    function sanitizeEmoji(str) {
        if (!str) return '';
        return str.replace(/[\u{1F300}-\u{1F9FF}\u{2600}-\u{27BF}\u{2300}-\u{23FF}\u{2B50}\u{2B55}\u{FE00}-\u{FEFF}\u{1F1E6}-\u{1F1FF}\u{1F680}-\u{1F6FF}\u{1F900}-\u{1F9FF}\u{1FA70}-\u{1FAFF}\u{200D}\u{2700}-\u{27BF}]/gu, '').trim();
    }

    function getQuickReplyIcon(label) {
        const l = (label || '').toLowerCase();
        if (l.includes('whatsapp') || l.includes('wa ') || l.includes(' cs')) {
            return `<svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>`;
        }
        if (l.includes('tagihan') || l.includes('invoice') || l.includes('bayar') || l.includes('rekening') || l.includes('transfer')) {
            return `<svg class="w-3.5 h-3.5 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2" stroke-width="2"/><path d="M2 10h20" stroke-width="2"/></svg>`;
        }
        if (l.includes('paket') || l.includes('speed') || l.includes('internet')) {
            return `<svg class="w-3.5 h-3.5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>`;
        }
        if (l.includes('los') || l.includes('merah') || l.includes('kendala') || l.includes('gangguan')) {
            return `<svg class="w-3.5 h-3.5 text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`;
        }
        if (l.includes('coverage') || l.includes('area') || l.includes('wilayah') || l.includes('cakupan') || l.includes('alamat')) {
            return `<svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`;
        }
        if (l.includes('sandi') || l.includes('password') || l.includes('wifi')) {
            return `<svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>`;
        }
        if (l.includes('menu') || l.includes('utama')) {
            return `<svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>`;
        }
        if (l.includes('daftar') || l.includes('registrasi')) {
            return `<svg class="w-3.5 h-3.5 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>`;
        }
        return `<svg class="w-3.5 h-3.5 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>`;
    }

    function formatMarkdown(text) {
        if (!text) return '';
        let escaped = sanitizeEmoji(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-white">$1</strong>');
        escaped = escaped.replace(/_(.*?)_/g, '<em class="italic text-slate-300">$1</em>');
        escaped = escaped.replace(/\*(.*?)\*/g, '<em class="italic text-slate-300">$1</em>');
        escaped = escaped.replace(/`([^`]+)`/g, '<code class="px-1.5 py-0.5 rounded bg-slate-800 text-cyan-300 font-mono text-[11px] border border-cyan-500/20">$1</code>');
        escaped = escaped.replace(/\n/g, '<br/>');

        return escaped;
    }

    function appendMessage(sender, data) {
        const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        const msgWrapper = document.createElement('div');
        msgWrapper.className = `flex flex-col ${sender === 'user' ? 'items-end' : 'items-start'} space-y-1.5`;

        if (sender === 'bot') {
            let contentHtml = `
                <div class="max-w-[88%] bg-slate-900 text-slate-200 border border-white/10 p-3.5 rounded-2xl rounded-tl-sm text-xs sm:text-[13px] leading-relaxed shadow-lg space-y-2">
                    <div>${formatMarkdown(data.text || data)}</div>
                    <div class="flex items-center justify-between mt-2 pt-1.5 border-t border-white/5 text-[10px] text-slate-400">
                        <span class="text-cyan-400 font-semibold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            TRINET-BILL Helpdesk
                        </span>
                        <span>${time}</span>
                    </div>
                </div>`;

            if (data.quick_replies && data.quick_replies.length > 0) {
                contentHtml += `<div class="flex flex-wrap gap-1.5 pt-1">`;
                data.quick_replies.forEach((btn) => {
                    const cleanLabel = sanitizeEmoji(btn.label);
                    const iconSvg = getQuickReplyIcon(cleanLabel);
                    if (btn.url) {
                        const isChoosePackage = cleanLabel.toUpperCase().includes('PILIH PAKET');
                        const btnClass = isChoosePackage 
                            ? 'bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-extrabold border-none shadow-md shadow-emerald-500/30' 
                            : 'bg-cyan-950/90 hover:bg-cyan-900 text-cyan-300 border border-cyan-500/30 hover:border-cyan-400 font-semibold shadow-sm';
                        contentHtml += `
                            <a href="${btn.url}" target="${btn.url.startsWith('http') ? '_blank' : '_self'}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] ${btnClass} transition transform active:scale-95">
                                ${iconSvg}
                                <span>${cleanLabel}</span>
                                <svg class="w-3 h-3 opacity-70 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>`;
                    } else {
                        contentHtml += `
                            <button type="button"
                                    data-intent="${btn.intent || ''}"
                                    data-label="${cleanLabel}"
                                    class="chatbot-quick-reply-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-medium bg-slate-800 hover:bg-slate-700 text-slate-200 border border-white/10 hover:border-cyan-400 hover:text-cyan-300 transition transform active:scale-95 shadow-sm">
                                ${iconSvg}
                                <span>${cleanLabel}</span>
                            </button>`;
                    }
                });
                contentHtml += `</div>`;
            }

            msgWrapper.innerHTML = contentHtml;
        } else {
            msgWrapper.innerHTML = `
                <div class="max-w-[85%] bg-gradient-to-r from-cyan-600 to-blue-600 text-white p-3 rounded-2xl rounded-tr-sm text-xs leading-relaxed shadow-lg">
                    <div>${formatMarkdown(data.text || data)}</div>
                    <span class="block text-[10px] text-cyan-200 mt-1 text-right">${time}</span>
                </div>`;
        }

        messagesContainer.appendChild(msgWrapper);
        scrollToBottom();

        // Automatic page navigation/highlight if response contains package action
        if (data && data.action === 'highlight_package' && data.package_id) {
            setTimeout(() => {
                if (typeof highlightAndScrollPackage === 'function') {
                    highlightAndScrollPackage(data.package_id);
                } else {
                    const targetCard = document.getElementById('pkg-card-' + data.package_id);
                    if (targetCard) {
                        targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        targetCard.classList.add('ring-4', 'ring-cyan-400', 'ring-offset-4', 'ring-offset-slate-950', 'animate-pulse');
                        setTimeout(() => targetCard.classList.remove('animate-pulse'), 3500);
                    } else {
                        const paketSec = document.getElementById('paket');
                        if (paketSec) paketSec.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            }, 300);
        }

        msgWrapper.querySelectorAll('.chatbot-quick-reply-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const intent = this.getAttribute('data-intent');
                const label = this.getAttribute('data-label');
                sendIntent(intent, label);
            });
        });
    }

    function sendIntent(intentName, displayText) {
        if (displayText) {
            appendMessage('user', { text: displayText });
        }
        sendToBot({ intent: intentName });
    }

    // Client-Side Fallback Engine (Guarantees zero-failure response)
    function getFallbackResponse(payload) {
        const query = (payload.message || payload.intent || '').toLowerCase().trim();
        const waUrl = "https://wa.me/6282138413292?text=Halo%20CS%20TRINET-BILL,%20saya%20ingin%20konsultasi%20layanan.";

        // 1. Rekomendasi Step 1: Budget
        if (query === 'recom_start' || query === 'recom_budget' || query.includes('rekomendasi') || query.includes('konsultasi') || query.includes('cari paket')) {
            return {
                text: "Halo! Selamat datang di WiFi Tricore Data. Saya asisten virtual yang siap membantu menemukan paket internet yang sesuai dengan kebutuhan dan anggaran Anda. Mari kita cari paket yang paling cocok!\n\n**Langkah 1 dari 4: Anggaran Bulanan**\n\nBerapa anggaran bulanan yang disiapkan untuk berlangganan WiFi?",
                quick_replies: [
                    { label: 'Hemat (< Rp 130.000)', intent: 'recom_devices:low' },
                    { label: 'Standar (Rp 130rb - 160rb)', intent: 'recom_devices:medium' },
                    { label: 'Menengah (Rp 160rb - 190rb)', intent: 'recom_devices:high' },
                    { label: 'Bisnis / Pro (> Rp 190.000)', intent: 'recom_devices:ultra' },
                    { label: 'Menu Utama', intent: 'menu' }
                ]
            };
        }

        // 2. Rekomendasi Step 2: Devices
        if (query.startsWith('recom_devices:')) {
            const budget = query.split(':')[1] || 'medium';
            return {
                text: "**Langkah 2 dari 4: Jumlah Perangkat & Pengguna**\n\nBerapa jumlah orang atau perangkat yang akan menggunakan WiFi?",
                quick_replies: [
                    { label: '1 - 3 Perangkat', intent: `recom_activity:${budget}:1_3` },
                    { label: '3 - 5 Perangkat', intent: `recom_activity:${budget}:3_5` },
                    { label: '5 - 8 Perangkat', intent: `recom_activity:${budget}:5_8` },
                    { label: '8 - 12 Perangkat', intent: `recom_activity:${budget}:8_12` },
                    { label: '12+ Perangkat / Bisnis', intent: `recom_activity:${budget}:12_plus` },
                    { label: 'Ubah Anggaran', intent: 'recom_start' }
                ]
            };
        }

        // 3. Rekomendasi Step 3: Activity
        if (query.startsWith('recom_activity:')) {
            const parts = query.split(':');
            const budget = parts[1] || 'medium';
            const devices = parts[2] || '3_5';
            return {
                text: "**Langkah 3 dari 4: Kebutuhan Utama Penggunaan Internet**\n\nApa kebutuhan utama penggunaan internet Anda?",
                quick_replies: [
                    { label: 'Browsing & Media Sosial', intent: `recom_priority:${budget}:${devices}:browsing` },
                    { label: 'Belajar & Kerjakan Tugas', intent: `recom_priority:${budget}:${devices}:study` },
                    { label: 'Streaming Film & Video HD/4K', intent: `recom_priority:${budget}:${devices}:streaming` },
                    { label: 'Gaming Online Bebas Lag', intent: `recom_priority:${budget}:${devices}:gaming` },
                    { label: 'Video Conference & WFH', intent: `recom_priority:${budget}:${devices}:wfx` },
                    { label: 'Penggunaan Intensif Banyak User', intent: `recom_priority:${budget}:${devices}:intensive` },
                    { label: 'Ubah Pilihan Perangkat', intent: `recom_devices:${budget}` }
                ]
            };
        }

        // 4. Rekomendasi Step 4: Priority
        if (query.startsWith('recom_priority:')) {
            const parts = query.split(':');
            const budget = parts[1] || 'medium';
            const devices = parts[2] || '3_5';
            const act = parts[3] || 'browsing';
            return {
                text: "**Langkah 4 dari 4: Prioritas Kestabilan & Kecepatan**\n\nApakah koneksi digunakan untuk aktivitas biasa atau aktivitas yang membutuhkan kecepatan dan kestabilan lebih tinggi?",
                quick_replies: [
                    { label: 'Aktivitas Biasa (Standar)', intent: `recom_eval:${budget}:${devices}:${act}:standard` },
                    { label: 'Kecepatan & Kestabilan Tinggi (Prioritas)', intent: `recom_eval:${budget}:${devices}:${act}:high_speed` },
                    { label: 'Ubah Kebutuhan Internet', intent: `recom_activity:${budget}:${devices}` }
                ]
            };
        }

        // 5. Rekomendasi Step 5: Evaluasi Rule-Based Fallback
        if (query.startsWith('recom_eval:')) {
            const parts = query.split(':');
            const budget = parts[1] || 'medium';
            const devices = parts[2] || '3_5';
            const act = parts[3] || 'browsing';
            const prio = parts[4] || 'standard';

            let pkgName = 'Paket Family 20 Mbps';
            let speed = 20;
            let price = 'Rp 135.000';
            let pkgId = 2;
            let matchScore = 100;

            if (budget === 'low') {
                pkgName = 'Paket Hemat 15 Mbps';
                speed = 15;
                price = 'Rp 115.000';
                pkgId = 1;
            } else if (prio === 'high_speed' || act === 'gaming' || devices === '5_8') {
                pkgName = 'Paket Favorit 25 Mbps';
                speed = 25;
                price = 'Rp 150.000';
                pkgId = 3;
            } else if (devices === '8_12' || act === 'intensive') {
                pkgName = 'Paket Turbo 35 Mbps';
                speed = 35;
                price = 'Rp 170.000';
                pkgId = 4;
            } else if (devices === '12_plus' || budget === 'ultra') {
                pkgName = 'Paket Ultimate 50 Mbps';
                speed = 50;
                price = 'Rp 200.000';
                pkgId = 5;
            }

            const resultText = `Terima kasih sudah memberikan informasi!\n\nBerdasarkan anggaran, jumlah pengguna, dan kebutuhan internet Anda, berikut rekomendasi paket yang paling sesuai.\n\n━━━━━━━━━━━━━━━━━━━━\n⭐ **REKOMENDASI UTAMA** ⭐\n━━━━━━━━━━━━━━━━━━━━\n\n• **Nama Paket:** **${pkgName}**\n• **Harga:** **${price}** / bulan\n• **Kecepatan:** **${speed} Mbps** (Simetris 100% Fiber Optic)\n• **Tingkat Kecocokan:** **${matchScore}%**\n\n💡 **Alasan rekomendasi:**\n1. Harga sesuai alokasi anggaran bulanan yang Anda pilih.\n2. Kecepatan ${speed} Mbps sangat mencukupi kebutuhan aktivitas internet Anda.\n3. Kapasitas koneksi sangat pas untuk jumlah perangkat yang digunakan.\n\n━━━━━━━━━━━━━━━━━━━━\n📌 **ALTERNATIF PAKET LAINNYA:**\n• Paket Favorit 25 Mbps (25 Mbps) - Rp 150.000/bln\n• Paket Hemat 15 Mbps (15 Mbps) - Rp 115.000/bln\n\nApakah Anda ingin melihat detail paket atau langsung melanjutkan pemesanan?`;

            return {
                text: resultText,
                package_id: pkgId,
                action: 'highlight_package',
                quick_replies: [
                    { label: 'Pilih Paket Ini', url: `/?package_id=${pkgId}#daftar` },
                    { label: `Lihat Detail ${pkgName}`, intent: `recom_detail:${pkgId}` },
                    { label: 'Lihat Rekomendasi Lainnya', intent: `recom_cycle:${pkgId}` },
                    { label: 'Ubah Anggaran', intent: 'recom_start' },
                    { label: 'Konsultasi Ulang Dari Awal', intent: 'recom_start' },
                    { label: 'Menu Utama', intent: 'menu' }
                ]
            };
        }

        if (!query || query === 'menu' || query === 'halo' || query === 'hai' || query === 'p') {
            return {
                text: "Halo! Selamat datang di WiFi Tricore Data. Saya asisten virtual yang siap membantu menemukan paket internet yang sesuai dengan kebutuhan dan anggaran Anda. Mari kita cari paket yang paling cocok!\n\nSilakan pilih layanan di bawah atau mulai konsultasi rekomendasi:",
                quick_replies: [
                    { label: 'Rekomendasi Paket Otomatis', intent: 'packages' },
                    { label: 'Konsultasi Bertahap (4 Langkah)', intent: 'recom_start' },
                    { label: 'Cek Tagihan', intent: 'check_bill_prompt' },
                    { label: 'Kendala LOS Merah', intent: 'troubleshoot_los' },
                    { label: 'Coverage Area', intent: 'coverage' },
                    { label: 'Menu Utama', intent: 'menu' }
                ]
            };
        }

        if (query === 'check_bill_prompt' || query === 'cek tagihan' || query === 'tagihan') {
            return {
                text: "**Pengecekan Tagihan Pelanggan TRICORE**\n\nMau cek tagihan untuk nomor pelanggan atau nomor WhatsApp yang mana kak?\n\nSilakan ketik **Nomor ID Pelanggan** 11 digit (contoh: `26000000001`) atau nomor WhatsApp. Atau langsung klik salah satu pilihan di bawah ini:",
                quick_replies: [
                    { label: '26000000001 (Rizky Pratama)', intent: 'check_bill_tdm2601' },
                    { label: '26000000004 (Dwi Wahyuni)', intent: 'check_bill_tdm2604' },
                    { label: '26000000002 (Siti Nurhaliza)', intent: 'check_bill_tdm2602' },
                    { label: '26000000003 (Agus Setiawan)', intent: 'check_bill_tdm2603' },
                    { label: 'Buka Form Cek Tagihan', url: '{{ route('bill.check') }}' },
                    { label: 'Menu Utama', intent: 'menu' }
                ]
            };
        }

        // Factual bill details for demo accounts
        if (query.includes('26000000001') || query === 'check_bill_tdm2601') {
            return {
                text: "📊 **Rincian Tagihan Resmi TRINET-BILL**\n\n• **Nama Pelanggan:** **Rizky P***\n• **ID Pelanggan:** `26000000001`\n• **Paket Internet:** Paket Family (20 Mbps)\n• **Status Koneksi:** [ Aktif (Normal) ]\n• **Wilayah:** Arcawinangun, Kec. Purwokerto Timur\n\n💳 **Status Tagihan Bulan Berjalan:**\n• **No. Invoice:** `INV-202610-001`\n• **Periode:** Oktober 2026\n• **Total Tagihan:** **Rp 135.000**\n• **Status Pembayaran:** **[ LUNAS ]**\n• **Jatuh Tempo:** 20 Oktober 2026\n• **Lunas Dibayar:** 01/10/2026 09:15 WIB via Bank Neo Commerce\n\n**Rekening Pembayaran Resmi TRICORE:**\n• Bank Neo Commerce (BNC): `5859459401627129` a.n TRICORE DATA MEDIA",
                quick_replies: [
                    { label: 'Lihat Bukti Lunas', url: '/tagihan/26000000001' },
                    { label: 'Cek Nomor Lain', intent: 'check_bill_prompt' },
                    { label: 'Diagnosis Gangguan (RBR)', intent: 'diagnosis_menu' },
                    { label: 'Menu Utama', intent: 'menu' }
                ]
            };
        }

        if (query.includes('26000000004') || query === 'check_bill_tdm2604') {
            return {
                text: "📊 **Rincian Tagihan Resmi TRINET-BILL**\n\n• **Nama Pelanggan:** **Dwi W***\n• **ID Pelanggan:** `26000000004`\n• **Paket Internet:** Paket Turbo (35 Mbps)\n• **Status Koneksi:** [ Terisolir (Pending Bayar) ]\n• **Wilayah:** Sokaraja\n\n💳 **Status Tagihan Bulan Berjalan:**\n• **No. Invoice:** `INV-202610-004`\n• **Periode:** Oktober 2026\n• **Total Tagihan:** **Rp 170.000**\n• **Status Pembayaran:** **[ BELUM DIBAYAR ]**\n• **Jatuh Tempo:** 15 Oktober 2026\n\n**Rekening Pembayaran Resmi:**\n• Bank Neo Commerce (BNC / 490): `5859459401627129` a.n TRICORE DATA MEDIA",
                quick_replies: [
                    { label: 'Konfirmasi Bayar ke WA', url: waUrl },
                    { label: 'Buka Halaman Tagihan', url: '/tagihan/26000000004' },
                    { label: 'Cek Nomor Lain', intent: 'check_bill_prompt' },
                    { label: 'Menu Utama', intent: 'menu' }
                ]
            };
        }

        // Diagnosis Rule-Based Reasoning Prompt
        if (query.includes('diagnosis') || query.includes('rbr') || query === 'diagnosis_menu') {
            return {
                text: "**Sistem Cerdas Diagnosis Gangguan Jaringan (Rule-Based Reasoning)**\n\nSistem pakar TRINET-BILL dapat mendiagnosis akar masalah kendala internet Anda berdasarkan indikator fisik modem ONT secara deterministik.\n\nPilih gejala atau kondisi indikator lampu modem Anda saat ini:",
                quick_replies: [
                    { label: 'Lampu LOS Berkedip Merah', intent: 'troubleshoot_los' },
                    { label: 'Modem Mati / Power Off', intent: 'troubleshoot_pon' },
                    { label: 'WiFi Lemot / Speed Drop', intent: 'troubleshoot_slow' },
                    { label: 'Game Lag / Ping RTO', intent: 'troubleshoot_ping' },
                    { label: 'Buka Panel Diagnosis Web', url: '{{ route('diagnosis') }}' },
                    { label: 'Menu Utama', intent: 'menu' }
                ]
            };
        }

        // Menu Pilihan Paket / Rekomendasi Otomatis (Rule-Based Instant Recommendation)
        if (query.includes('paket') || query.includes('harga') || query.includes('speed') || query.includes('mbps') || query.startsWith('recom_cycle:')) {
            let pkgId = 3; // Default best value: Paket Favorit 25 Mbps
            let pkgName = 'Paket Favorit 25 Mbps';
            let price = 'Rp 150.000';
            let speed = 25;
            let desc = 'Paket paling banyak dipilih warga Purwokerto! Streaming lancar 4K UHD, game online bebas lag, meeting Zoom lancar.';
            let reason = 'Paket ini memiliki rasio nilai dan kestabilan terbaik (best value) dengan bandwidth 25 Mbps murni simetris yang sangat optimal untuk multitasking 5-8 perangkat (streaming 4K lancar, game bebas lag, dan meeting online tanpa kendala).';

            if (query.includes('recom_cycle:3') || query.includes('recom_cycle:25')) {
                pkgId = 1;
                pkgName = 'Paket Hemat 15 Mbps';
                price = 'Rp 115.000';
                speed = 15;
                desc = 'Ideal untuk penggunaan harian ringan, browsing media sosial, chatting WhatsApp, dan sekolah online.';
                reason = 'Paling ekonomis dan ramah anggaran bulanan untuk kebutuhan internet harian 1-3 perangkat.';
            } else if (query.includes('recom_cycle:1') || query.includes('recom_cycle:15')) {
                pkgId = 2;
                pkgName = 'Paket Family 20 Mbps';
                price = 'Rp 135.000';
                speed = 20;
                desc = 'Pilihan tepat untuk keluarga kecil dengan aktivitas streaming video HD dan bekerja dari rumah.';
                reason = 'Pilihan seimbang untuk keluarga kecil 3-5 perangkat dengan harga terjangkau.';
            } else if (query.includes('recom_cycle:2') || query.includes('recom_cycle:20')) {
                pkgId = 4;
                pkgName = 'Paket Turbo 35 Mbps';
                price = 'Rp 170.000';
                speed = 35;
                desc = 'Performa kencang untuk rumah besar, konten kreator, live streaming tanpa buffering sedikitpun.';
                reason = 'Bandwidth tinggi 35 Mbps ideal untuk 8-12 perangkat aktif dan upload file besar.';
            } else if (query.includes('recom_cycle:4') || query.includes('recom_cycle:35')) {
                pkgId = 5;
                pkgName = 'Paket Ultimate 50 Mbps';
                price = 'Rp 200.000';
                speed = 50;
                desc = 'Solusi internet kelas bisnis untuk kantor, cafe, kos-kosan, dan pengguna profesional dengan transfer data intensif.';
                reason = 'Kapasitas super dedicated 50 Mbps untuk multi-user 12+ perangkat dan bisnis kafe/kantor.';
            }

            return {
                text: `Rekomendasi Paket Terbaik untuk Anda!\n\nBerdasarkan paket internet yang tersedia, kami merekomendasikan:\n\n**${pkgName}** [Paling Diminati]\n\n• **Harga:** **${price}** / bulan (Flat)\n• **Kecepatan:** **${speed} Mbps** (100% Simetris Fiber Optic)\n• **Deskripsi:** ${desc}\n\nMengapa kami merekomendasikan paket ini?\n${reason}\n\nPaket ini merupakan rekomendasi utama kami untuk Anda.`,
                package_id: pkgId,
                action: 'highlight_package',
                quick_replies: [
                    { label: 'PILIH PAKET INI', url: `/?package_id=${pkgId}#daftar` },
                    { label: 'LIHAT PAKET LAINNYA', intent: `recom_cycle:${pkgId}` },
                    { label: 'Lihat Detail Spesifikasi', intent: `recom_detail:${pkgId}` },
                    { label: 'Menu Utama', intent: 'menu' }
                ]
            };
        }

        if (query.includes('los') || query.includes('merah') || query.includes('lampu')) {
            return {
                text: "**Panduan Mengatasi Lampu LOS Berkedip Merah**:\n\nLampu LOS merah menandakan sinyal optik dari kabel fiber terputus:\n\n1. Periksa kabel optik tipis (warna kuning/hitam) yang tertancap di belakang modem.\n2. Pastikan konektor biru tertancap erat dan kabel **tidak tertekuk tajam / terjepit**.\n3. Jangan cabut-pasang kabel secara paksa.\n4. Jika kabel sudah rapi namun lampu LOS tetap berkedip merah, segera laporkan ke teknisi kami.",
                quick_replies: [
                    { label: 'Lapor Gangguan ke Teknisi WA', url: waUrl },
                    { label: 'Menu Utama', intent: 'menu' }
                ]
            };
        }

        if (query.includes('coverage') || query.includes('area') || query.includes('daerah') || query.includes('purwokerto') || query.includes('sokaraja') || query.includes('wilayah') || query.includes('jangkauan')) {
            return {
                text: "**Jangkauan Jaringan (Coverage Area)**:\n\nJaringan 100% Fiber Optic TRICORE aktif di berbagai wilayah:\n• **Kec. Purwokerto Timur**: Arcawinangun, Kranji, Mersi, Sokanegara (88 ODP)\n• **Kec. Purwokerto Wetan**: Karangpucung, Tanjung, Teluk (64 ODP)\n• **Kec. Sokaraja**: Sokaraja Kidul, Lor, Kulon, Wetan, Banjaranyar (52 ODP)\n• **Area Perluasan**: Segera hadir di kecamatan baru!",
                quick_replies: [
                    { label: 'Cek Coverage di Web', url: '/#coverage' },
                    { label: 'Konfirmasi Alamat ke WA', url: waUrl },
                    { label: 'Menu Utama', intent: 'menu' }
                ]
            };
        }

        if (query.includes('ganti password') || query.includes('sandi') || query.includes('ssid')) {
            return {
                text: "**Cara Mengganti Password & Nama WiFi (SSID)**:\n\n1. Sambungkan HP/Laptop ke WiFi TRICORE Anda.\n2. Buka browser dan ketik alamat IP router: `192.168.1.1`\n3. Masukkan username dan password router (tertera di stiker bawah modem).\n4. Masuk ke menu **Network / Wireless / WLAN** -> **Security**.\n5. Ubah kolom **WPA Passphrase / Pre-shared Key**, lalu klik **Apply / Save**.",
                quick_replies: [
                    { label: 'Butuh Bantuan Teknisi WA', url: waUrl },
                    { label: 'Menu Utama', intent: 'menu' }
                ]
            };
        }

        if (query.includes('rekening') || query.includes('bayar') || query.includes('transfer')) {
            return {
                text: "**Metode Pembayaran Resmi TRICORE DATA MEDIA**:\n\n**Rekening Resmi Transfer Bank:**\n• **Bank:** Bank Neo Commerce (BNC / 490)\n• **No. Rekening:** `5859459401627129`\n• **Nama Pemilik:** TRICORE DATA MEDIA\n\nTransfer dapat dilakukan dari seluruh bank (BCA, BRI, Mandiri, BNI) maupun dompet digital (DANA, OVO, GoPay, ShopeePay).\n\nSetelah transfer, buka halaman pembayaran untuk mengunggah bukti struk atau konfirmasi ke WhatsApp Admin.",
                quick_replies: [
                    { label: 'Cek Tagihan & Bayar', intent: 'check_bill_prompt' },
                    { label: 'Konfirmasi via WhatsApp', url: waUrl },
                    { label: 'Menu Utama', intent: 'menu' }
                ]
            };
        }

        return {
            text: `Rekomendasi Paket Terbaik untuk Anda!\n\nBerdasarkan paket internet yang tersedia, kami merekomendasikan:\n\n**Paket Favorit 25 Mbps** [Paling Diminati]\n\n• **Harga:** **Rp 150.000** / bulan (Flat)\n• **Kecepatan:** **25 Mbps** (100% Simetris Fiber Optic)\n• **Deskripsi:** Paket paling banyak dipilih warga Purwokerto! Streaming lancar 4K UHD, game online bebas lag, meeting Zoom lancar.\n\nMengapa kami merekomendasikan paket ini?\nPaket ini memiliki rasio nilai dan kestabilan terbaik (*best value*) dengan bandwidth 25 Mbps murni simetris yang sangat optimal untuk multitasking 5-8 perangkat (streaming 4K lancar, game bebas lag, dan meeting online tanpa kendala).\n\nPaket ini merupakan rekomendasi utama kami untuk Anda.`,
            package_id: 3,
            action: 'highlight_package',
            quick_replies: [
                { label: 'PILIH PAKET INI', url: '/?package_id=3#daftar' },
                { label: 'LIHAT PAKET LAINNYA', intent: 'recom_cycle:3' },
                { label: 'Lihat Detail Spesifikasi', intent: 'recom_detail:3' },
                { label: 'Menu Utama', intent: 'menu' }
            ]
        };
    }

    async function sendToBot(payload) {
        showTyping(true);
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 12000);

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload),
                signal: controller.signal
            });
            clearTimeout(timeoutId);

            if (!response.ok) {
                throw new Error('Server returned ' + response.status);
            }

            const data = await response.json();
            setTimeout(() => {
                showTyping(false);
                appendMessage('bot', data);
            }, 250);

        } catch (error) {
            clearTimeout(timeoutId);
            // Seamless client-side fallback — never leave user stranded with error
            const fallbackData = getFallbackResponse(payload);
            setTimeout(() => {
                showTyping(false);
                appendMessage('bot', fallbackData);
            }, 300);
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

    pillButtons.forEach(pill => {
        pill.addEventListener('click', function () {
            const intent = this.getAttribute('data-intent');
            const promptText = this.getAttribute('data-prompt');
            const label = this.innerText.trim();
            if (intent) {
                sendIntent(intent, label);
            } else if (promptText) {
                if (promptText.toLowerCase().includes('paket')) {
                    sendIntent('packages', label);
                } else {
                    appendMessage('user', { text: promptText });
                    sendToBot({ message: promptText });
                }
            }
        });
    });

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
