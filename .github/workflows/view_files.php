<?php include 'sidebar.php'; ?>
<div style="margin-right: 270px; padding: 30px;">
  <h2 class="mb-4">📂 الملفات المرفوعة</h2>
  <?php
  $baseDir = 'uploads/';
  $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
  $folders = array_filter(glob($baseDir . '*'), 'is_dir');
  $selectedFolder = isset($_GET['folder']) ? basename($_GET['folder']) : '';
  ?>
  <div class="mb-4">
    <b>المجلدات:</b>
    <?php foreach ($folders as $folder): $f = basename($folder); ?>
      <a href="?folder=<?= urlencode($f) ?>" class="btn btn-outline-primary btn-sm<?= $selectedFolder===$f?' active':'' ?>" style="margin-left:5px;">📁 <?= htmlspecialchars($f) ?></a>
    <?php endforeach; ?>
  </div>
  <?php if ($selectedFolder && is_dir($baseDir . $selectedFolder)): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>معاينة</th>
            <th>اسم الملف</th>
            <th>الحجم</th>
            <th>تاريخ الرفع</th>
            <th>تحميل</th>
            <th>حذف</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $files = scandir($baseDir . $selectedFolder);
        $hasFiles = false;
        foreach ($files as $file) {
          if ($file === '.' || $file === '..') continue;
          $filePath = $baseDir . $selectedFolder . '/' . $file;
          if (!is_file($filePath)) continue;
          $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
          $isImage = in_array($fileExt, $imageExts);
          $hasFiles = true;
          echo '<tr>';
          // معاينة
          if ($isImage) {
            echo "<td><img src='".htmlspecialchars($filePath)."' alt='' style='max-width:60px;max-height:60px;border-radius:6px;'></td>";
          } else {
            echo "<td><span style='font-size:2em;color:#888;'>📄</span></td>";
          }
          // اسم الملف
          echo "<td>".htmlspecialchars($file)."</td>";
          // الحجم
          $size = filesize($filePath);
          if ($size < 1024) $sizeStr = $size.' بايت';
          elseif ($size < 1024*1024) $sizeStr = round($size/1024,2).' ك.ب';
          else $sizeStr = round($size/1024/1024,2).' م.ب';
          echo "<td>$sizeStr</td>";
          // التاريخ
          echo "<td>".date('Y-m-d H:i', filemtime($filePath))."</td>";
          // تحميل
          echo "<td><a href='".htmlspecialchars($filePath)."' download class='btn btn-success btn-sm'>تحميل</a></td>";
          // حذف
          echo "<td><a href='?folder=".urlencode($selectedFolder)."&delete=".urlencode($file)."' onclick=\"return confirm('هل أنت متأكد من حذف الملف؟');\" class='btn btn-danger btn-sm'>حذف</a></td>";
          echo '</tr>';
        }
        if (!$hasFiles) {
          echo '<tr><td colspan="6" class="text-center text-muted">لا توجد ملفات في هذا المجلد.</td></tr>';
        }
        // حذف ملف
        if ($selectedFolder && isset($_GET['delete'])) {
          $delFile = basename($_GET['delete']);
          $delPath = $baseDir . $selectedFolder . '/' . $delFile;
          if (file_exists($delPath)) {
            unlink($delPath);
            echo "<script>alert('تم حذف الملف بنجاح');window.location='view_files.php?folder=".urlencode($selectedFolder)."';</script>";
          }
        }
        ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-secondary">اختر مجلدًا لعرض الملفات بداخله.</div>
  <?php endif; ?>
</div>
