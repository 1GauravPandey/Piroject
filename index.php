  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Jutta Sansaar - Step Into Style</title>
    <style>
      /* Reset & base */
      * {
        box-sizing: border-box;
        margin: 0; padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }
      body {
        background: #fff;
        color: #222;
        line-height: 1.6;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }
      a {
        text-decoration: none;
        color: inherit;
      }
      img {
        max-width: 100%;
        display: block;
      }

      /* HEADER & NAV */
      header {
        background: #222;
        color: white;
        padding: 1rem 2rem;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 6px rgba(0,0,0,0.4);
      }
      .container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      header h1 {
        font-size: 1.8rem;
        letter-spacing: 2px;
        user-select: none;
      }
      nav {
        display: flex;
        gap: 1.5rem;
        align-items: center;
      }
      nav a {
        font-weight: 600;
        color: #ddd;
        padding: 0.5rem 0.75rem;
        border-radius: 4px;
        transition: background 0.3s, color 0.3s;
      }
      nav a:hover, nav a.active {
        background: #f39c12;
        color: #222;
      }

      /* Hamburger menu */
      #menu-toggle {
        display: none;
      }
      .menu-icon {
        display: none;
        cursor: pointer;
        flex-direction: column;
        gap: 5px;
        width: 25px;
      }
      .menu-icon span {
        display: block;
        height: 3px;
        background: white;
        border-radius: 3px;
        transition: 0.3s;
      }

      /* HERO */
      .hero {
        background: url('https://images.unsplash.com/photo-1577208288347-b24488f3efa5?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8c2hvZSUyMGNvbGxlY3Rpb258ZW58MHx8MHx8fDA%3D') no-repeat center center/cover;
        color: white;
        text-align: center;
        padding: 6rem 2rem;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 60vh;
      }
      .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 0;
      }
      .hero h2 {
        font-size: 3rem;
        z-index: 1;
        margin-bottom: 0.5rem;
        letter-spacing: 3px;
      }
      .hero p {
        font-size: 1.3rem;
        z-index: 1;
        margin-bottom: 2rem;
        font-weight: 500;
      }
      .hero .btn {
        background: #f39c12;
        color: #222;
        padding: 0.75rem 2rem;
        font-weight: 700;
        font-size: 1.1rem;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        align-self: center;
        z-index: 1;
        transition: background 0.3s;
        text-transform: uppercase;
        box-shadow: 0 5px 15px rgba(243,156,18,0.5);
      }
      .hero .btn:hover {
        background: #e67e22;
        box-shadow: 0 8px 20px rgba(230,126,34,0.7);
      }

      /* FEATURED SECTION */
      .featured {
        max-width: 1200px;
        margin: 3rem auto 6rem;
        padding: 0 1rem;
      }
      .featured h3 {
        font-size: 2rem;
        margin-bottom: 1.5rem;
        color: #222;
        letter-spacing: 1.5px;
        text-align: center;
      }

      /* Carousel container */
      .carousel {
        position: relative;
        overflow: hidden;
      }
      .carousel-track {
        display: flex;
        transition: transform 0.5s ease-in-out;
        gap: 1.5rem;
      }
      .carousel-item {
        flex: 0 0 250px;
        background: #fafafa;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgb(0 0 0 / 0.1);
        padding: 1rem;
        text-align: center;
        user-select: none;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.3s ease;
      }
      .carousel-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgb(0 0 0 / 0.15);
      }
      .carousel-item img {
        border-radius: 8px;
        max-height: 180px;
        object-fit: contain;
        margin-bottom: 1rem;
      }
      .carousel-item h4 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
      }
      .carousel-item p.price {
        font-weight: 700;
        color: #f39c12;
        margin-bottom: 1rem;
        font-size: 1.2rem;
      }
      .carousel-item button {
        padding: 0.5rem 1rem;
        border: none;
        background: #f39c12;
        color: #222;
        font-weight: 700;
        border-radius: 25px;
        cursor: pointer;
        transition: background 0.3s;
        text-transform: uppercase;
      }
      .carousel-item button:hover {
        background: #e67e22;
      }

      /* Carousel navigation buttons */
      .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(243, 156, 18, 0.7);
        border: none;
        padding: 0.75rem 1rem;
        cursor: pointer;
        border-radius: 50%;
        color: #222;
        font-weight: 700;
        font-size: 1.3rem;
        transition: background 0.3s;
        user-select: none;
        z-index: 10;
      }
      .carousel-btn:hover {
        background: #e67e22;
      }
      .carousel-btn.prev {
        left: 10px;
      }
      .carousel-btn.next {
        right: 10px;
      }

      /* FOOTER */
      footer {
        background: #222;
        color: white;
        text-align: center;
        padding: 1.5rem 2rem;
        margin-top: auto;
        font-size: 0.9rem;
        letter-spacing: 1px;
        user-select: none;
      }

      /* RESPONSIVE */
      @media (max-width: 900px) {
        .carousel-item {
          flex: 0 0 45%;
        }
        nav {
          gap: 1rem;
        }
      }
      @media (max-width: 600px) {
        .carousel-item {
          flex: 0 0 80%;
        }
        header {
          padding: 1rem;
        }
        nav {
          display: none;
          flex-direction: column;
          background: #222;
          position: absolute;
          top: 100%;
          right: 0;
          width: 200px;
          border-radius: 0 0 0 10px;
        }
        nav.show {
          display: flex;
        }
        .menu-icon {
          display: flex;
        }
      }
    </style>
  </head>
  <body>
