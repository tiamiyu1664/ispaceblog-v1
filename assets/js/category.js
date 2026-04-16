document.addEventListener('DOMContentLoaded',function(){
    const myForm = document.querySelector('#categoryForm');
    myForm.addEventListener('submit', function(e){
        e.preventDefault();
        const Category = document.querySelector('#Category').value.trim();
        if(!Category){
              Swal.fire("Missing Fields", "Enter The category", "warning");
                return;
        }

        const formData = new FormData();
        formData.append('Category', Category);
        fetch('../api/?endpoint=CreateCategory', {
            method: "POST",
            body: formData
        }).then(async (res) => {
           const text = await res.text();
           let data;
           try{
            data = JSON.parse(text)
           }catch(err){
            Swal.fire("Server Error", "Invalid server response:\n"+text, "error");
            return;
           }
           if(data.success == "Yes"){
            Swal.fire({
                icon: "Success",
                title: "Success",
                text: data.message || "Category Created Successfully",
                timer: 2000,
                showConfirmButton: false
            }).then(()=>{
                if(data.redirect){
                    window.location.href = data.redirect
                }
            })
           }
            
        })
    })
})