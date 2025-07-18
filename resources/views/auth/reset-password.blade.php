<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đặt lại mật khẩu - Electro</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
    <style>
      body {
        font-family: 'Roboto', sans-serif;
        background-color: #1a1c23;
      }
      .auth-container {
        background-color: #24262d;
      }
      .logo {
        color: #e63946;
        font-family: 'Pacifico', serif;
      }
      .input-field {
        background-color: #f8f9fa;
        border: none;
        border-radius: 8px;
        padding: 12px 16px;
        width: 100%;
        color: #333;
      }
      .input-field:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.3);
      }
      .primary-btn {
        background-color: #dc2626;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px;
        width: 100%;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
      }
      .primary-btn:hover {
        background-color: #b91c1c;
      }
      .link {
        color: #dc2626;
        text-decoration: none;
        cursor: pointer;
      }
      .link:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body class="min-h-screen flex items-center justify-center p-4">
@if(session('reset_success'))
  <div id="reset-success-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg p-8 text-center max-w-xs">
      <h2 class="text-green-600 text-xl font-bold mb-4">Đặt lại mật khẩu thành công!</h2>
      <p class="mb-6 text-gray-700">Bạn đã đặt lại mật khẩu thành công.</p>
      <button id="close-modal-btn" class="primary-btn w-full">Đóng</button>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('close-modal-btn').onclick = function() {
        window.location.href = "{{ route('login') }}";
      };
    });
  </script>
@endif
    <div class="auth-container w-full max-w-md rounded-lg p-8 shadow-lg">
      <div class="text-center mb-6">
        <h1 class="logo text-3xl mb-3">Electro</h1>
        <p class="text-gray-300 text-sm">Đặt lại mật khẩu</p>
        <p class="text-gray-400 text-xs mt-2">
          Vui lòng nhập mật khẩu mới của bạn
        </p>
      </div>

      @if ($errors->any())
        <div class="mb-4 text-sm text-red-500">
          @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif
@if (session('status'))
  <div class="mb-4 text-green-500 text-sm">
    {{ session('status') }}
  </div>
@endif

      <form method="POST" action="{{ route('password.store') }}">
        @csrf
        {{-- @method('PUT') --}}

        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

        <div class="mb-4 relative">
          <input
            type="password"
            name="password"
            id="newPassword"
            class="input-field pr-10"
            placeholder="Mật khẩu mới"
            required
          />
          <button
            type="button"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
            data-password-toggle="newPassword"
          >
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ri-eye-off-line"></i>
            </div>
          </button>
          <div class="text-xs text-gray-400 mt-1">
            Mật khẩu phải có ít nhất 8 ký tự
          </div>
        </div>

        <div class="mb-6 relative">
          <input
            type="password"
            name="password_confirmation"
            id="confirmPassword"
            class="input-field pr-10"
            placeholder="Xác nhận mật khẩu mới"
            required
          />
          <button
            type="button"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
            data-password-toggle="confirmPassword"
          >
            <div class="w-6 h-6 flex items-center justify-center">
              <i class="ri-eye-off-line"></i>
            </div>
          </button>
          <div id="password-match-error" class="text-xs text-red-500 mt-1" style="display:none;"></div>
        </div>

        <div class="mb-6">
          <button type="submit" class="primary-btn !rounded-button">
            Xác nhận
          </button>
        </div>
        <div class="text-center text-sm">
          <span class="text-gray-400">Đã nhớ mật khẩu? </span>
          <a href="{{ route('login') }}" class="link">Đăng nhập</a>
        </div>
      </form>
    </div>


    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const toggleButtons = document.querySelectorAll("[data-password-toggle]");
        toggleButtons.forEach((button) => {
          button.addEventListener("click", function () {
            const inputId = this.getAttribute("data-password-toggle");
            const input = document.getElementById(inputId);
            const icon = this.querySelector("i");
            if (input.type === "password") {
              input.type = "text";
              icon.className = "ri-eye-line";
            } else {
              input.type = "password";
              icon.className = "ri-eye-off-line";
            }
          });
        });
        // Kiểm tra mật khẩu khớp nhau và hiển thị lỗi dưới ô xác nhận
        const form = document.querySelector('form');
        const pw = document.getElementById('newPassword');
        const pwc = document.getElementById('confirmPassword');
        const errorDiv = document.getElementById('password-match-error');

        form.addEventListener('submit', function(e) {
          if (pw.value !== pwc.value) {
            e.preventDefault();
            errorDiv.textContent = 'Mật khẩu xác nhận không khớp!';
            errorDiv.style.display = 'block';
            pwc.focus();
          } else {
            errorDiv.style.display = 'none';
          }
        });
        pwc.addEventListener('input', function() {
          if (pw.value === pwc.value) {
            errorDiv.style.display = 'none';
          }
        });
      });
    </script>
  </body>
</html>
