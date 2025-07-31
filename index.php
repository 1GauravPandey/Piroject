<?php include 'session.php'; // Start or resume session and load session-related functions ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" /> <!-- Character encoding -->
  <meta name="viewport" content="width=device-width, initial-scale=1" /> <!-- Responsive meta tag -->
  <title>Jutta Sansaar - Step Into Style</title> <!-- Page title -->
  <link rel="stylesheet" href="index.css"> <!-- Link to external CSS -->
</head>
<body>
  
  <header>
    <div class="container">
      <!-- Website logo/title linking to homepage -->
      <h1><a href="index.php" style="text-decoration: none; color: inherit;">👟 Jutta Sansaar</a></h1>

      <!-- Navigation menu with ARIA label for accessibility -->
      <nav id="nav-menu" aria-label="Primary">
        <a href="index.php" class="nav-link active" aria-current="page">Home</a> <!-- Current page -->
        <a href="products.php" class="nav-link">Shop</a>
        <a href="cart.php" class="nav-link">Cart</a>
        <a href="checkout.php" class="nav-link">Checkout</a>

        <!-- Conditional Login/Logout link based on user session -->
        <?php if (!is_logged_in()): ?>
          <a href="login.php">Login</a>
        <?php else: ?>
          <a href="logout.php">Logout</a>
        <?php endif; ?>
      </nav>

      <!-- Hamburger menu icon for mobile/responsive navigation -->
      <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </header>

  <main>
    <!-- Hero section with welcoming message and call-to-action -->
    <section class="hero" role="banner">
      <h2>Step into Style</h2>
      <p>Find your perfect pair at Jutta Sansaar</p>
      <button class="btn" onclick="location.href='products.php'">Shop Now</button>
    </section>

    <!-- Featured shoes section -->
    <section class="featured" aria-label="Featured shoes">
      <h3>Featured Shoes</h3>

      <!-- Carousel container with ARIA roledescription for screen readers -->
      <div class="carousel" aria-roledescription="carousel">
        <!-- Previous button with accessible label -->
        <button class="carousel-btn prev" aria-label="Previous featured shoe">&#10094;</button>

        <!-- Carousel track container for sliding items -->
        <div class="carousel-track" id="carousel-track">
          <?php
            // Include PHP file that fetches and outputs featured product cards dynamically
            include 'fetch_featured.php';

            // For demonstration, output a sample product card
            echo '
              <div class="carousel-item">
                <img src="https://assets.myntassets.com/w_412,q_60,dpr_2,fl_progressive/assets/images/32682459/2025/2/13/65c6648d-4d0d-4d43-adc8-ccfed9cff1d61739430870295-Puma-Galaxis-Pro-Womens-Performance-Boost-Running-Shoes-8771-1.jpg" alt="Stylish running shoe">
                <h4>Classic Running shoe</h4>
                <p class="price">रु4999</p>
                <button onclick="addToCart(1)">Add to Cart</button>
              </div>
            ';
          ?>
        </div>

        <!-- Next button with accessible label -->
        <button class="carousel-btn next" aria-label="Next featured shoe">&#10095;</button>
      </div>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Jutta Sansaar. All rights reserved.</p> <!-- Footer copyright -->
  </footer>

  <script>
    // Hamburger menu toggle functionality for mobile navigation
    const menuIcon = document.getElementById('menu-icon');
    const navMenu = document.getElementById('nav-menu');

    // Toggle menu visibility on click
    menuIcon.addEventListener('click', () => {
      navMenu.classList.toggle('show');
    });

    // Also toggle menu on keyboard Enter or Space for accessibility
    menuIcon.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        navMenu.classList.toggle('show');
      }
    });

    // Carousel functionality setup
    const track = document.getElementById('carousel-track');
    const prevBtn = document.querySelector('.carousel-btn.prev');
    const nextBtn = document.querySelector('.carousel-btn.next');
    let currentIndex = 0;

    // Update carousel position based on current index
    function updateCarousel() {
      const items = track.children.length;
      const itemWidth = track.children[0].offsetWidth + 24; // item width + gap approx
      const maxIndex = items - Math.floor(track.parentElement.offsetWidth / itemWidth);

      // Loop carousel back to end or start
      if (currentIndex < 0) currentIndex = maxIndex >= 0 ? maxIndex : 0;
      if (currentIndex > maxIndex) currentIndex = 0;

      // Move the carousel track using CSS transform
      track.style.transform = `translateX(${-currentIndex * itemWidth}px)`;
    }

    // Event listeners for previous and next buttons
    prevBtn.addEventListener('click', () => {
      currentIndex--;
      updateCarousel();
    });
    nextBtn.addEventListener('click', () => {
      currentIndex++;
      updateCarousel();
    });

    // Update carousel on window resize for responsiveness
    window.addEventListener('resize', updateCarousel);

    // Auto slide carousel every 5 seconds
    setInterval(() => {
      currentIndex++;
      updateCarousel();
    }, 5000);

    // Dummy function to simulate adding a product to cart
    function addToCart(productId) {
      alert('Added product ' + productId + ' to cart!');
      // TODO: Implement AJAX call to add product to cart on the server
    }

    // Initialize carousel on page load
    updateCarousel();
  </script>
</body>
</html>
