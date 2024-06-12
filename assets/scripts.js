alert = document.getElementById('alert');
setTimeout(function () {
    alert.style.display = 'none';
    alert.style.height = '35px';
}, 3000);

document.querySelector('input[name="reset"]').addEventListener('click', function() {
    document.querySelector('form').reset();
});