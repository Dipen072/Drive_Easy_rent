/**
 * DriveEase — localStorage Storage Helpers
 * storage.js
 */

const Storage = {
  KEYS: {
    CARS:        'driveease_cars',
    BOOKINGS:    'driveease_bookings',
    CUSTOMERS:   'driveease_customers',
    REVIEWS:     'driveease_reviews',
    COUPONS:     'driveease_coupons',
    LOCATIONS:   'driveease_locations',
    CATEGORIES:  'driveease_categories',
    BRANDS:      'driveease_brands',
    WISHLIST:    'driveease_wishlist',
    SETTINGS:    'driveease_settings',
    AUTH_USER:   'driveease_auth_user',
    ADMIN_AUTH:  'driveease_admin_auth',
    MESSAGES:    'driveease_contact_messages',
    SEEDED:      'driveease_seeded_v3',
    THEME:       'driveease_theme',
    NOTIFS:      'driveease_notifications',
    SEARCH:      'driveease_last_search'
  },

  seed() {
    if (localStorage.getItem(this.KEYS.SEEDED)) {
      const cars = this.getCars();
      if (cars && cars.length > 0) return;
    }

    try {
      localStorage.setItem(this.KEYS.CARS,       JSON.stringify(CARS_DATA));
      localStorage.setItem(this.KEYS.BOOKINGS,   JSON.stringify(BOOKINGS_DATA));
      localStorage.setItem(this.KEYS.CUSTOMERS,  JSON.stringify(CUSTOMERS_DATA));
      localStorage.setItem(this.KEYS.REVIEWS,    JSON.stringify(REVIEWS_DATA));
      localStorage.setItem(this.KEYS.COUPONS,    JSON.stringify(COUPONS_DATA));
      localStorage.setItem(this.KEYS.LOCATIONS,  JSON.stringify(LOCATIONS_DATA));
      localStorage.setItem(this.KEYS.CATEGORIES, JSON.stringify(CATEGORIES_DATA));
      localStorage.setItem(this.KEYS.BRANDS,     JSON.stringify(BRANDS_DATA));
      localStorage.setItem(this.KEYS.MESSAGES,   JSON.stringify(MESSAGES_DEFAULT));
      if (!localStorage.getItem(this.KEYS.WISHLIST)) {
        localStorage.setItem(this.KEYS.WISHLIST, JSON.stringify([]));
      }
      localStorage.setItem(this.KEYS.NOTIFS,     JSON.stringify(NOTIFICATIONS_DEFAULT));
      localStorage.setItem(this.KEYS.SETTINGS,   JSON.stringify(DEFAULT_SETTINGS));
      localStorage.setItem(this.KEYS.SEEDED,     'true');
      console.log('[DriveEase] Data successfully seeded to localStorage.');
    } catch(e) {
      console.error('[DriveEase] Seeding failed:', e);
    }
  },

  // --- Generic CRUD ---
  _get(key) {
    try {
      const data = localStorage.getItem(key);
      return data ? JSON.parse(data) : [];
    } catch(e) {
      return [];
    }
  },
  _set(key, data) {
    localStorage.setItem(key, JSON.stringify(data));
  },
  _getObj(key, def) {
    try {
      const data = localStorage.getItem(key);
      return data ? JSON.parse(data) : def;
    } catch(e) {
      return def;
    }
  },

  // --- CARS ---
  getCars() {
    const cars = this._get(this.KEYS.CARS);
    return (cars && cars.length > 0) ? cars : CARS_DATA;
  },
  getCarById(id) {
    return this.getCars().find(c => c.id === id) || null;
  },
  saveCar(car) {
    const cars = this.getCars();
    const idx  = cars.findIndex(c => c.id === car.id);
    if (idx >= 0) cars[idx] = car; else cars.unshift(car);
    this._set(this.KEYS.CARS, cars);
  },
  deleteCar(id) {
    this._set(this.KEYS.CARS, this.getCars().filter(c => c.id !== id));
  },
  toggleCarAvailability(id) {
    const cars = this.getCars();
    const car  = cars.find(c => c.id === id);
    if (car) { car.available = !car.available; this._set(this.KEYS.CARS, cars); }
    return car;
  },
  getAvailableCars() {
    return this.getCars().filter(c => c.available && c.status === 'Active');
  },
  nextCarId() {
    const cars = this.getCars();
    const max  = cars.reduce((m, c) => Math.max(m, parseInt(c.id.replace('C','')) || 0), 0);
    return `C${String(max + 1).padStart(3,'0')}`;
  },

  // --- BOOKINGS ---
  getBookings() {
    const b = this._get(this.KEYS.BOOKINGS);
    return (b && b.length > 0) ? b : BOOKINGS_DATA;
  },
  getBookingById(id) {
    return this.getBookings().find(b => b.id === id) || null;
  },
  getBookingsByCustomer(cid) {
    return this.getBookings().filter(b => b.customerId === cid);
  },
  saveBooking(booking) {
    const bookings = this.getBookings();
    const idx      = bookings.findIndex(b => b.id === booking.id);
    if (idx >= 0) bookings[idx] = booking; else bookings.unshift(booking);
    this._set(this.KEYS.BOOKINGS, bookings);
  },
  updateBookingStatus(id, status) {
    const bookings = this.getBookings();
    const b        = bookings.find(b => b.id === id);
    if (b) { b.status = status; this._set(this.KEYS.BOOKINGS, bookings); }
    return b;
  },
  nextBookingId() {
    const bookings = this.getBookings();
    const max      = bookings.reduce((m, b) => Math.max(m, parseInt(b.id.replace('BK','')) || 0), 0);
    return `BK${String(max + 1).padStart(3,'0')}`;
  },
  getBookingStats() {
    const all = this.getBookings();
    return {
      total:     all.length,
      pending:   all.filter(b => b.status === 'Pending').length,
      confirmed: all.filter(b => b.status === 'Confirmed').length,
      active:    all.filter(b => b.status === 'Active').length,
      completed: all.filter(b => b.status === 'Completed').length,
      cancelled: all.filter(b => b.status === 'Cancelled').length,
      revenue:   all.filter(b => b.paymentStatus === 'Paid').reduce((s, b) => s + b.total, 0)
    };
  },

  // --- CUSTOMERS ---
  getCustomers() {
    const c = this._get(this.KEYS.CUSTOMERS);
    return (c && c.length > 0) ? c : CUSTOMERS_DATA;
  },
  getCustomerById(id) {
    return this.getCustomers().find(c => c.id === id) || null;
  },
  saveCustomer(cust) {
    const customers = this.getCustomers();
    const idx       = customers.findIndex(c => c.id === cust.id);
    if (idx >= 0) customers[idx] = cust; else customers.unshift(cust);
    this._set(this.KEYS.CUSTOMERS, customers);
  },
  nextCustomerId() {
    const custs = this.getCustomers();
    const max   = custs.reduce((m, c) => Math.max(m, parseInt(c.id.replace('CU','')) || 0), 0);
    return `CU${String(max + 1).padStart(3,'0')}`;
  },

  // --- REVIEWS ---
  getReviews() {
    const r = this._get(this.KEYS.REVIEWS);
    return (r && r.length > 0) ? r : REVIEWS_DATA;
  },
  getReviewById(id) {
    return this.getReviews().find(r => r.id === id) || null;
  },
  saveReview(review) {
    const reviews = this.getReviews();
    const idx     = reviews.findIndex(r => r.id === review.id);
    if (idx >= 0) reviews[idx] = review; else reviews.unshift(review);
    this._set(this.KEYS.REVIEWS, reviews);
  },
  deleteReview(id) {
    this._set(this.KEYS.REVIEWS, this.getReviews().filter(r => r.id !== id));
  },
  updateReviewStatus(id, status) {
    const reviews = this.getReviews();
    const r = reviews.find(r => r.id === id);
    if (r) { r.status = status; this._set(this.KEYS.REVIEWS, reviews); }
    return r;
  },

  // --- COUPONS ---
  getCoupons() {
    const c = this._get(this.KEYS.COUPONS);
    return (c && c.length > 0) ? c : COUPONS_DATA;
  },
  getCouponByCode(code) {
    return this.getCoupons().find(c => c.code === code.toUpperCase() && c.status === 'Active') || null;
  },
  saveCoupon(coupon) {
    const coupons = this.getCoupons();
    const idx     = coupons.findIndex(c => c.id === coupon.id);
    if (idx >= 0) coupons[idx] = coupon; else coupons.unshift(coupon);
    this._set(this.KEYS.COUPONS, coupons);
  },
  deleteCoupon(id) {
    this._set(this.KEYS.COUPONS, this.getCoupons().filter(c => c.id !== id));
  },
  validateCoupon(code, amount) {
    const coupon = this.getCouponByCode(code);
    if (!coupon) return { valid: false, message: 'Invalid coupon code.' };
    if (new Date(coupon.expiryDate) < new Date()) return { valid: false, message: 'This coupon has expired.' };
    if (amount < coupon.minAmount) return { valid: false, message: `Minimum booking amount of ₹${coupon.minAmount.toLocaleString()} required.` };
    if (coupon.used >= coupon.usageLimit) return { valid: false, message: 'Coupon usage limit reached.' };
    const discount = coupon.type === 'percentage' ? Math.floor(amount * coupon.value / 100) : coupon.value;
    return { valid: true, discount, coupon, message: `${coupon.type === 'percentage' ? coupon.value + '% off' : '₹' + coupon.value + ' off'} applied!` };
  },

  // --- LOCATIONS ---
  getLocations() {
    const l = this._get(this.KEYS.LOCATIONS);
    return (l && l.length > 0) ? l : LOCATIONS_DATA;
  },
  getLocationById(id) {
    return this.getLocations().find(l => l.id === id) || null;
  },
  saveLocation(loc) {
    const locations = this.getLocations();
    const idx       = locations.findIndex(l => l.id === loc.id);
    if (idx >= 0) locations[idx] = loc; else locations.unshift(loc);
    this._set(this.KEYS.LOCATIONS, locations);
  },
  deleteLocation(id) {
    this._set(this.KEYS.LOCATIONS, this.getLocations().filter(l => l.id !== id));
  },

  // --- CATEGORIES ---
  getCategories() {
    const cat = this._get(this.KEYS.CATEGORIES);
    return (cat && cat.length > 0) ? cat : CATEGORIES_DATA;
  },
  saveCategory(cat) {
    const cats = this.getCategories();
    const idx  = cats.findIndex(c => c.id === cat.id);
    if (idx >= 0) cats[idx] = cat; else cats.unshift(cat);
    this._set(this.KEYS.CATEGORIES, cats);
  },
  deleteCategory(id) {
    this._set(this.KEYS.CATEGORIES, this.getCategories().filter(c => c.id !== id));
  },

  // --- BRANDS ---
  getBrands() {
    const b = this._get(this.KEYS.BRANDS);
    return (b && b.length > 0) ? b : BRANDS_DATA;
  },
  saveBrand(brand) {
    const brands = this.getBrands();
    const idx    = brands.findIndex(b => b.id === brand.id);
    if (idx >= 0) brands[idx] = brand; else brands.unshift(brand);
    this._set(this.KEYS.BRANDS, brands);
  },
  deleteBrand(id) {
    this._set(this.KEYS.BRANDS, this.getBrands().filter(b => b.id !== id));
  },

  // --- CONTACT MESSAGES ---
  getContactMessages() {
    const m = this._get(this.KEYS.MESSAGES);
    return (m && m.length > 0) ? m : MESSAGES_DEFAULT;
  },
  saveContactMessage(msg) {
    const msgs = this.getContactMessages();
    const idx  = msgs.findIndex(m => m.id === msg.id);
    if (idx >= 0) msgs[idx] = msg; else msgs.unshift(msg);
    this._set(this.KEYS.MESSAGES, msgs);
  },
  deleteContactMessage(id) {
    this._set(this.KEYS.MESSAGES, this.getContactMessages().filter(m => m.id !== id));
  },

  // --- WISHLIST ---
  getWishlist() {
    return this._get(this.KEYS.WISHLIST);
  },
  isWishlisted(carId) {
    return this.getWishlist().includes(carId);
  },
  toggleWishlist(carId) {
    let wl = this.getWishlist();
    const inWL = wl.includes(carId);
    if (inWL) wl = wl.filter(id => id !== carId); else wl.push(carId);
    this._set(this.KEYS.WISHLIST, wl);
    return !inWL;
  },
  getWishlistedCars() {
    const wl   = this.getWishlist();
    const cars = this.getCars();
    return cars.filter(c => wl.includes(c.id));
  },

  // --- CUSTOMER AUTH ---
  getAuthUser()     { return this._getObj(this.KEYS.AUTH_USER, null); },
  setAuthUser(user) { localStorage.setItem(this.KEYS.AUTH_USER, JSON.stringify(user)); },
  logout()          { localStorage.removeItem(this.KEYS.AUTH_USER); },
  isLoggedIn()      { return !!this.getAuthUser(); },

  // --- ADMIN AUTH ---
  getAdminAuth()     { return this._getObj(this.KEYS.ADMIN_AUTH, DEFAULT_ADMIN_PROFILE); },
  setAdminAuth(user) { localStorage.setItem(this.KEYS.ADMIN_AUTH, JSON.stringify(user)); },
  logoutAdmin()      { localStorage.removeItem(this.KEYS.ADMIN_AUTH); },
  isAdminLoggedIn()  { return !!localStorage.getItem(this.KEYS.ADMIN_AUTH); },

  // --- THEME ---
  getTheme()      { return localStorage.getItem(this.KEYS.THEME) || 'light'; },
  setTheme(theme) { localStorage.setItem(this.KEYS.THEME, theme); document.documentElement.setAttribute('data-theme', theme); },
  toggleTheme()   { const t = this.getTheme() === 'light' ? 'dark' : 'light'; this.setTheme(t); return t; },

  // --- SETTINGS ---
  getSettings()   { return this._getObj(this.KEYS.SETTINGS, DEFAULT_SETTINGS); },
  saveSettings(s) { this._set(this.KEYS.SETTINGS, s); },

  // --- NOTIFICATIONS ---
  getNotifications() { return this._get(this.KEYS.NOTIFS); },
  markAllRead() {
    const notifs = this.getNotifications().map(n => ({ ...n, read: true }));
    this._set(this.KEYS.NOTIFS, notifs);
  },
  getUnreadCount() { return this.getNotifications().filter(n => !n.read).length; },

  // --- LAST SEARCH ---
  saveSearch(params) { localStorage.setItem(this.KEYS.SEARCH, JSON.stringify(params)); },
  getLastSearch()   { return this._getObj(this.KEYS.SEARCH, {}); },

  // --- STATS (Admin) ---
  getDashboardStats() {
    const cars      = this.getCars();
    const customers = this.getCustomers();
    const bStats    = this.getBookingStats();
    return {
      totalCars:      cars.length,
      availableCars:  cars.filter(c => c.available).length,
      totalCustomers: customers.length,
      ...bStats
    };
  }
};

