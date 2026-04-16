

  const blogForm = document.getElementById("blogForm");
  const titleInput = document.getElementById("title");
  const categoryInput = document.getElementById("category");
  const imageInput = document.getElementById("image");
  const quill = new Quill("#editor", { theme: "snow" });

  blogForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const Title = titleInput.value.trim();
    const CategoryID = categoryInput.value;
    const ContentHTML = quill.root.innerHTML;
    const ContentText = quill.getText().trim();
    const ImageFile = imageInput.files[0];

    // ✅ 1. VALIDATION
    if (!Title || !CategoryID || !ImageFile) {
      Swal.fire("Missing Fields", "All fields including image are required", "warning");
      return;
    }

    if (ContentText.length < 20) {
      Swal.fire("Content Required", "Blog content must be at least 20 characters", "warning");
      return;
    }

    // ✅ 2. PREPARE FORMDATA
    const formData = new FormData();
    formData.append("Title", Title);
    formData.append("CategoryID", CategoryID);
    formData.append("Content", ContentHTML);
    formData.append("Image", ImageFile);

    // ✅ 3. LOADING ALERT
    Swal.fire({
      title: "Publishing...",
      text: "Please wait",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    // ✅ 4. FETCH (same pattern as login.js)
    fetch("api/?endpoint=CreateBlog", {
      method: "POST",
      body: formData
    })
    .then(async (res) => {
      const text = await res.text();
      let data;

      try {
        data = JSON.parse(text);
      } catch (err) {
        Swal.fire("Server Error", "Invalid response:\n" + text, "error");
        return;
      }

      Swal.close();

      // ✅ 5. HANDLE RESPONSE
      if (data.success === "Yes") {
        Swal.fire({
          icon: "success",
          title: "Blog Published",
          text: data.message || "Blog created successfully",
          timer: 2000,
          showConfirmButton: false
        }).then(() => {
          if (data.redirect) {
            window.location.href = data.redirect;
          } else {
            blogForm.reset();
            quill.setContents([]);
          }
        });

      } else {
        Swal.fire("Failed", data.message || "Unable to create blog", "error");
      }
    })
    .catch((error) => {
      Swal.fire("Network Error", error.message, "error");
    });

  });


