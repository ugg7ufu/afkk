<?php
$baseDir = 'uploads/';
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0777, true);
}

$imageExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
$videoExts = ['mp4', 'webm', 'ogg'];
$folders = array_filter(glob($baseDir . '*'), 'is_dir');

$selectedFolder = isset($_GET['folder']) ? basename($_GET['folder']) : '';
$selectedFolderPath = realpath($baseDir . $selectedFolder);

// حذف ملف
if (isset($_GET['delete']) && $selectedFolderPath) {
    $fileToDelete = basename($_GET['delete']);
    $filePath = $selectedFolderPath . DIRECTORY_SEPARATOR . $fileToDelete;
    if (is_file($filePath)) {
        unlink($filePath);
    }
    header("Location: ?folder=$selectedFolder");
    exit;
}

// حذف مجلد
if (isset($_GET['delete_folder'])) {
    $folderToDelete = basename($_GET['delete_folder']);
    $folderPath = realpath($baseDir . $folderToDelete);
    if ($folderPath && strpos($folderPath, realpath($baseDir)) === 0) {
        array_map('unlink', glob("$folderPath/*.*"));
        rmdir($folderPath);
    }
    header("Location: ?");
    exit;
}

// رفع ملفات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files']) && $selectedFolderPath) {
    foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
        $name = basename($_FILES['files']['name'][$key]);
        move_uploaded_file($tmp_name, $selectedFolderPath . DIRECTORY_SEPARATOR . $name);
    }
    header("Location: ?folder=$selectedFolder");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مدير الملفات</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 20px; }
        h2 { text-align: center; }
        .folders, .files { display: flex; flex-wrap: wrap; gap: 20px; }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 10px;
            width: 200px;
            text-align: center;
            position: relative;
        }
        .card img, .card video {
            max-width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 5px;
        }
        .delete-btn {
            position: absolute;
            top: 5px;
            left: 5px;
            background: red;
            color: white;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 12px;
            text-decoration: none;
        }
        form { margin-top: 20px; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div style="margin-right: 270px; padding: 40px;">
        <h2>مدير الملفات</h2>

        <div class="folders">
            <?php foreach ($folders as $folder): 
                $name = basename($folder); ?>
                <div class="card">
                    <strong><?= $name ?></strong><br>
                    <a href="?folder=<?= $name ?>">فتح</a> | 
                    <a class="delete-btn" href="?delete_folder=<?= $name ?>" onclick="return confirm('هل تريد حذف المجلد؟')">🗑</a>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($selectedFolderPath): ?>
            <hr>
            <h3>المجلد: <?= htmlspecialchars($selectedFolder) ?></h3>

            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="files[]" multiple required>
                <button type="submit">رفع</button>
            </form>

            <div class="files">
                <?php
                $files = glob($selectedFolderPath . '/*');
                foreach ($files as $file):
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $name = basename($file);
                    $url = $selectedFolderPath ? $baseDir . $selectedFolder . '/' . $name : '';
                    ?>
                    <div class="card">
                        <a class="delete-btn" href="?folder=<?= $selectedFolder ?>&delete=<?= $name ?>" onclick="return confirm('هل تريد حذف هذا الملف؟')">🗑</a>
                        <?php if (in_array($ext, $imageExts)): ?>
                            <a href="<?= $url ?>" target="_blank"><img src="<?= $url ?>"></a>
                        <?php elseif (in_array($ext, $videoExts)): ?>
                            <video controls src="<?= $url ?>"></video>
                        <?php else: ?>
                            <p><?= $name ?></p>
                            <a href="<?= $url ?>" download>📥 تحميل</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
