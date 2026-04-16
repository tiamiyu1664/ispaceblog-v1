<?php
session_start();
$name = $_SESSION['FullName'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tech Blog – Admin Dashboard</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Quill -->
  <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>

<body class="bg-slate-100">

  <div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <!-- <aside class="hidden md:flex w-64 bg-slate-900 text-white flex-col">
      <div class="p-6 text-2xl font-bold border-b border-slate-700">
        Tech Blog
      </div>

      <nav class="flex-1 p-4 space-y-3">
        <a href="#" class="block px-4 py-2 rounded bg-blue-600">Dashboard</a>
        <a href="#" class="block px-4 py-2 rounded hover:bg-slate-700">Posts</a>
        <a href="#" class="block px-4 py-2 rounded hover:bg-slate-700">Categories</a>
        <a href="#" class="block px-4 py-2 rounded hover:bg-slate-700">Users</a>
        <a href="#" class="block px-4 py-2 rounded hover:bg-slate-700">Analytics</a>
        <a href="#" class="block px-4 py-2 rounded hover:bg-slate-700">Settings</a>
      </nav>

      <div class="p-4 border-t border-slate-700 text-sm">
        © 2026 Tech Blog
      </div>
    </aside> -->
    <?php include_once 'include/sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col">

      <!-- TOP BAR -->
      <!-- <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-slate-800">
          Dashboard Overview
        </h1>

        <div class="flex items-center gap-4">
          <span class="text-slate-600 text-sm">Admin</span>
          <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
            A
          </div>
        </div>
      </header> -->
      <!-- header -->
      <?php include_once 'include/header.php' ?>
      <!-- /header -->
      <!-- CONTENT -->
      <main class="p-6 flex-1">

        <!-- STATS -->
        <div class="bg-white mb-10">
          <div class="p-6 border-b">
            <h2 class="text-lg font-bold">Post New Blog</h2>
          </div>
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow overflow-x-auto">


          <!-- Form -->
          <form id="blogForm" method="POST" class="space-y-6 my-4 px-6">

            <!-- Title -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Blog Title</label>
              <input type="text" name="title"
                class="w-full rounded-lg border border-gray-200 focus:border-green-600 focus:ring-green-600">
            </div>

            <!-- Author & Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                <input type="text" name="author"
                  class="w-full rounded-lg  border border-gray-200 focus:border-green-600 focus:ring-green-600">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category" id="categorySelect"
                  class="w-full rounded-lg border  border-gray-200 focus:border-green-600 focus:ring-green-600">
                  <option value="">Select category</option>
                </select>
              </div>
            </div>

            <!-- Featured Image -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
              <input type="file" name="image"
                class="w-full file:mr-4 file:py-2 file:px-4 border border-gray-200
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

      </main>
    </div>
  </div>
  <!-- Quill JS -->
  <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
  <!-- coming -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Write your blog content here...',
        modules: {
          toolbar: [
            [{
              header: [1, 2, 3, false]
            }],
            ['bold', 'italic', 'underline'],
            [{
              list: 'ordered'
            }, {
              list: 'bullet'
            }],
            ['link', 'image'],
            ['clean']
          ]
        }
      });

      // On submit, copy Quill content to hidden input
      document.getElementById('blogForm').addEventListener('submit', function() {
        document.getElementById('content').value = quill.root.innerHTML;
      });

      getData();
      async function getData() {
        const url = "http://localhost:8080/ispaceBlog/api/?endpoint=GetAllCategory";
        try {
          const response = await fetch(url);
          if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
          }

          const result = await response.json();
          var data = Object.values(result.data);

          //   console.log(data);
          //   alert(data)
          // return result;
          const select = document.getElementById("categorySelect");

          data.forEach(cat => {
            const option = document.createElement("option");
            option.value = cat;
            option.textContent = cat;
            select.appendChild(option);
          });
        } catch (error) {
          console.error(error.message);
        }
      }

      async function submitBlog(formData) {
  try {
    const response = await fetch("/ispaceBlog/api/?endpoint=CreateBlog", {
      method: "POST",
      body: formData
    });

    const text = await response.text();   // 👈 read raw text first
    let res;

    if (!text) {
      Swal.fire("Server Error", "Empty response from server", "error");
      return;
    }

    try {
      res = JSON.parse(text);
    } catch (err) {
      Swal.fire("Server Error", "Invalid JSON response:\n" + text, "error");
      console.error("RAW RESPONSE:", text);
      return;
    }

    if (res.success === "Yes") {
      Swal.fire("Success 🎉", res.message || "Blog published", "success");
      document.getElementById("blogForm").reset();
      quill.root.innerHTML = "";
    } else {
      Swal.fire("Failed", res.message || "Something went wrong", "error");
    }

  } catch (error) {
    Swal.fire("Network Error", error.message, "error");
  }
}


      //submitting of form
      document.getElementById("blogForm").addEventListener("submit", async function(e) {
        e.preventDefault();

        // 🎯 Get form values
        const title = document.querySelector('[name="title"]').value.trim();
        const author = document.querySelector('[name="author"]').value.trim();
        const category = document.querySelector('[name="category"]').value;
        const imageInput = document.querySelector('[name="image"]');
        const image = imageInput.files[0];

        // 🧠 Quill content
        const quillContent = quill.root.innerHTML.trim();
        document.getElementById("content").value = quillContent;

        // ✅ VALIDATION
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

        // 📦 MANUAL FormData (like your Email/Password example)
        const formData = new FormData();
        formData.append("title", title);
        formData.append("author", author);
        formData.append("category", category);
        formData.append("content", quillContent);

        if (image) {
          formData.append("image", image);
        }

        // 🚀 Loading state
        Swal.fire({
          title: "Publishing...",
          text: "Please wait",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });
        //submit blog
         submitBlog(formData);
        // try {
        //   const response = await fetch("../api/?endpoint=CreateBlog", {
        //     method: "POST",
        //     body: formData
        //   });

        //   const res = await response.json();
        //   Swal.close();

        //   if (res.success === "Yes") {
        //     Swal.fire("Success 🎉", res.message || "Blog published", "success");
        //     document.getElementById("blogForm").reset();
        //     quill.root.innerHTML = "";
        //   } else {
        //     Swal.fire("Failed", res.message || "Something went wrong", "error");
        //   }

        // } catch (error) {
        //   Swal.close();
        //   Swal.fire("Error", "Server not responding", "error");
        //   console.error(error);
        // }
      });

    })
    // end
  </script>
 <!-- <script>
  document.addEventListener("DOMContentLoaded", function () {

  // ✅ 1. INIT QUILL
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

  // ✅ 2. LOAD CATEGORIES
  getData();
  async function getData() {
    try {
      const response = await fetch("api/?endpoint=GetAllCategory");
      const text = await response.text();
      let result;

      try {
        result = JSON.parse(text);
      } catch (err) {
        Swal.fire("Server Error", "Invalid category response:\n" + text, "error");
        return;
      }

      if (result.success === "Yes") {
        const select = document.getElementById("categorySelect");
        select.innerHTML = `<option value="">-- Select Category --</option>`;

        Object.values(result.data).forEach(cat => {
          const option = document.createElement("option");
          option.value = cat;
          option.textContent = cat;
          select.appendChild(option);
        });
      }
    } catch (error) {
      Swal.fire("Error", "Failed to load categories", "error");
      console.error(error);
    }
  }

  // ✅ 3. FORM SUBMIT HANDLER
  document.getElementById("blogForm").addEventListener("submit", function (e) {
    e.preventDefault();
    submitBlog();
  });

  // ✅ 4. ASYNC SUBMIT FUNCTION
  async function submitBlog() {

    const title = document.querySelector('[name="title"]').value.trim();
    const author = document.querySelector('[name="author"]').value.trim();
    const category = document.querySelector('[name="category"]').value;
    const imageInput = document.querySelector('[name="image"]');
    const image = imageInput.files[0];

    const quillContent = quill.root.innerHTML.trim();
    document.getElementById("content").value = quillContent;

    // ✅ VALIDATION
    if (!title) {
      Swal.fire("Missing Title", "Blog title is required", "warning");
      return;
    }

    if (!category) {
      Swal.fire("Select Category", "Please choose a category", "warning");
      return;
    }

    if (quillContent === "<p><br></p>" || quillContent.length < 20) {
      Swal.fire("Content Required", "Blog content is too short", "warning");
      return;
    }

    // ✅ FORMDATA
    const formData = new FormData();
    formData.append("title", title);
    formData.append("author", author);
    formData.append("category", category);
    formData.append("content", quillContent);

    if (image) {
      formData.append("image", image);
    }

    Swal.fire({
      title: "Publishing...",
      text: "Please wait",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    try {
      const response = await fetch("api/?endpoint=CreateBlog", {
        method: "POST",
        body: formData
      });

      const text = await response.text();
      let res;

      try {
        res = JSON.parse(text);
      } catch (err) {
        Swal.close();
        Swal.fire("Server Error", "Invalid server response:\n" + text, "error");
        return;
      }

      Swal.close();

      if (res.success === "Yes") {
        Swal.fire("Success 🎉", res.message || "Blog published", "success");
        document.getElementById("blogForm").reset();
        quill.root.innerHTML = "";
      } else {
        Swal.fire("Failed", res.message || "Something went wrong", "error");
      }

    } catch (error) {
      Swal.close();
      Swal.fire("Network Error", error.message, "error");
      console.error(error);
    }
  }

});
</script> -->
 
  <!-- sweeet alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="../assets/js/setting-blog-form.js"></script>

  <script>

  </script>
</body>

</html>