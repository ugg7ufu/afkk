<?php include 'sidebar.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>رفع صور احترافي</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
  <style>
    .dropzone { border: 2px dashed #2563eb; background: #f3f4f6; border-radius: 12px; }
    .dz-message { color: #2563eb; font-size: 1.2em; }
    .dz-preview .dz-remove { color: #d32f2f; }
  </style>
</head>
<body>
<div style="margin-right: 270px; padding: 30px; max-width:700px; margin-left:auto;">
  <h2 class="mb-4">🖼️ رفع صور احترافي</h2>
  <form action="upload_handler.php" class="dropzone" id="myDropzone"></form>
  <div class="alert alert-info mt-4">يمكنك سحب الصور هنا أو الضغط للاختيار. الحد الأقصى لكل صورة: 10 ميجابايت.</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
Dropzone.options.myDropzone = {
  paramName: "file",
  maxFilesize: 10, // MB
  acceptedFiles: "image/*",
  dictDefaultMessage: "اسحب الصور هنا أو اضغط للاختيار",
  addRemoveLinks: true,
  dictRemoveFile: "حذف",
  init: function() {
    this.on("success", function(file, response) {
      file.previewElement.classList.add("dz-success");
    });
    this.on("error", function(file, errorMessage) {
      file.previewElement.classList.add("dz-error");
    });
  }
};
</script>
</body>
</html> 