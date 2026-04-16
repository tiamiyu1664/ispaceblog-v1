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
                        <h2 class="text-lg font-bold">Create New Category</h2>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="bg-white rounded-xl shadow overflow-x-auto">
                    <!-- Form -->
                    <form id="categoryForm" method="POST" class="space-y-6 my-4 px-6">

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <input type="text" id="Category" name="Category" 
                                class="w-full rounded-lg border border-gray-200 focus:border-green-600 focus:ring-green-600">
                        </div>
                        <!-- Buttons -->
                        <div class="flex justify-end gap-4 pt-6">
                            <button type="reset"
                                class="px-6 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700">
                                Create
                            </button>
                        </div>

                    </form>
                </div>

            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/category.js"></script>
</body>
</html>