const DEFAULT_ADMIN_PROFILE = {
  name: 'Super Admin',
  email: 'admin@driveease.in',
  role: 'System Administrator',
  phone: '+91 98765 00000',
  avatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=150&q=80',
  lastLogin: '2025-07-25 10:30 AM'
};

const MESSAGES_DEFAULT = [
  { id: 'M001', name: 'Vikram Sethi', email: 'vikram.sethi@gmail.com', phone: '+91 98111 22334', subject: 'Corporate Fleet Inquiry', message: 'We are looking to rent 5 SUVs for a 2-week corporate executive retreat in Goa. Do you offer corporate billing and volume discounts?', date: '2025-07-24', status: 'Unread' },
  { id: 'M002', name: 'Nisha Verma',  email: 'nisha.v@yahoo.com',       phone: '+91 97222 33445', subject: 'Airport Drop Clarification', message: 'Can I pick up the car at Mumbai Terminal 2 and drop it at Pune station? Is there a one-way fee charged?', date: '2025-07-22', status: 'Replied' },
  { id: 'M003', name: 'Amitabh Sen',  email: 'amitabh.sen@hotmail.com', phone: '+91 96333 44556', subject: 'Electric Car Range',       message: 'Interested in renting Tata Tiago EV for Bangalore to Mysore trip. Are charging cables provided with the vehicle?', date: '2025-07-20', status: 'Read' }
];

