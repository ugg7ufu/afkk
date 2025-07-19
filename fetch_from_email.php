<?php include 'sidebar.php'; ?>
<div style="margin-right: 270px; padding: 30px;">
<?php
// إعدادات الاتصال بالبريد
$hostname = '{imap-mail.outlook.com:993/imap/ssl}INBOX';
$username = 'Mazenhijazi0788885183@outlook.sa';
$password = 'ضع_كلمة_مرور_التطبيق_الخاصة_بك_هنا'; // App Password

$inbox = imap_open($hostname, $username, $password) or die('❌ فشل الاتصال: ' . imap_last_error());
$emails = imap_search($inbox, 'UNSEEN');

if ($emails) {
    rsort($emails);
    foreach ($emails as $email_number) {
        $structure = imap_fetchstructure($inbox, $email_number);
        if (isset($structure->parts)) {
            for ($i = 0; $i < count($structure->parts); $i++) {
                $part = $structure->parts[$i];
                if ($part->ifdparameters) {
                    foreach ($part->dparameters as $object) {
                        if (strtolower($object->attribute) == 'filename') {
                            $filename = $object->value;
                            $size = isset($part->bytes) ? $part->bytes : 0;
                            $type = isset($part->subtype) ? $part->subtype : 'unknown';

                            // حفظ في قاعدة البيانات فقط
                            try {
                                $pdo = new PDO("mysql:host=localhost;dbname=my_app_data;charset=utf8mb4", "root", "");
                                $stmt = $pdo->prepare("INSERT INTO uploads (file_name, file_type, file_size, uploaded_at)
                                                       VALUES (?, ?, ?, NOW())");
                                $stmt->execute([
                                    $filename,
                                    $type,
                                    $size
                                ]);
                                echo "✔️ تم تسجيل بيانات الملف: $filename<br>";
                            } catch (PDOException $e) {
                                echo "❌ خطأ في قاعدة البيانات: " . $e->getMessage();
                            }
                        }
                    }
                }
            }
        }
    }
} else {
    echo "📭 لا توجد رسائل جديدة.";
}

imap_close($inbox);
?>
</div>
