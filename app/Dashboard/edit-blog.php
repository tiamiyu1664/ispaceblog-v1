<?php
session_start();
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin'){
  header("Location: ../index.php");
  exit;
}
$name = $_SESSION['FullName'];

$conn = mysqli_connect("localhost", "root", "", "ispaceblogdb");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$blogID = $_GET['id'] ?? '';
if (empty($blogID)) {
    header("Location: manage-blogs.php");
    exit();
}

$res = mysqli_query($conn, "SELECT * FROM blog_add_00001 WHERE BlogID = '" . mysqli_real_escape_string($conn, $blogID) . "' LIMIT 1");
if (mysqli_num_rows($res) === 0) {
    header("Location: manage-blogs.php");
    exit();
}
$blog = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Post – Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Quill -->
  <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
</head>
<body class="bg-slate-100">
  <div class="flex min-h-screen">
    
    <!-- SIDEBAR -->
    <?php include_once 'include/sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col">
      <!-- HEADER -->
      <?php include_once 'include/header.php'; ?>

      <!-- CONTENT -->
      <main class="p-6 flex-1">
        <div class="bg-white mb-6 p-6 border-b rounded-xl flex justify-between items-center shadow-sm">
          <h2 class="text-xl font-bold text-slate-800">Edit Blog Post</h2>
          <a href="manage-blogs.php" class="text-gray-500 hover:text-gray-700 font-semibold text-sm">
            &larr; Back to List
          </a>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
          <form id="editBlogForm" method="POST" class="space-y-6">
            <input type="hidden" name="BlogID" value="<?= htmlspecialchars($blog['BlogID']) ?>">

            <!-- Title -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Blog Title</label>
              <input type="text" name="title" value="<?= htmlspecialchars($blog['Title']) ?>"
                class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-green-600 focus:ring-green-600 outline-none">
            </div>

            <!-- Author & Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                <input type="text" name="author" value="<?= htmlspecialchars($blog['Author']) ?>"
                  class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-green-600 focus:ring-green-600 outline-none">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category" id="categorySelect"
                  class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-green-600 focus:ring-green-600 outline-none">
                  <option value="<?= htmlspecialchars($blog['Category']) ?>"><?= htmlspecialchars($blog['Category']) ?></option>
                </select>
              </div>
            </div>

            <!-- Featured Image -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image (Leave blank to keep existing)</label>
              <?php if (!empty($blog['Image'])): ?>
                <div class="mb-3">
                  <img src="../../api/uploads/blogs/<?= htmlspecialchars($blog['Image']) ?>" alt="Current featured image" class="w-32 h-20 object-cover rounded-lg border">
                </div>
              <?php endif; ?>
              <input type="file" name="image"
                class="w-full file:mr-4 file:py-2 file:px-4 border border-gray-300 rounded-lg
                  file:border-0 file:rounded-lg file:text-sm file:font-semibold
                  file:bg-green-600 file:text-white hover:file:bg-green-700">
            </div>

            <!-- Status ID -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select name="StatusID"
                class="w-full rounded-lg border border-gray-300 p-2.5 focus:border-green-600 focus:ring-green-600 outline-none">
                <option value="A" <?= $blog['StatusID'] === 'A' ? 'selected' : '' ?>>Published</option>
                <option value="I" <?= $blog['StatusID'] === 'I' ? 'selected' : '' ?>>Draft / Inactive</option>
              </select>
            </div>

            <!-- Quill Editor -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Blog Content</label>
              <div id="editor" class="bg-white h-64 border rounded-lg"></div>
              <!-- Hidden input to store Quill HTML -->
              <input type="hidden" name="content" id="content">
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4 pt-6 border-t">
              <a href="manage-blogs.php" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 text-center font-medium">
                Cancel
              </a>
              <button type="submit" class="px-6 py-2.5 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 shadow-sm">
                Save Changes
              </button>
            </div>

          </form>
        </div>
      </main>
    </div>
  </div>

  <!-- Quill JS -->
  <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Initialize Quill
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

      // Load original content into Quill
      const originalContent = `<?= $blog['Content'] ?>`;
      quill.root.innerHTML = originalContent;

      // Load categories list
      getCategories();
      async function getCategories() {
        const url = "../../api/?endpoint=GetAllCategory";
        try {
          const response = await fetch(url);
          if (response.ok) {
            const result = await response.json();
            const data = Object.values(result.data);
            const select = document.getElementById("categorySelect");
            
            // Clear existing except current
            const currentCat = select.options[0].value;
            select.innerHTML = '';
            
            data.forEach(cat => {
              const option = document.createElement("option");
              option.value = cat;
              option.textContent = cat;
              if (cat === currentCat) {
                option.selected = true;
              }
              select.appendChild(option);
            });
          }
        } catch (error) {
          console.error(error.message);
        }
      }

      // Handle form submit
      document.getElementById('editBlogForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const title = document.querySelector('[name="title"]').value.trim();
        const author = document.querySelector('[name="author"]').value.trim();
        const category = document.querySelector('[name="category"]').value;
        const status = document.querySelector('[name="StatusID"]').value;
        const imageInput = document.querySelector('[name="image"]');
        const image = imageInput.files[0];

        const quillContent = quill.root.innerHTML.trim();
        document.getElementById("content").value = quillContent;

        if (!title) {
          Swal.fire("Missing Title", "Blog title is required", "warning");
          return;
        }
        if (!category) {
          Swal.fire("Select Category", "Please choose a category", "warning");
          return;
        }
        if (quillContent.length < 20) {
          Swal.fire("Content Required", "Blog content is too short", "warning");
          return;
        }

        const formData = new FormData();
        formData.append("BlogID", "<?= $blog['BlogID'] ?>");
        formData.append("title", title);
        formData.append("author", author);
        formData.append("category", category);
        formData.append("StatusID", status);
        formData.append("content", quillContent);
        if (image) {
          formData.append("image", image);
        }

        Swal.fire({
          title: "Saving changes...",
          text: "Please wait",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });

        try {
          const response = await fetch(`../../api/?endpoint=UpdateBlog&id=<?= $blog['BlogID'] ?>`, {
            method: "POST",
            body: formData
          });

          const text = await response.text();
          let res;
          try {
            res = JSON.parse(text);
          } catch (err) {
            Swal.fire("Server Error", "Invalid response from server: " + text, "error");
            return;
          }

          if (res.success === "Yes") {
            Swal.fire("Success 🎉", res.message || "Blog updated successfully", "success").then(() => {
              window.location.href = "manage-blogs.php";
            });
          } else {
            Swal.fire("Failed", res.message || "Something went wrong", "error");
          }
        } catch (error) {
          Swal.fire("Network Error", error.message, "error");
        }
      });

    });
  </script>
</body>
</html>
