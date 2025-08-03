<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Blog Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5" id="blogDetail"></div>

<script>
  const urlParams = new URLSearchParams(window.location.search);
  const blogId = urlParams.get('id');
  const blogs = JSON.parse(localStorage.getItem('blogs')) || [];
  const blog = blogs.find(b => b.id == blogId);
  const blogDetail = document.getElementById('blogDetail');

  if (!blog) {
    blogDetail.innerHTML = `<h3 class="text-danger">❌ Blog not found</h3>`;
  } else {
    blogDetail.innerHTML = `
      <h1>${blog.title}</h1>
      <p class="text-muted">Category: ${blog.category} | Tags: ${blog.tags.join(', ')} | ${new Date(blog.created_at).toLocaleString()}</p>
      <img src="${blog.image}" class="img-fluid mb-4" style="max-height: 400px; object-fit: cover;">
      <div>${blog.content}</div>
      <a href="blog-list.html" class="btn btn-outline-secondary mt-4">⬅ Back to Blogs</a>
    `;
  }
</script>
</body>
</html>
