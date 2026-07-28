<?php
session_start();
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin'){
$name = $_SESSION['FullName'];

$conn = mysqli_connect("localhost", "root", "", "ispaceblogdb");
$totalPosts = 0;
$totalCategories = 0;
$pendingComments = 0;
$activeAuthors = 0;

if ($conn) {
    // Total posts
    $postCountRes = mysqli_query($conn, "SELECT COUNT(*) FROM blog_add_00001");
    if ($postCountRes) {
        $totalPosts = mysqli_fetch_array($postCountRes)[0];
    }
    
    // Categories count
    $catCountRes = mysqli_query($conn, "SELECT COUNT(*) FROM add_category_00001");
    if ($catCountRes) {
        $totalCategories = mysqli_fetch_array($catCountRes)[0];
    }
    
    // Pending comments
    $commentCountRes = mysqli_query($conn, "SELECT COUNT(*) FROM blog_comments WHERE StatusID = 'P'");
    if ($commentCountRes) {
        $pendingComments = mysqli_fetch_array($commentCountRes)[0];
    }
    
    // Active authors
    $authorCountRes = mysqli_query($conn, "SELECT COUNT(DISTINCT Author) FROM blog_add_00001");
    if ($authorCountRes) {
        $activeAuthors = mysqli_fetch_array($authorCountRes)[0];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tech Blog – Admin Dashboard</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

          <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-slate-500 text-sm">Total Posts</h3>
            <p class="text-3xl font-bold mt-2"><?= $totalPosts ?></p>
          </div>

          <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-slate-500 text-sm">Categories</h3>
            <p class="text-3xl font-bold mt-2"><?= $totalCategories ?></p>
          </div>

          <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-slate-500 text-sm">Pending Comments</h3>
            <p class="text-3xl font-bold mt-2"><?= $pendingComments ?></p>
          </div>

          <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-slate-500 text-sm">Active Authors</h3>
            <p class="text-3xl font-bold mt-2"><?= $activeAuthors ?></p>
          </div>

        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow overflow-x-auto">
          <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold">Recent Blog Posts</h2>
            <a href="manage-blogs.php" class="text-blue-600 hover:underline text-sm font-semibold">View All</a>
          </div>

          <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-600">
              <tr>
                <th class="text-left px-6 py-3">Title</th>
                <th class="text-left px-6 py-3">Category</th>
                <th class="text-left px-6 py-3">Author</th>
                <th class="text-left px-6 py-3">Status</th>
                <th class="text-left px-6 py-3">Published Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($conn) {
                  $recentRes = mysqli_query($conn, "SELECT * FROM blog_add_00001 ORDER BY CreationDate DESC LIMIT 5");
                  if (mysqli_num_rows($recentRes) > 0) {
                      while ($row = mysqli_fetch_assoc($recentRes)) {
                          $statusText = $row['StatusID'] === 'A' ? 'Published' : 'Draft';
                          $statusClass = $row['StatusID'] === 'A' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
                          ?>
                          <tr class="border-t">
                            <td class="px-6 py-4 font-semibold"><?= htmlspecialchars($row['Title']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($row['Category']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($row['Author']) ?></td>
                            <td class="px-6 py-4">
                              <span class="<?= $statusClass ?> px-3 py-1 rounded-full text-xs">
                                <?= $statusText ?>
                              </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500"><?= date('M d, Y', strtotime($row['CreationDate'])) ?></td>
                          </tr>
                          <?php
                      }
                  } else {
                      echo '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No blog posts found.</td></tr>';
                  }
              }
              ?>
            </tbody>
          </table>
        </div>

      </main>
    </div>
  </div>

</body>

</html>

<?php 
}else{
  header("Location: ../index.php");
  exit;
}
?>