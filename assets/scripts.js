alert = document.getElementById('alert');
setTimeout(function () {
    if (alert) {
        alert.style.display = 'none';
        alert.style.height = '35px';}
}, 3000);

document.querySelector('input[name="reset"]').addEventListener('click', function() {
    // riaggiorna pagina in caso di reset ed azzera i metodi
    document.querySelector('form').reset();
    var options = document.querySelectorAll('option[selected]');
    for (var i = 0; i < options.length; i++) {
        options[i].removeAttribute('selected');
    }
    window.location.reload();
});