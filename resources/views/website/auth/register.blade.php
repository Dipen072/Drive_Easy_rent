<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Sign Up — DriveEase</title>
  <meta name="description" content="Create a DriveEase customer account for fast, hassle-free car rentals across India.">
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{url('website/css/main.css')}}">
  <style>
    body { background: var(--surface-2); min-height: 100vh; padding: 2.5rem 0; }
    .signup-card { max-width: 820px; margin: 0 auto; background: var(--surface); border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-md); overflow: hidden; }
    .signup-header { background: var(--primary-gradient); color: #fff; padding: 2.5rem 2rem; text-align: center; }
    .section-title-form { font-size: 1.05rem; font-weight: 700; font-family: var(--font-heading); color: var(--primary); margin-bottom: 1.25rem; border-bottom: 2px solid var(--primary-lighter); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; }
    .social-btn { border: 1px solid var(--border); background: var(--surface); padding: 0.6rem 1rem; border-radius: var(--radius-sm); font-weight: 600; font-size: 0.875rem; color: var(--text-primary); transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
    .social-btn:hover { background: var(--surface-2); border-color: var(--primary-light); }
    .profile-avatar-preview { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-light); }
  </style>
</head>
<body>

<div class="container">
  <div class="signup-card">
    
    <!-- HEADER -->
    <div class="signup-header">
      <a href="{{url('/index')}}" class="navbar-brand justify-content-center text-white mb-2">
        <div class="brand-logo" style="background:#fff; color:var(--primary); font-size:1.25rem;"><i class="fas fa-car-side"></i></div>
        <span class="brand-name" style="color:#fff !important;">Drive<span style="color:var(--accent);">Ease</span></span>
      </a>
      <h3 class="fw-800 font-heading mb-1 text-white">Create Your Account</h3>
      <p class="mb-0 text-white-50 font-sm">Join India's premier car rental network in less than 2 minutes</p>
    </div>

    <div class="p-4 p-sm-5">
      
      <!-- SOCIAL REGISTRATION -->
      <div class="row g-2 mb-4">
        <div class="col-sm-4">
          <button class="social-btn w-100" type="button" onclick="Toast.info('Social Login', 'Simulating Google OAuth Connection...')">
            <i class="fab fa-google text-danger"></i> Google
          </button>
        </div>
        <div class="col-sm-4">
          <button class="social-btn w-100" type="button" onclick="Toast.info('Social Login', 'Simulating Facebook Connection...')">
            <i class="fab fa-facebook-f text-primary"></i> Facebook
          </button>
        </div>
        <div class="col-sm-4">
          <button class="social-btn w-100" type="button" onclick="Toast.info('Social Login', 'Simulating Apple ID Connection...')">
            <i class="fab fa-apple text-dark"></i> Apple ID
          </button>
        </div>
      </div>

      <div class="text-center position-relative my-4">
        <hr class="text-muted">
        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 font-xs text-muted fw-700 uppercase">Or Register with Email</span>
      </div>

      <!-- MAIN FORM -->
      @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i><strong>Please fix the following errors:</strong>
          <ul class="mb-0 mt-2 ps-3 font-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <form id="customerSignupForm" action="{{ url('/register') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- 1. 👤 BASIC DETAILS -->
        <div class="mb-4">
          <div class="section-title-form"><i class="fas fa-user-circle"></i> 👤 Basic Details</div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">First Name *</label>
              <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" id="firstName" value="{{ old('first_name') }}" required placeholder="e.g. Arjun">
              @error('first_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Last Name *</label>
              <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" id="lastName" value="{{ old('last_name') }}" required placeholder="e.g. Sharma">
              @error('last_name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Email Address *</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="signupEmail" value="{{ old('email') }}" required placeholder="arjun.sharma@email.com">
              @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Mobile Number *</label>
              <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" id="signupMobile" value="{{ old('phone') }}" required placeholder="+91 98765 43210">
              @error('phone')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Date of Birth <span class="text-muted font-xs">(optional)</span></label>
              <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" id="dob" value="{{ old('dob') }}">
              @error('dob')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Profile Picture <span class="text-muted font-xs">(optional)</span></label>
              <div class="d-flex align-items-center gap-3">
                <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=100&q=80" id="avatarPreview" class="profile-avatar-preview">
                <button type="button" class="btn btn-outline-brand btn-sm" onclick="$('#avatarFileInput').click()">Upload Photo</button>
                <input type="file" name="profile_picture" class="d-none" id="avatarFileInput" accept="image/*">
              </div>
              @error('profile_picture')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <!-- 2. 🔐 ACCOUNT SECURITY -->
        <div class="mb-4">
          <div class="section-title-form"><i class="fas fa-lock"></i> 🔐 Account Security</div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Password *</label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="signupPassword" required placeholder="At least 6 characters">
              @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Confirm Password *</label>
              <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="confirmPassword" required placeholder="Re-enter password">
              @error('password_confirmation')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <!-- 3. 🏠 ADDRESS DETAILS (Optional) -->
        <div class="mb-4">
          <div class="section-title-form"><i class="fas fa-house"></i> 🏠 Address Details <span class="font-xs text-muted font-body fw-normal me-auto">(optional during signup)</span></div>
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Street Address</label>
              <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" id="address" value="{{ old('address') }}" placeholder="Apartment / Flat / House No, Street">
              @error('address')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3 col-6">
              <label class="form-label">City</label>
              <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" id="city" value="{{ old('city') }}" placeholder="Mumbai">
              @error('city')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3 col-6">
              <label class="form-label">State</label>
              <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" id="state" value="{{ old('state') }}" placeholder="Maharashtra">
              @error('state')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3 col-6">
              <label class="form-label">Country</label>
              <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" id="country" value="{{ old('country', 'India') }}">
              @error('country')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3 col-6">
              <label class="form-label">ZIP / Postal Code</label>
              <input type="text" name="zip_code" class="form-control @error('zip_code') is-invalid @enderror" id="zipCode" value="{{ old('zip_code') }}" placeholder="400001">
              @error('zip_code')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <!-- 4. 📄 DRIVING DETAILS & CONDITIONAL ID SELECTION -->
        <div class="mb-4">
          <div class="section-title-form"><i class="fas fa-id-card"></i> 📄 Verification & Identity Details</div>
          
          <!-- CONDITIONAL TOGGLE -->
          <div class="p-3 bg-surface-2 border rounded-3 mb-3">
            <div class="form-check form-switch me-0">
              <input class="form-check-input me-2" type="checkbox" name="no_dl" value="1" id="noDlToggle" {{ old('no_dl') ? 'checked' : '' }}>
              <label class="form-check-label fw-700 font-sm" for="noDlToggle">
                I don't have a Driving License right now <span class="text-primary">(Upload Alternate Government ID instead)</span>
              </label>
            </div>
          </div>

          <!-- OPTION A: DRIVING LICENSE (Default Show) -->
          <div id="dlSection" class="p-3 border rounded-3 bg-surface mb-3 {{ old('no_dl') ? 'd-none' : '' }}">
            <div class="fw-700 text-primary font-sm mb-2"><i class="fas fa-id-badge me-1"></i> Driving License Details</div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Driving License Number</label>
                <input type="text" name="dl_number" class="form-control @error('dl_number') is-invalid @enderror" id="dlNumber" value="{{ old('dl_number') }}" placeholder="MH-0120230012345">
                @error('dl_number')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">License Expiry Date</label>
                <input type="date" name="dl_expiry" class="form-control @error('dl_expiry') is-invalid @enderror" id="dlExpiry" value="{{ old('dl_expiry') }}">
                @error('dl_expiry')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-12">
                <label class="form-label">Upload Driving License Copy (Front & Back)</label>
                <input type="file" name="dl_file" class="form-control @error('dl_file') is-invalid @enderror" id="dlFile">
                @error('dl_file')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- OPTION B: ALTERNATE ID (Hidden by Default, Opens when user has no DL) -->
          <div id="altIdSection" class="p-3 border rounded-3 bg-primary-lighter border-primary mb-3 {{ old('no_dl') ? '' : 'd-none' }}">
            <div class="fw-700 text-primary font-sm mb-2"><i class="fas fa-passport me-1"></i> Alternate Identity Verification</div>
            <p class="font-xs text-muted mb-3">Since you don't have a Driving License right now, please provide an alternate government ID. You can present your license later before key pickup.</p>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Select ID Type</label>
                <select name="alt_id_type" class="form-select @error('alt_id_type') is-invalid @enderror" id="altIdType">
                  <option value="Aadhaar Card" {{ old('alt_id_type') == 'Aadhaar Card' ? 'selected' : '' }}>Aadhaar Card</option>
                  <option value="Passport" {{ old('alt_id_type') == 'Passport' ? 'selected' : '' }}>Passport</option>
                  <option value="Voter ID" {{ old('alt_id_type') == 'Voter ID' ? 'selected' : '' }}>Voter ID</option>
                </select>
                @error('alt_id_type')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-8">
                <label class="form-label">Government ID Number</label>
                <input type="text" name="alt_id_number" class="form-control @error('alt_id_number') is-invalid @enderror" id="altIdNumber" value="{{ old('alt_id_number') }}" placeholder="Enter Aadhaar / Passport / Voter ID No.">
                @error('alt_id_number')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-12">
                <label class="form-label">Upload Alternate ID Document</label>
                <input type="file" name="alt_id_file" class="form-control @error('alt_id_file') is-invalid @enderror" id="altIdFile">
                @error('alt_id_file')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

        </div>

        <!-- 5. ✅ OTHER & AGREEMENTS -->
        <div class="mb-4 pt-2 border-top">
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="termsCheck" required>
            <label class="form-check-label font-sm text-secondary" for="termsCheck">
              I agree to the <a href="{{url('/terms')}}" target="_blank" class="fw-700 text-primary">Terms & Conditions</a> *
            </label>
          </div>
          <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="privacyCheck" required>
            <label class="form-check-label font-sm text-secondary" for="privacyCheck">
              I agree to the <a href="{{url('/privacy-policy')}}" target="_blank" class="fw-700 text-primary">Privacy Policy</a> *
            </label>
          </div>

          <button type="submit" class="btn btn-primary-brand btn-lg-brand w-100 fw-800 fs-5">
            <i class="fas fa-user-plus me-2"></i> Create Account & Sign Up
          </button>
        </div>

      </form>

      <!-- FOOTER LINKS -->
      <div class="text-center mt-4 pt-3 border-top">
        <p class="font-sm text-muted mb-0">Already have an account? <a href="{{url('/login')}}" class="fw-800 text-primary">Log In Here</a></p>
      </div>

    </div>
  </div>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{url('website/js/data.js')}}"></script>
<script src="{{url ('website/js/storage.js')}}"></script>
<script src="{{url ('website/js/ui.js')}}"></script>
<script>
  $(document).ready(function() {
    // CONDITIONAL SHOW/HIDE LOGIC FOR DRIVING LICENSE VS ALTERNATE ID
    $('#noDlToggle').on('change', function() {
      if ($(this).is(':checked')) {
        $('#dlSection').addClass('d-none');
        $('#altIdSection').removeClass('d-none');
      } else {
        $('#dlSection').removeClass('d-none');
        $('#altIdSection').addClass('d-none');
      }
    });

    // Avatar preview file reader
    $('#avatarFileInput').on('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => $('#avatarPreview').attr('src', e.target.result);
        reader.readAsDataURL(file);
      }
    });

    // FORM SUBMISSION VALIDATION
    $('#customerSignupForm').on('submit', function(e) {
      const pass = $('#signupPassword').val();
      const confirmPass = $('#confirmPassword').val();

      if (pass !== confirmPass) {
        e.preventDefault();
        if (typeof Toast !== 'undefined') {
          Toast.error('Password Mismatch', 'Password and Confirm Password do not match.');
        } else {
          alert('Password and Confirm Password do not match.');
        }
        return false;
      }

      if (!$('#termsCheck').is(':checked') || !$('#privacyCheck').is(':checked')) {
        e.preventDefault();
        if (typeof Toast !== 'undefined') {
          Toast.warning('Agreements Required', 'Please accept the Terms & Conditions and Privacy Policy.');
        } else {
          alert('Please accept the Terms & Conditions and Privacy Policy.');
        }
        return false;
      }
    });
  });
</script>
@include('sweetalert::alert')
</body>
</html>
