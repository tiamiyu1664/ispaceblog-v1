<aside id="sidebar-menu" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white flex flex-col transform -translate-x-full md:translate-x-0 md:static md:flex transition-transform duration-300 ease-in-out">
    <div class="p-6 text-2xl font-black tracking-tight border-b border-slate-800 bg-slate-950 flex justify-between items-center">
        <span>ISpaceTech</span>
        <button id="close-sidebar-btn" class="md:hidden text-slate-400 hover:text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <a href="overview.php" class="block px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 transition">Overview</a>
        <a href="manage-blogs.php" class="block px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 transition">Manage Posts</a>
        <a href="create-blog.php" class="block px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 transition">Post New Blog</a>
        <a href="manage-categories.php" class="block px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 transition">Categories</a>
        <a href="manage-comments.php" class="block px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 transition">Comments</a>
        <a href="manage-users.php" class="block px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 transition">Users</a>
        <a href="manage-admins.php" class="block px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-800 transition">Administrators</a>
        <a href="../../blog.php" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-blue-400 hover:bg-slate-800 transition">Go to Public Site</a>
        <a href="../../logout.php" class="block px-4 py-2.5 rounded-lg text-sm font-semibold text-red-400 hover:bg-red-950/20 hover:text-red-300 transition">Logout</a>
    </nav>

    <div class="p-4 border-t border-slate-800 text-xs text-slate-500 bg-slate-950">
        © 2026 ISpaceTech Dashboard
    </div>
</aside>

<!-- Backdrop Overlay for Mobile Drawer -->
<div id="sidebar-backdrop" class="fixed inset-0 bg-slate-950/40 z-40 hidden transition-opacity duration-300"></div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtn = document.getElementById("mobile-menu-toggle");
    const closeBtn = document.getElementById("close-sidebar-btn");
    const sidebar = document.getElementById("sidebar-menu");
    const backdrop = document.getElementById("sidebar-backdrop");

    if (toggleBtn && sidebar && backdrop) {
        function openSidebar() {
            sidebar.classList.remove("-translate-x-full");
            backdrop.classList.remove("hidden");
        }
        function closeSidebar() {
            sidebar.classList.add("-translate-x-full");
            backdrop.classList.add("hidden");
        }

        toggleBtn.addEventListener("click", openSidebar);
        backdrop.addEventListener("click", closeSidebar);
        if (closeBtn) {
            closeBtn.addEventListener("click", closeSidebar);
        }
    }
});
</script>