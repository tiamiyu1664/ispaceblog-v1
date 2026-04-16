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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

          <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-slate-500 text-sm">Total Posts</h3>
            <p class="text-3xl font-bold mt-2">128</p>
          </div>

          <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-slate-500 text-sm">Categories</h3>
            <p class="text-3xl font-bold mt-2">12</p>
          </div>

          <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-slate-500 text-sm">Monthly Visitors</h3>
            <p class="text-3xl font-bold mt-2">34,210</p>
          </div>

          <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-slate-500 text-sm">Active Authors</h3>
            <p class="text-3xl font-bold mt-2">8</p>
          </div>

        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow overflow-x-auto">
          <div class="p-6 border-b">
            <h2 class="text-lg font-bold">Recent Blog Posts</h2>
          </div>

          <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-600">
              <tr>
                <th class="text-left px-6 py-3">Title</th>
                <th class="text-left px-6 py-3">Category</th>
                <th class="text-left px-6 py-3">Author</th>
                <th class="text-left px-6 py-3">Status</th>
              </tr>
            </thead>
            <tbody>

              <tr class="border-t">
                <td class="px-6 py-4">Future of AI in Insurance</td>
                <td class="px-6 py-4">AI</td>
                <td class="px-6 py-4">Admin</td>
                <td class="px-6 py-4">
                  <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                    Published
                  </span>
                </td>
              </tr>

              <tr class="border-t">
                <td class="px-6 py-4">Core Systems Modernization</td>
                <td class="px-6 py-4">Enterprise</td>
                <td class="px-6 py-4">Editor</td>
                <td class="px-6 py-4">
                  <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
                    Draft
                  </span>
                </td>
              </tr>

              <tr class="border-t">
                <td class="px-6 py-4">API Strategy for SaaS</td>
                <td class="px-6 py-4">Development</td>
                <td class="px-6 py-4">Admin</td>
                <td class="px-6 py-4">
                  <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                    Published
                  </span>
                </td>
              </tr>

            </tbody>
          </table>
        </div>

      </main>
    </div>
  </div>

</body>

</html>