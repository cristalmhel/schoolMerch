<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>Study Essentials</title>
  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>   

  <!-- HEADER -->
  <header class="header-landing">
    <div class="logo">
      <img src="image/hellya.JPG" alt="Hero Logo" class="hero-logo">
      <span>School Merchandise</span>
    </div>
    <nav class="nav-landing">
      <ul>
        <li><a href="">Home</a></li>
        @auth
          <li><a href="{{ route('shop') }}">Shop</a></li>
        @else
          <li><a href="{{ route('login') }}">Shop</a></li>
        @endauth
        <li><a href="">About</a></li>
        <li><a href="">Contact</a></li>
      </ul>
    </nav>
    <div>
      @auth
        @if (Auth::user()->role !== 'student')
            <a href="{{ route('dashboard.index') }}" class="login-btn">Dashboard</a>
        @endif
      @else
        <a href="{{ route('login') }}" class="login-btn">Login</a>

        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="login-btn">Register</a>
        @endif
      @endauth
    </div>
  </header>

  <!-- BANNER -->
  <div class="banner">
    Free Shipping on Orders Over $50!
  </div>
<!-- HERO -->
  <section class="hero">
    <div class="hero-content">
      <h1>Everything You Need for Academic Success</h1>
      <p>Your One-Stop Shop for Student Merchandise – Trendy, Quality, and Budget-Friendly!</p>
      <div class="hero-buttons">
        @auth
          <a href="{{ route('shop') }}" class="btn-shop">Shop Now</a>
          <a href="{{ route('shop') }}" class="btn-view">View Catalog</a>
        @else
          <a href="{{ route('login') }}" class="btn-shop">Shop Now</a>
          <a href="{{ route('login') }}" class="btn-view">View Catalog</a>
        @endauth
      </div>
    </div>
  </section>

  <!-- DEPARTMENTS -->
  <section class="departments">
    <h2>Shop by Department</h2>
    <div class="dept-list">
      <div class="dept-card">
        <h3>Books</h3>
        <p>Textbooks & study guides.</p>
        <a href="login.html" class="explore-btn">Explore</a>
      </div>
      <div class="dept-card">
        <h3>Electronics</h3>
        <p>Laptops & gadgets.</p>
        <a href="login.html" class="explore-btn">Explore</a>
      </div>
      <div class="dept-card">
        <h3>Stationery</h3>
        <p>Notebooks & pens.</p>
        <a href="login.html" class="explore-btn">Explore</a>
      </div>
      <div class="dept-card">
        <h3>Accessories</h3>
        <p>Bags & essentials.</p>
        <a href="login.html" class="explore-btn">Explore</a>
      </div>
    </div>
  </section>

  <!-- TRUSTED -->
  <section class="trusted">
    <h3>Trusted by Students Nationwide</h3>
    <div class="trusted-stats">
      <div><span>5000+</span><small>Happy Students</small></div>
      <div><span>500+</span><small>Products Available</small></div>
      <div><span>98%</span><small>Satisfaction Rate</small></div>
      <div><span>24/7</span><small>Customer Support</small></div>
    </div>
  </section>

  <!-- PRODUCTS -->
  <section class="products">
    <h2>Featured Products</h2>
    <div class="product-list">
      <div class="product">
        <img src="image/tshirt.jpg" alt="tshirt">
        <h3>T-Shirt</h3>
        <p class="price">₱350.00</p>
      </div>
      <div class="product">
        <img src="image/lanyard.jpg" alt="Lanyard">
        <h3>Lanyard</h3>
        <p class="price">₱200.00</p>
      </div>
      <div class="product">
        <img src="image/HoodieJacket.jpg" alt="HoodieJacket">
        <h3>Hoodie Jacket</h3>
        <p class="price">₱550.00</p>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer-landing">
    <div class="footer-container">
      <div class="footer-column">
        <h4>About Us</h4>
        <p>StudyEssentials provides students with the tools they need to succeed.</p>
      </div>
      <div class="footer-column">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">Departments</a></li>
          <li><a href="#">Products</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h4>Support</h4>
        <ul>
          <li><a href="#">Help Center</a></li>
          <li><a href="#">FAQs</a></li>
          <li><a href="#">Shipping</a></li>
          <li><a href="#">Returns</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h4>Follow Us</h4>
        <ul>
          <li><a href="#">Facebook</a></li>
          <li><a href="#">Twitter</a></li>
          <li><a href="#">Instagram</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      © 2025 StudyEssentials. All rights reserved.
    </div>
  </footer>


</body>
</html>
