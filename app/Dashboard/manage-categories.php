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
  <title>Manage Categories – Admin Dashboard</title>
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
          <h1 class="text-2xl font-bold text-slate-800">Manage Categories</h1>
          <a href="create-category.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition shadow-sm">
            + Create Category
          </a>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
          <table class="w-full text-sm text-left">
            <thead class="bg-slate-900 text-white text-xs uppercase">
              <tr>
                <th class="px-6 py-4">CategoryID</th>
                <th class="px-6 py-4">Category Name</th>
                <th class="px-6 py-4">Created Date</th>
                <th class="px-6 py-4 text-center">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <?php
              $res = mysqli_query($conn, "SELECT * FROM add_category_00001 ORDER BY CreationDate DESC");
              if (mysqli_num_rows($res) > 0) {
                  while ($row = mysqli_fetch_assoc($res)) {
                      ?>
                      <tr class="hover:bg-slate-50 transition" id="row-<?= $row['CategoryID'] ?>">
                        <td class="px-6 py-4 font-mono text-gray-500"><?= htmlspecialchars($row['CategoryID']) ?></td>
                        <td class="px-6 py-4 font-semibold text-slate-800" id="name-<?= $row['CategoryID'] ?>">
                          <?= htmlspecialchars($row['Category']) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                          <?= date('M d, Y', strtotime($row['CreationDate'])) ?>
                        </td>
                        <td class="px-6 py-4">
                          <div class="flex justify-center items-center gap-3">
                            <button onclick="editCategory('<?= $row['CategoryID'] ?>', '<?= htmlspecialchars($row['Category'], ENT_QUOTES) ?>')" class="text-blue-600 hover:underline font-semibold text-xs">
                              Edit Name
                            </button>
                            <button onclick="deleteCategory('<?= $row['CategoryID'] ?>')" class="text-red-600 hover:underline font-semibold text-xs">
                              Delete
                            </button>
                          </div>
                        </td>
                      </tr>
                      <?php
                  }
              } else {
                  echo '<tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">No categories found.</td></tr>';
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
    function editCategory(categoryID, currentName) {
      Swal.fire({
        title: 'Edit Category Name',
        input: 'text',
        inputValue: currentName,
        inputLabel: 'New name:',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        inputValidator: (value) => {
          if (!value.trim()) {
            return 'Category name cannot be empty!'
          }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const newName = result.value.trim();
          
          const formData = new FormData();
          formData.append("CategoryID", categoryID);
          formData.append("Category", newName);

          fetch(`../../api/?endpoint=UpdateCategory&id=${categoryID}`, {
            method: "POST",
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (data.success === 'Yes') {
              Swal.fire('Updated!', data.message || 'Category updated.', 'success').then(() => {
                document.getElementById(`name-${categoryID}`).textContent = newName;
              });
            } else {
              Swal.fire('Failed', data.message || 'Update failed.', 'error');
            }
          });
        }
      });
    }

    function deleteCategory(categoryID) {
      Swal.fire({
        title: 'Delete this category?',
        text: "Make sure no active posts rely on this category!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`../../api/?endpoint=DeleteCategory&id=${categoryID}`)
            .then(res => res.json())
            .then(data => {
              if (data.success === 'Yes') {
                Swal.fire('Deleted!', data.message || 'Category removed.', 'success').then(() => {
                  location.reload();
                });
              } else {
                Swal.fire('Failed', data.message || 'Deletion failed.', 'error');
              }
            });
        }
      });
    }
  </script>
</body>
</html>
