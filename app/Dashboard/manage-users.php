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
  <title>Manage Users – Admin Dashboard</title>
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
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Standard Users Directory</h1>

        <div class="bg-white rounded-xl shadow overflow-hidden">
          <table class="w-full text-sm text-left">
            <thead class="bg-slate-900 text-white text-xs uppercase">
              <tr>
                <th class="px-6 py-4">Full Name</th>
                <th class="px-6 py-4">Email</th>
                <th class="px-6 py-4">Mobile No</th>
                <th class="px-6 py-4">Gender</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Registration Date</th>
                <th class="px-6 py-4 text-center">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <?php
              $res = mysqli_query($conn, "SELECT * FROM add_user_00001 WHERE Role = 'user' ORDER BY CreationDate DESC");
              if (mysqli_num_rows($res) > 0) {
                  while ($row = mysqli_fetch_assoc($res)) {
                      $statusText = $row['StatusID'] === 'A' ? 'Active' : 'Suspended';
                      $statusColor = $row['StatusID'] === 'A' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                      $targetStatus = $row['StatusID'] === 'A' ? 'I' : 'A';
                      $toggleText = $row['StatusID'] === 'A' ? 'Suspend' : 'Activate';
                      ?>
                      <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                          <?= htmlspecialchars($row['FullName']) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-700 font-medium"><?= htmlspecialchars($row['Email']) ?></td>
                        <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($row['MobileNo']) ?></td>
                        <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($row['Gender']) ?></td>
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
                            <button onclick="toggleUserStatus('<?= $row['UserID'] ?>', '<?= $targetStatus ?>')" class="text-xs font-semibold px-2.5 py-1.5 rounded border border-gray-300 hover:bg-slate-100">
                              <?= $toggleText ?>
                            </button>
                            <button onclick="deleteUser('<?= $row['UserID'] ?>')" class="text-red-600 hover:underline font-semibold text-xs">
                              Delete Account
                            </button>
                          </div>
                        </td>
                      </tr>
                      <?php
                  }
              } else {
                  echo '<tr><td colspan="7" class="px-6 py-10 text-center text-gray-500">No standard users registered yet.</td></tr>';
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
    function toggleUserStatus(userID, status) {
      Swal.fire({
        title: 'Change status?',
        text: "This will affect user's ability to log in and post comments.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        confirmButtonText: 'Yes, change status'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`../../api/?endpoint=ToggleUserStatus&id=${userID}&status=${status}`)
            .then(res => res.json())
            .then(data => {
              if (data.success === 'Yes') {
                Swal.fire('Updated!', data.message, 'success').then(() => location.reload());
              } else {
                Swal.fire('Error', data.message, 'error');
              }
            });
        }
      });
    }

    function deleteUser(userID) {
      Swal.fire({
        title: 'Delete user account?',
        text: "This action is permanent and deletes the user profile and their comments!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`../../api/?endpoint=DeleteUser&id=${userID}`)
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
