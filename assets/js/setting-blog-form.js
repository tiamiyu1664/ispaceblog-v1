//  document.addEventListener("DOMContentLoaded", function() {
//       const quill = new Quill('#editor', {
//         theme: 'snow',
//         placeholder: 'Write your blog content here...',
//         modules: {
//           toolbar: [
//             [{
//               header: [1, 2, 3, false]
//             }],
//             ['bold', 'italic', 'underline'],
//             [{
//               list: 'ordered'
//             }, {
//               list: 'bullet'
//             }],
//             ['link', 'image'],
//             ['clean']
//           ]
//         }
//       });

//       // On submit, copy Quill content to hidden input
//       document.getElementById('blogForm').addEventListener('submit', function() {
//         document.getElementById('content').value = quill.root.innerHTML;
//       });

//       getData();
//       async function getData() {
//         const url = "http://localhost:8080/ispaceBlog/api/?endpoint=GetAllCategory";
//         try {
//           const response = await fetch(url);
//           if (!response.ok) {
//             throw new Error(`Response status: ${response.status}`);
//           }

//           const result = await response.json();
//           var data = Object.values(result.data);

//         //   console.log(data);
//         //   alert(data)
//           // return result;
//           const select = document.getElementById("categorySelect");

//           data.forEach(cat => {
//             const option = document.createElement("option");
//             option.value = cat;
//             option.textContent = cat;
//             select.appendChild(option);
//           });
//         } catch (error) {
//           console.error(error.message);
//         }
//       }


//     })