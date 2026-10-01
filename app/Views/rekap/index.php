<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Load Library html2canvas[cite: 1] -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<div class="flex flex-col items-center justify-center min-h-[80vh] gap-6">
    
    <!-- Kanvas 9:16 (Instagram Story Ratio)[cite: 1] -->
    <div id="scrapbook-canvas" class="relative w-[360px] h-[640px] bg-gradient-to-br from-petalfrost to-lavender overflow-hidden p-6 rounded-3xl shadow-2xl flex flex-col items-center border-4 border-white">
        
        <!-- Y2K Star Stickers (Decoration) -->
        <div class="absolute top-8 left-6 text-persianblue text-3xl opacity-50">✦</div>
        <div class="absolute bottom-20 right-6 text-midnight text-4xl opacity-40">✧</div>

        <!-- Center Card (Editorial Text) -->
        <div class="z-10 text-center mt-6 w-full bg-white/40 backdrop-blur-md p-4 rounded-2xl border border-white/50 shadow-sm">
            <h2 class="text-4xl font-serif italic text-midnight mb-2"><?= $currentMonth ?> in Review</h2>
            <p class="text-xs font-bold text-white bg-persianblue inline-block px-4 py-1.5 rounded-full shadow-sm">
                <?= esc($totalTugas) ?> Assignments, <?= esc($totalNongkrong) ?> Coffee Runs
            </p>
        </div>

        <!-- Photo Scrapbook (Overlapping Grid) -->
        <div class="relative w-full flex-1 mt-8">
            <?php 
            // Posisi absolut dan rotasi acak untuk efek Pinterest Collage
            $styles = [
                'top-0 left-2 -rotate-6 z-10',
                'top-16 right-2 rotate-3 z-20',
                'top-36 left-8 -rotate-2 z-30',
                'top-48 right-6 rotate-6 z-40'
            ];
            ?>
            
            <?php foreach($moments as $index => $m): ?>
                <?php if(isset($styles[$index])): ?>
                <div class="absolute <?= $styles[$index] ?> p-2 bg-white shadow-lg rounded-xl border border-glaucous/20 transition-transform hover:scale-105">
                    <img src="/uploads/moments/<?= esc($m['image_path']) ?>" class="w-32 h-40 object-cover rounded-lg">
                    <!-- Metadata Badge[cite: 1] -->
                    <div class="absolute -bottom-3 -right-3 bg-midnight text-petalfrost text-[10px] font-extrabold px-3 py-1 rounded-full shadow-md border border-petalfrost">
                        <?= esc($m['subject_tags']) ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <!-- Fallback if no photos -->
            <?php if(empty($moments)): ?>
                <div class="flex items-center justify-center h-full text-midnight/50 font-bold text-sm text-center">
                    Belum ada momen.<br>Jepret BeReal dulu!
                </div>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="z-10 pb-4 text-[10px] font-bold text-midnight/60 tracking-widest uppercase">
            Captured via Kampuskuu
        </div>
    </div>

    <!-- Action Button -->
    <button id="download-btn" class="bg-midnight text-white font-extrabold py-3 px-8 rounded-full hover:bg-persianblue transition-all shadow-xl hover:-translate-y-1">
        ✨ Unduh Rekap (PNG)
    </button>

</div>

<script>
    document.getElementById('download-btn').addEventListener('click', function() {
        const btn = this;
        btn.innerText = "Memproses...";
        
        const target = document.getElementById('scrapbook-canvas');
        
        html2canvas(target, { 
            scale: 2, // Kualitas HD
            backgroundColor: null,
            useCORS: true
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'LifeRecap-<?= $currentMonth ?>.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            btn.innerText = "✨ Unduh Rekap (PNG)";
        });
    });
</script>

<?= $this->endSection(); ?>