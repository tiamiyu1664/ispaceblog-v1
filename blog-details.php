<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "ispaceblogdb");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$blogID = $_GET['id'] ?? '';
if (empty($blogID)) {
    header("Location: blog.php");
    exit();
}

$blogSql = "SELECT * FROM blog_add_00001 WHERE BlogID = ? LIMIT 1";
$stmt = $conn->prepare($blogSql);
$stmt->bind_param("s", $blogID);
$stmt->execute();
$blogResult = $stmt->get_result();

if ($blogResult->num_rows === 0) {
    header("Location: blog.php");
    exit();
}

$blog = $blogResult->fetch_assoc();

// Allow admin to preview unpublished/inactive blogs, otherwise block public
$isAdmin = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin';
if ($blog['StatusID'] !== 'A' && !$isAdmin) {
    header("Location: blog.php");
    exit();
}

// Fetch approved comments
$commentsSql = "
    SELECT c.CommentText, c.CreationDate, u.FullName 
    FROM blog_comments c 
    JOIN add_user_00001 u ON c.UserID = u.UserID 
    WHERE c.BlogID = ? AND c.StatusID = 'A' 
    ORDER BY c.CreationDate DESC
";
$cStmt = $conn->prepare($commentsSql);
$cStmt->bind_param("s", $blogID);
$cStmt->execute();
$commentsResult = $cStmt->get_result();
$comments = [];
while ($row = $commentsResult->fetch_assoc()) {
    $comments[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($blog['Title']) ?> – TechPulse Blog</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root {
      --primary: #0f172a;
      --accent: #3b82f6;
      --light: #f8fafc;
      --gray: #64748b;
    }
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", sans-serif;
    }
    body {
      background: var(--light);
      color: var(--primary);
      line-height: 1.6;
    }
    header {
      background: white;
      padding: 1rem 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
      z-index: 100;
    }
    nav {
      max-width: 1100px;
      margin: auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    nav h1 {
      color: var(--accent);
    }
    nav ul {
      list-style: none;
      display: flex;
      gap: 1.5rem;
    }
    nav a {
      text-decoration: none;
      color: var(--primary);
      font-weight: 500;
    }
    .content-container {
      max-w-4xl: 800px;
      margin: 3rem auto;
      padding: 2rem;
      background: white;
      border-radius: 12px;
      box-shadow: 0 5px 25px rgba(0,0,0,0.05);
    }
    footer {
      background: var(--primary);
      color: white;
      text-align: center;
      padding: 2rem;
      margin-top: 4rem;
    }
    .ql-content img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <header>
    <nav>
      <a href="index.php" style="text-decoration: none;"><h1 style="margin:0;">TechPulse</h1></a>
      <ul style="display:flex; align-items:center; gap:1.5rem; margin:0;">
        <li><a href="blog.php">Blogs</a></li>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
          <?php if (isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin'): ?>
            <li><a href="app/Dashboard/overview.php" class="text-blue-600 font-semibold">Admin Panel</a></li>
          <?php endif; ?>
          <li><a href="#"><img class="h-6 w-6 rounded-full" src="assets/images/ts-avatar.jpg" alt=""></a></li>
          <li><a href="#"><?= htmlspecialchars($_SESSION['FullName']) ?></a></li>
          <li><a href="logout.php" class="text-red-500 hover:text-red-700">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php" class="text-blue-600 hover:underline">Login</a></li>
          <li><a href="signup.php" class="text-blue-600 hover:underline">SignUp</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <!-- BLOG ARTICLE -->
  <div class="max-w-3xl mx-auto px-4 mt-10">
    <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 md:p-10">
      
      <!-- Meta -->
      <div class="flex items-center gap-2 text-sm font-semibold text-blue-600 uppercase mb-4">
        <span><?= htmlspecialchars($blog['Category']) ?></span>
        <?php if ($blog['StatusID'] !== 'A'): ?>
          <span class="bg-yellow-100 text-yellow-800 text-xs px-2.5 py-0.5 rounded-full">Draft Preview</span>
        <?php endif; ?>
      </div>

      <!-- Title -->
      <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight mb-6">
        <?= htmlspecialchars($blog['Title']) ?>
      </h1>

      <!-- Author and Date -->
      <div class="flex items-center gap-4 text-gray-500 text-sm border-b pb-6 mb-8">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-700">
            <?= strtoupper(substr($blog['Author'], 0, 1)) ?>
          </div>
          <span>By <strong><?= htmlspecialchars($blog['Author']) ?></strong></span>
        </div>
        <span>•</span>
        <span>Published on <?= date('F d, Y h:i A', strtotime($blog['CreationDate'])) ?></span>
      </div>

      <!-- Featured Image -->
      <?php if (!empty($blog['Image'])): ?>
        <div class="mb-8 overflow-hidden rounded-xl">
          <img src="api/uploads/blogs/<?= htmlspecialchars($blog['Image']) ?>" alt="<?= htmlspecialchars($blog['Title']) ?>" class="w-full h-auto max-h-[450px] object-cover">
        </div>
      <?php endif; ?>

      <!-- Article Content -->
      <div class="prose prose-slate max-w-none text-slate-800 leading-relaxed mb-10 ql-content">
        <?= $blog['Content'] ?>
      </div>

    </article>

    <!-- COMMENTS SECTION -->
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-10 mt-10">
      <h2 class="text-2xl font-bold text-slate-900 mb-6">
        Comments (<?= count($comments) ?>)
      </h2>

      <!-- Add Comment Form -->
      <div class="mb-8">
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
          <form id="commentForm" class="space-y-4">
            <input type="hidden" id="BlogID" value="<?= htmlspecialchars($blogID) ?>">
            <div>
              <label for="CommentText" class="block text-sm font-medium text-slate-700 mb-2">Leave a Comment</label>
              <textarea id="CommentText" rows="4" class="w-full rounded-xl border border-gray-200 p-4 text-slate-800 placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 focus:outline-none" placeholder="Share your thoughts..."></textarea>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-sm">
              Post Comment
            </button>
          </form>
        <?php else: ?>
          <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
            <p class="text-blue-800 font-medium mb-3">You must be logged in to participate in the discussion.</p>
            <div class="flex justify-center gap-4">
              <a href="login.php" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition shadow-sm">Login</a>
              <a href="signup.php" class="px-5 py-2 border border-blue-300 text-blue-700 text-sm font-semibold rounded-lg hover:bg-blue-100 transition">Sign Up</a>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Approved Comments List -->
      <div class="space-y-6" id="commentsList">
        <?php if (count($comments) > 0): ?>
          <?php foreach ($comments as $comment): ?>
            <div class="border-b pb-6 last:border-b-0 last:pb-0">
              <div class="flex justify-between items-center mb-2">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-xs">
                    <?= strtoupper(substr($comment['FullName'], 0, 1)) ?>
                  </div>
                  <span class="font-semibold text-slate-900 text-sm"><?= htmlspecialchars($comment['FullName']) ?></span>
                </div>
                <span class="text-xs text-gray-400"><?= date('M d, Y h:i A', strtotime($comment['CreationDate'])) ?></span>
              </div>
              <p class="text-slate-700 text-sm pl-9 whitespace-pre-line"><?= htmlspecialchars($comment['CommentText']) ?></p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center text-gray-500 py-6" id="noCommentsMsg">No comments yet. Be the first to share your thoughts!</p>
        <?php endif; ?>
      </div>

    </section>
  </div>

  <!-- FOOTER -->
  <footer>
    <p>&copy; 2026 TechPulse Blog. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const commentForm = document.getElementById("commentForm");
      if (commentForm) {
        commentForm.addEventListener("submit", function(e) {
          e.preventDefault();

          const BlogID = document.getElementById("BlogID").value;
          const CommentText = document.getElementById("CommentText").value.trim();

          if (!CommentText) {
            Swal.fire("Blank Comment", "Please type something before posting.", "warning");
            return;
          }

          const formData = new FormData();
          formData.append("BlogID", BlogID);
          formData.append("CommentText", CommentText);

          // Show loader
          Swal.fire({
            title: "Submitting comment...",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
          });

          fetch("api/?endpoint=CreateComment", {
            method: "POST",
            body: formData
          })
          .then(async (res) => {
            Swal.close();
            const text = await res.text();
            let data;
            try {
              data = JSON.parse(text);
            } catch (err) {
              Swal.fire("Server Error", "Invalid response: " + text, "error");
              return;
            }

            if (data.success === "Yes") {
              Swal.fire({
                icon: "success",
                title: "Submitted! 🎉",
                text: data.message || "Your comment has been submitted and is awaiting approval.",
                confirmButtonColor: "#3b82f6"
              }).then(() => {
                document.getElementById("CommentText").value = "";
              });
            } else {
              Swal.fire("Post Failed", data.message || "Something went wrong.", "error");
            }
          })
          .catch((error) => {
            Swal.close();
            Swal.fire("Network Error", error.message, "error");
          });
        });
      }
    });
  </script>
</body>
</html>
