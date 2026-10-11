<!-- TRINET-BILL - Professional Customer Support & Helpdesk Widget -->
<div id="tricore-chatbot" class="fixed bottom-5 right-5 z-50 flex flex-col items-end font-sans">

    <!-- Floating Action Buttons Row -->
    <div class="flex items-center gap-2.5">
        <!-- Direct WhatsApp "Hubungi Kami" Floating Button -->
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

        <!-- Helpdesk Launcher Floating Button -->
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

    <!-- Chat Box Window -->
    <div id="chatbot-window"
         class="hidden flex flex-col w-[360px] sm:w-[420px] h-[590px] max-h-[85vh] mb-3 bg-[#0c121e]/98 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-2xl shadow-cyan-950/90 overflow-hidden transition-all duration-300 origin-bottom-right">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3.5 bg-slate-900 border-b border-white/10">
            <div class="flex items-center gap-3">
                <div class="relative w-9 h-9 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-slate-950 shadow-md">
                    <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 18v-6a9 9 0 0118 0v6M3 14a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H5a2 2 0 00-2 2zm18 0a2 2 0 00-2-2h-1a2 2 0 00-2 2v3a2 2 0 002 2h1a2 2 0 002-2zM9 20h6a2 2 0 002-2v-1H7v1a2 2 0 002 2z" />
                    </svg>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-slate-900"></span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-1.5 leading-tight">
                        TRINET-BILL Support
                        <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Online</span>
                    </h3>
                    <p class="text-[11px] text-slate-400">
                        Helpdesk Pelanggan TRICORE DATA MEDIA
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
        <div class="px-3 py-2 bg-slate-950 border-b border-white/5 overflow-x-auto whitespace-nowrap flex gap-1.5 text-[11px] no-scrollbar">
            <button type="button" data-prompt="Cek Tagihan" class="chatbot-pill-btn shrink-0 px-2.5 py-1 rounded-full bg-slate-800/90 hover:bg-cyan-900/60 text-slate-300 hover:text-cyan-300 border border-white/10 hover:border-cyan-500/40 transition">
                💳 Cek Tagihan
            </button>
            <button type="button" data-prompt="Lampu LOS merah" class="chatbot-pill-btn shrink-0 px-2.5 py-1 rounded-full bg-slate-800/90 hover:bg-rose-900/60 text-slate-300 hover:text-rose-300 border border-white/10 hover:border-rose-500/40 transition">
                🔴 Kendala LOS Merah
            </button>
            <button type="button" data-prompt="Paket WiFi" class="chatbot-pill-btn shrink-0 px-2.5 py-1 rounded-full bg-slate-800/90 hover:bg-blue-900/60 text-slate-300 hover:text-blue-300 border border-white/10 hover:border-blue-500/40 transition">
                ⚡ Pilihan Paket
            </button>
            <button type="button" data-prompt="Coverage area" class="chatbot-pill-btn shrink-0 px-2.5 py-1 rounded-full bg-slate-800/90 hover:bg-emerald-900/60 text-slate-300 hover:text-emerald-300 border border-white/10 hover:border-emerald-500/40 transition">
                📍 Area Cakupan
            </button>
            <button type="button" data-prompt="Cara ganti password wifi" class="chatbot-pill-btn shrink-0 px-2.5 py-1 rounded-full bg-slate-800/90 hover:bg-amber-900/60 text-slate-300 hover:text-amber-300 border border-white/10 hover:border-amber-500/40 transition">
                🔑 Ganti Password
            </button>
        </div>

        <!-- Message Body Container -->
        <div id="chatbot-messages" class="flex-1 p-4 overflow-y-auto space-y-3.5 text-xs sm:text-sm scroll-smooth bg-[#080d17]/90">
            <!-- Messages inserted dynamically via JS -->
        </div>

        <!-- Typing Indicator -->
        <div id="chatbot-typing" class="hidden px-4 py-2 bg-[#080d17] flex items-center gap-2 text-slate-400 text-xs border-t border-white/5">
            <div class="flex gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-bounce" style="animation-delay: 0s;"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-bounce" style="animation-delay: 0.15s;"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-bounce" style="animation-delay: 0.3s;"></span>
            </div>
            <span class="text-[11px] text-cyan-300">Customer Support sedang mengetik...</span>
        </div>

        <!-- Input Footer Form -->
        <form id="chatbot-form" class="p-3 bg-slate-900/95 border-t border-white/10 flex items-center gap-2">
            <input type="text"
                   id="chatbot-input"
                   autocomplete="off"
                   placeholder="Ketik pertanyaan, ID pelanggan, atau kendala..."
                   class="flex-1 bg-slate-950 text-white placeholder-slate-500 text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-white/10 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
            <button type="submit"
                    id="chatbot-send-btn"
                    aria-label="Kirim Pesan"
                    class="p-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-slate-950 font-bold hover:brightness-110 active:scale-95 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-md shadow-cyan-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
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
    const endpoint = "/chatbot/message";
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
            sessionStorage.removeItem('trinet_chat_history');
            sendIntent('menu');
        });
    }

    function scrollToBottom() {
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 50);
    }

    function formatMarkdown(text) {
        if (!text) return '';
        let escaped = text
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
                    if (btn.url) {
                        contentHtml += `
                            <a href="${btn.url}" target="${btn.url.startsWith('http') ? '_blank' : '_self'}"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-medium bg-cyan-950/90 hover:bg-cyan-900 text-cyan-300 border border-cyan-500/30 hover:border-cyan-400 transition transform active:scale-95 shadow-sm">
                                ${btn.label}
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>`;
                    } else {
                        contentHtml += `
                            <button type="button"
                                    data-intent="${btn.intent || ''}"
                                    data-label="${btn.label}"
                                    class="chatbot-quick-reply-btn inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-medium bg-slate-800 hover:bg-slate-700 text-slate-200 border border-white/10 hover:border-cyan-400 hover:text-cyan-300 transition transform active:scale-95 shadow-sm">
                                ${btn.label}
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

        if (!query || query === 'menu' || query === 'halo' || query === 'hai' || query === 'p') {
            return {
                text: "Halo! 👋 Selamat datang di **Pusat Bantuan TRINET-BILL** (TRICORE DATA MEDIA Purwokerto).\n\nTim Support & Teknisi kami siap membantu Anda seputar tagihan, kendala teknis WiFi, paket internet, dan jaringan fiber optic.\n\nSilakan pilih topik bantuan cepat berikut:",
                quick_replies: [
                    { label: '💳 Cek Tagihan', intent: 'check_bill_prompt' },
                    { label: '⚡ Paket Internet', intent: 'packages' },
                    { label: '🔴 Kendala LOS Merah', intent: 'troubleshoot_los' },
                    { label: '📍 Coverage Area', intent: 'coverage' },
                    { label: '🔑 Cara Ganti Sandi WiFi', intent: 'troubleshoot_password' },
                    { label: '💬 Chat WhatsApp CS', url: waUrl }
                ]
            };
        }

        if (query === 'check_bill_prompt' || query.includes('tagihan') || query.includes('bayar') || query.includes('invoice')) {
            return {
                text: "💳 **Pengecekan Tagihan Pelanggan**\n\nUntuk mengecek status tagihan bulanan dan detail paket Anda:\n1. Buka fitur cek tagihan di halaman beranda.\n2. Masukkan ID Pelanggan (contoh: `TDM-2601`) atau nomor WhatsApp terdaftar.",
                quick_replies: [
                    { label: '💳 Buka Form Cek Tagihan', url: '/#cek-tagihan' },
                    { label: '🏦 Rekening Pembayaran', intent: 'payment_info' },
                    { label: '💬 Hubungi CS WhatsApp', url: waUrl },
                    { label: '🔙 Menu Utama', intent: 'menu' }
                ]
            };
        }

        if (query.includes('paket') || query.includes('harga') || query.includes('speed') || query.includes('mbps')) {
            return {
                text: "📦 **Pilihan Paket Internet Fiber TRICORE DATA MEDIA**:\n\n• **Paket Hemat 15 Mbps** - Rp115.000/bln (1-3 perangkat)\n• **Paket Family 20 Mbps** - Rp135.000/bln (3-5 perangkat)\n• **Paket Favorit 25 Mbps** - Rp150.000/bln (5-8 perangkat, Paling Diminati!)\n• **Paket Turbo 35 Mbps** - Rp170.000/bln (8-12 perangkat)\n• **Paket Ultimate 50 Mbps** - Rp200.000/bln (Bisnis / Cafe)\n\n✨ 100% Fiber Optic, Unlimited Tanpa FUP, Gratis Sewa Modem WiFi!",
                quick_replies: [
                    { label: '📝 Daftar Online', url: '/#daftar' },
                    { label: '💬 Daftar via WhatsApp', url: waUrl },
                    { label: '🔙 Menu Utama', intent: 'menu' }
                ]
            };
        }

        if (query.includes('los') || query.includes('merah') || query.includes('lampu')) {
            return {
                text: "🔴 **Panduan Mengatasi Lampu LOS Berkedip Merah**:\n\nLampu LOS merah menandakan sinyal optik dari kabel fiber terputus:\n\n1. Periksa kabel optik tipis (warna kuning/hitam) yang tertancap di belakang modem.\n2. Pastikan konektor biru tertancap erat dan kabel **tidak tertekuk tajam / terjepit**.\n3. Jangan cabut-pasang kabel secara paksa.\n4. Jika kabel sudah rapi namun lampu LOS tetap berkedip merah, segera laporkan ke teknisi kami.",
                quick_replies: [
                    { label: '💬 Lapor Gangguan ke Teknisi WA', url: waUrl },
                    { label: '🔙 Menu Utama', intent: 'menu' }
                ]
            };
        }

        if (query.includes('coverage') || query.includes('area') || query.includes('daerah') || query.includes('purwokerto') || query.includes('sokaraja')) {
            return {
                text: "📍 **Jangkauan Jaringan (Coverage Area)**:\n\nJaringan 100% Fiber Optic TRICORE aktif di:\n• **Purwokerto Timur**: Arcawinangun, Kranji, Mersi, Purwokerto Lor, Sokanegara (88 ODP)\n• **Purwokerto Wetan**: Karangpucung, Tanjung, Teluk (64 ODP)\n• **Sokaraja**: Sokaraja Kidul, Lor, Kulon, Wetan, Banjaranyar (52 ODP)",
                quick_replies: [
                    { label: '🔍 Cek Coverage di Web', url: '/#coverage' },
                    { label: '💬 Konfirmasi Alamat ke WA', url: waUrl },
                    { label: '🔙 Menu Utama', intent: 'menu' }
                ]
            };
        }

        if (query.includes('ganti password') || query.includes('sandi') || query.includes('ssid')) {
            return {
                text: "🔑 **Cara Mengganti Password & Nama WiFi (SSID)**:\n\n1. Sambungkan HP/Laptop ke WiFi TRICORE Anda.\n2. Buka browser dan ketik alamat IP router: `192.168.1.1`\n3. Masukkan username dan password router (tertera di stiker bawah modem).\n4. Masuk ke menu **Network / Wireless / WLAN** -> **Security**.\n5. Ubah kolom **WPA Passphrase / Pre-shared Key**, lalu klik **Apply / Save**.",
                quick_replies: [
                    { label: '💬 Butuh Bantuan Teknisi WA', url: waUrl },
                    { label: '🔙 Menu Utama', intent: 'menu' }
                ]
            };
        }

        if (query.includes('rekening') || query.includes('bayar') || query.includes('transfer')) {
            return {
                text: "💳 **Metode Pembayaran Resmi TRICORE DATA MEDIA**:\n\n🏦 **Transfer Bank:**\n• BCA: `046-1234-567` (a.n TRICORE DATA MEDIA)\n• BRI: `0022-01-001234-53-1` (a.n TRICORE DATA MEDIA)\n\n📲 **QRIS & E-Wallet**: Menerima BCA Mobile, Livin, GoPay, OVO, DANA, ShopeePay.\n\n⚠️ Cantumkan ID Pelanggan Anda saat transfer dan konfirmasi bukti ke WhatsApp CS.",
                quick_replies: [
                    { label: '💬 Kirim Bukti Transfer ke WA', url: waUrl },
                    { label: '🔙 Menu Utama', intent: 'menu' }
                ]
            };
        }

        return {
            text: `Terima kasih atas pesan Anda. Untuk pertanyaan lebih lanjut, tim teknisi & CS kami siap melayani Anda melalui WhatsApp:`,
            quick_replies: [
                { label: '💬 Chat Teknisi WhatsApp', url: waUrl },
                { label: '💳 Cek Tagihan', intent: 'check_bill_prompt' },
                { label: '⚡ Pilihan Paket', intent: 'packages' },
                { label: '🔙 Menu Utama', intent: 'menu' }
            ]
        };
    }

    async function sendToBot(payload) {
        showTyping(true);
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 5000);

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
            const promptText = this.getAttribute('data-prompt');
            appendMessage('user', { text: promptText });
            sendToBot({ message: promptText });
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
