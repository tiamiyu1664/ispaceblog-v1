<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Blog Post</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Quill -->
  <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg p-6 md:p-10">

    <!-- Header -->
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-gray-800">Create New Blog Post</h2>
      <p class="text-gray-500 text-sm">Publish articles to your website</p>
    </div>

    <!-- Form -->
    <form id="blogForm" method="POST" class="space-y-6">

      <!-- Title -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Blog Title</label>
        <input type="text" name="title" required
          class="w-full rounded-lg border border-gray-200 focus:border-green-600 focus:ring-green-600">
      </div>

      <!-- Author & Category -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Author</label>
          <input type="text" name="author"
            class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
          <select name="category"
            class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">
            <option value="">Select category</option>
            <option>Technology</option>
            <option>Construction</option>
            <option>Business</option>
          </select>
        </div>
      </div>

      <!-- Featured Image -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
        <input type="file" name="image"
          class="w-full file:mr-4 file:py-2 file:px-4
          file:rounded-lg file:border-0
          file:text-sm file:font-semibold
          file:bg-green-600 file:text-white hover:file:bg-green-700">
      </div>

      <!-- Quill Editor -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Blog Content
        </label>

        <div id="editor" class="bg-white h-64"></div>

        <!-- Hidden input to store Quill HTML -->
        <input type="hidden" name="content" id="content">
      </div>

      <!-- SEO -->
      <div class="border-t pt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">SEO Settings</h3>

        <input type="text" name="meta_title" placeholder="Meta Title"
          class="w-full mb-4 rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">

        <textarea name="meta_description" rows="3" placeholder="Meta Description"
          class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600"></textarea>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-4 pt-6">
        <button type="reset"
          class="px-6 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">
          Cancel
        </button>

        <button type="submit"
          class="px-6 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700">
          Publish Blog
        </button>
      </div>

    </form>
  </div>

  <!-- Quill JS -->
  <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

  <script>
    const quill = new Quill('#editor', {
      theme: 'snow',
      placeholder: 'Write your blog content here...',
      modules: {
        toolbar: [
          [{ header: [1, 2, 3, false] }],
          ['bold', 'italic', 'underline'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['link', 'image'],
          ['clean']
        ]
      }
    });

    // On submit, copy Quill content to hidden input
    document.getElementById('blogForm').addEventListener('submit', function () {
      document.getElementById('content').value = quill.root.innerHTML;
    });
  </script>

</body>
</html>
