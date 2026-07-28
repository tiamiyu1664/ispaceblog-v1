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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Posts – Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-slate-800">Manage Blog Posts</h1>
          <a href="create-blog.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition shadow-sm">
            + Post New Blog
          </a>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
          <table class="w-full text-sm text-left">
            <thead class="bg-slate-900 text-white text-xs uppercase">
              <tr>
                <th class="px-6 py-4">Image</th>
                <th class="px-6 py-4">Title</th>
                <th class="px-6 py-4">Category</th>
                <th class="px-6 py-4">Author</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Created Date</th>
                <th class="px-6 py-4 text-center">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <?php
              $res = mysqli_query($conn, "SELECT * FROM blog_add_00001 ORDER BY CreationDate DESC");
              if (mysqli_num_rows($res) > 0) {
                  while ($row = mysqli_fetch_assoc($res)) {
                      $img = !empty($row['Image']) ? '../../api/uploads/blogs/' . $row['Image'] : 'https://images.unsplash.com/photo-1556157382-97eda2d62296';
                      $statusText = $row['StatusID'] === 'A' ? 'Published' : 'Draft';
                      $statusColor = $row['StatusID'] === 'A' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
                      $targetStatus = $row['StatusID'] === 'A' ? 'I' : 'A';
                      $toggleText = $row['StatusID'] === 'A' ? 'Unpublish' : 'Publish';
                      ?>
                      <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                          <img src="<?= htmlspecialchars($img) ?>" alt="" class="w-16 h-10 object-cover rounded-lg border">
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                          <?= htmlspecialchars($row['Title']) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($row['Category']) ?></td>
                        <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($row['Author']) ?></td>
                        <td class="px-6 py-4">
                          <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $statusColor ?>">
                            <?= $statusText ?>
                          </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                          <?= date('M d, Y', strtotime($row['CreationDate'])) ?>
                        </td>
                        <td class="px-6 py-4">
                          <div class="flex justify-center items-center gap-3">
                            <button onclick="toggleStatus('<?= $row['BlogID'] ?>', '<?= $targetStatus ?>')" class="text-xs font-semibold px-2.5 py-1.5 rounded border border-gray-300 hover:bg-slate-100">
                              <?= $toggleText ?>
                            </button>
                            <a href="edit-blog.php?id=<?= $row['BlogID'] ?>" class="text-blue-600 hover:underline font-semibold text-xs">
                              Edit
                            </a>
                            <button onclick="deletePost('<?= $row['BlogID'] ?>')" class="text-red-600 hover:underline font-semibold text-xs">
                              Delete
                            </button>
                          </div>
                        </td>
                      </tr>
                      <?php
                  }
              } else {
                  echo '<tr><td colspan="7" class="px-6 py-10 text-center text-gray-500">No blog posts found.</td></tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    function toggleStatus(blogID, status) {
      Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to change this post's visibility status?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        confirmButtonText: 'Yes, change it!'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`../../api/?endpoint=ToggleBlogStatus&id=${blogID}&status=${status}`)
            .then(res => res.json())
            .then(data => {
              if (data.success === 'Yes') {
                Swal.fire('Updated!', data.message, 'success').then(() => location.reload());
              } else {
                Swal.fire('Failed', data.message, 'error');
              }
            });
        }
      });
    }

    function deletePost(blogID) {
      Swal.fire({
        title: 'Delete this post?',
        text: "This action is permanent and will delete all associated comments!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`../../api/?endpoint=DeleteBlog&id=${blogID}`)
            .then(res => res.json())
            .then(data => {
              if (data.success === 'Yes') {
                Swal.fire('Deleted!', data.message, 'success').then(() => location.reload());
              } else {
                Swal.fire('Failed', data.message, 'error');
              }
            });
        }
      });
    }
  </script>
</body>
</html>
