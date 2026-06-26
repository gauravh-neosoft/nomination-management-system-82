<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page</title>

  <!-- Bootstrap 5.3.8 -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet" />

  <!-- Bootstrap Icons -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css" />

  <!-- Theme Custom CSS Styles -->
  <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/utilities.css') }}" />
</head>

<body>
  <main class="container-fluid">
    <div class="row vh-100">
      <!-- Left Side Illustration Panel -->
      <section
        class="h-50 h-lg-100 col-lg-7 bg-primary p-5 pb-1 position-relative">
        <div class="d-flex flex-column justify-content-between h-100">
          <header class="ms-auto ms-lg-0">
            <img
              src="{{ asset('assets/icons/infosys_logo.svg') }}"
              height="30"
              alt="Logo" />
          </header>
          <div class="pb-5 pb-lg-0">
            <h1 class="text-white login-heading pt-0 mb-4">
              Nomination <br />
              Management <br />
              System
            </h1>

            <p class="text-white">Powered by Events COE</p>
          </div>
          <footer class="d-none d-lg-block">
            <small class="text-white text-opacity-50">© 2026 Infosys Ltd. All rights reserved.</small>
          </footer>
          <!-- Circular decoration image -->
          <img
            src="{{ asset('assets/images/login_circles.svg') }}"
            class="login-decorative-img position-absolute"
            alt="" />
        </div>
      </section>

      <!-- Right Side Login Form Card Panel -->
      <section class="bg-primary col-12 col-lg-5 h-50 h-lg-100 login-bg-grey">
        <div
          class="my-0 bg-primary login-bg-grey h-100 d-flex align-items-lg-center justify-content-center w-100">
          <article
            class="d-flex flex-column justify-content-center my-3 shadow-card overflow-auto scrollable-content-box bg-white p-3 p-md-5 w-100 rounded-4">
            <h2 class="mb-3">Hello User!</h2>
            <p class="fs-09 text-secondary mb-4">
              Sign in with your Infosys id to access nominations, events, and
              your personal dashboard.
            </p>

            <!-- Authentication Form -->
            <form action="{{ route('login') }}" method="post" class="w-100">
              @csrf

              @if ($errors->any())
                  <div class="alert alert-danger py-2 px-3 fs-08 mb-3 rounded-3 shadow-sm border-0">
                      <ul class="mb-0 ps-3">
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif

              <!-- Role Testing Email Selector -->
              <div class="mb-3">
                <label for="user_email" class="form-label text-dark fw-bold">Email address / ID</label>
                <select name="user_email" id="user_email" class="form-select form-select-sm rounded-3">
                  <!-- Seeded Evaluation Dataset -->
                  <optgroup label="Super Admin (1 User)">
                    <option value="hedagaurav1378-super_admin-super1@gmail.com">hedagaurav1378-super_admin-super1@gmail.com</option>
                  </optgroup>
                  <optgroup label="Admins (3 Users)">
                    <option value="hedagaurav1378-admin-admin1@gmail.com">hedagaurav1378-admin-admin1@gmail.com</option>
                    <option value="hedagaurav1378-admin-admin2@gmail.com">hedagaurav1378-admin-admin2@gmail.com</option>
                    <option value="hedagaurav1378-admin-admin3@gmail.com">hedagaurav1378-admin-admin3@gmail.com</option>
                  </optgroup>
                  <optgroup label="Nominators (10 Users)">
                    <option value="hedagaurav1378-nominator-nom1@gmail.com">hedagaurav1378-nominator-nom1@gmail.com</option>
                    <option value="hedagaurav1378-nominator-nom2@gmail.com">hedagaurav1378-nominator-nom2@gmail.com</option>
                    <option value="hedagaurav1378-nominator-nom3@gmail.com">hedagaurav1378-nominator-nom3@gmail.com</option>
                    <option value="hedagaurav1378-nominator-nom4@gmail.com">hedagaurav1378-nominator-nom4@gmail.com</option>
                    <option value="hedagaurav1378-nominator-nom5@gmail.com">hedagaurav1378-nominator-nom5@gmail.com</option>
                    <option value="hedagaurav1378-nominator-nom6@gmail.com">hedagaurav1378-nominator-nom6@gmail.com</option>
                    <option value="hedagaurav1378-nominator-nom7@gmail.com">hedagaurav1378-nominator-nom7@gmail.com</option>
                    <option value="hedagaurav1378-nominator-nom8@gmail.com">hedagaurav1378-nominator-nom8@gmail.com</option>
                    <option value="hedagaurav1378-nominator-nom9@gmail.com">hedagaurav1378-nominator-nom9@gmail.com</option>
                    <option value="hedagaurav1378-nominator-nom10@gmail.com">hedagaurav1378-nominator-nom10@gmail.com</option>
                  </optgroup>
                  <optgroup label="Unit SPOCs / Unit Ops (5 Users)">
                    <option value="hedagaurav1378-unit_spoc-spoc1@gmail.com">hedagaurav1378-unit_spoc-spoc1@gmail.com</option>
                    <option value="hedagaurav1378-unit_spoc-spoc2@gmail.com">hedagaurav1378-unit_spoc-spoc2@gmail.com</option>
                    <option value="hedagaurav1378-unit_spoc-spoc3@gmail.com">hedagaurav1378-unit_spoc-spoc3@gmail.com</option>
                    <option value="hedagaurav1378-unit_spoc-spoc4@gmail.com">hedagaurav1378-unit_spoc-spoc4@gmail.com</option>
                    <option value="hedagaurav1378-unit_spoc-spoc5@gmail.com">hedagaurav1378-unit_spoc-spoc5@gmail.com</option>
                  </optgroup>
                  <optgroup label="Event Ops (10 Users)">
                    <option value="hedagaurav1378-event_ops-ops1@gmail.com">hedagaurav1378-event_ops-ops1@gmail.com</option>
                    <option value="hedagaurav1378-event_ops-ops2@gmail.com">hedagaurav1378-event_ops-ops2@gmail.com</option>
                    <option value="hedagaurav1378-event_ops-ops3@gmail.com">hedagaurav1378-event_ops-ops3@gmail.com</option>
                    <option value="hedagaurav1378-event_ops-ops4@gmail.com">hedagaurav1378-event_ops-ops4@gmail.com</option>
                    <option value="hedagaurav1378-event_ops-ops5@gmail.com">hedagaurav1378-event_ops-ops5@gmail.com</option>
                    <option value="hedagaurav1378-event_ops-ops6@gmail.com">hedagaurav1378-event_ops-ops6@gmail.com</option>
                    <option value="hedagaurav1378-event_ops-ops7@gmail.com">hedagaurav1378-event_ops-ops7@gmail.com</option>
                    <option value="hedagaurav1378-event_ops-ops8@gmail.com">hedagaurav1378-event_ops-ops8@gmail.com</option>
                    <option value="hedagaurav1378-event_ops-ops9@gmail.com">hedagaurav1378-event_ops-ops9@gmail.com</option>
                    <option value="hedagaurav1378-event_ops-ops10@gmail.com">hedagaurav1378-event_ops-ops10@gmail.com</option>
                  </optgroup>
                  <optgroup label="Default Test Accounts">
                    <option value="gaurav@nominator.com">gaurav@nominator.com (Nominator)</option>
                    <option value="sunny@nominator.com">sunny@nominator.com (Nominator)</option>
                    <option value="gaurav@unitspoc.com">gaurav@unitspoc.com (Unit SPOC)</option>
                    <option value="gaurav@eventops.com">gaurav@eventops.com (Event OPS)</option>
                    <option value="gaurav@admin.com">gaurav@admin.com (Admin)</option>
                    <option value="suraj@superadmin.com">suraj@superadmin.com (Admin)</option>
                  </optgroup>
                </select>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                class="btn btn-primary mb-3 rounded-3 btn-sm bg-primary w-100">
                Login with email <i class="bi bi-arrow-right-short"></i>
              </button>
            </form>

            <!-- SSO divider layout -->
            <div class="d-flex align-items-center mb-5">
              <!-- Left Line -->
              <div class="flex-grow-1 border-top border-light-grey"></div>

              <!-- Centered Text -->
              <small class="px-3 text-light-grey fw-bold letter-spacing-1">SECURE SSO</small>

              <!-- Right Line -->
              <div class="flex-grow-1 border-top border-light-grey"></div>
            </div>

            <!-- Security Information -->
            <div class="d-flex p-3 gap-3">
              <i class="bi bi-shield-check text-light-grey"></i>
              <p class="fs-09 text-secondary">
                Your credentials are managed by Infosys IT. Whenever store
                your password authentication happens through your
                organisation's identity provider.
              </p>
            </div>
          </article>
        </div>
      </section>
    </div>
  </main>
</body>

</html>