const NOTIFICATIONS_DEFAULT = [
  { id: 'N001', title: 'Welcome to DriveEase!', message: 'Start exploring our wide range of cars.', time: '2 hours ago', read: false, icon: 'fa-car', type: 'info' },
  { id: 'N002', title: 'Booking Confirmed',      message: 'Your booking BK001 has been confirmed.', time: '1 day ago', read: false, icon: 'fa-check-circle', type: 'success' },
  { id: 'N003', title: 'Special Offer',          message: 'Use code WEEKEND20 to get 20% off this weekend!', time: '2 days ago', read: true, icon: 'fa-tag', type: 'warning' }
];

const DEFAULT_SETTINGS = {
  company: { name: 'DriveEase', tagline: 'Your Journey, Our Commitment', email: 'info@driveease.in', phone: '+91 1800-123-4567', address: 'DriveEase HQ, 12th Floor, BKC, Mumbai - 400051' },
  currency: { code: 'INR', symbol: '₹', position: 'before' },
  booking: { minAdvance: 2, maxDuration: 90, depositPercent: 20, cancellationHours: 48 },
  notifications: { email: true, sms: true, bookingConfirm: true, bookingReminder: true, promotions: true }
};

document.addEventListener('DOMContentLoaded', () => {
  const theme = Storage.getTheme();
  document.documentElement.setAttribute('data-theme', theme);
});
