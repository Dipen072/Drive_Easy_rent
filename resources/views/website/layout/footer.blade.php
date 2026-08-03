<footer class="site-footer" id="footer">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-3 col-md-6">
        <div class="footer-brand-name mb-3">Drive<span>Ease</span></div>
        <p>India's trusted car rental platform. Quality cars, transparent pricing, and exceptional service across 10 cities.</p>
        <div class="footer-social mt-3">
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
          <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <h6>Quick Links</h6>
        <ul>
          <li><a href="{{url('/index')}}">Home</a></li>
          <li><a href="{{url('/cars')}}">Browse Cars</a></li>
          <li><a href="{{url('/offers')}}">Special Offers</a></li>
          <li><a href="{{url('/locations')}}">Locations</a></li>
          <li><a href="{{url('/about')}}">About Us</a></li>
          <li><a href="{{url('/contact')}}">Contact</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <h6>Customer Support</h6>
        <ul>
          <li><a href="{{url('/faq')}}">FAQ</a></li>
          <li><a href="{{url('/cancellation-policy')}}">Cancellation Policy</a></li>
          <li><a href="{{url('/refund-policy')}}">Refund Policy</a></li>
          <li><a href="{{url('/rental-policy')}}">Rental Policy</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-6 col-6">
        <h6>Popular Locations</h6>
        <ul>
          <li><a href="{{url('/cars?city=Mumbai')}}">Mumbai</a></li>
          <li><a href="{{url('/cars?city=Delhi')}}">Delhi</a></li>
          <li><a href="{{url('/cars?city=Bangalore')}}">Bangalore</a></li>
          <li><a href="{{url('/cars?city=Hyderabad')}}">Hyderabad</a></li>
          <li><a href="{{url('/cars?city=Chennai')}}">Chennai</a></li>
          <li><a href="{{url('/cars?city=Goa')}}">Goa</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6">
        <h6>Contact Us</h6>
        <ul>
          <li style="color:rgba(255,255,255,.6);"><i class="fas fa-phone-alt me-2 text-accent"></i>1800-123-4567</li>
          <li style="color:rgba(255,255,255,.6);"><i class="fas fa-envelope me-2 text-accent"></i>support@driveease.in</li>
          <li style="color:rgba(255,255,255,.6);"><i class="fas fa-map-marker-alt me-2 text-accent"></i>BKC, Mumbai - 400051</li>
          <li style="color:rgba(255,255,255,.6);"><i class="fas fa-clock me-2 text-accent"></i>24/7 Customer Support</li>
        </ul>
        <!-- App Badges -->
        <div class="app-badges mt-3">
          <a href="#" class="app-badge">
            <i class="fab fa-google-play"></i>
            <div><span>Get it on</span><strong>Google Play</strong></div>
          </a>
          <a href="#" class="app-badge">
            <i class="fab fa-apple"></i>
            <div><span>Download on the</span><strong>App Store</strong></div>
          </a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p class="mb-0">© 2025 DriveEase. All rights reserved. Designed & built with ❤️</p>
      <div class="footer-legal">
        <a href="{{url('/privacy-policy')}}">Privacy Policy</a>
        <a href="{{url('/terms')}}">Terms & Conditions</a>
        <a href="{{url('/refund-policy')}}">Refund Policy</a>
      </div>
    </div>
  </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Leaflet JS & DriveEase Location Picker -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{url('website/js/location-picker.js')}}"></script>
<script src="{{url('website/js/data.js')}}"></script>
<script src="{{url('website/js/storage.js')}}"></script>
<script src="{{url('website/js/ui.js')}}"></script>
<script src="{{url('website/js/home.js')}}"></script>
<script src="{{url('website/js/chatbot.js')}}"></script>

<!-- AI Chatbot View -->
@include('website.layout.chatbot')

@include('sweetalert::alert')
</body>
</html>
