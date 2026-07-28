/**
 * DriveEase — Mock Data
 * data.js
 * All realistic mock data for the car rental platform
 */

// ============================================================
// CAR IMAGES (using Unsplash for realistic car photos)
// ============================================================
const CAR_IMAGES = {
  'toyota-camry':      'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=600&q=80',
  'honda-city':        'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=600&q=80',
  'hyundai-creta':     'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=600&q=80',
  'bmw-3series':       'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=600&q=80',
  'mercedes-cclass':   'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=600&q=80',
  'audi-a4':           'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=600&q=80',
  'kia-seltos':        'https://images.unsplash.com/photo-1568844293986-8d0400bd4745?w=600&q=80',
  'tata-nexon':        'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=600&q=80',
  'mahindra-xuv700':   'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=600&q=80',
  'mg-hector':         'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=600&q=80',
  'toyota-fortuner':   'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=600&q=80',
  'honda-amaze':       'https://images.unsplash.com/photo-1597007066704-67bf2068d5b2?w=600&q=80',
  'hyundai-i20':       'https://images.unsplash.com/photo-1542362567-b07e54358753?w=600&q=80',
  'bmw-5series':       'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?w=600&q=80',
  'audi-q7':           'https://images.unsplash.com/photo-1606016159991-dfe4f2746ad5?w=600&q=80',
  'kia-carnival':      'https://images.unsplash.com/photo-1527515637462-cff94edd56f3?w=600&q=80',
  'tata-tiago-ev':     'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?w=600&q=80',
  'mg-zs-ev':          'https://images.unsplash.com/photo-1571987502951-c7f4a2fa83ef?w=600&q=80',
  'bmw-m4':            'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=600&q=80',
  'toyota-innova':     'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=600&q=80'
};

const EXTRA_IMAGES = {
  hero:    'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=1400&q=80',
  about:   'https://images.unsplash.com/photo-1552960562-daf630e9278b?w=900&q=80',
  office:  'https://images.unsplash.com/photo-1497366216548-37526070297c?w=900&q=80',
  team1:   'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=300&q=80',
  team2:   'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=300&q=80',
  team3:   'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300&q=80',
  team4:   'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=300&q=80',
  avatar1: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=100&q=80',
  avatar2: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&q=80',
  avatar3: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&q=80',
  avatar4: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&q=80',
  avatar5: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100&q=80',
};

// ============================================================
// LOCATIONS DATA
// ============================================================
const LOCATIONS_DATA = [
  { id: 'L001', name: 'Mumbai Airport',       city: 'Mumbai',    address: 'Chhatrapati Shivaji Maharaj International Airport, Santacruz East, Mumbai - 400099', phone: '+91 22 6685 1000', hours: '24/7', type: 'airport', state: 'Maharashtra' },
  { id: 'L002', name: 'Delhi IGI Airport',    city: 'Delhi',     address: 'Indira Gandhi International Airport, New Delhi - 110037', phone: '+91 11 2567 5000', hours: '24/7', type: 'airport', state: 'Delhi' },
  { id: 'L003', name: 'Bangalore Koramangala',city: 'Bangalore', address: '12th Cross, Koramangala 4th Block, Bengaluru - 560034', phone: '+91 80 4123 7800', hours: '8 AM – 10 PM', type: 'city', state: 'Karnataka' },
  { id: 'L004', name: 'Hyderabad Hitech City',city: 'Hyderabad', address: 'HUDA Techno Enclave, Hitech City, Hyderabad - 500081', phone: '+91 40 6770 1234', hours: '8 AM – 9 PM', type: 'city', state: 'Telangana' },
  { id: 'L005', name: 'Pune Station',         city: 'Pune',      address: 'Near Pune Railway Station, Shivajinagar, Pune - 411005', phone: '+91 20 2553 8800', hours: '7 AM – 10 PM', type: 'railway', state: 'Maharashtra' },
  { id: 'L006', name: 'Chennai Anna Salai',   city: 'Chennai',   address: '45 Anna Salai, Thousand Lights, Chennai - 600006', phone: '+91 44 2811 2200', hours: '8 AM – 10 PM', type: 'city', state: 'Tamil Nadu' },
  { id: 'L007', name: 'Kolkata Park Street',  city: 'Kolkata',   address: '18 Park Street, Kolkata - 700016', phone: '+91 33 2229 4400', hours: '8 AM – 9 PM', type: 'city', state: 'West Bengal' },
  { id: 'L008', name: 'Goa Airport Branch',   city: 'Goa',       address: 'Dabolim International Airport, South Goa - 403801', phone: '+91 832 254 0800', hours: '6 AM – 11 PM', type: 'airport', state: 'Goa' },
  { id: 'L009', name: 'Jaipur City Center',   city: 'Jaipur',    address: 'C-Scheme, Ashok Marg, Jaipur - 302001', phone: '+91 141 222 7700', hours: '8 AM – 9 PM', type: 'city', state: 'Rajasthan' },
  { id: 'L010', name: 'Ahmedabad SG Highway', city: 'Ahmedabad', address: 'SG Highway, Satellite, Ahmedabad - 380015', phone: '+91 79 2646 5500', hours: '8 AM – 10 PM', type: 'city', state: 'Gujarat' }
];

