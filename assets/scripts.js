alert = document.getElementById('alert');
setTimeout(function () {
    if (alert) {
        alert.style.display = 'none';
        alert.style.height = '35px';}
}, 3000);

const resetButton = document.querySelector('input[name="reset"]');
if (resetButton) {
    resetButton.addEventListener('click', function() {
        // riaggiorna pagina in caso di reset ed azzera i metodi
        document.querySelector('form').reset();
        var options = document.querySelectorAll('option[selected]');
        for (var i = 0; i < options.length; i++) {
            options[i].removeAttribute('selected');
        }
        window.location.reload();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const installmentCheckbox = document.getElementById('installmentCheckbox');
    const installmentEndDateContainer = document.getElementById('installmentEndDateContainer');
    
    if (installmentCheckbox && installmentEndDateContainer) {
        installmentCheckbox.addEventListener('change', function() {
            if (this.checked) {
                installmentEndDateContainer.style.display = 'block';
            } else {
                installmentEndDateContainer.style.display = 'none';
                document.getElementById('installmentenddate').value = ''; 
            }
        });
    }
});

// Funzione per gestire il cambio di elementi per pagina
function changeItemsPerPage(value) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('itemsPerPage', value);
    urlParams.delete('page'); // Reset alla prima pagina
    window.location.search = urlParams.toString();
}