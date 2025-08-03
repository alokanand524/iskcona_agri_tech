<?php
// admin/blog-store.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// connect to DB
require_once __DIR__ . '/../config/database.php';

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CKEditor CDN -->
    <!-- <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script> -->
    <!-- <script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script> -->

    <!-- Trumbowyg CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trumbowyg@2.27.3/dist/ui/trumbowyg.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/trumbowyg@2.27.3/dist/trumbowyg.min.js"></script>


</head>

<body>
    <div class="container my-5">
        <h2 class="text-center mb-4">📝 Create a New Blog</h2>
        <form action="blog-store.php" method="POST" enctype="multipart/form-data" id="blogForm">
            <div class="row">
                <!-- Title -->
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">Blog Title</label>
                    <input type="text" name="title" id="title" class="form-control" required oninput="generateSlug()">
                </div>

                <!-- Slug -->
                <div class="col-md-6 mb-3">
                    <label for="slug" class="form-label">Slug (auto-generated)</label>
                    <input type="text" name="slug" id="slug" class="form-control" readonly>
                </div>
            </div>

            <div class="row">
                <!-- Category -->
                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select name="category" id="category" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <option value="technology">Technology</option>
                        <option value="travel">Travel</option>
                        <option value="lifestyle">Lifestyle</option>
                    </select>
                </div>

                <!-- Tags -->
                <div class="col-md-6 mb-3">
                    <label for="tags" class="form-label">Tags (comma-separated)</label>
                    <input type="text" name="tags" id="tags" class="form-control" placeholder="e.g., php,laravel,web">
                </div>
            </div>

            <div class="row">
                <!-- Image -->
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Featured Image</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/*"
                        onchange="previewImage(event)">
                    <img id="imagePreview" class="mt-2" src="#" style="display: none; max-height: 200px;" />
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Publish Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
            </div>

            <textarea id="content" name="content"></textarea>
            <script>
                $('#content').trumbowyg();
            </script>
            <!-- Content (Full Width) -->
            <!-- <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" id="content" rows="8" class="form-control" required></textarea>
            </div> -->

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100">🚀 Publish Blog</button>
        </form>

    </div>

    <script>
        function generateSlug() {
            let title = document.getElementById('title').value;
            let slug = title.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
            document.getElementById('slug').value = slug;
        }

        function previewImage(event) {
            let reader = new FileReader();
            reader.onload = function () {
                let output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Initialize CKEditor
        CKEDITOR.replace('content');
    </script>

    <script>
        CKEDITOR.replace('content', {
            filebrowserUploadUrl: 'upload-image.php',
            filebrowserUploadMethod: 'form'
        });

        // Remove upgrade warning
        CKEDITOR.on('instanceReady', function () {
            const interval = setInterval(() => {
                const warningBox = document.querySelector('.cke_notification_warning');
                if (warningBox) {
                    warningBox.remove();
                    clearInterval(interval);
                }
            }, 500);
        });
    </script>


</body>

</html>