// ============================================================
// CARS DATA — 20 Vehicles
// ============================================================
const CARS_DATA = [
  {
    id: 'C001', brand: 'Toyota', model: 'Camry', year: 2023,
    category: 'Sedan', price: 2500, weeklyPrice: 15000, monthlyPrice: 55000,
    transmission: 'Automatic', fuel: 'Petrol', seats: 5, doors: 4,
    mileage: '15 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 10000, taxes: 450, location: 'Mumbai', locationId: 'L001',
    available: true, rating: 4.8, reviews: 124, status: 'Active',
    image: CAR_IMAGES['toyota-camry'],
    images: [CAR_IMAGES['toyota-camry'], CAR_IMAGES['honda-city'], CAR_IMAGES['hyundai-creta'], CAR_IMAGES['bmw-3series']],
    features: ['Cruise Control','Sunroof','Leather Seats','Reverse Camera','Apple CarPlay','Android Auto'],
    description: "The Toyota Camry is a reliable and spacious mid-size sedan, perfect for both business and leisure trips. Known for its smooth ride and fuel efficiency.",
    color: 'Pearl White'
  },
  {
    id: 'C002', brand: 'Honda', model: 'City', year: 2023,
    category: 'Sedan', price: 2200, weeklyPrice: 13000, monthlyPrice: 48000,
    transmission: 'Automatic', fuel: 'Petrol', seats: 5, doors: 4,
    mileage: '18 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 8000, taxes: 396, location: 'Delhi', locationId: 'L002',
    available: true, rating: 4.7, reviews: 98, status: 'Active',
    image: CAR_IMAGES['honda-city'],
    images: [CAR_IMAGES['honda-city'], CAR_IMAGES['toyota-camry'], CAR_IMAGES['audi-a4'], CAR_IMAGES['kia-seltos']],
    features: ['Lane Watch','Honda Sensing','Heated Mirrors','Wireless Charging','Sunroof'],
    description: "The Honda City combines style, comfort and advanced safety features. Ideal for city commutes and outstation trips.",
    color: 'Radiant Red'
  },
  {
    id: 'C003', brand: 'Hyundai', model: 'Creta', year: 2024,
    category: 'SUV', price: 3200, weeklyPrice: 19500, monthlyPrice: 72000,
    transmission: 'Automatic', fuel: 'Petrol', seats: 5, doors: 4,
    mileage: '16 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 12000, taxes: 576, location: 'Bangalore', locationId: 'L003',
    available: true, rating: 4.9, reviews: 203, status: 'Active',
    image: CAR_IMAGES['hyundai-creta'],
    images: [CAR_IMAGES['hyundai-creta'], CAR_IMAGES['kia-seltos'], CAR_IMAGES['tata-nexon'], CAR_IMAGES['mg-hector']],
    features: ['Panoramic Sunroof','BOSE Sound','Ventilated Seats','ADAS','360° Camera'],
    description: "The Hyundai Creta 2024 brings premium features to the SUV segment. Perfect for families and adventure seekers alike.",
    color: 'Atlas White'
  },
  {
    id: 'C004', brand: 'BMW', model: '3 Series', year: 2023,
    category: 'Luxury', price: 8500, weeklyPrice: 52000, monthlyPrice: 190000,
    transmission: 'Automatic', fuel: 'Petrol', seats: 5, doors: 4,
    mileage: '13 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 30000, taxes: 1530, location: 'Mumbai', locationId: 'L001',
    available: true, rating: 4.9, reviews: 67, status: 'Active',
    image: CAR_IMAGES['bmw-3series'],
    images: [CAR_IMAGES['bmw-3series'], CAR_IMAGES['mercedes-cclass'], CAR_IMAGES['audi-a4'], CAR_IMAGES['bmw-5series']],
    features: ['iDrive','M Sport Package','Harman Kardon','Adaptive Cruise','Head-Up Display','Wireless Charging'],
    description: "Experience the ultimate driving machine — the BMW 3 Series. Sporty dynamics, luxury interiors and cutting-edge technology in one iconic package.",
    color: 'Mineral White'
  },
  {
    id: 'C005', brand: 'Mercedes', model: 'C-Class', year: 2023,
    category: 'Luxury', price: 9000, weeklyPrice: 55000, monthlyPrice: 200000,
    transmission: 'Automatic', fuel: 'Petrol', seats: 5, doors: 4,
    mileage: '12 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 35000, taxes: 1620, location: 'Delhi', locationId: 'L002',
    available: true, rating: 4.9, reviews: 54, status: 'Active',
    image: CAR_IMAGES['mercedes-cclass'],
    images: [CAR_IMAGES['mercedes-cclass'], CAR_IMAGES['bmw-3series'], CAR_IMAGES['audi-a4'], CAR_IMAGES['bmw-5series']],
    features: ['MBUX Infotainment','Burmester Sound','AMG Line','Ambient Lighting','Widescreen Cockpit'],
    description: "The Mercedes-Benz C-Class defines what a luxury sedan should be. Impeccable engineering, breathtaking design and supreme comfort.",
    color: 'Obsidian Black'
  },
  {
    id: 'C006', brand: 'Audi', model: 'A4', year: 2023,
    category: 'Luxury', price: 8000, weeklyPrice: 49000, monthlyPrice: 180000,
    transmission: 'Automatic', fuel: 'Petrol', seats: 5, doors: 4,
    mileage: '13 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 28000, taxes: 1440, location: 'Bangalore', locationId: 'L003',
    available: false, rating: 4.8, reviews: 45, status: 'Active',
    image: CAR_IMAGES['audi-a4'],
    images: [CAR_IMAGES['audi-a4'], CAR_IMAGES['bmw-3series'], CAR_IMAGES['mercedes-cclass'], CAR_IMAGES['audi-q7']],
    features: ['Virtual Cockpit','Matrix LED','Bang & Olufsen','MMI Navigation','Sports Suspension'],
    description: "The Audi A4 is the benchmark for progressive luxury sedans. Sharp design meets quattro all-wheel drive performance.",
    color: 'Glacier White'
  },
  {
    id: 'C007', brand: 'Kia', model: 'Seltos', year: 2024,
    category: 'SUV', price: 3000, weeklyPrice: 18000, monthlyPrice: 66000,
    transmission: 'Automatic', fuel: 'Petrol', seats: 5, doors: 4,
    mileage: '16 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 11000, taxes: 540, location: 'Hyderabad', locationId: 'L004',
    available: true, rating: 4.7, reviews: 156, status: 'Active',
    image: CAR_IMAGES['kia-seltos'],
    images: [CAR_IMAGES['kia-seltos'], CAR_IMAGES['hyundai-creta'], CAR_IMAGES['tata-nexon'], CAR_IMAGES['mg-hector']],
    features: ['Bose Premium Sound','Dual Pane Sunroof','ADAS','UVO Connected','Ventilated Seats'],
    description: "The Kia Seltos is a feature-packed SUV that punches above its weight with premium tech, bold styling and connected car features.",
    color: 'Intense Red'
  },
  {
    id: 'C008', brand: 'Tata', model: 'Nexon', year: 2024,
    category: 'SUV', price: 2800, weeklyPrice: 16800, monthlyPrice: 62000,
    transmission: 'Manual', fuel: 'Diesel', seats: 5, doors: 4,
    mileage: '22 km/l', ac: true, gps: false, bluetooth: true,
    deposit: 10000, taxes: 504, location: 'Pune', locationId: 'L005',
    available: true, rating: 4.6, reviews: 89, status: 'Active',
    image: CAR_IMAGES['tata-nexon'],
    images: [CAR_IMAGES['tata-nexon'], CAR_IMAGES['hyundai-creta'], CAR_IMAGES['kia-seltos'], CAR_IMAGES['mahindra-xuv700']],
    features: ['5-Star Safety Rated','iRA Connected','JBL Sound System','Electric Sunroof','Auto Headlamps'],
    description: "The Tata Nexon is India's safest compact SUV with a 5-star Global NCAP rating. Fuel-efficient diesel engine and feature-rich interiors make it a top choice.",
    color: 'Calgary White'
  },
  {
    id: 'C009', brand: 'Mahindra', model: 'XUV700', year: 2023,
    category: 'SUV', price: 3500, weeklyPrice: 21000, monthlyPrice: 78000,
    transmission: 'Automatic', fuel: 'Diesel', seats: 7, doors: 4,
    mileage: '20 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 14000, taxes: 630, location: 'Chennai', locationId: 'L006',
    available: true, rating: 4.7, reviews: 112, status: 'Active',
    image: CAR_IMAGES['mahindra-xuv700'],
    images: [CAR_IMAGES['mahindra-xuv700'], CAR_IMAGES['tata-nexon'], CAR_IMAGES['toyota-fortuner'], CAR_IMAGES['kia-carnival']],
    features: ['ADAS Level 2','360° View Camera','Panoramic Sunroof','Sony Sound','Wireless Charging','7 Seats'],
    description: "The Mahindra XUV700 is a game-changer in the 3-row SUV segment with ADAS Level 2 technology, premium interiors and powerful diesel performance.",
    color: 'Dazzling Silver'
  },
  {
    id: 'C010', brand: 'MG', model: 'Hector', year: 2024,
    category: 'SUV', price: 3200, weeklyPrice: 19200, monthlyPrice: 70000,
    transmission: 'Manual', fuel: 'Petrol', seats: 5, doors: 4,
    mileage: '15 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 12000, taxes: 576, location: 'Kolkata', locationId: 'L007',
    available: true, rating: 4.6, reviews: 78, status: 'Active',
    image: CAR_IMAGES['mg-hector'],
    images: [CAR_IMAGES['mg-hector'], CAR_IMAGES['kia-seltos'], CAR_IMAGES['hyundai-creta'], CAR_IMAGES['mahindra-xuv700']],
    features: ['14" Touchscreen','i-Smart Connected','Wi-Fi Hotspot','TPMS','Panoramic Sunroof'],
    description: "The MG Hector offers internet connectivity, a massive touchscreen and a generous feature list at a compelling price point.",
    color: 'Starry Black'
  },
  {
    id: 'C011', brand: 'Toyota', model: 'Fortuner', year: 2023,
    category: 'SUV', price: 5500, weeklyPrice: 33000, monthlyPrice: 120000,
    transmission: 'Automatic', fuel: 'Diesel', seats: 7, doors: 4,
    mileage: '14 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 20000, taxes: 990, location: 'Mumbai', locationId: 'L001',
    available: true, rating: 4.8, reviews: 91, status: 'Active',
    image: CAR_IMAGES['toyota-fortuner'],
    images: [CAR_IMAGES['toyota-fortuner'], CAR_IMAGES['mahindra-xuv700'], CAR_IMAGES['audi-q7'], CAR_IMAGES['kia-carnival']],
    features: ['4WD','Terrain Select','Toyota Safety Sense','Premium JBL Sound','Power Tailgate','7 Seats'],
    description: "The Toyota Fortuner is India's most iconic 7-seater SUV. Rugged, powerful and refined — it handles every terrain with authority.",
    color: 'Super White'
  },
  {
    id: 'C012', brand: 'Honda', model: 'Amaze', year: 2023,
    category: 'Economy', price: 1800, weeklyPrice: 10500, monthlyPrice: 38000,
    transmission: 'Manual', fuel: 'Petrol', seats: 5, doors: 4,
    mileage: '20 km/l', ac: true, gps: false, bluetooth: true,
    deposit: 7000, taxes: 324, location: 'Delhi', locationId: 'L002',
    available: true, rating: 4.4, reviews: 67, status: 'Active',
    image: CAR_IMAGES['honda-amaze'],
    images: [CAR_IMAGES['honda-amaze'], CAR_IMAGES['hyundai-i20'], CAR_IMAGES['honda-city'], CAR_IMAGES['toyota-camry']],
    features: ['Rear Parking Camera','Honda Connect','One-Touch Sunroof','Automatic Dimming IRVM'],
    description: "The Honda Amaze is an affordable and fuel-efficient compact sedan ideal for everyday city driving and budget-friendly outstation trips.",
    color: 'Lunar Silver'
  },
  {
    id: 'C013', brand: 'Hyundai', model: 'i20', year: 2023,
    category: 'Economy', price: 1600, weeklyPrice: 9500, monthlyPrice: 34000,
    transmission: 'Manual', fuel: 'Petrol', seats: 5, doors: 4,
    mileage: '20 km/l', ac: true, gps: false, bluetooth: true,
    deposit: 6000, taxes: 288, location: 'Bangalore', locationId: 'L003',
    available: true, rating: 4.5, reviews: 112, status: 'Active',
    image: CAR_IMAGES['hyundai-i20'],
    images: [CAR_IMAGES['hyundai-i20'], CAR_IMAGES['honda-amaze'], CAR_IMAGES['tata-nexon'], CAR_IMAGES['honda-city']],
    features: ['8" Touchscreen','Bose Sound','Smart Key','Wireless Charging','Auto Climate Control'],
    description: "The Hyundai i20 is a premium hatchback with a stylish design, packed with features that make every drive enjoyable.",
    color: 'Typhoon Silver'
  },
  {
    id: 'C014', brand: 'BMW', model: '5 Series', year: 2023,
    category: 'Luxury', price: 12000, weeklyPrice: 73000, monthlyPrice: 270000,
    transmission: 'Automatic', fuel: 'Petrol', seats: 5, doors: 4,
    mileage: '12 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 45000, taxes: 2160, location: 'Mumbai', locationId: 'L001',
    available: true, rating: 5.0, reviews: 34, status: 'Active',
    image: CAR_IMAGES['bmw-5series'],
    images: [CAR_IMAGES['bmw-5series'], CAR_IMAGES['mercedes-cclass'], CAR_IMAGES['bmw-3series'], CAR_IMAGES['audi-q7']],
    features: ['Executive Lounge','Massage Seats','Bowers & Wilkins Sound','Gesture Control','Laser Headlights'],
    description: "The BMW 5 Series is the epitome of executive luxury. Commanding presence, effortless power and a cabin that pampers every passenger.",
    color: 'Black Sapphire'
  },
  {
    id: 'C015', brand: 'Audi', model: 'Q7', year: 2023,
    category: 'SUV', price: 11000, weeklyPrice: 67000, monthlyPrice: 245000,
    transmission: 'Automatic', fuel: 'Petrol', seats: 7, doors: 4,
    mileage: '11 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 42000, taxes: 1980, location: 'Hyderabad', locationId: 'L004',
    available: false, rating: 4.9, reviews: 28, status: 'Active',
    image: CAR_IMAGES['audi-q7'],
    images: [CAR_IMAGES['audi-q7'], CAR_IMAGES['bmw-5series'], CAR_IMAGES['mercedes-cclass'], CAR_IMAGES['toyota-fortuner']],
    features: ['quattro AWD','Air Suspension','Bang & Olufsen','Adaptive Matrix LED','7 Seats','Panoramic Sunroof'],
    description: "The Audi Q7 is a flagship luxury SUV that combines genuine off-road ability with a sumptuous interior and cutting-edge quattro AWD technology.",
    color: 'Florett Silver'
  },
  {
    id: 'C016', brand: 'Kia', model: 'Carnival', year: 2023,
    category: 'Van', price: 6000, weeklyPrice: 36000, monthlyPrice: 130000,
    transmission: 'Automatic', fuel: 'Diesel', seats: 8, doors: 4,
    mileage: '14 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 22000, taxes: 1080, location: 'Pune', locationId: 'L005',
    available: true, rating: 4.8, reviews: 56, status: 'Active',
    image: CAR_IMAGES['kia-carnival'],
    images: [CAR_IMAGES['kia-carnival'], CAR_IMAGES['toyota-innova'], CAR_IMAGES['mahindra-xuv700'], CAR_IMAGES['toyota-fortuner']],
    features: ['VIP Lounge Seats','Bose Premium Sound','Sliding Power Doors','8 Seats','Rear Entertainment','Wireless Charging'],
    description: "The Kia Carnival redefines the luxury MPV segment. Whether it's a family trip or corporate travel, it delivers unmatched comfort for 8 passengers.",
    color: 'Aurora Black'
  },
  {
    id: 'C017', brand: 'Tata', model: 'Tiago EV', year: 2024,
    category: 'Electric', price: 2000, weeklyPrice: 12000, monthlyPrice: 44000,
    transmission: 'Automatic', fuel: 'Electric', seats: 5, doors: 4,
    mileage: '315 km/charge', ac: true, gps: true, bluetooth: true,
    deposit: 8000, taxes: 360, location: 'Bangalore', locationId: 'L003',
    available: true, rating: 4.6, reviews: 88, status: 'Active',
    image: CAR_IMAGES['tata-tiago-ev'],
    images: [CAR_IMAGES['tata-tiago-ev'], CAR_IMAGES['mg-zs-ev'], CAR_IMAGES['tata-nexon'], CAR_IMAGES['honda-amaze']],
    features: ['315 km Range','Fast Charging','iRA Connected','Regenerative Braking','ZConnect App'],
    description: "The Tata Tiago EV is India's most affordable electric car with an impressive 315 km range. Zero-emission driving with no compromise on features.",
    color: 'Triton Teal'
  },
  {
    id: 'C018', brand: 'MG', model: 'ZS EV', year: 2024,
    category: 'Electric', price: 2800, weeklyPrice: 17000, monthlyPrice: 62000,
    transmission: 'Automatic', fuel: 'Electric', seats: 5, doors: 4,
    mileage: '461 km/charge', ac: true, gps: true, bluetooth: true,
    deposit: 11000, taxes: 504, location: 'Delhi', locationId: 'L002',
    available: true, rating: 4.7, reviews: 73, status: 'Active',
    image: CAR_IMAGES['mg-zs-ev'],
    images: [CAR_IMAGES['mg-zs-ev'], CAR_IMAGES['tata-tiago-ev'], CAR_IMAGES['hyundai-creta'], CAR_IMAGES['kia-seltos']],
    features: ['461 km Range','DC Fast Charge','i-Smart AI','10.1" HD Display','Vehicle-to-Load','ADAS'],
    description: "The MG ZS EV offers an incredible 461 km range with DC fast charging support. A premium electric SUV that makes EV adoption effortless.",
    color: 'Camden Grey'
  },
  {
    id: 'C019', brand: 'BMW', model: 'M4', year: 2023,
    category: 'Sports', price: 15000, weeklyPrice: 92000, monthlyPrice: 340000,
    transmission: 'Automatic', fuel: 'Petrol', seats: 4, doors: 2,
    mileage: '9 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 55000, taxes: 2700, location: 'Mumbai', locationId: 'L001',
    available: true, rating: 5.0, reviews: 21, status: 'Active',
    image: CAR_IMAGES['bmw-m4'],
    images: [CAR_IMAGES['bmw-m4'], CAR_IMAGES['bmw-3series'], CAR_IMAGES['bmw-5series'], CAR_IMAGES['audi-a4']],
    features: ['S58 Twin-Turbo','503 bhp','M xDrive','Carbon Fibre Roof','M Sport Exhaust','Track Mode'],
    description: "The BMW M4 is pure performance art. With 503 bhp from its twin-turbocharged S58 engine, every drive is an unforgettable experience.",
    color: 'Isle of Man Green'
  },
  {
    id: 'C020', brand: 'Toyota', model: 'Innova HyCross', year: 2024,
    category: 'Van', price: 4500, weeklyPrice: 27000, monthlyPrice: 98000,
    transmission: 'Automatic', fuel: 'Hybrid', seats: 7, doors: 4,
    mileage: '21 km/l', ac: true, gps: true, bluetooth: true,
    deposit: 18000, taxes: 810, location: 'Chennai', locationId: 'L006',
    available: true, rating: 4.8, reviews: 94, status: 'Active',
    image: CAR_IMAGES['toyota-innova'],
    images: [CAR_IMAGES['toyota-innova'], CAR_IMAGES['kia-carnival'], CAR_IMAGES['mahindra-xuv700'], CAR_IMAGES['toyota-fortuner']],
    features: ['Self-Charging Hybrid','7 Seats','Captain Chairs','Wireless Charging','JBL Sound','ADAS'],
    description: "The Toyota Innova HyCross is the world's first MPV with a strong hybrid powertrain. Luxurious, spacious and incredibly fuel efficient.",
    color: 'Platinum White Pearl'
  }
];

