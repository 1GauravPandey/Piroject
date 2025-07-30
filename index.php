<?php include 'session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Jutta Sansaar - Step Into Style</title>
  <link rel="stylesheet" href="index.css">
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
        <!-- Show Login if not logged in, otherwise Logout -->
    <?php if (!is_logged_in()): ?>
      <a href="login.php">Login</a>
    <?php else: ?>
      <a href="logout.php">Logout</a>
    <?php endif; ?>
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