<header>
  <div class="container">
    <h1>👟 Jutta Sansaar</h1>
    <nav id="nav-menu" aria-label="Primary">
      
      <a href="index.php" class="nav-link active" aria-current="page">Home</a>
      <a href="products.php" class="nav-link">Shop</a>
      <a href="cart.php" class="nav-link">Cart</a>
      <a href="checkout.php" class="nav-link">Checkout</a>
      <a href="login.php" class="nav-link">Login</a>
      <a href="logout.php" class="nav-link">Logout</a>

      
    </nav>
    <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
</header>

    <main>
      <section class="hero" role="banner">
        
        <h2>Step into Style</h2>
        <p>Find your perfect pair at Jutta Sansaar</p>
        <button class="btn" onclick="location.href='products.php'">Shop Now</button>
      </section>

      <section class="featured" aria-label="Featured shoes">
        <h3>Featured Shoes</h3>
        <div class="carousel" aria-roledescription="carousel">
          <button class="carousel-btn prev" aria-label="Previous featured shoe">&#10094;</button>
          <div class="carousel-track" id="carousel-track">
            <?php
              include 'fetch_featured.php'; // This should output product cards with the below structure:
              // For demo, I’ll simulate here 5 products:
              
              echo '
                <div class="carousel-item">
                  <img src="shoe1.jpg" alt="Stylish running shoe">
                  <h4>Running Shoe Pro</h4>
                  <p class="price">रु4999</p>
                  <button onclick="addToCart(1)">Add to Cart</button>
                </div>
                ... (more items)
              ';
              
            ?>
          </div>
          <button class="carousel-btn next" aria-label="Next featured shoe">&#10095;</button>
        </div>
      </section>
    </main>

    <footer>
      <p>&copy; 2025 Jutta Sansaar. All rights reserved.</p>
    </footer>

    <script>
      // Hamburger menu toggle
      const menuIcon = document.getElementById('menu-icon');
      const navMenu = document.getElementById('nav-menu');
      menuIcon.addEventListener('click', () => {
        navMenu.classList.toggle('show');
      });
      menuIcon.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          navMenu.classList.toggle('show');
        }
      });

      // Carousel functionality
      const track = document.getElementById('carousel-track');
      const prevBtn = document.querySelector('.carousel-btn.prev');
      const nextBtn = document.querySelector('.carousel-btn.next');
      let currentIndex = 0;

      function updateCarousel() {
        const items = track.children.length;
        const itemWidth = track.children[0].offsetWidth + 24; // including gap approx
        const maxIndex = items - Math.floor(track.parentElement.offsetWidth / itemWidth);
        if (currentIndex < 0) currentIndex = maxIndex >= 0 ? maxIndex : 0;
        if (currentIndex > maxIndex) currentIndex = 0;
        track.style.transform = `translateX(${-currentIndex * itemWidth}px)`;
      }

      prevBtn.addEventListener('click', () => {
        currentIndex--;
        updateCarousel();
      });
      nextBtn.addEventListener('click', () => {
        currentIndex++;
        updateCarousel();
      });

      window.addEventListener('resize', updateCarousel);

      // Auto slide every 5 seconds
      setInterval(() => {
        currentIndex++;
        updateCarousel();
      }, 5000);

      // Dummy addToCart function for demo:
      function addToCart(productId) {
        alert('Added product ' + productId + ' to cart!');
        // Here, implement AJAX to add product to cart
      }

      // Initialize on load
      updateCarousel();
    </script>
  </body>
  </html>
