
$(document).ready(function () {

  $("#signupForm").on("submit", function (e) {
    e.preventDefault();

    const FullName = $("#FullName").val().trim();
    const MobileNo = $("#MobileNo").val().trim();
    const Email = $("#Email").val().trim();
    const Gender = $("#Gender").val();
    const Password = $("#password").val().trim(); MobileNo
    const Remember = $("#remember").is(":checked");

    // ✅ BASIC VALIDATION
    if (!FullName || !Email || !Password || !MobileNo) {
      Swal.fire("Missing Fields", "All fields are required", "warning");
      return;
    }

    // ✅ EMAIL VALIDATION
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(Email)) {
      Swal.fire("Invalid Email", "Enter a valid email address", "error");
      return;
    }

    // ✅ PASSWORD LENGTH
    if (Password.length < 6) {
      Swal.fire("Weak Password", "Password must be at least 6 characters", "warning");
      return;
    }

    // ✅ GENDER VALIDATION
    if (Gender === "Choose a Gender") {
      Swal.fire("Select Gender", "Please select your gender", "info");
      return;
    }

    // ✅ TERMS CHECK
    if (!Remember) {
      Swal.fire("Terms Required", "You must agree to the terms", "warning");
      return;
    }

    // ✅ CREATE FORMDATA
    const formData = new FormData();
    formData.append("FullName", FullName);
    formData.append("MobileNo", MobileNo);
    formData.append("Email", Email);
    formData.append("Gender", Gender);
    formData.append("Password", Password);

    // ✅ SHOW LOADING
    Swal.fire({
      title: "Creating Account...",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    const apiLink = "api/?endpoint=registerUser";

    // ✅ jQuery AJAX CALL
    $.ajax({
      url: apiLink,
      type: "POST",
      data: formData,
      processData: false, // important for FormData
      contentType: false, // important for FormData
      success: function (response) {
        Swal.close();

        let data;
        try {
          data = typeof response === "string" ? JSON.parse(response) : response;
        } catch (e) {
          Swal.fire("Error", "Invalid server response", "error");
          return;
        }

        if (data.success === true || data.success === "Yes") {
          Swal.fire("Success", "Account created successfully!", "success").then(() => {
            const urlParams = new URLSearchParams(window.location.search);
            const redirectUrl = urlParams.get('redirect');
            if (redirectUrl) {
              window.location.href = 'login.php?redirect=' + encodeURIComponent(redirectUrl);
            } else {
              window.location.href = data.redirect || "login.php";
            }
          });
        } else {
          Swal.fire("Error", data.message || "Registration failed", "error");
        }
      },
      error: function (xhr, status, error) {
        Swal.close();
        let redirectTarget = "login.php";
        try {
          const errData = JSON.parse(xhr.responseText);
          if (errData.redirect) {
            redirectTarget = errData.redirect;
          }
        } catch (e) {}

        Swal.fire("Server Error", xhr.responseText || `${status}: ${error}`, "error").then(() => {
          const urlParams = new URLSearchParams(window.location.search);
          const redirectUrl = urlParams.get('redirect');
          if (redirectUrl) {
            window.location.href = 'login.php?redirect=' + encodeURIComponent(redirectUrl);
          } else {
            window.location.href = redirectTarget;
          }
        });
        console.error(xhr.responseText);
      }
    });

  });

});

