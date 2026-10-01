<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="space-y-6">

    <!-- 1. WIDGET TIPS PRODUKTIVITAS & IIP HACKS (CAROUSEL AUTO-SLIDE) -->
    <div class="bg-white dark:bg-darkcard border border-gray-200 dark:border-gray-800 rounded-2xl p-4 shadow-sm border-l-4 border-l-persianblue relative overflow-hidden">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-black uppercase tracking-widest text-persianblue dark:text-petalfrost flex items-center gap-1">
                ⚡ Productivity & IIP Hacks
            </span>
            <div class="flex gap-1" id="carouselDots">
                <span class="w-2 h-2 rounded-full bg-persianblue"></span>
                <span class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-700"></span>
                <span class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-700"></span>
                <span class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-700"></span>
            </div>
        </div>

        <div id="tipCarousel" class="relative min-h-[75px] flex items-center">
            <div class="tip-slide transition-opacity duration-500 w-full">
                <h5 class="text-xs font-extrabold text-gray-900 dark:text-white mb-1">⏰ Teknik Pomodoro & Eisenhower</h5>
                <p class="text-xs text-gray-600 dark:text-gray-300 italic">"Bagi agenda harianmu jadi blok 25 menit fokus + 5 menit istirahat. Pilah mana yang Penting-Mendesak di Kalender!"</p>
            </div>
            <div class="tip-slide transition-opacity duration-500 w-full hidden">
                <h5 class="text-xs font-extrabold text-gray-900 dark:text-white mb-1">📁 Digital Housekeeping (IIP Style)</h5>
                <p class="text-xs text-gray-600 dark:text-gray-300 italic">"Gunakan struktur penamaan file rapi: <code class="text-persianblue dark:text-petalfrost bg-lavender dark:bg-midnight px-1 rounded">[Tahun]_[Matkul]_[Tugas]_v1</code> biar nggak panik pas mau UTS/UAS!"</p>
            </div>
            <div class="tip-slide transition-opacity duration-500 w-full hidden">
                <h5 class="text-xs font-extrabold text-gray-900 dark:text-white mb-1">🌿 Mindful Reflection & Rule 20-20-20</h5>
                <p class="text-xs text-gray-600 dark:text-gray-300 italic">"Jepret 1 momen foto berkesan hari ini untuk melatih rasa syukur, plus istirahatkan mata setiap 20 menit!"</p>
            </div>
            <div class="tip-slide transition-opacity duration-500 w-full hidden">
                <h5 class="text-xs font-extrabold text-gray-900 dark:text-white mb-1">💬 Etika Akademik & Literasi Digital</h5>
                <p class="text-xs text-gray-600 dark:text-gray-300 italic">"Cek kebenaran berita sebelum re-share ke grup kelas, & gunakan template pesan sopan saat diskusi dengan dosen."</p>
            </div>
        </div>
    </div>

    <!-- 2. WIDGET DAILEE AI BOT -->
    <div class="bg-white dark:bg-darkcard border border-persianblue/30 rounded-2xl p-4 shadow-sm ai-glow flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-persianblue flex items-center justify-center text-lg text-white shadow-md">
                🤖
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h4 class="text-xs font-black text-gray-900 dark:text-white tracking-wide">DAILEE AI BOT</h4>
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-ping"></span>
                </div>
                <p class="text-[10px] text-gray-500 dark:text-gray-400"><?= esc(lang('Web.ai_description')) ?></p>
            </div>
        </div>
        <button onclick="askDaileeAI()" class="px-4 py-2 text-xs font-extrabold bg-persianblue hover:bg-midnight text-white rounded-xl shadow transition">
            <?= esc(lang('Web.ask_ai')) ?>
        </button>
    </div>

    <!-- Tab feed: teman langsung, teman dari teman, dan log pribadi -->
    <nav class="max-w-lg mx-auto flex items-center justify-center gap-8 border-b border-gray-200 dark:border-gray-800 mb-4"
         aria-label="Filter feed">
        <a href="<?= site_url('dashboard?tab=my_friends') ?>"
           class="py-3 text-sm font-semibold transition <?= $currentTab === 'my_friends' ? 'text-persianblue border-b-2 border-persianblue' : 'text-gray-500 hover:text-persianblue' ?>">
            <?= esc(lang('Web.feed_my_friends')) ?>
        </a>
        <a href="<?= site_url('dashboard?tab=friends_of_friends') ?>"
           class="py-3 text-sm font-semibold transition <?= $currentTab === 'friends_of_friends' ? 'text-persianblue border-b-2 border-persianblue' : 'text-gray-500 hover:text-persianblue' ?>">
            <?= esc(lang('Web.feed_fof')) ?>
        </a>
        <a href="<?= site_url('dashboard?tab=my_log') ?>"
           class="py-3 text-sm font-semibold transition <?= $currentTab === 'my_log' ? 'text-persianblue border-b-2 border-persianblue' : 'text-gray-500 hover:text-persianblue' ?>">
            <?= esc(lang('Web.feed_my_logs')) ?>
        </a>
    </nav>

    <!-- 3. FEED BEREAL DUAL-FRAME MOMENTS -->
    <div class="max-w-lg mx-auto space-y-6">
        <?php if (!empty($logs) && is_array($logs)) : ?>
            <?php foreach ($logs as$post) : ?>
                <article id="moment-<?= (int) $post['id'] ?>" class="bg-white dark:bg-darkcard border border-gray-200 dark:border-gray-800 rounded-3xl p-4 shadow-sm scroll-mt-24" data-post-id="<?= $post['id'] ?>">
                    
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full bg-persianblue text-white flex items-center justify-center font-bold text-xs">
                                <?= strtoupper(substr($post['username'] ?? 'U', 0, 1)) ?>
                            </div>
                            <div>
                                <h4 class="font-bold text-xs text-gray-900 dark:text-white"><?= esc($post['nama_lengkap'] ?? $post['username'] ?? 'User') ?></h4>
                                <p class="text-[9px] text-gray-400"><?= date('d M Y • H:i', strtotime($post['created_at'])) ?></p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 text-[9px] font-black rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                            <?= !empty($post['schedule_id']) ? esc(lang('Web.on_time')) : esc(lang('Web.late')) ?>
                        </span>
                    </div>

                    <!-- Dual Camera Frame -->
                    <div class="relative w-full aspect-[4/5] bg-gray-900 rounded-2xl overflow-hidden mb-3 border border-gray-200 dark:border-gray-800">
                        <?php if (!empty($post['main_image'])) : ?>
                            <img src="<?= base_url('uploads/moments/' . $post['main_image']) ?>" class="w-full h-full object-cover">
                        <?php else : ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-500 text-xs">📷 <?= esc(lang('Web.main_photo')) ?></div>
                        <?php endif; ?>

                        <div class="absolute top-3 left-3 w-24 h-32 bg-black rounded-xl overflow-hidden border-2 border-white shadow-lg">
                            <?php if (!empty($post['inset_image'])) : ?>
                                <img src="<?= base_url('uploads/moments/' . $post['inset_image']) ?>" class="w-full h-full object-cover">
                            <?php else : ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-[9px]">🤳 <?= esc(lang('Web.selfie')) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Caption & Schedule -->
                    <?php if (!empty($post['agenda_title'])) : ?>
                        <div class="mb-2 text-xs font-bold text-persianblue dark:text-petalfrost"><?= esc(lang('Web.schedule_label')) ?>: <?= esc($post['agenda_title']) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($post['caption'])) : ?>
                        <p class="text-xs text-gray-800 dark:text-gray-200 mb-3">
                            <strong>@<?= esc($post['username']) ?></strong> <?php helper('hashtag'); echo parse_hashtags($post['caption']); ?>
                        </p>
                    <?php endif; ?>

                    <!-- Interaction Bar -->
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-800 text-xs">
                        <button onclick="handleReact(<?= $post['id'] ?>)" class="flex items-center gap-1 text-gray-500 hover:text-glaucous">
                            <span>⚡</span> <span class="react-count font-bold"><?= $post['total_likes'] ?? 0 ?></span>
                        </button>
                        <button onclick="toggleCommentBox(<?= $post['id'] ?>)" class="flex items-center gap-1 text-gray-500 hover:text-glaucous">
                            <span>💬</span> <span class="comment-count font-bold">(<?= $post['total_comments'] ?? 0 ?>)</span>
                        </button>
                        <button onclick="handleShare(<?= $post['id'] ?>)" class="text-gray-500 hover:text-glaucous">🚀</button>
                    </div>

                    <div id="comment-box-<?= $post['id'] ?>" class="hidden mt-3 pt-3 border-t border-gray-100 dark:border-gray-800">
                        <div id="comment-list-<?= $post['id'] ?>" class="mb-3 max-h-52 space-y-2 overflow-y-auto">
                            <?php foreach (($post['recent_comments'] ?? []) as $existingComment): ?>
                                <div class="rounded-xl bg-gray-50 px-3 py-2 text-xs dark:bg-white/5">
                                    <div class="flex items-center justify-between gap-2"><strong><?= esc($existingComment['nama_lengkap'] ?: '@' . $existingComment['username']) ?></strong><time class="text-[10px] opacity-50"><?= esc($existingComment['created_at'] ? date('d M H:i', strtotime($existingComment['created_at'])) : '') ?></time></div>
                                    <p class="mt-1 break-words opacity-80"><?= esc($existingComment['comment']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="flex gap-2">
                            <input type="text" id="comment-input-<?= $post['id'] ?>" placeholder="<?= esc(lang('Web.comment_placeholder')) ?>" class="w-full px-3 py-1 text-xs bg-gray-100 dark:bg-gray-800 rounded-xl focus:outline-none dark:text-white">
                            <button onclick="handleComment(<?= $post['id'] ?>)" class="px-3 py-1 text-xs bg-persianblue text-white font-bold rounded-xl"><?= esc(lang('Web.send')) ?></button>
                        </div>
                    </div>

                </article>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="text-center text-gray-400 py-16">
                <p class="text-3xl mb-1">⚡</p>
                <p class="text-xs font-bold"><?= esc(lang('Web.no_feed')) ?></p>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
    // Carousel logic
    let curSlide = 0;
    const s = document.querySelectorAll('.tip-slide');
    const d = document.querySelectorAll('#carouselDots span');
    setInterval(() => {
        s[curSlide].classList.add('hidden');
        d[curSlide].className = 'w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-700';
        curSlide = (curSlide + 1) % s.length;
        s[curSlide].classList.remove('hidden');
        d[curSlide].className = 'w-2 h-2 rounded-full bg-persianblue';
    }, 5000);

    function askDaileeAI() {
        let p = prompt("🤖 Dailee AI Bot:\nTanyakan materi kuliah, tips IIP, atau bantuan jadwal:");
        if (p && p.trim()) window.location.href = <?= json_encode(site_url('chat')) ?> + '?prompt=' + encodeURIComponent(p.trim());
    }

    const reactUrl = <?= json_encode(site_url('post/react')) ?>;
    const commentUrl = <?= json_encode(site_url('post/comment')) ?>;
    const shareUrl = <?= json_encode(site_url('post/share')) ?>;

    function toggleCommentBox(id) {
        const box = document.getElementById('comment-box-' + id);
        if (box) {
            box.classList.toggle('hidden');
        }
    }

    async function sendPost(url, data) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(data)
        });

        const result = await response.json().catch(() => ({
            status: 'error',
            message: 'Server tidak mengirim respons JSON. Coba login ulang lalu ulangi.'
        }));

        if (!response.ok || result.status !== 'success') {
            throw new Error(result.message || 'Permintaan gagal.');
        }

        return result;
    }

    async function handleReact(id) {
        try {
            const result = await sendPost(reactUrl, {
                post_id: id,
                type: 'like'
            });

            const article = document.querySelector('[data-post-id="' + id + '"]');
            if (article) {
                article.querySelector('.react-count').textContent = result.total;
            }
        } catch (error) {
            alert(error.message);
        }
    }

    async function handleComment(id) {
        const input = document.getElementById('comment-input-' + id);
        const comment = input ? input.value.trim() : '';

        if (!comment) {
            alert('Tulis komentar dulu.');
            return;
        }

        try {
            const result = await sendPost(commentUrl, {
                post_id: id,
                comment: comment
            });

            const article = document.querySelector('[data-post-id="' + id + '"]');
            const count = article ? article.querySelector('.comment-count') : null;
            if (count) {
                const current = parseInt(count.textContent.replace(/\D/g, ''), 10) || 0;
                count.textContent = '(' + (current + 1) + ')';
            }

            const list = document.getElementById('comment-list-' + id);
            if (list && result.comment) {
                const row = document.createElement('div');
                row.className = 'rounded-xl bg-gray-50 px-3 py-2 text-xs dark:bg-white/5';
                const header = document.createElement('div');
                header.className = 'flex items-center justify-between gap-2';
                const name = document.createElement('strong');
                name.textContent = result.comment.name;
                const time = document.createElement('time');
                time.className = 'text-[10px] opacity-50';
                time.textContent = result.comment.created_at;
                header.append(name, time);
                const text = document.createElement('p');
                text.className = 'mt-1 break-words opacity-80';
                text.textContent = result.comment.text;
                row.append(header, text);
                list.prepend(row);
            }

            input.value = '';
            alert('Komentar berhasil dikirim.');
        } catch (error) {
            alert(error.message);
        }
    }

    async function handleShare(id) {
        try {
            await sendPost(shareUrl, {
                post_id: id
            });

            const link = window.location.href;
            if (navigator.share) {
                await navigator.share({
                    title: 'Dailee',
                    text: 'Lihat daily log ini di Dailee',
                    url: link
                });
            } else if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(link);
                alert('Link halaman berhasil disalin.');
            } else {
                window.prompt('Salin link halaman ini:', link);
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                alert(error.message);
            }
        }
    }

    if (window.location.hash.startsWith('#moment-')) {
        const moment = document.querySelector(window.location.hash);
        if (moment) {
            document.getElementById('comment-box-' + moment.dataset.postId)?.classList.remove('hidden');
            window.setTimeout(() => moment.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
        }
    }
</script>

<?= $this->endSection() ?>
