<!-- end modal-shared -->
<div class="modal-popup">
  <div
    class="modal fade"
    id="loginPopupForm"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <h5 class="modal-title title" id="exampleModalLongTitle2">
              تسجيل الدخول
            </h5>
            <p class="font-size-14">مرحبا! مرحبا بك في حسابك</p>
          </div>
          <button
            type="button"
            class="btn-close close"
            data-bs-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true" class="la la-close"></span>
          </button>
        </div>
        <div class="modal-body">
          <div class="contact-form-action">
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="input-box">
        <label class="label-text">البريد الإلكتروني</label>
        <div class="form-group">
            <span class="la la-user form-icon"></span>
            <input
                class="form-control"
                type="email"
                name="email"
                placeholder="اكتب البريد الإلكتروني الخاص بك"
                required
            />
        </div>
    </div>
    <!-- end input-box -->
    <div class="input-box">
        <label class="label-text">كلمة المرور</label>
        <div class="form-group mb-2">
            <span class="la la-lock form-icon"></span>
            <input
                class="form-control"
                type="password"
                name="password"
                placeholder="اكتب كلمة المرور الخاصة بك"
                required
            />
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <div class="custom-checkbox mb-0">
                <input
                    type="checkbox"
                    class="form-check-input"
                    id="rememberchb"
                    name="remember"
                />
                <label for="rememberchb">تذكرني</label>
            </div>
            <p class="forgot-password">
                <a href="#">هل نسيت كلمة المرور؟</a>
            </p>
        </div>
    </div>
    <!-- end input-box -->

    @if($errors->any())
        <div class="alert alert-danger mt-2">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="btn-box pt-3 pb-4">
        <button type="submit" class="theme-btn w-100">
            تسجيل الدخول
        </button>
    </div>

    <div class="action-box text-center">
        <p class="font-size-14">أو تسجيل الدخول باستخدام</p>
        <ul class="social-profile py-3">
            <li><a href="#" class="bg-5 text-white"><i class="lab la-facebook-f"></i></a></li>
            <li><a href="#" class="bg-6 text-white"><i class="lab la-twitter"></i></a></li>
            <li><a href="#" class="bg-7 text-white"><i class="lab la-instagram"></i></a></li>
            <li><a href="#" class="bg-5 text-white"><i class="lab la-linkedin-in"></i></a></li>
        </ul>
    </div>
</form>

          </div>
          <!-- end contact-form-action -->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- end modal-popup -->