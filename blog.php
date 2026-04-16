<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TechPulse Blog</title>

  <style>
    :root {
      --primary: #0f172a;
      --accent: #3b82f6;
      --light: #f8fafc;
      --gray: #64748b;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", sans-serif;
    }

    body {
      background: var(--light);
      color: var(--primary);
      line-height: 1.6;
    }

    header {
      background: white;
      padding: 1rem 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    nav {
      max-width: 1100px;
      margin: auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    nav h1 {
      color: var(--accent);
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 1.5rem;
    }

    nav a {
      text-decoration: none;
      color: var(--primary);
      font-weight: 500;
    }

    .hero {
      max-width: 1100px;
      margin: 3rem auto;
      padding: 2rem;
      background: linear-gradient(135deg, #3b82f6, #1e40af);
      color: white;
      border-radius: 12px;
    }

    .hero h2 {
      font-size: 2.2rem;
      margin-bottom: 1rem;
    }

    .container {
      max-width: 1100px;
      margin: auto;
      padding: 2rem;
    }

    .filters {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
      flex-wrap: wrap;
    }

    .filters button {
      padding: 0.5rem 1rem;
      border: none;
      background: white;
      border-radius: 20px;
      cursor: pointer;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .filters button.active {
      background: var(--accent);
      color: white;
    }

    .blog-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
    }

    .card {
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0,0,0,0.05);
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .card-content {
      padding: 1rem;
    }

    .card span {
      color: var(--accent);
      font-size: 0.85rem;
      font-weight: bold;
    }

    .card h3 {
      margin: 0.5rem 0;
    }

    footer {
      background: var(--primary);
      color: white;
      text-align: center;
      padding: 2rem;
      margin-top: 4rem;
    }

    @media (max-width: 600px) {
      .hero h2 {
        font-size: 1.6rem;
      }
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <header>
    <nav>
      <h1>TechPulse</h1>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Blog</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </nav>
  </header>

  <!-- HERO -->
  <section class="hero">
    <h2>Latest Insights in Technology</h2>
    <p>Stay updated with trends in AI, Web Development, Cybersecurity, and more.</p>
  </section>

  <!-- BLOG -->
  <section class="container">

    <!-- FILTERS -->
    <div class="filters">
      <button class="active" onclick="filterPosts('all')">All</button>
      <button onclick="filterPosts('ai')">AI</button>
      <button onclick="filterPosts('web')">Web</button>
      <button onclick="filterPosts('security')">Security</button>
    </div>

    <!-- POSTS -->
    <div class="blog-grid" id="blogGrid">

      <div class="card" data-category="ai">
        <img src="https://source.unsplash.com/600x400/?artificial-intelligence" />
        <div class="card-content">
          <span>AI</span>
          <h3>The Future of Artificial Intelligence</h3>
          <p>How AI is reshaping industries worldwide.</p>
        </div>
      </div>

      <div class="card" data-category="web">
        <img src="https://source.unsplash.com/600x400/?web-development" />
        <div class="card-content">
          <span>Web</span>
          <h3>Modern Web Development Trends</h3>
          <p>Frameworks, performance, and design systems.</p>
        </div>
      </div>

      <div class="card" data-category="security">
        <img src="https://source.unsplash.com/600x400/?cyber-security" />
        <div class="card-content">
          <span>Security</span>
          <h3>Cybersecurity in 2026</h3>
          <p>Protecting data in a connected world.</p>
        </div>
      </div>

    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <p>&copy; 2026 TechPulse Blog. All rights reserved.</p>
  </footer>

  <!-- JAVASCRIPT -->
  <script>
    function filterPosts(category) {
      const cards = document.querySelectorAll(".card");
      const buttons = document.querySelectorAll(".filters button");

      buttons.forEach(btn => btn.classList.remove("active"));
      event.target.classList.add("active");

      cards.forEach(card => {
        if (category === "all" || card.dataset.category === category) {
          card.style.display = "block";
        } else {
          card.style.display = "none";
        }
      });
    }
  </script>

</body>
</html>
