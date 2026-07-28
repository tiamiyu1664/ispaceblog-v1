<?php
session_start();
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['Role']) || $_SESSION['Role'] !== 'admin'){
  header("Location: ../index.php");
  exit;
}
$name = $_SESSION['FullName'];
$currentAdminID = $_SESSION['UserID'];

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
  <title>Manage Administrators – Admin Dashboard</title>
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
      <main class="p-6 flex-1 grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT: LIST ADMINS -->
        <div class="lg:col-span-2 space-y-6">
          <h1 class="text-2xl font-bold text-slate-800">Administrators</h1>

          <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm text-left">
              <thead class="bg-slate-900 text-white text-xs uppercase">
                <tr>
                  <th class="px-6 py-4">Full Name</th>
                  <th class="px-6 py-4">Email</th>
                  <th class="px-6 py-4">Mobile No</th>
                  <th class="px-6 py-4">Gender</th>
                  <th class="px-6 py-4 text-center">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <?php
                $res = mysqli_query($conn, "SELECT * FROM add_user_00001 WHERE Role = 'admin' ORDER BY CreationDate DESC");
                if (mysqli_num_rows($res) > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        $isSelf = $row['UserID'] === $currentAdminID;
                        ?>
                        <tr class="hover:bg-slate-50 transition">
                          <td class="px-6 py-4 font-semibold text-slate-800">
                            <?= htmlspecialchars($row['FullName']) ?>
                            <?php if ($isSelf): ?>
                              <span class="ml-2 bg-blue-100 text-blue-700 text-[10px] font-bold px-1.5 py-0.5 rounded-full">You</span>
                            <?php endif; ?>
                          </td>
                          <td class="px-6 py-4 text-gray-700 font-medium"><?= htmlspecialchars($row['Email']) ?></td>
                          <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($row['MobileNo']) ?></td>
                          <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($row['Gender']) ?></td>
                          <td class="px-6 py-4 text-center">
                            <?php if (!$isSelf): ?>
                              <button onclick="deleteAdmin('<?= $row['UserID'] ?>')" class="text-red-600 hover:underline font-semibold text-xs">
                                Remove Admin
                              </button>
                            <?php else: ?>
                              <span class="text-gray-400 text-xs italic">-</span>
                            <?php endif; ?>
                          </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">No administrators found.</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- RIGHT: CREATE ADMIN -->
        <div>
          <h2 class="text-xl font-bold text-slate-800 mb-6">Add New Admin</h2>
          
          <div class="bg-white rounded-xl shadow p-6">
            <form id="adminForm" class="space-y-4">
              <div>
                <label for="FullName" class="block text-xs font-semibold text-gray-600 mb-1">Full Name</label>
                <input type="text" id="FullName" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm outline-none focus:border-green-600" placeholder="e.g. Jane Doe" required>
              </div>

              <div>
                <label for="MobileNo" class="block text-xs font-semibold text-gray-600 mb-1">Mobile Number</label>
                <input type="text" id="MobileNo" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm outline-none focus:border-green-600" placeholder="e.g. 09012345678" required>
              </div>

              <div>
                <label for="Email" class="block text-xs font-semibold text-gray-600 mb-1">Email Address</label>
                <input type="email" id="Email" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm outline-none focus:border-green-600" placeholder="e.g. jane@example.com" required>
              </div>

              <div>
                <label for="Gender" class="block text-xs font-semibold text-gray-600 mb-1">Gender</label>
                <select id="Gender" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm outline-none focus:border-green-600" required>
                  <option value="">Choose a Gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </div>

              <div>
                <label for="Password" class="block text-xs font-semibold text-gray-600 mb-1">Password</label>
                <input type="password" id="Password" class="w-full rounded-lg border border-gray-300 p-2.5 text-sm outline-none focus:border-green-600" placeholder="••••••••" required>
              </div>

              <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg text-sm transition shadow-sm">
                Create Admin Account
              </button>
            </form>
          </div>
        </div>

      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const adminForm = document.getElementById("adminForm");

      adminForm.addEventListener("submit", function(e) {
        e.preventDefault();

        const FullName = document.getElementById("FullName").value.trim();
        const MobileNo = document.getElementById("MobileNo").value.trim();
        const Email = document.getElementById("Email").value.trim();
        const Gender = document.getElementById("Gender").value;
        const Password = document.getElementById("Password").value.trim();

        if (!FullName || !MobileNo || !Email || !Gender || !Password) {
          Swal.fire("Missing Fields", "Please fill in all details.", "warning");
          return;
        }

        if (Password.length < 6) {
          Swal.fire("Weak Password", "Password must be at least 6 characters.", "warning");
          return;
        }

        const formData = new FormData();
        formData.append("FullName", FullName);
        formData.append("MobileNo", MobileNo);
        formData.append("Email", Email);
        formData.append("Gender", Gender);
        formData.append("Password", Password);

        Swal.fire({
          title: "Creating Admin...",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });

        fetch("../../api/?endpoint=AddAdmin", {
          method: "POST",
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          Swal.close();
          if (data.success === 'Yes') {
            Swal.fire('Success 🎉', data.message || 'Admin created successfully.', 'success').then(() => {
              location.reload();
            });
          } else {
            Swal.fire('Error', data.message || 'Creation failed.', 'error');
          }
        })
        .catch(err => {
          Swal.close();
          Swal.fire('Network Error', err.message, 'error');
        });
      });
    });

    function deleteAdmin(userID) {
      Swal.fire({
        title: 'Remove Administrator?',
        text: "This will revoke dashboard privileges and delete their account profile.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, remove them'
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
