<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="max-w-xl mx-auto pb-24 flex flex-col h-[calc(100vh-140px)] min-h-[500px]">

    <!-- Header Chat -->
    <div class="bg-white/70 dark:bg-darkcard/70 backdrop-blur-2xl border border-white/60 dark:border-white/10 rounded-3xl p-4 shadow-xl mb-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-persianblue to-petalfrost text-white flex items-center justify-center text-lg shadow-md">
                🤖
            </div>
            <div>
                <h2 class="text-sm font-black text-midnight dark:text-white flex items-center gap-1.5">
                    Dailee AI Assistant <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </h2>
                <p class="text-[10px] text-gray-400 font-semibold">Powered by Gemini AI • Productivity & IIP Expert</p>
            </div>
        </div>
        <a href="<?= base_url('dashboard'); ?>" class="text-xs font-bold text-gray-400 hover:text-midnight dark:hover:text-white transition">
            Tutup
        </a>
    </div>

    <!-- Kotak Pesan Chat -->
    <div id="chat-stream" class="flex-1 overflow-y-auto space-y-3.5 px-1 pr-2 rounded-3xl mb-4 scroll-smooth">
        <!-- Sambutan Awal -->
        <div class="flex items-start gap-2.5">
            <div class="w-7 h-7 rounded-xl bg-persianblue/20 text-persianblue flex items-center justify-center text-xs flex-shrink-0 font-bold">
                🤖
            </div>
            <div class="bg-white/80 dark:bg-darkcard/80 backdrop-blur-xl border border-white/60 dark:border-white/10 rounded-2xl rounded-tl-none p-3.5 shadow-sm text-xs text-midnight/90 dark:text-gray-200 max-w-[85%] leading-relaxed">
                Halo! Aku <strong>Dailee AI</strong>. Ada materi produktivitas, tips manajemen file kuliah, atau facts yang pengen kamu bedah lebih lanjut hari ini? Tulis aja di bawah! ✨
            </div>
        </div>
    </div>

    <!-- Input Form Kirim Pesan -->
    <div class="bg-white/80 dark:bg-darkcard/80 backdrop-blur-2xl border border-white/60 dark:border-white/10 rounded-3xl p-2.5 shadow-2xl">
        <form id="chat-form" onsubmit="handleChatSubmit(event)" class="flex items-center gap-2">
            <input type="text" id="chat-input" placeholder="Tanya apa saja seputar tugas, waktu, atau facts..." class="flex-1 bg-gray-100 dark:bg-white/5 border border-transparent focus:border-persianblue outline-none rounded-2xl px-4 py-3 text-xs font-medium text-midnight dark:text-white transition" autocomplete="off" required>
            
            <button type="submit" id="send-btn" class="bg-persianblue hover:bg-midnight text-white text-xs font-black px-5 py-3 rounded-2xl shadow-lg transition flex items-center gap-1">
                <span>Kirim</span>
                <span id="send-icon">🚀</span>
            </button>
        </form>
    </div>

</div>

<!-- Marked.js CDN untuk merender format markdown teks tebal/list Gemini secara estetik -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<script>
    const chatStream = document.getElementById('chat-stream');
    const chatInput  = document.getElementById('chat-input');
    const sendBtn    = document.getElementById('send-btn');
    const initialPrompt = <?= json_encode($initialPrompt ?? ''); ?>;

    function appendMessage(sender, text) {
        const wrapper = document.createElement('div');

        if (sender === 'user') {
            wrapper.className = 'flex justify-end';
            wrapper.innerHTML = `
                <div class="bg-persianblue text-white rounded-2xl rounded-tr-none px-4 py-3 shadow-md text-xs font-semibold max-w-[85%] leading-relaxed">
                    ${escapeHtml(text)}
                </div>
            `;
        } else {
            wrapper.className = 'flex items-start gap-2.5';
            // Parse Markdown dari Gemini
            const parsedHtml = marked.parse(text);
            wrapper.innerHTML = `
                <div class="w-7 h-7 rounded-xl bg-persianblue/20 text-persianblue flex items-center justify-center text-xs flex-shrink-0 font-bold">
                    🤖
                </div>
                <div class="bg-white/80 dark:bg-darkcard/80 backdrop-blur-xl border border-white/60 dark:border-white/10 rounded-2xl rounded-tl-none p-3.5 shadow-sm text-xs text-midnight/90 dark:text-gray-200 max-w-[85%] leading-relaxed prose prose-xs dark:prose-invert">
                    ${parsedHtml}
                </div>
            `;
        }

        chatStream.appendChild(wrapper);
        chatStream.scrollTop = chatStream.scrollHeight;
    }

    function appendLoading() {
        const wrapper = document.createElement('div');
        wrapper.id = 'ai-loading-bubble';
        wrapper.className = 'flex items-start gap-2.5';
        wrapper.innerHTML = `
            <div class="w-7 h-7 rounded-xl bg-persianblue/20 text-persianblue flex items-center justify-center text-xs flex-shrink-0 font-bold">
                🤖
            </div>
            <div class="bg-white/80 dark:bg-darkcard/80 backdrop-blur-xl border border-white/60 dark:border-white/10 rounded-2xl rounded-tl-none px-4 py-3 shadow-sm text-xs text-gray-400 flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-persianblue animate-bounce"></span>
                <span class="inline-block w-2 h-2 rounded-full bg-persianblue animate-bounce [animation-delay:0.2s]"></span>
                <span class="inline-block w-2 h-2 rounded-full bg-persianblue animate-bounce [animation-delay:0.4s]"></span>
                <span class="text-[11px] italic">Dailee AI sedang mengetik...</span>
            </div>
        `;
        chatStream.appendChild(wrapper);
        chatStream.scrollTop = chatStream.scrollHeight;
    }

    function removeLoading() {
        const loading = document.getElementById('ai-loading-bubble');
        if (loading) loading.remove();
    }

    function escapeHtml(string) {
        const div = document.createElement('div');
        div.innerText = string;
        return div.innerHTML;
    }

    async function sendPrompt(msg) {
        if (!msg) return;

        appendMessage('user', msg);
        appendLoading();
        chatInput.value = '';
        sendBtn.disabled = true;

        try {
            const formData = new FormData();
            formData.append('message', msg);

            const res = await fetch('<?= base_url('chat/send'); ?>', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();
            removeLoading();

            if (data.status === 'success') {
                appendMessage('ai', data.reply);
            } else {
                appendMessage('ai', '⚠️ Maaf: ' + (data.message || 'Terjadi gangguan saat memproses jawaban.'));
            }
        } catch (err) {
            removeLoading();
            appendMessage('ai', '⚠️ Terjadi kesalahan koneksi server.');
        } finally {
            sendBtn.disabled = false;
            chatInput.focus();
        }
    }

    function handleChatSubmit(e) {
        e.preventDefault();
        const msg = chatInput.value.trim();
        sendPrompt(msg);
    }

    // AUTO TRIGGER: Jika user datang dari tombol "Tanya AI" di dashboard
    window.addEventListener('DOMContentLoaded', () => {
        if (initialPrompt && initialPrompt.trim() !== '') {
            setTimeout(() => {
                sendPrompt(initialPrompt);
            }, 400);
        }
    });
</script>
<?= $this->endSection(); ?>