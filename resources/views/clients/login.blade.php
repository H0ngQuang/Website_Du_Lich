@include('clients.blocks.header')

<div class="login-template">
    <div class="main">
        <!-- Sign in  Form -->
        <section class="sign-in show">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="{{ asset('clients/assets/images/login/signin-image.jpg') }}"
                                alt="sing up image"></figure>
                        <a href="javascript:void(0)" class="signup-image-link" id="sign-up">Tạo tài khoản</a>
                    </div>

                    <div class="signin-form">
                        <h2 class="form-title">Đăng nhập</h2>
                        <form action="{{ route('user-login') }}" method="POST" class="login-form" id="login-form" style="margin-top: 15px">
                            <div class="form-group">
                                <label for="username_login"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="username_login" id="username_login" placeholder="Tên đăng nhập" required/>
                            </div>
                            <div class="invalid-feedback" style="margin-top:-15px" id="validate_username"></div>
                            @csrf
                            <div class="form-group">
                                <label for="password_login"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password_login" id="password_login" placeholder="Mật khẩu" required/>
                            </div>
                            <div class="invalid-feedback" style="margin-top:-15px" id="validate_password"></div>
                            <div class="form-group form-button">
                                <input type="submit" name="signin" id="signin" class="form-submit"
                                    value="Đăng nhập" />
                            </div>
                        </form>
                        <div class="social-login">
                            <span class="social-label">Hoặc đăng nhập bằng</span>
                            <ul class="socials">
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-facebook"></i></a></li>
                                <li><a href="{{ route('login-google') }}"><i class="display-flex-center zmdi zmdi-google"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Sign up form -->
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Đăng ký</h2>
                        <div class="loader"></div>
                        <form action="{{ route('register') }}" method="POST" class="register-form" id="register-form" style="margin-top: 15px">
                            <div class="form-group">
                                <label for="username_register"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="username_register" id="username_register" placeholder="Tên tài khoản (chữ cái, số, _)" maxlength="50" required/>
                            </div>
                            <div class="invalid-feedback" style="margin-top:-15px" id="validate_username_regis"></div>
                            @csrf
                            <div class="form-group">
                                <label for="email_register"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email_register" id="email_register" placeholder="Email" required/>
                            </div>
                            <div class="invalid-feedback" style="margin-top:-15px" id="validate_email_regis"></div>
                            <div class="form-group">
                                <label for="password_register"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password_register" id="password_register" placeholder="Mật khẩu (tối thiểu 8 ký tự)" required/>
                            </div>
                            <div id="password_strength" style="margin-top:-12px; margin-bottom:8px; font-size:12px;"></div>
                            <div class="invalid-feedback" style="margin-top:-5px" id="validate_password_regis"></div>
                            <div class="form-group">
                                <label for="re_pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="re_pass" id="re_pass" placeholder="Nhập lại mật khẩu" required/>
                            </div>
                            <div class="invalid-feedback" style="margin-top:-15px" id="validate_repass"></div>
                            <div class="form-group form-button">
                                <input type="submit" name="signup" id="signup" class="form-submit"
                                    value="Đăng ký" />
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure><img src="{{ asset('clients/assets/images/login/signup-image.jpg') }}"
                                alt="sing up image"></figure>
                        <a href="javascript:void(0)" class="signup-image-link" id="sign-in">Tôi đã có tài khoản rồi</a>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>

<script>
// ============================================================
// Real-time client-side validation
// ============================================================
const usernameInput   = document.getElementById('username_register');
const emailInput      = document.getElementById('email_register');
const passwordInput   = document.getElementById('password_register');
const repassInput     = document.getElementById('re_pass');

function showError(id, msg) {
    const el = document.getElementById(id);
    el.textContent = msg;
    el.style.display = msg ? 'block' : 'none';
    el.style.color = '#e74c3c';
}
function clearError(id) { showError(id, ''); }