// ============================================================
// CATEGORIES DATA
// ============================================================
const CATEGORIES_DATA = [
  { id: 'CAT001', name: 'Economy',  icon: 'fa-car-side',    description: 'Budget-friendly cars for everyday travel',           cars: 2, status: 'Active',  image: 'https://images.unsplash.com/photo-1542362567-b07e54358753?w=300&q=80' },
  { id: 'CAT002', name: 'Sedan',    icon: 'fa-car',          description: 'Comfortable sedans for business and leisure',        cars: 2, status: 'Active',  image: 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=300&q=80' },
  { id: 'CAT003', name: 'SUV',      icon: 'fa-truck-monster', description: 'Spacious SUVs for families and adventures',          cars: 7, status: 'Active',  image: 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=300&q=80' },
  { id: 'CAT004', name: 'Luxury',   icon: 'fa-gem',          description: 'Premium luxury vehicles for a first-class experience',cars: 4, status: 'Active',  image: 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=300&q=80' },
  { id: 'CAT005', name: 'Sports',   icon: 'fa-bolt',         description: 'High-performance sports cars for thrill seekers',    cars: 1, status: 'Active',  image: 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=300&q=80' },
  { id: 'CAT006', name: 'Electric', icon: 'fa-charging-station', description: 'Zero-emission electric vehicles for eco-conscious drivers', cars: 2, status: 'Active', image: 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?w=300&q=80' },
  { id: 'CAT007', name: 'Van',      icon: 'fa-van-shuttle',  description: 'Spacious vans for groups and family travel',        cars: 2, status: 'Active',  image: 'https://images.unsplash.com/photo-1527515637462-cff94edd56f3?w=300&q=80' }
];

// ============================================================
// BRANDS DATA
// ============================================================
const BRANDS_DATA = [
  { id: 'B001', name: 'Toyota',   logo: '🚗', cars: 3, status: 'Active' },
  { id: 'B002', name: 'Honda',    logo: '🚗', cars: 2, status: 'Active' },
  { id: 'B003', name: 'Hyundai',  logo: '🚗', cars: 2, status: 'Active' },
  { id: 'B004', name: 'BMW',      logo: '🚗', cars: 3, status: 'Active' },
  { id: 'B005', name: 'Mercedes', logo: '🚗', cars: 1, status: 'Active' },
  { id: 'B006', name: 'Audi',     logo: '🚗', cars: 2, status: 'Active' },
  { id: 'B007', name: 'Kia',      logo: '🚗', cars: 2, status: 'Active' },
  { id: 'B008', name: 'Tata',     logo: '🚗', cars: 2, status: 'Active' },
  { id: 'B009', name: 'Mahindra', logo: '🚗', cars: 1, status: 'Active' },
  { id: 'B010', name: 'MG',       logo: '🚗', cars: 2, status: 'Active' }
];

// ============================================================
// CUSTOMERS DATA — 15 Customers
// ============================================================
const CUSTOMERS_DATA = [
  { id: 'CU001', name: 'Arjun Sharma',     email: 'arjun.sharma@email.com',   phone: '+91 98765 43210', city: 'Mumbai',    state: 'Maharashtra', status: 'Active',   registered: '2024-01-15', bookings: 5, avatar: EXTRA_IMAGES.avatar1, license: 'MH-0120230012345' },
  { id: 'CU002', name: 'Priya Patel',      email: 'priya.patel@email.com',    phone: '+91 87654 32109', city: 'Delhi',     state: 'Delhi',       status: 'Active',   registered: '2024-02-20', bookings: 3, avatar: EXTRA_IMAGES.avatar2, license: 'DL-0420230054321' },
  { id: 'CU003', name: 'Rahul Gupta',      email: 'rahul.gupta@email.com',    phone: '+91 76543 21098', city: 'Bangalore', state: 'Karnataka',   status: 'Active',   registered: '2024-03-10', bookings: 7, avatar: EXTRA_IMAGES.avatar3, license: 'KA-0520220034567' },
  { id: 'CU004', name: 'Sneha Reddy',      email: 'sneha.reddy@email.com',    phone: '+91 65432 10987', city: 'Hyderabad', state: 'Telangana',   status: 'Active',   registered: '2024-04-05', bookings: 2, avatar: EXTRA_IMAGES.avatar4, license: 'TS-0920230065432' },
  { id: 'CU005', name: 'Vikram Singh',     email: 'vikram.singh@email.com',   phone: '+91 54321 09876', city: 'Pune',      state: 'Maharashtra', status: 'Inactive', registered: '2024-05-18', bookings: 1, avatar: EXTRA_IMAGES.avatar5, license: 'MH-1220220087654' },
  { id: 'CU006', name: 'Ananya Krishnan',  email: 'ananya.k@email.com',       phone: '+91 43210 98765', city: 'Chennai',   state: 'Tamil Nadu',  status: 'Active',   registered: '2024-06-22', bookings: 4, avatar: EXTRA_IMAGES.avatar1, license: 'TN-0320230076543' },
  { id: 'CU007', name: 'Rohan Mehta',      email: 'rohan.mehta@email.com',    phone: '+91 32109 87654', city: 'Kolkata',   state: 'West Bengal', status: 'Active',   registered: '2024-07-11', bookings: 2, avatar: EXTRA_IMAGES.avatar2, license: 'WB-0720230023456' },
  { id: 'CU008', name: 'Kavya Nair',       email: 'kavya.nair@email.com',     phone: '+91 21098 76543', city: 'Goa',       state: 'Goa',         status: 'Active',   registered: '2024-08-30', bookings: 6, avatar: EXTRA_IMAGES.avatar3, license: 'GA-0120230098765' },
  { id: 'CU009', name: 'Aditya Joshi',     email: 'aditya.joshi@email.com',   phone: '+91 10987 65432', city: 'Jaipur',    state: 'Rajasthan',   status: 'Active',   registered: '2024-09-14', bookings: 3, avatar: EXTRA_IMAGES.avatar4, license: 'RJ-1420220056789' },
  { id: 'CU010', name: 'Pooja Agarwal',    email: 'pooja.agarwal@email.com',  phone: '+91 99876 54321', city: 'Ahmedabad', state: 'Gujarat',     status: 'Active',   registered: '2024-10-08', bookings: 1, avatar: EXTRA_IMAGES.avatar5, license: 'GJ-0820230034512' },
  { id: 'CU011', name: 'Suresh Iyer',      email: 'suresh.iyer@email.com',    phone: '+91 88765 43210', city: 'Mumbai',    state: 'Maharashtra', status: 'Active',   registered: '2024-11-25', bookings: 4, avatar: EXTRA_IMAGES.avatar1, license: 'MH-0420230054389' },
  { id: 'CU012', name: 'Meera Bose',       email: 'meera.bose@email.com',     phone: '+91 77654 32109', city: 'Bangalore', state: 'Karnataka',   status: 'Inactive', registered: '2024-12-03', bookings: 0, avatar: EXTRA_IMAGES.avatar2, license: 'KA-1120220012378' },
  { id: 'CU013', name: 'Kiran Pillai',     email: 'kiran.pillai@email.com',   phone: '+91 66543 21098', city: 'Hyderabad', state: 'Telangana',   status: 'Active',   registered: '2025-01-17', bookings: 2, avatar: EXTRA_IMAGES.avatar3, license: 'TS-0120250043210' },
  { id: 'CU014', name: 'Divya Menon',      email: 'divya.menon@email.com',    phone: '+91 55432 10987', city: 'Chennai',   state: 'Tamil Nadu',  status: 'Active',   registered: '2025-02-28', bookings: 3, avatar: EXTRA_IMAGES.avatar4, license: 'TN-0220250065439' },
  { id: 'CU015', name: 'Nikhil Verma',     email: 'nikhil.verma@email.com',   phone: '+91 44321 09876', city: 'Delhi',     state: 'Delhi',       status: 'Active',   registered: '2025-03-15', bookings: 1, avatar: EXTRA_IMAGES.avatar5, license: 'DL-0320250012456' }
];

// ============================================================
// BOOKINGS DATA — 20 Bookings
// ============================================================
const BOOKINGS_DATA = [
  { id: 'BK001', customerId: 'CU001', customerName: 'Arjun Sharma',   carId: 'C004', carName: 'BMW 3 Series',      pickup: 'L001', pickupName: 'Mumbai Airport',       dropoff: 'L001', dropoffName: 'Mumbai Airport',        pickupDate: '2025-07-28', returnDate: '2025-07-31', pickupTime: '10:00', returnTime: '10:00', days: 3, basePrice: 25500, extras: 1000, tax: 4779, discount: 0,    total: 31279, status: 'Confirmed', payment: 'Credit Card', paymentStatus: 'Paid',    createdAt: '2025-07-20' },
  { id: 'BK002', customerId: 'CU002', customerName: 'Priya Patel',    carId: 'C003', carName: 'Hyundai Creta',     pickup: 'L002', pickupName: 'Delhi IGI Airport',    dropoff: 'L002', dropoffName: 'Delhi IGI Airport',     pickupDate: '2025-07-26', returnDate: '2025-07-28', pickupTime: '09:00', returnTime: '09:00', days: 2, basePrice: 6400,  extras: 500,  tax: 1278, discount: 500, total: 7678,  status: 'Active',    payment: 'UPI',         paymentStatus: 'Paid',    createdAt: '2025-07-19' },
  { id: 'BK003', customerId: 'CU003', customerName: 'Rahul Gupta',    carId: 'C007', carName: 'Kia Seltos',        pickup: 'L003', pickupName: 'Bangalore Koramangala', dropoff: 'L003', dropoffName: 'Bangalore Koramangala', pickupDate: '2025-08-05', returnDate: '2025-08-10', pickupTime: '08:00', returnTime: '08:00', days: 5, basePrice: 15000, extras: 1500, tax: 2970, discount: 750, total: 18720, status: 'Confirmed', payment: 'Credit Card', paymentStatus: 'Paid',    createdAt: '2025-07-21' },
  { id: 'BK004', customerId: 'CU004', customerName: 'Sneha Reddy',    carId: 'C009', carName: 'Mahindra XUV700',   pickup: 'L004', pickupName: 'Hyderabad Hitech City', dropoff: 'L004', dropoffName: 'Hyderabad Hitech City', pickupDate: '2025-07-22', returnDate: '2025-07-25', pickupTime: '11:00', returnTime: '11:00', days: 3, basePrice: 10500, extras: 0,    tax: 1890, discount: 0,    total: 12390, status: 'Completed', payment: 'PayPal',      paymentStatus: 'Paid',    createdAt: '2025-07-15' },
  { id: 'BK005', customerId: 'CU005', customerName: 'Vikram Singh',   carId: 'C008', carName: 'Tata Nexon',        pickup: 'L005', pickupName: 'Pune Station',         dropoff: 'L005', dropoffName: 'Pune Station',          pickupDate: '2025-09-01', returnDate: '2025-09-07', pickupTime: '09:00', returnTime: '09:00', days: 6, basePrice: 16800, extras: 1000, tax: 3204, discount: 840, total: 20164, status: 'Pending',   payment: 'UPI',         paymentStatus: 'Pending', createdAt: '2025-07-23' },
  { id: 'BK006', customerId: 'CU006', customerName: 'Ananya Krishnan',carId: 'C020', carName: 'Toyota Innova',     pickup: 'L006', pickupName: 'Chennai Anna Salai',   dropoff: 'L006', dropoffName: 'Chennai Anna Salai',    pickupDate: '2025-07-25', returnDate: '2025-07-26', pickupTime: '07:00', returnTime: '18:00', days: 1, basePrice: 4500,  extras: 500,  tax: 900, discount: 0,    total: 5900,  status: 'Active',    payment: 'Cash',        paymentStatus: 'Pending', createdAt: '2025-07-22' },
  { id: 'BK007', customerId: 'CU007', customerName: 'Rohan Mehta',    carId: 'C010', carName: 'MG Hector',         pickup: 'L007', pickupName: 'Kolkata Park Street',  dropoff: 'L007', dropoffName: 'Kolkata Park Street',   pickupDate: '2025-06-10', returnDate: '2025-06-15', pickupTime: '10:00', returnTime: '10:00', days: 5, basePrice: 16000, extras: 0,    tax: 2880, discount: 800, total: 18080, status: 'Completed', payment: 'Credit Card', paymentStatus: 'Paid',    createdAt: '2025-06-05' },
  { id: 'BK008', customerId: 'CU008', customerName: 'Kavya Nair',     carId: 'C019', carName: 'BMW M4',            pickup: 'L001', pickupName: 'Mumbai Airport',       dropoff: 'L001', dropoffName: 'Mumbai Airport',        pickupDate: '2025-08-15', returnDate: '2025-08-17', pickupTime: '12:00', returnTime: '12:00', days: 2, basePrice: 30000, extras: 2000, tax: 5760, discount: 0,    total: 37760, status: 'Confirmed', payment: 'Credit Card', paymentStatus: 'Paid',    createdAt: '2025-07-20' },
  { id: 'BK009', customerId: 'CU009', customerName: 'Aditya Joshi',   carId: 'C012', carName: 'Honda Amaze',       pickup: 'L002', pickupName: 'Delhi IGI Airport',    dropoff: 'L009', dropoffName: 'Jaipur City Center',    pickupDate: '2025-07-18', returnDate: '2025-07-20', pickupTime: '06:00', returnTime: '18:00', days: 2, basePrice: 3600,  extras: 0,    tax: 648, discount: 0,    total: 4248,  status: 'Completed', payment: 'UPI',         paymentStatus: 'Paid',    createdAt: '2025-07-12' },
  { id: 'BK010', customerId: 'CU010', customerName: 'Pooja Agarwal',  carId: 'C013', carName: 'Hyundai i20',       pickup: 'L010', pickupName: 'Ahmedabad SG Highway', dropoff: 'L010', dropoffName: 'Ahmedabad SG Highway',  pickupDate: '2025-10-01', returnDate: '2025-10-05', pickupTime: '09:00', returnTime: '09:00', days: 4, basePrice: 6400,  extras: 500,  tax: 1242, discount: 640, total: 7502,  status: 'Pending',   payment: 'UPI',         paymentStatus: 'Pending', createdAt: '2025-07-24' },
  { id: 'BK011', customerId: 'CU011', customerName: 'Suresh Iyer',    carId: 'C005', carName: 'Mercedes C-Class',  pickup: 'L002', pickupName: 'Delhi IGI Airport',    dropoff: 'L002', dropoffName: 'Delhi IGI Airport',     pickupDate: '2025-07-10', returnDate: '2025-07-12', pickupTime: '14:00', returnTime: '14:00', days: 2, basePrice: 18000, extras: 1500, tax: 3510, discount: 0,    total: 23010, status: 'Completed', payment: 'Credit Card', paymentStatus: 'Paid',    createdAt: '2025-07-05' },
  { id: 'BK012', customerId: 'CU012', customerName: 'Meera Bose',     carId: 'C017', carName: 'Tata Tiago EV',    pickup: 'L003', pickupName: 'Bangalore Koramangala', dropoff: 'L003', dropoffName: 'Bangalore Koramangala', pickupDate: '2025-05-20', returnDate: '2025-05-25', pickupTime: '08:00', returnTime: '08:00', days: 5, basePrice: 10000, extras: 0,    tax: 1800, discount: 500, total: 11300, status: 'Cancelled', payment: 'UPI',         paymentStatus: 'Refunded',createdAt: '2025-05-15' },
  { id: 'BK013', customerId: 'CU013', customerName: 'Kiran Pillai',   carId: 'C018', carName: 'MG ZS EV',          pickup: 'L002', pickupName: 'Delhi IGI Airport',    dropoff: 'L002', dropoffName: 'Delhi IGI Airport',     pickupDate: '2025-08-20', returnDate: '2025-08-25', pickupTime: '10:00', returnTime: '10:00', days: 5, basePrice: 14000, extras: 1000, tax: 2700, discount: 700, total: 17000, status: 'Confirmed', payment: 'Credit Card', paymentStatus: 'Paid',    createdAt: '2025-07-22' },
  { id: 'BK014', customerId: 'CU014', customerName: 'Divya Menon',    carId: 'C016', carName: 'Kia Carnival',      pickup: 'L006', pickupName: 'Chennai Anna Salai',   dropoff: 'L006', dropoffName: 'Chennai Anna Salai',    pickupDate: '2025-09-15', returnDate: '2025-09-20', pickupTime: '07:00', returnTime: '07:00', days: 5, basePrice: 30000, extras: 2000, tax: 5760, discount: 1500, total: 36260, status: 'Pending',   payment: 'PayPal',      paymentStatus: 'Pending', createdAt: '2025-07-23' },
  { id: 'BK015', customerId: 'CU015', customerName: 'Nikhil Verma',   carId: 'C002', carName: 'Honda City',        pickup: 'L002', pickupName: 'Delhi IGI Airport',    dropoff: 'L002', dropoffName: 'Delhi IGI Airport',     pickupDate: '2025-07-26', returnDate: '2025-07-28', pickupTime: '09:00', returnTime: '09:00', days: 2, basePrice: 4400,  extras: 0,    tax: 792, discount: 0,    total: 5192,  status: 'Active',    payment: 'Cash',        paymentStatus: 'Pending', createdAt: '2025-07-24' },
  { id: 'BK016', customerId: 'CU001', customerName: 'Arjun Sharma',   carId: 'C011', carName: 'Toyota Fortuner',  pickup: 'L001', pickupName: 'Mumbai Airport',       dropoff: 'L008', dropoffName: 'Goa Airport Branch',    pickupDate: '2025-04-10', returnDate: '2025-04-17', pickupTime: '08:00', returnTime: '18:00', days: 7, basePrice: 38500, extras: 2500, tax: 7380, discount: 1925, total: 46455, status: 'Completed', payment: 'Credit Card', paymentStatus: 'Paid',    createdAt: '2025-04-05' },
  { id: 'BK017', customerId: 'CU003', customerName: 'Rahul Gupta',    carId: 'C014', carName: 'BMW 5 Series',      pickup: 'L001', pickupName: 'Mumbai Airport',       dropoff: 'L001', dropoffName: 'Mumbai Airport',        pickupDate: '2025-03-01', returnDate: '2025-03-03', pickupTime: '12:00', returnTime: '12:00', days: 2, basePrice: 24000, extras: 1500, tax: 4590, discount: 0,    total: 30090, status: 'Completed', payment: 'Credit Card', paymentStatus: 'Paid',    createdAt: '2025-02-25' },
  { id: 'BK018', customerId: 'CU006', customerName: 'Ananya Krishnan',carId: 'C001', carName: 'Toyota Camry',      pickup: 'L001', pickupName: 'Mumbai Airport',       dropoff: 'L001', dropoffName: 'Mumbai Airport',        pickupDate: '2025-02-14', returnDate: '2025-02-16', pickupTime: '10:00', returnTime: '10:00', days: 2, basePrice: 5000,  extras: 500,  tax: 990, discount: 250, total: 6240,  status: 'Completed', payment: 'UPI',         paymentStatus: 'Paid',    createdAt: '2025-02-10' },
  { id: 'BK019', customerId: 'CU008', customerName: 'Kavya Nair',     carId: 'C016', carName: 'Kia Carnival',      pickup: 'L008', pickupName: 'Goa Airport Branch',   dropoff: 'L008', dropoffName: 'Goa Airport Branch',    pickupDate: '2025-01-20', returnDate: '2025-01-27', pickupTime: '09:00', returnTime: '09:00', days: 7, basePrice: 42000, extras: 3500, tax: 8190, discount: 2100, total: 51590, status: 'Completed', payment: 'Credit Card', paymentStatus: 'Paid',    createdAt: '2025-01-15' },
  { id: 'BK020', customerId: 'CU009', customerName: 'Aditya Joshi',   carId: 'C015', carName: 'Audi Q7',           pickup: 'L004', pickupName: 'Hyderabad Hitech City', dropoff: 'L004', dropoffName: 'Hyderabad Hitech City', pickupDate: '2025-06-05', returnDate: '2025-06-06', pickupTime: '11:00', returnTime: '20:00', days: 1, basePrice: 11000, extras: 1000, tax: 2160, discount: 0,    total: 14160, status: 'Cancelled', payment: 'PayPal',      paymentStatus: 'Refunded',createdAt: '2025-06-01' }
];

// ============================================================
// REVIEWS DATA — 10 Reviews
// ============================================================
const REVIEWS_DATA = [
  { id: 'R001', customerId: 'CU001', customerName: 'Arjun Sharma',    avatar: EXTRA_IMAGES.avatar1, carId: 'C004', carName: 'BMW 3 Series',     rating: 5, review: "Absolutely amazing experience! The BMW 3 Series was in pristine condition and drove like a dream. The pickup was seamless and the DriveEase team was incredibly professional. Will definitely book again!", date: '2025-07-15', status: 'Approved' },
  { id: 'R002', customerId: 'CU002', customerName: 'Priya Patel',     avatar: EXTRA_IMAGES.avatar2, carId: 'C003', carName: 'Hyundai Creta',     rating: 5, review: "The Creta was perfect for our family road trip. Clean, well-maintained and the booking process was so simple. The app made everything hassle-free. Highly recommend DriveEase!", date: '2025-07-10', status: 'Approved' },
  { id: 'R003', customerId: 'CU003', customerName: 'Rahul Gupta',     avatar: EXTRA_IMAGES.avatar3, carId: 'C007', carName: 'Kia Seltos',        rating: 4, review: "Great car at a fair price. The Kia Seltos had all the features I needed. Only minor issue was the GPS took a while to configure, but overall a 9/10 experience. Would rent again.", date: '2025-07-05', status: 'Approved' },
  { id: 'R004', customerId: 'CU004', customerName: 'Sneha Reddy',     avatar: EXTRA_IMAGES.avatar4, carId: 'C009', carName: 'Mahindra XUV700',  rating: 5, review: "The XUV700 is a beast! Comfortable, powerful and the 360° camera made parking in tight spots easy. DriveEase staff at Hyderabad were super friendly and helped with all queries.", date: '2025-07-01', status: 'Approved' },
  { id: 'R005', customerId: 'CU007', customerName: 'Rohan Mehta',     avatar: EXTRA_IMAGES.avatar5, carId: 'C010', carName: 'MG Hector',         rating: 4, review: "Good experience overall. The MG Hector was spacious and the internet connectivity worked well. The return process was quick. Small suggestion: more parking options at the Kolkata branch.", date: '2025-06-20', status: 'Approved' },
  { id: 'R006', customerId: 'CU008', customerName: 'Kavya Nair',      avatar: EXTRA_IMAGES.avatar1, carId: 'C019', carName: 'BMW M4',            rating: 5, review: "Renting the BMW M4 was a dream come true! That engine sound, the handling — absolutely phenomenal. Worth every rupee. DriveEase made the whole luxury experience feel accessible.", date: '2025-07-18', status: 'Approved' },
  { id: 'R007', customerId: 'CU009', customerName: 'Aditya Joshi',    avatar: EXTRA_IMAGES.avatar2, carId: 'C012', carName: 'Honda Amaze',       rating: 4, review: "Perfect budget-friendly option for the Delhi-Jaipur trip. The Honda Amaze was comfortable and fuel-efficient. The one-way drop feature is really convenient. Great value for money!", date: '2025-06-25', status: 'Approved' },
  { id: 'R008', customerId: 'CU011', customerName: 'Suresh Iyer',     avatar: EXTRA_IMAGES.avatar3, carId: 'C005', carName: 'Mercedes C-Class',  rating: 5, review: "Business travel will never be the same after experiencing the Mercedes C-Class. Impeccably clean, smooth ride and the MBUX system is fantastic. DriveEase is now my go-to platform.", date: '2025-07-12', status: 'Approved' },
  { id: 'R009', customerId: 'CU006', customerName: 'Ananya Krishnan', avatar: EXTRA_IMAGES.avatar4, carId: 'C001', carName: 'Toyota Camry',      rating: 4, review: "Very pleasant experience. The Toyota Camry was comfortable and fuel-efficient. The sunroof made the Valentine's Day drive extra special. Would have given 5 stars if GPS was included.", date: '2025-02-17', status: 'Approved' },
  { id: 'R010', customerId: 'CU013', customerName: 'Kiran Pillai',    avatar: EXTRA_IMAGES.avatar5, carId: 'C018', carName: 'MG ZS EV',          rating: 5, review: "Trying an EV for the first time with DriveEase was a great decision. The MG ZS EV is so quiet and smooth. 461 km range means zero range anxiety. Eco-friendly and premium at the same time!", date: '2025-07-20', status: 'Pending' }
];

// ============================================================
// COUPONS / OFFERS DATA — 10 Offers
// ============================================================
const COUPONS_DATA = [
  { id: 'CP001', code: 'FIRSTRIDE',  type: 'percentage', value: 15, minAmount: 2000,  startDate: '2025-01-01', expiryDate: '2025-12-31', usageLimit: 1000, used: 342, status: 'Active',   description: 'First Booking Discount', bgColor: '#2563eb' },
  { id: 'CP002', code: 'WEEKEND20',  type: 'percentage', value: 20, minAmount: 5000,  startDate: '2025-01-01', expiryDate: '2025-12-31', usageLimit: 500,  used: 128, status: 'Active',   description: 'Weekend Special Offer',  bgColor: '#7c3aed' },
  { id: 'CP003', code: 'LONGTRIP10', type: 'percentage', value: 10, minAmount: 20000, startDate: '2025-01-01', expiryDate: '2025-12-31', usageLimit: 200,  used: 67,  status: 'Active',   description: 'Long Term Rental Deal',  bgColor: '#059669' },
  { id: 'CP004', code: 'SUMMER25',   type: 'percentage', value: 25, minAmount: 8000,  startDate: '2025-04-01', expiryDate: '2025-06-30', usageLimit: 300,  used: 189, status: 'Inactive', description: 'Summer Season Sale',     bgColor: '#d97706' },
  { id: 'CP005', code: 'FLAT500',    type: 'fixed',      value: 500, minAmount: 3000, startDate: '2025-07-01', expiryDate: '2025-08-31', usageLimit: 400,  used: 56,  status: 'Active',   description: 'Flat ₹500 Off',          bgColor: '#dc2626' },
  { id: 'CP006', code: 'EV15',       type: 'percentage', value: 15, minAmount: 1500,  startDate: '2025-01-01', expiryDate: '2025-12-31', usageLimit: 600,  used: 92,  status: 'Active',   description: 'Electric Vehicle Offer', bgColor: '#0891b2' },
  { id: 'CP007', code: 'LUXURY30',   type: 'percentage', value: 30, minAmount: 25000, startDate: '2025-06-01', expiryDate: '2025-07-31', usageLimit: 100,  used: 34,  status: 'Active',   description: 'Luxury Car Sale',        bgColor: '#1a3c6e' },
  { id: 'CP008', code: 'AIRPORT10',  type: 'percentage', value: 10, minAmount: 2000,  startDate: '2025-01-01', expiryDate: '2025-12-31', usageLimit: 800,  used: 213, status: 'Active',   description: 'Airport Pickup Offer',   bgColor: '#4f46e5' },
  { id: 'CP009', code: 'MONSOON15',  type: 'percentage', value: 15, minAmount: 4000,  startDate: '2025-07-01', expiryDate: '2025-09-30', usageLimit: 350,  used: 41,  status: 'Active',   description: 'Monsoon Special',        bgColor: '#0284c7' },
  { id: 'CP010', code: 'FLAT1000',   type: 'fixed',      value: 1000, minAmount: 8000, startDate: '2025-07-15', expiryDate: '2025-07-31', usageLimit: 150, used: 18,  status: 'Active',   description: 'Flash Sale Offer',       bgColor: '#be185d' }
];

// ============================================================
// MONTHLY REVENUE CHART DATA
// ============================================================
const MONTHLY_REVENUE = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  data:   [185000, 220000, 265000, 310000, 278000, 345000, 420000, 0, 0, 0, 0, 0]
};

const MONTHLY_BOOKINGS = {
  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
  data:   [32, 41, 55, 63, 48, 72, 58]
};

// ============================================================
// FAQ DATA
// ============================================================
const FAQ_DATA = [
  { id: 1, category: 'Booking', q: "How do I book a car on DriveEase?", a: "Booking is simple! Search for your preferred pickup location and dates, browse available cars, select the one you like, and follow the 4-step checkout process. You'll receive a confirmation email instantly." },
  { id: 2, category: 'Booking', q: "Can I modify my booking after confirmation?", a: "Yes! You can modify your booking up to 24 hours before the pickup time through your Customer Dashboard under \"My Bookings\". Subject to availability, changes to dates and locations can be made without any fees." },
  { id: 3, category: 'Booking', q: "What is the minimum age to rent a car?", a: "The minimum age to rent a car on DriveEase is 21 years. Renters between 21-25 years may be subject to a young driver surcharge for certain vehicle categories." },
  { id: 4, category: 'Payment', q: "What payment methods are accepted?", a: "We accept Credit/Debit Cards (Visa, Mastercard, Amex), UPI (Google Pay, PhonePe, Paytm), PayPal, and Cash on Pickup for select locations." },
  { id: 5, category: 'Payment', q: "When is the security deposit charged?", a: "The security deposit is pre-authorized on your card at the time of booking but is only captured if there are damages or violations. It is typically released within 5-7 business days after return." },
  { id: 6, category: 'Cancellation', q: "What is the cancellation policy?", a: "Cancellations made more than 48 hours before pickup receive a full refund. Cancellations 24-48 hours before pickup receive a 50% refund. Cancellations less than 24 hours before pickup are non-refundable." },
  { id: 7, category: 'Cancellation', q: "How long does a refund take?", a: "Refunds are processed within 2-3 business days. The amount will appear in your bank account within 5-7 business days depending on your bank." },
  { id: 8, category: 'Rental Policy', q: "What documents do I need to bring?", a: "You need: (1) Valid driving license, (2) Original ID proof (Aadhaar/Passport), (3) Booking confirmation email/SMS. Foreign nationals must carry a valid passport and International Driving Permit." },
  { id: 9, category: 'Rental Policy', q: "Is there a mileage limit?", a: "For standard rentals, we offer 300 km per day included in the price. Additional kilometers are charged at ₹15/km. Long-term rentals (7+ days) have unlimited mileage." },
  { id: 10, category: 'Insurance', q: "Is insurance included in the rental price?", a: "Basic third-party insurance is included in all rentals. We recommend opting for our Comprehensive Insurance add-on (₹500/day) which covers accidental damage, theft, and roadside assistance." },
  { id: 11, category: 'Insurance', q: "What happens if the car breaks down?", a: "All DriveEase rentals include 24/7 Roadside Assistance. In case of a breakdown, call our helpline: 1800-123-4567. We will arrange towing, a replacement car (if available), or mechanical assistance." },
  { id: 12, category: 'Driving License', q: "Do I need an international driving license?", a: "Indian nationals only need a valid Indian driving license. Foreign nationals visiting India must carry their home country driving license along with an International Driving Permit (IDP)." }
];

// ============================================================
// TESTIMONIALS DATA
// ============================================================
const TESTIMONIALS_DATA = [
  { name: 'Arjun Sharma',    location: 'Mumbai', rating: 5, text: "DriveEase made my business trip so much easier. The BMW 3 Series was immaculate and the seamless booking experience saved me hours. The app is intuitive and customer support is excellent. Highly recommended!", avatar: EXTRA_IMAGES.avatar1 },
  { name: 'Priya Patel',     location: 'Delhi',  rating: 5, text: "Booked the Hyundai Creta for a family road trip and it was perfect. Clean car, easy pickup, no hidden charges. The transparent pricing and professional staff made it a 5-star experience from start to finish.", avatar: EXTRA_IMAGES.avatar2 },
  { name: 'Rahul Gupta',     location: 'Bangalore', rating: 4, text: "Very reliable service. I've used DriveEase 7 times now and each experience has been consistently good. The variety of cars and competitive pricing keep me coming back. The loyalty program is a great bonus too.", avatar: EXTRA_IMAGES.avatar3 },
  { name: 'Sneha Reddy',     location: 'Hyderabad', rating: 5, text: "Outstanding service! The Mahindra XUV700 was exactly as described — feature-packed and comfortable. The staff at Hyderabad helped us understand all the features and the fuel was topped up. Brilliant!", avatar: EXTRA_IMAGES.avatar4 },
  { name: 'Kavya Nair',      location: 'Goa',    rating: 5, text: "Renting from DriveEase in Goa was the highlight of my trip. Easy online booking, fast pickup at the airport, and the car was perfect for exploring the beaches. Free cancellation policy gave me peace of mind too.", avatar: EXTRA_IMAGES.avatar5 }
];

// ============================================================
// TEAM DATA
// ============================================================
const TEAM_DATA = [
  { name: 'Rajesh Mehta',    role: 'CEO & Co-Founder',       bio: "15+ years in automotive and mobility industry. Previously led operations at India's largest fleet company.", image: EXTRA_IMAGES.team1 },
  { name: 'Snehal Kapoor',   role: 'CTO & Co-Founder',       bio: "Ex-Google engineer with a passion for building scalable mobility platforms. Led tech teams across 3 startups.", image: EXTRA_IMAGES.team2 },
  { name: 'Vikram Nair',     role: 'Head of Operations',     bio: "10 years managing large-scale fleet operations. Passionate about delivering exceptional customer experiences.",  image: EXTRA_IMAGES.team3 },
  { name: 'Anita Singh',     role: 'Head of Customer Success', bio: "Dedicated to ensuring every DriveEase customer has an outstanding rental experience from booking to return.",  image: EXTRA_IMAGES.team4 }
];

// Export all data
if (typeof module !== 'undefined') {
  module.exports = { CARS_DATA, CATEGORIES_DATA, BRANDS_DATA, LOCATIONS_DATA, CUSTOMERS_DATA, BOOKINGS_DATA, REVIEWS_DATA, COUPONS_DATA, TESTIMONIALS_DATA, FAQ_DATA, TEAM_DATA, MONTHLY_REVENUE, MONTHLY_BOOKINGS, CAR_IMAGES, EXTRA_IMAGES };
}
