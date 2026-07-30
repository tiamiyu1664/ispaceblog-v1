<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TechPulse – Dynamic Insights & Technology Blog</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Google Fonts Outfit & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    h1,
    h2,
    h3,
    h4,
    .font-display {
      font-family: 'Outfit', sans-serif;
    }
  </style>
</head>

<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col">

  <!-- NAVBAR -->
  <?php include_once 'includes/header.php'; ?>

  <!-- HERO SECTION -->
  <section class="relative bg-slate-900 text-white overflow-hidden py-20 px-6 sm:px-12">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-blue-400 via-indigo-600 to-slate-900 pointer-events-none"></div>
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center relative z-10">
      <div class="space-y-6">
        <span class="inline-flex items-center bg-blue-500/20 text-blue-400 text-xs px-3 py-1.5 rounded-full font-semibold uppercase tracking-wider">
          Welcome to IspaceTech
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black leading-tight tracking-tight">
          Stay Ahead in <br>
          <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-300">Modern Technology</span>
        </h1>
        <p class="text-slate-300 text-base sm:text-lg leading-relaxed max-w-lg">
          Dive into expert insights, programming tutorials, artificial intelligence trends, and cybersecurity best practices curated for digital innovators.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="#explore" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-xl font-semibold transition shadow-md hover:shadow-lg">
            Explore Articles
          </a>
          <?php if (!$isLoggedIn): ?>
            <a href="signup.php" class="border border-slate-700 hover:border-slate-500 hover:bg-slate-800 text-slate-200 px-6 py-3.5 rounded-xl font-semibold transition">
              Get Started
            </a>
          <?php endif; ?>
        </div>
      </div>
      <div class="hidden md:flex justify-end">
        <img
          src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80"
          alt="Tech coding workspace"
          class="rounded-3xl shadow-2xl border border-slate-800 max-w-md w-full object-cover aspect-[4/3] transform hover:scale-[1.02] transition duration-300">
      </div>
    </div>
  </section>

  <!-- MAIN EXPLORE AREA -->
  <main id="explore" class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <!-- SEARCH & FILTER BAR -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-100 p-6 mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">

      <!-- Category Tabs -->
      <div class="flex flex-wrap gap-2" id="categoryTabs">
        <button onclick="selectCategory('all')" id="tab-all" class="category-tab px-4 py-2 text-sm font-semibold rounded-xl bg-blue-600 text-white shadow-xs transition">
          All Posts
        </button>
        <!-- Categories loaded dynamically -->
      </div>

      <!-- Search Input -->
      <div class="relative max-w-xs w-full">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
          <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </span>
        <input type="text" id="searchInput" oninput="handleSearch()" placeholder="Search articles..."
          class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
      </div>

    </div>

    <!-- FEATURED POST (HERO VIEW) -->
    <div id="featuredPostContainer" class="mb-16 hidden">
      <!-- Generated dynamically -->
    </div>

    <!-- RECENT POSTS SECTION TITLE -->
    <div class="flex items-center justify-between mb-8">
      <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Recent Articles</h2>
      <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest" id="postsCount">Loading articles...</span>
    </div>

    <!-- RECENT BLOG POSTS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="blogGrid">
      <!-- Loading Skeleton Cards -->
      <div class="bg-white rounded-2xl border border-slate-100 p-4 animate-pulse space-y-4">
        <div class="bg-slate-200 h-48 rounded-xl w-full"></div>
        <div class="h-4 bg-slate-200 rounded w-1/3"></div>
        <div class="h-6 bg-slate-200 rounded w-3/4"></div>
        <div class="h-4 bg-slate-200 rounded w-full"></div>
        <div class="h-10 bg-slate-200 rounded-lg w-1/4"></div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 p-4 animate-pulse space-y-4">
        <div class="bg-slate-200 h-48 rounded-xl w-full"></div>
        <div class="h-4 bg-slate-200 rounded w-1/3"></div>
        <div class="h-6 bg-slate-200 rounded w-3/4"></div>
        <div class="h-4 bg-slate-200 rounded w-full"></div>
        <div class="h-10 bg-slate-200 rounded-lg w-1/4"></div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 p-4 animate-pulse space-y-4">
        <div class="bg-slate-200 h-48 rounded-xl w-full"></div>
        <div class="h-4 bg-slate-200 rounded w-1/3"></div>
        <div class="h-6 bg-slate-200 rounded w-3/4"></div>
        <div class="h-4 bg-slate-200 rounded w-full"></div>
        <div class="h-10 bg-slate-200 rounded-lg w-1/4"></div>
      </div>
    </div>

    <!-- empty state -->
    <div id="emptyState" class="hidden text-center py-20 bg-white rounded-2xl border border-slate-100 shadow-xs max-w-xl mx-auto mt-6">
      <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
        </svg>
      </div>
      <h3 class="text-lg font-bold text-slate-800 mb-1">No articles found</h3>
      <p class="text-slate-500 text-sm px-6">We couldn't find any articles matching your search query or selected category. Try checking your spelling or selecting another topic!</p>
    </div>

  </main>

  <!-- FOOTER -->
  <footer class="bg-slate-900 text-slate-400 py-12 px-6 border-t border-slate-800">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
      <div class="space-y-4">
        <span class="text-xl font-black text-white">ISpaceTech</span>
        <p class="text-sm leading-relaxed text-slate-400">
          Empowering technology professionals with modern development guides and industry updates.
        </p>
      </div>
      <div>
        <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Quick Links</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="index.php" class="hover:text-white transition">Home</a></li>
          <li><a href="blog.php" class="hover:text-white transition">Blogs</a></li>
          <li><a href="login.php" class="hover:text-white transition">Sign In</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Topic Areas</h4>
        <ul class="space-y-2 text-sm" id="footerCategories">
          <li><a href="#explore" onclick="selectCategory('all')" class="hover:text-white transition">All Topics</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Subscribe</h4>
        <p class="text-xs text-slate-400 mb-3">Get the latest technology insights delivered straight to your inbox.</p>
        <div class="flex gap-2">
          <input type="email" placeholder="Email address" class="bg-slate-800 text-white rounded-lg px-3 py-2 text-xs border border-slate-700 outline-none w-full focus:border-blue-500">
          <button class="bg-blue-600 text-white text-xs font-semibold px-3 py-2 rounded-lg hover:bg-blue-700 transition">Join</button>
        </div>
      </div>
    </div>
    <div class="max-w-7xl mx-auto border-t border-slate-800 pt-8 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-4">
      <p>&copy; 2026 TechPulse Blog. All rights reserved.</p>
      <div class="flex space-x-6">
        <a href="#" class="hover:text-slate-300">Privacy Policy</a>
        <a href="#" class="hover:text-slate-300">Terms of Service</a>
      </div>
    </div>
  </footer>

  <!-- DYNAMIC DATA SCRIPT -->
  <script>
    const isLoggedIn = <?= json_encode($isLoggedIn) ?>;
    let allBlogs = [];
    let activeCategory = 'all';

    document.addEventListener("DOMContentLoaded", async function() {
      // 1. Fetch Categories
      try {
        const response = await fetch("api/?endpoint=GetAllCategory");
        if (response.ok) {
          const res = await response.json();
          if (res.success === "Yes") {
            renderCategories(res.data);
          }
        }
      } catch (err) {
        console.error("Failed to load categories", err);
      }

      // 2. Fetch Blogs
      try {
        const response = await fetch("api/?endpoint=GetBlogs");
        if (response.ok) {
          const res = await response.json();
          if (res.success === "Yes") {
            allBlogs = res.data;
            filterAndRender();
          }
        }
      } catch (err) {
        document.getElementById("blogGrid").innerHTML = `<p class="col-span-full text-center text-red-500 py-8">Failed to fetch articles from network: ${err.message}</p>`;
      }
    });

    function renderCategories(categories) {
      const container = document.getElementById("categoryTabs");
      const footerCat = document.getElementById("footerCategories");

      categories.forEach(cat => {
        // Tab button
        const btn = document.createElement("button");
        btn.id = `tab-${cat.toLowerCase().replace(/\s+/g, '-')}`;
        btn.onclick = () => selectCategory(cat);
        btn.className = "category-tab px-4 py-2 text-sm font-semibold rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition";
        btn.textContent = cat;
        container.appendChild(btn);

        // Footer link
        const li = document.createElement("li");
        const a = document.createElement("a");
        a.href = "#explore";
        a.onclick = () => selectCategory(cat);
        a.className = "hover:text-white transition";
        a.textContent = cat;
        li.appendChild(a);
        footerCat.appendChild(li);
      });
    }

    function selectCategory(category) {
      activeCategory = category;

      // Update Tab Styles
      document.querySelectorAll(".category-tab").forEach(tab => {
        tab.className = "category-tab px-4 py-2 text-sm font-semibold rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition";
      });
      const selectedId = `tab-${category.toLowerCase().replace(/\s+/g, '-')}`;
      const activeTab = document.getElementById(selectedId);
      if (activeTab) {
        activeTab.className = "category-tab px-4 py-2 text-sm font-semibold rounded-xl bg-blue-600 text-white shadow-xs transition";
      }

      filterAndRender();
    }

    function handleSearch() {
      filterAndRender();
    }

    function filterAndRender() {
      const searchVal = document.getElementById("searchInput").value.toLowerCase().trim();

      // Filter list
      let filtered = allBlogs;
      if (activeCategory !== 'all') {
        filtered = filtered.filter(post => post.Category.toLowerCase() === activeCategory.toLowerCase());
      }
      if (searchVal) {
        filtered = filtered.filter(post =>
          post.Title.toLowerCase().includes(searchVal) ||
          post.Content.toLowerCase().includes(searchVal) ||
          post.Author.toLowerCase().includes(searchVal)
        );
      }

      const grid = document.getElementById("blogGrid");
      const featuredContainer = document.getElementById("featuredPostContainer");
      const emptyState = document.getElementById("emptyState");
      const counterText = document.getElementById("postsCount");

      // Clear layout
      grid.innerHTML = '';
      featuredContainer.innerHTML = '';
      featuredContainer.classList.add("hidden");
      emptyState.classList.add("hidden");

      if (filtered.length === 0) {
        emptyState.classList.remove("hidden");
        counterText.textContent = "0 articles";
        return;
      }

      counterText.textContent = `${filtered.length} article(s)`;

      // If we are showing "All" category and not searching, show the latest post as Featured Hero card
      let gridStartIndex = 0;
      if (activeCategory === 'all' && !searchVal && filtered.length > 0) {
        const featured = filtered[0];
        renderFeatured(featured);
        gridStartIndex = 1; // start grid from next item
      }

      // Render the remaining cards in grid
      let cardsRendered = 0;
      for (let i = gridStartIndex; i < filtered.length; i++) {
        const post = filtered[i];
        grid.appendChild(createCard(post));
        cardsRendered++;
      }

      // If only featured was rendered and grid has no cards left, or vice versa
      if (cardsRendered === 0 && gridStartIndex === 1) {
        // Grid is empty because the only post was featured. That's fine!
      }
    }

    function renderFeatured(post) {
      const container = document.getElementById("featuredPostContainer");
      container.classList.remove("hidden");

      const imgSrc = post.Image ? `api/uploads/blogs/${post.Image}` : 'https://images.unsplash.com/photo-1556157382-97eda2d62296?auto=format&fit=crop&w=1200&q=80';
      const readUrl = isLoggedIn ?
        `blog-detail.php?id=${post.BlogID}` :
        `login.php?redirect=blog-detail.php%3Fid%3D${post.BlogID}`;

      const excerpt = cleanExcerpt(post.Content, 180);

      container.innerHTML = `
        <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-xs grid lg:grid-cols-2 gap-0 group">
          <div class="overflow-hidden relative h-64 lg:h-auto">
            <a href="${readUrl}">
              <img src="${imgSrc}" alt="${post.Title}" class="w-full h-full object-cover group-hover:scale-102 transition duration-500 absolute inset-0">
            </a>
            <span class="absolute top-4 left-4 bg-blue-600 text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-xs">
              ★ Featured Post
            </span>
          </div>
          <div class="p-8 lg:p-12 flex flex-col justify-between space-y-6">
            <div class="space-y-4">
              <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">${post.Category}</span>
              <h3 class="text-2xl lg:text-3xl font-bold text-slate-800 group-hover:text-blue-600 transition leading-tight">
                <a href="${readUrl}">${post.Title}</a>
              </h3>
              <p class="text-slate-500 text-sm leading-relaxed">${excerpt}</p>
            </div>
            
            <div class="flex items-center justify-between border-t border-slate-100 pt-6 mt-6">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600">
                  ${post.Author.charAt(0).toUpperCase()}
                </div>
                <div>
                  <span class="block text-sm font-semibold text-slate-800">${post.Author}</span>
                  <span class="block text-xs text-slate-400">${formatDate(post.CreationDate)}</span>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <span class="text-xs text-slate-500 font-medium">💬 ${post.CommentsCount} comment(s)</span>
                <a href="${readUrl}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-700 hover:translate-x-0.5 transition">
                  Read More 
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>
      `;
    }

    function createCard(post) {
      const div = document.createElement("div");
      div.className = "bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-xs hover:shadow-md hover:translate-y-[-4px] transition duration-300 flex flex-col justify-between group";

      const imgSrc = post.Image ? `api/uploads/blogs/${post.Image}` : 'https://images.unsplash.com/photo-1556157382-97eda2d62296?auto=format&fit=crop&w=800&q=80';
      const readUrl = isLoggedIn ?
        `blog-detail.php?id=${post.BlogID}` :
        `login.php?redirect=blog-detail.php%3Fid%3D${post.BlogID}`;

      const excerpt = cleanExcerpt(post.Content, 100);

      div.innerHTML = `
        <div>
          <div class="overflow-hidden aspect-video h-48 relative">
            <a href="${readUrl}">
              <img src="${imgSrc}" alt="${post.Title}" class="w-full h-full object-cover group-hover:scale-102 transition duration-500">
            </a>
            <span class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-xs text-slate-800 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-xs">
              ${post.Category}
            </span>
          </div>
          <div class="p-6 space-y-3">
            <h3 class="text-lg font-bold text-slate-800 group-hover:text-blue-600 transition leading-snug">
              <a href="${readUrl}">${post.Title}</a>
            </h3>
            <p class="text-slate-500 text-xs leading-relaxed line-clamp-3">${excerpt}</p>
          </div>
        </div>
        
        <div class="p-6 pt-0">
          <div class="border-t border-slate-100 pt-4 flex flex-col gap-3">
            <div class="flex items-center justify-between text-[11px] text-slate-500">
              <span class="font-semibold text-slate-600">${post.Author}</span>
              <span>${formatDate(post.CreationDate)}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-[11px] text-slate-500">💬 ${post.CommentsCount} comment(s)</span>
              <a href="${readUrl}" class="inline-flex items-center text-[11px] font-bold text-blue-600 hover:text-blue-700 transition">
                Read More &rarr;
              </a>
            </div>
          </div>
        </div>
      `;

      return div;
    }

    function cleanExcerpt(content, limit) {
      // Remove HTML tags
      const div = document.createElement("div");
      div.innerHTML = content;
      const text = div.textContent || div.innerText || "";
      if (text.length > limit) {
        return text.substring(0, limit) + "...";
      }
      return text;
    }

    function formatDate(dateStr) {
      const options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      };
      const date = new Date(dateStr.replace(/-/g, "/"));
      return date.toLocaleDateString('en-US', options);
    }
  </script>
</body>

</html>