<?php
session_start();

// Strict Authentication Check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $redirectUrl = 'blog-detail.php?' . $_SERVER['QUERY_STRING'];
    header("Location: login.php?redirect=" . urlencode($redirectUrl));
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "ispaceblogdb");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$blogID = $_GET['id'] ?? '';
if (empty($blogID)) {
    header("Location: blog.php");
    exit();
}

// Fetch post
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

// Check post status: allow admins to preview drafts, otherwise restrict
$isAdmin = isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin';
if ($blog['StatusID'] !== 'A' && !$isAdmin) {
    header("Location: blog.php");
    exit();
}

// Log Activity Analytics
include_once 'api/General.php';
$general = new GeneralHandler();
$general->logActivity($_SESSION['UserID'], 'post_view', $blogID, 'blog-detail.php?id=' . $blogID);

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

// Fetch related posts (same category, different BlogID)
$relatedSql = "
    SELECT * FROM blog_add_00001 
    WHERE StatusID = 'A' AND Category = ? AND BlogID != ? 
    ORDER BY CreationDate DESC LIMIT 3
";
$rStmt = $conn->prepare($relatedSql);
$rStmt->bind_param("ss", $blog['Category'], $blogID);
$rStmt->execute();
$relatedResult = $rStmt->get_result();
$relatedPosts = [];
while ($row = $relatedResult->fetch_assoc()) {
    $relatedPosts[] = $row;
}

