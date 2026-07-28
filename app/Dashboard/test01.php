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

    <div class="flex min-h-screen relative">

        <!-- MOBILE OVERLAY -->
        <div id="overlay"
            class="fixed inset-0 bg-black bg-opacity-40 hidden z-30 md:hidden"
            onclick="toggleSidebar()"></div>


        <aside id="sidebar"
            class="fixed md:static z-40 inset-y-0 left-0 w-64 bg-slate-900 text-white
                transform -translate-x-full md:translate-x-0 transition-transform duration-300
                flex flex-col">

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
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col md:ml-64">

            <!-- TOP BAR -->
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">

                <!-- MOBILE MENU BUTTON -->
                <button class="md:hidden text-black-700 text-2xl"
                    onclick="toggleSidebar()">
                    ☰
                </button>

                <h1 class="text-lg sm:text-xl font-bold text-slate-800">
                    Dashboard Overview
                </h1>

                <div class="flex items-center gap-3">
                    <span class="hidden sm:block text-slate-600 text-sm">Admin</span>
                    <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                        A
                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <main class="p-4 sm:p-6 flex-1">

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

                    <table class="min-w-full text-sm">
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

                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("overlay");

            sidebar.classList.toggle("-translate-x-full");
            overlay.classList.toggle("hidden");
        }
    </script>

</body>

</html>