// Username: only letters, numbers, underscore
usernameInput.addEventListener('input', function () {
    const val = this.value;
    if (!val) { clearError('validate_username_regis'); return; }
    if (val.length > 50) {
        showError('validate_username_regis', 'Tên tài khoản tối đa 50 ký tự.');
    } else if (!/^[a-zA-Z0-9_]+$/.test(val)) {
        showError('validate_username_regis', 'Chỉ được dùng chữ cái, số và dấu gạch dưới (_).');
    } else {
        clearError('validate_username_regis');
    }
});

// Email format
emailInput.addEventListener('input', function () {
    const val = this.value;
    if (!val) { clearError('validate_email_regis'); return; }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(val)) {
        showError('validate_email_regis', 'Email không đúng định dạng.');
    } else {
        clearError('validate_email_regis');
    }
});

// Password strength indicator
passwordInput.addEventListener('input', function () {
    const val = this.value;
    const strengthEl = document.getElementById('password_strength');
    if (!val) { strengthEl.textContent = ''; clearError('validate_password_regis'); return; }

    const checks = {
        length:  val.length >= 8,
        lower:   /[a-z]/.test(val),
        upper:   /[A-Z]/.test(val),
        digit:   /\d/.test(val),
        special: /[\W_]/.test(val),
    };
    const passed = Object.values(checks).filter(Boolean).length;

    const labels = ['Rất yếu', 'Yếu', 'Trung bình', 'Khá', 'Mạnh'];
    const colors = ['#e74c3c', '#e67e22', '#f1c40f', '#2ecc71', '#27ae60'];
    strengthEl.textContent = '🔒 Độ mạnh: ' + (labels[passed - 1] || 'Rất yếu');
    strengthEl.style.color = colors[passed - 1] || '#e74c3c';

    if (passed < 5) {
        showError('validate_password_regis', 'Phải có chữ hoa, chữ thường, số và ký tự đặc biệt (tối thiểu 8 ký tự).');
    } else {
        clearError('validate_password_regis');
    }

    // re-check confirm password
    if (repassInput.value) checkRepass();
});

// Confirm password
function checkRepass() {
    if (repassInput.value !== passwordInput.value) {
        showError('validate_repass', 'Mật khẩu nhập lại không khớp.');
    } else {
        clearError('validate_repass');
    }
}
repassInput.addEventListener('input', checkRepass);

// ============================================================
// Form submit — handle server validation errors (HTTP 422)
// ============================================================
document.getElementById('register-form').addEventListener('submit', function (e) {
    e.preventDefault();

    // Clear all previous errors
    ['validate_username_regis','validate_email_regis','validate_password_regis','validate_repass'].forEach(clearError);

    const formEl = this;
    const loaderEl = document.querySelector('.loader'); // in the sign-up section
    
    // Show loader, hide form
    if (loaderEl) loaderEl.style.display = 'block';
    formEl.style.display = 'none';

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: formData,
    })
    .then(res => res.json().then(data => ({ status: res.status, data })))
    .then(({ status, data }) => {
        // Restore form, hide loader
        if (loaderEl) loaderEl.style.display = 'none';
        formEl.style.display = 'block';

        if (data.success) {
            toastr.success(data.message, { timeOut: 5000 });
            formEl.reset();
            document.getElementById('password_strength').textContent = '';
            // if success, trigger sign in tab or reload
            setTimeout(() => { window.location.reload(); }, 2000);
        } else if (status === 422 && data.errors) {
            // Map server field errors to display elements
            const fieldMap = {
                'username_register': 'validate_username_regis',
                'email_register':    'validate_email_regis',
                'password_register': 'validate_password_regis',
                're_pass':           'validate_repass',
            };
            Object.entries(data.errors).forEach(([field, messages]) => {
                if (fieldMap[field]) showError(fieldMap[field], messages[0]);
            });
        } else {
            toastr.error(data.message || 'Đã có lỗi xảy ra, vui lòng thử lại.');
        }
    })
    .catch(() => {
        if (loaderEl) loaderEl.style.display = 'none';
        formEl.style.display = 'block';
        toastr.error('Không thể kết nối đến máy chủ.');
    });
});
</script>

@include('clients.blocks.footer')

