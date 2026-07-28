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
  <title>Manage Comments – Admin Dashboard</title>
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
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Comment Moderation</h1>

        <div class="bg-white rounded-xl shadow overflow-hidden">
          <table class="w-full text-sm text-left">
            <thead class="bg-slate-900 text-white text-xs uppercase">
              <tr>
                <th class="px-6 py-4">Author</th>
                <th class="px-6 py-4">Blog Post</th>
                <th class="px-6 py-4">Comment Text</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Date Posted</th>
                <th class="px-6 py-4 text-center">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <?php
              $sql = "
                  SELECT c.CommentID, c.CommentText, c.StatusID, c.CreationDate, u.FullName, b.Title AS BlogTitle 
                  FROM blog_comments c 
                  JOIN add_user_00001 u ON c.UserID = u.UserID 
                  JOIN blog_add_00001 b ON c.BlogID = b.BlogID 
                  ORDER BY c.CreationDate DESC
              ";
              $res = mysqli_query($conn, $sql);
              if (mysqli_num_rows($res) > 0) {
                  while ($row = mysqli_fetch_assoc($res)) {
                      $statusText = 'Pending';
                      $statusColor = 'bg-yellow-100 text-yellow-700';
                      if ($row['StatusID'] === 'A') {
                          $statusText = 'Approved';
                          $statusColor = 'bg-green-100 text-green-700';
                      } elseif ($row['StatusID'] === 'R') {
                          $statusText = 'Rejected';
                          $statusColor = 'bg-red-100 text-red-700';
                      }
                      ?>
                      <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                          <?= htmlspecialchars($row['FullName']) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-700 font-medium">
                          <?= htmlspecialchars($row['BlogTitle']) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="<?= htmlspecialchars($row['CommentText']) ?>">
                          <?= htmlspecialchars($row['CommentText']) ?>
                        </td>
                        <td class="px-6 py-4">
                          <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $statusColor ?>">
                            <?= $statusText ?>
                          </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                          <?= date('M d, Y H:i', strtotime($row['CreationDate'])) ?>
                        </td>
                        <td class="px-6 py-4">
                          <div class="flex justify-center items-center gap-2">
                            <?php if ($row['StatusID'] !== 'A'): ?>
                              <button onclick="approveComment('<?= $row['CommentID'] ?>')" class="text-green-600 hover:text-green-800 font-semibold text-xs border border-green-300 px-2 py-1 rounded hover:bg-green-50">
                                Approve
                              </button>
                            <?php endif; ?>
                            <?php if ($row['StatusID'] !== 'R'): ?>
                              <button onclick="rejectComment('<?= $row['CommentID'] ?>')" class="text-yellow-600 hover:text-yellow-800 font-semibold text-xs border border-yellow-300 px-2 py-1 rounded hover:bg-yellow-50">
                                Reject
                              </button>
                            <?php endif; ?>
                            <button onclick="deleteComment('<?= $row['CommentID'] ?>')" class="text-red-600 hover:text-red-800 font-semibold text-xs border border-red-300 px-2 py-1 rounded hover:bg-red-50">
                              Delete
                            </button>
                          </div>
                        </td>
                      </tr>
                      <?php
                  }
              } else {
                  echo '<tr><td colspan="6" class="px-6 py-10 text-center text-gray-500">No comments found.</td></tr>';
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
    function approveComment(commentID) {
      fetch(`../../api/?endpoint=ApproveComment&id=${commentID}`)
        .then(res => res.json())
        .then(data => {
          if (data.success === 'Yes') {
            Swal.fire('Approved!', data.message || 'Comment approved.', 'success').then(() => location.reload());
          } else {
            Swal.fire('Error', data.message, 'error');
          }
        });
    }

    function rejectComment(commentID) {
      fetch(`../../api/?endpoint=RejectComment&id=${commentID}`)
        .then(res => res.json())
        .then(data => {
          if (data.success === 'Yes') {
            Swal.fire('Rejected!', data.message || 'Comment rejected.', 'warning').then(() => location.reload());
          } else {
            Swal.fire('Error', data.message, 'error');
          }
        });
    }

    function deleteComment(commentID) {
      Swal.fire({
        title: 'Delete comment?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, delete'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`../../api/?endpoint=DeleteComment&id=${commentID}`)
            .then(res => res.json())
            .then(data => {
              if (data.success === 'Yes') {
                Swal.fire('Deleted!', data.message, 'success').then(() => location.reload());
              } else {
                Swal.fire('Error', data.message, 'error');
              }
            });
        }
      });
    }
  </script>
</body>
</html>
