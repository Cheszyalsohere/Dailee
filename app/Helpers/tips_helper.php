<?php

if (!function_exists('get_productivity_tips')) {
    function get_productivity_tips(): array
    {
        return [
            [
                'id'       => 1,
                'category' => 'Waktu & Fokus',
                'badge'    => '⏱️ Pomodoro',
                'title'    => 'Teknik 25:5 untuk Marathon Tugas',
                'desc'     => 'Bagi tugas besarmu jadi blok 25 menit fokus penuh tanpa distraksi HP, lalu ambil jeda istirahat 5 menit. Efektif cegah burnout saat ngerjain laporan!',
                'action'   => 'Pasang Agenda Belajar'
            ],
            [
                'id'       => 2,
                'category' => 'Waktu & Fokus',
                'badge'    => '⚡ 2-Minute Rule',
                'title'    => 'Selesaikan yang Cepat Sekarang Juga',
                'desc'     => 'Kalau suatu kegiatan butuh waktu kurang dari 2 menit (seperti konfirmasi presensi, simpan draft, balas email penting), eksekusi detik ini juga tanpa ditunda.',
                'action'   => 'Cek To-Do List'
            ],
            [
                'id'       => 3,
                'category' => 'Info & File',
                'badge'    => '🗂️ Digital Housekeeping',
                'title'    => 'Format Nama File Anti-Panik',
                'desc'     => 'Gunakan rumus penamaan standar IIP: [Tahun]_[NamaMatkul]_[Tugas]_[Versi] (Contoh: 2026_Semiotika_Tugas1_vFinal). Gak bakal ada lagi drama salah upload file revisi!',
                'action'   => 'Rapikan Folder Kuliah'
            ],
            [
                'id'       => 4,
                'category' => 'Info & File',
                'badge'    => '🧠 Second Brain',
                'title'    => 'Digital Decluttering Akhir Bulan',
                'desc'     => 'Bersihkan folder Downloads laptop dan screenshot random di galeri HP secara berkala. Ruang memori yang lega bikin pikiran jauh lebih fokus.',
                'action'   => 'Lihat Monthly Memories'
            ],
            [
                'id'       => 5,
                'category' => 'Well-being',
                'badge'    => '👀 Aturan 20-20-20',
                'title'    => 'Cegah Mata Lelah di Depan Laptop',
                'desc'     => 'Setiap 20 menit menatap layar monitor, alihkan pandanganmu ke objek sejauh 20 kaki (sekitar 6 meter) selama minimal 20 detik. Mata tetap segar dan anti-pusing.',
                'action'   => 'Istirahat Sejenak'
            ],
            [
                'id'       => 6,
                'category' => 'Well-being',
                'badge'    => '🌱 Mindful Moment',
                'title'    => 'Satu Momen Berkesan Hari Ini',
                'desc'     => 'Menjepret satu foto progres kecil dan mencatat rasa syukur setiap hari di dailee bisa menurunkan hormon stres akademis secara signifikan.',
                'action'   => 'Ambil dailee Log'
            ],
            [
                'id'       => 7,
                'category' => 'Literasi & Etika',
                'badge'    => '💬 Etika Chat Dosen',
                'title'    => 'Formula Chat Dosen yang Santun',
                'desc'     => 'Gunakan struktur: Salam + Identitas (Nama/NIM/Kelas) + Keperluan to the point + Permohonan maaf atas waktu beliau + Terima kasih. Kirim di jam kerja.',
                'action'   => 'Salin Template Chat'
            ],
            [
                'id'       => 8,
                'category' => 'Literasi & Etika',
                'badge'    => '🔍 Fact Checking',
                'title'    => 'Filter Informasi Sebelum Forward',
                'desc'     => 'Dapat info mengejutkan di grup WA angkatan? Cek sumber primer dan tanggal kejadian sebelum menyebarkannya. Literasi informasi dimulai dari diri sendiri.',
                'action'   => 'Verifikasi Sumber'
            ],
        ];
    }
}