// Fallback: If no posts in same category, pull latest published posts
if (count($relatedPosts) === 0) {
    $fallbackSql = "
        SELECT * FROM blog_add_00001 
        WHERE StatusID = 'A' AND BlogID != ? 
        ORDER BY CreationDate DESC LIMIT 3
    ";
    $fStmt = $conn->prepare($fallbackSql);
    $fStmt->bind_param("s", $blogID);
    $fStmt->execute();
    $fallbackResult = $fStmt->get_result();
    while ($row = $fallbackResult->fetch_assoc()) {
        $relatedPosts[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($blog['Title']) ?> – TechPulse</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts Outfit & Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    h1, h2, h3, h4, .font-display {
      font-family: 'Outfit', sans-serif;
    }
    .ql-content img {
      max-width: 100%;
      height: auto;
      border-radius: 12px;
      margin: 1.5rem 0;
    }
  </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">

  <!-- NAVBAR -->
  <?php include_once 'includes/header.php'; ?>

  <!-- ARTICLE CONTAINER -->
  <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 py-12">
    
    <!-- BACK TO BLOGS -->
    <div class="mb-6">
      <a href="blog.php" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition inline-flex items-center">
        &larr; Back to Feed
      </a>
    </div>

    <!-- MAIN BLOG ARTICLE -->
    <article class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden p-6 sm:p-10">
      
      <!-- Category Badge -->
      <div class="flex items-center gap-2 text-xs font-bold text-blue-600 uppercase tracking-widest mb-4">
        <span><?= htmlspecialchars($blog['Category']) ?></span>
        <?php if ($blog['StatusID'] !== 'A'): ?>
          <span class="bg-yellow-100 text-yellow-800 text-[10px] px-2 py-0.5 rounded-full lowercase font-semibold">draft preview</span>
        <?php endif; ?>
      </div>

      <!-- Title -->
      <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-800 tracking-tight leading-tight mb-6">
        <?= htmlspecialchars($blog['Title']) ?>
      </h1>

      <!-- Author and Meta -->
      <div class="flex items-center gap-4 text-slate-500 text-xs border-b border-slate-100 pb-6 mb-8">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-sm">
            <?= strtoupper(substr($blog['Author'], 0, 1)) ?>
          </div>
          <span class="text-slate-700 font-semibold"><?= htmlspecialchars($blog['Author']) ?></span>
        </div>
        <span>•</span>
        <span>Published on <?= date('M d, Y h:i A', strtotime($blog['CreationDate'])) ?></span>
      </div>

      <!-- Featured Image -->
      <?php if (!empty($blog['Image'])): ?>
        <div class="mb-8 overflow-hidden rounded-2xl border border-slate-100">
          <img src="api/uploads/blogs/<?= htmlspecialchars($blog['Image']) ?>" alt="<?= htmlspecialchars($blog['Title']) ?>" class="w-full h-auto max-h-[450px] object-cover">
        </div>
      <?php endif; ?>

      <!-- Article Content -->
      <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed ql-content">
        <?= $blog['Content'] ?>
      </div>

    </article>

    <!-- RELATED POSTS -->
    <?php if (count($relatedPosts) > 0): ?>
      <section class="mt-16">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight mb-8">Related Articles</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <?php foreach ($relatedPosts as $rPost): ?>
            <?php 
              $rImg = $rPost['Image'] ? 'api/uploads/blogs/' . $rPost['Image'] : 'https://images.unsplash.com/photo-1556157382-97eda2d62296?auto=format&fit=crop&w=400&q=80';
            ?>
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-xs hover:shadow-md transition duration-300 flex flex-col justify-between group">
              <div class="overflow-hidden aspect-video h-36">
                <a href="blog-detail.php?id=<?= $rPost['BlogID'] ?>">
                  <img src="<?= $rImg ?>" alt="<?= htmlspecialchars($rPost['Title']) ?>" class="w-full h-full object-cover group-hover:scale-102 transition duration-500">
                </a>
              </div>
              <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                <h3 class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition line-clamp-2">
                  <a href="blog-detail.php?id=<?= $rPost['BlogID'] ?>"><?= htmlspecialchars($rPost['Title']) ?></a>
                </h3>
                <div class="text-[10px] text-slate-400">
                  By <?= htmlspecialchars($rPost['Author']) ?> • <?= date('M d, Y', strtotime($rPost['CreationDate'])) ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- COMMENTS SECTION -->
    <section class="bg-white rounded-3xl border border-slate-100 shadow-xs p-6 sm:p-10 mt-12">
      <h2 class="text-2xl font-bold text-slate-800 tracking-tight mb-6">
        Comments (<?= count($comments) ?>)
      </h2>

      <!-- Add Comment Form -->
      <div class="mb-8">
        <form id="commentForm" class="space-y-4">
          <input type="hidden" id="BlogID" value="<?= htmlspecialchars($blogID) ?>">
          <div>
            <label for="CommentText" class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wider">Leave a Comment</label>
            <textarea id="CommentText" rows="4" class="w-full rounded-xl border border-slate-200 p-4 text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" placeholder="Join the discussion..."></textarea>
          </div>
          <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow-xs">
            Post Comment
          </button>
        </form>
      </div>

      <!-- Approved Comments List -->
      <div class="space-y-6" id="commentsList">
        <?php if (count($comments) > 0): ?>
          <?php foreach ($comments as $comment): ?>
            <div class="border-b border-slate-100 pb-6 last:border-b-0 last:pb-0">
              <div class="flex justify-between items-center mb-2">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-xs">
                    <?= strtoupper(substr($comment['FullName'], 0, 1)) ?>
                  </div>
                  <span class="font-semibold text-slate-800 text-sm"><?= htmlspecialchars($comment['FullName']) ?></span>
                </div>
                <span class="text-xs text-slate-400"><?= date('M d, Y h:i A', strtotime($comment['CreationDate'])) ?></span>
              </div>
              <p class="text-slate-600 text-sm pl-9 whitespace-pre-line"><?= htmlspecialchars($comment['CommentText']) ?></p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center text-slate-400 py-6 text-sm" id="noCommentsMsg">No comments yet. Be the first to share your thoughts!</p>
        <?php endif; ?>
      </div>

    </section>

  </main>

  <!-- FOOTER -->
  <footer class="bg-slate-900 text-slate-400 py-12 px-6 border-t border-slate-800 mt-16">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-4">
      <p>&copy; 2026 TechPulse Blog. All rights reserved.</p>
      <div class="flex space-x-6">
        <a href="#" class="hover:text-slate-300">Privacy Policy</a>
        <a href="#" class="hover:text-slate-300">Terms of Service</a>
      </div>
    </div>
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
