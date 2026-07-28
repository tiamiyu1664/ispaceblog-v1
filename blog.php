<?php 
session_start();

// Strict Authentication Check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php?redirect=blog.php");
    exit();
}

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Log activity visit
include_once 'api/General.php';
$general = new GeneralHandler();
$general->logActivity($_SESSION['UserID'], 'page_visit', null, 'blog.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  <title>TechPulse Blog</title>
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

    .hero {
      max-width: 1100px;
      margin: 3rem auto;
      padding: 2rem;
      background: linear-gradient(135deg, #3b82f6, #1e40af);
      color: white;
      border-radius: 12px;
    }

    .hero h2 {
      font-size: 2.2rem;
      margin-bottom: 1rem;
    }

    .container {
      max-width: 1100px;
      margin: auto;
      padding: 2rem;
    }

    .filters {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
      flex-wrap: wrap;
    }

    .filters button {
      padding: 0.5rem 1rem;
      border: none;
      background: white;
      border-radius: 20px;
      cursor: pointer;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .filters button.active {
      background: var(--accent);
      color: white;
    }

    .blog-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
    }

    .card {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .card-content {
      padding: 1rem;
    }

    .card span {
      color: var(--accent);
      font-size: 0.85rem;
      font-weight: bold;
    }

    .card h3 {
      margin: 0.5rem 0;
    }

    footer {
      background: var(--primary);
      color: white;
      text-align: center;
      padding: 2rem;
      margin-top: 4rem;
    }

    @media (max-width: 600px) {
      .hero h2 {
        font-size: 1.6rem;
      }
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <?php include_once 'includes/header.php'; ?>

  <!-- HERO -->
  <section class="hero">
    <h2>Latest Insights in Technology</h2>
    <p>Stay updated with trends in AI, Web Development, Cybersecurity, and more.</p>
  </section>

  <!-- BLOG -->
  <section class="container">

    <!-- FILTERS -->
    <div class="filters">
      <button class="active" onclick="filterPosts('all')">All</button>
      <?php
      $conn = mysqli_connect("localhost", "root", "", "ispaceblogdb");
      if ($conn) {
          $catResult = mysqli_query($conn, "SELECT Category FROM add_category_00001 ORDER BY CreationDate ASC");
          while ($catRow = mysqli_fetch_assoc($catResult)) {
              $catName = htmlspecialchars($catRow['Category']);
              $catSlug = strtolower(trim($catRow['Category']));
              echo '<button onclick="filterPosts(\'' . $catSlug . '\')">' . $catName . '</button>';
          }
      }
      ?>
    </div>

    <!-- POSTS -->
    <div class="blog-grid" id="blogGrid">
      <?php
      if ($conn) {
          $blogSql = "
              SELECT b.*, 
                     (SELECT COUNT(*) FROM blog_comments c WHERE c.BlogID = b.BlogID AND c.StatusID = 'A') AS CommentsCount 
              FROM blog_add_00001 b 
              WHERE b.StatusID = 'A' 
              ORDER BY b.CreationDate DESC
          ";
          $blogResult = mysqli_query($conn, $blogSql);
          if (mysqli_num_rows($blogResult) > 0) {
              while ($blogRow = mysqli_fetch_assoc($blogResult)) {
                  $imgSrc = !empty($blogRow['Image']) ? 'api/uploads/blogs/' . htmlspecialchars($blogRow['Image']) : 'https://images.unsplash.com/photo-1556157382-97eda2d62296';
                  $categorySlug = strtolower(trim($blogRow['Category']));
                  $excerpt = substr(strip_tags($blogRow['Content']), 0, 100);
                  if (strlen(strip_tags($blogRow['Content'])) > 100) {
                      $excerpt .= '...';
                  }
                  ?>
                  <div class="card" data-category="<?= htmlspecialchars($categorySlug) ?>">
                    <a href="blog-detail.php?id=<?= htmlspecialchars($blogRow['BlogID']) ?>">
                      <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($blogRow['Title']) ?>" />
                    </a>
                    <div class="card-content">
                      <span><?= htmlspecialchars($blogRow['Category']) ?></span>
                      <h3 class="hover:text-blue-600 transition">
                        <a href="blog-detail.php?id=<?= htmlspecialchars($blogRow['BlogID']) ?>">
                          <?= htmlspecialchars($blogRow['Title']) ?>
                        </a>
                      </h3>
                      <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars($excerpt) ?></p>
                      <div class="flex flex-col gap-1 border-t pt-3 mt-3 text-xs text-gray-500">
                        <div class="flex justify-between">
                          <span>By <strong><?= htmlspecialchars($blogRow['Author']) ?></strong></span>
                          <span><?= date('M d, Y H:i', strtotime($blogRow['CreationDate'])) ?></span>
                        </div>
                        <div class="text-blue-600 font-semibold mt-1">
                          💬 <?= (int)$blogRow['CommentsCount'] ?> comment(s)
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
              }
          } else {
              echo '<p class="col-span-full text-center text-gray-500 py-8">No published blog posts found.</p>';
          }
      }
      ?>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <p>&copy; 2026 TechPulse Blog. All rights reserved.</p>
  </footer>

  <!-- JAVASCRIPT -->
  <script>
    function filterPosts(category) {
      const cards = document.querySelectorAll(".card");
      const buttons = document.querySelectorAll(".filters button");

      buttons.forEach(btn => btn.classList.remove("active"));
      event.target.classList.add("active");

      cards.forEach(card => {
        if (category === "all" || card.dataset.category === category) {
          card.style.display = "block";
        } else {
          card.style.display = "none";
        }
      });
    }
  </script>

</body>
</html>

