<?php
// Membutuhkan $judul_halaman & $subjudul_halaman (opsional) sebelum di-include
?>
<header class="topbar">
    <div style="display:flex; align-items:center; gap:.9rem;">
        <button class="menu-toggle"><i class="bi bi-list"></i></button>
        <div>
            <h1><?= htmlspecialchars($judul_halaman ?? 'Dashboard') ?></h1>
            <?php if (!empty($subjudul_halaman)): ?>
                <div class="topbar-sub"><?= htmlspecialchars($subjudul_halaman) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div style="font-size:.82rem; color:var(--text-muted);">
        <i class="bi bi-calendar3"></i> <?= date('d F Y') ?>
    </div>
</header>
