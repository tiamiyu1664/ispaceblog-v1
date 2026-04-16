
document.addEventListener("DOMContentLoaded", function () {

  const loginForm = document.getElementById("LoginForm");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");

  loginForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const Email = emailInput.value.trim();
    const Password = passwordInput.value.trim();

    // ✅ 1. BASIC VALIDATION
    if (!Email || !Password) {
      Swal.fire("Missing Fields", "Both email and password are required", "warning");
      return;
    }

    // ✅ 2. EMAIL FORMAT VALIDATION
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(Email)) {
      Swal.fire("Invalid Email", "Enter a valid email address", "error");
      return;
    }

    // ✅ 3. PASSWORD LENGTH CHECK
    if (Password.length < 6) {
      Swal.fire("Weak Password", "Password must be at least 6 characters", "warning");
      return;
    }

    // ✅ 4. CREATE FORMDATA
    const formData = new FormData();
    formData.append("Email", Email);
    formData.append("Password", Password);

    // ✅ 5. SEND WITH FETCH
    fetch("api/?endpoint=LoginUser", {
      method: "POST",
      body: formData
    })
    .then(async (res) => {
      const text = await res.text();
      let data;

      try {
        data = JSON.parse(text);
      } catch (err) {
        Swal.fire("Server Error", "Invalid server response:\n" + text, "error");
        return;
      }

      // ✅ 6. HANDLE RESPONSE
      if (data.success === "Yes") {
        Swal.fire({
          icon: "success",
          title: "Success",
          text: data.message || "Login successful",
          timer: 2000,
          showConfirmButton: false
        }).then(() => {
          if (data.redirect) {
            window.location.href = data.redirect;
          }
        });

      } else {
        Swal.fire("Login Failed", data.message || "Invalid credentials", "error");
      }
    })
    .catch((error) => {
      Swal.fire("Network Error", error.message, "error");
    });

  });

});

