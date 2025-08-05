const checkbox = document.getElementById('checkDefault');
const uploadBtn = document.getElementById('uploadBtn');
 checkbox.addEventListener('change', function () {
        uploadBtn.disabled = !this.checked;
    });