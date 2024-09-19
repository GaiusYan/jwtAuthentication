document.getElementById('uploadForm').addEventListener('submit', (e) => {
    e.preventDefault();

    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0]
    console.log(file)
})