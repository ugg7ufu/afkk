<?php
// صفحة معرض الصور حسب المجلدات
$baseDir = 'uploads/';
$selectedFolder = isset($_GET['folder']) ? basename($_GET['folder']) : '';
$imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
$folders = array_filter(glob($baseDir . '*'), 'is_dir');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>معرض الصور حسب المجلدات</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
  <style>
    .gallery-img { max-width: 180px; max-height: 180px; border-radius: 10px; box-shadow: 0 2px 8px #0001; margin-bottom: 8px; cursor:pointer; transition:0.2s; }
    .gallery-img:hover { box-shadow: 0 4px 16px #0002; transform:scale(1.04); }
    .gallery-item { text-align: center; margin-bottom: 30px; }
    .folder-link { font-size: 1.1em; font-weight: bold; margin: 0 10px; }
    .folder-link.active { color: #fff; background: #2563eb; border-radius: 6px; padding: 2px 10px; }
    /* Lightbox styles */
    #lightbox {
      display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.85); align-items: center; justify-content: center;
    }
    #lightbox.active { display: flex; }
    #lightbox-img { max-width: 90vw; max-height: 80vh; border-radius: 12px; box-shadow: 0 0 30px #000; }
    .lightbox-nav { position: absolute; top: 50%; transform: translateY(-50%); font-size: 2.5em; color: #fff; background:rgba(0,0,0,0.2); border:none; border-radius:50%; width:50px; height:50px; display:flex; align-items:center; justify-content:center; cursor:pointer; }
    .lightbox-nav.left { left: 30px; }
    .lightbox-nav.right { right: 30px; }
    #lightbox-close { position: absolute; top: 30px; left: 30px; font-size: 2em; color: #fff; background:rgba(0,0,0,0.2); border:none; border-radius:50%; width:45px; height:45px; display:flex; align-items:center; justify-content:center; cursor:pointer; }
    @media (max-width: 600px) {
      .gallery-img { max-width: 90vw; max-height: 40vw; }
      #lightbox-img { max-width: 98vw; max-height: 60vh; }
    }
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div style="margin-right: 270px; padding: 30px;">
  <h2 class="mb-4">🖼️ معرض الصور حسب المجلدات</h2>
  <div class="mb-4">
    <b>المجلدات:</b>
    <?php foreach ($folders as $folder): $f = basename($folder); ?>
      <a href="?folder=<?= urlencode($f) ?>" class="folder-link<?= $selectedFolder===$f?' active':'' ?>">📁 <?= htmlspecialchars($f) ?></a>
    <?php endforeach; ?>
  </div>
  <?php if ($selectedFolder && is_dir($baseDir . $selectedFolder)): ?>
    <div class="row" id="gallery-row">
      <?php
      $files = scandir($baseDir . $selectedFolder);
      $imgList = [];
      foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, $imageExts)) {
          $imgPath = $baseDir . $selectedFolder . '/' . $file;
          $imgList[] = $imgPath;
        }
      }
      $hasImages = count($imgList) > 0;
      foreach ($imgList as $i => $imgPath) {
        echo '<div class="col-md-3 gallery-item">';
        echo '<img src="' . htmlspecialchars($imgPath) . '" class="gallery-img" alt="" data-index="'.$i.'">';
        echo '<div class="small mt-2">' . htmlspecialchars(basename($imgPath)) . '</div>';
        echo '</div>';
      }
      if (!$hasImages) {
        echo '<div class="alert alert-info">لا توجد صور في هذا المجلد.</div>';
      }
      ?>
    </div>
    <script>
      const imgList = <?= json_encode($imgList) ?>;
      let currentIndex = 0;
      function showLightbox(idx) {
        currentIndex = idx;
        document.getElementById('lightbox-img').src = imgList[idx];
        document.getElementById('lightbox').classList.add('active');
      }
      function closeLightbox() {
        document.getElementById('lightbox').classList.remove('active');
      }
      function nextImg() {
        currentIndex = (currentIndex + 1) % imgList.length;
        document.getElementById('lightbox-img').src = imgList[currentIndex];
      }
      function prevImg() {
        currentIndex = (currentIndex - 1 + imgList.length) % imgList.length;
        document.getElementById('lightbox-img').src = imgList[currentIndex];
      }
      document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.gallery-img').forEach(function(img) {
          img.addEventListener('click', function() {
            showLightbox(parseInt(this.getAttribute('data-index')));
          });
        });
        document.getElementById('lightbox-close').onclick = closeLightbox;
        document.getElementById('lightbox-left').onclick = prevImg;
        document.getElementById('lightbox-right').onclick = nextImg;
        document.getElementById('lightbox').onclick = function(e) {
          if (e.target === this) closeLightbox();
        };
        document.addEventListener('keydown', function(e) {
          if (!document.getElementById('lightbox').classList.contains('active')) return;
          if (e.key === 'ArrowRight') nextImg();
          if (e.key === 'ArrowLeft') prevImg();
          if (e.key === 'Escape') closeLightbox();
        });
      });
    </script>
    <div id="lightbox">
      <button id="lightbox-close" title="إغلاق">&times;</button>
      <button class="lightbox-nav left" id="lightbox-left" title="السابق">&#8592;</button>
      <img id="lightbox-img" src="" alt="صورة مكبرة">
      <button class="lightbox-nav right" id="lightbox-right" title="التالي">&#8594;</button>
    </div>
  <?php else: ?>
    <div class="alert alert-secondary">اختر مجلدًا لعرض الصور بداخله.</div>
  <?php endif; ?>
</div>
</body>
</html> 