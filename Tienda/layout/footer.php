<script>
    function toggleDropdown() {
        var dropdown = document.getElementById('dropdownMenu');
        if (dropdown.style.display === "none") {
            dropdown.style.display = "block";
        } else {
            dropdown.style.display = "none";
        }
    }
</script>

<!-- Scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.querySelectorAll('.product-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(form);

        var sucursalDropdown = document.getElementById('sucursalDropdown');
        var mensajeSucursal = document.getElementById('mensaje-sucursal');
        
        if (sucursalDropdown.value === "") {
            mensajeSucursal.innerText = 'Selecciona una sucursal antes de agregar al carrito.';
            return; // Detiene el proceso de envío del formulario
        } else {
            mensajeSucursal.innerText = ''; // Borra el mensaje si una sucursal ha sido seleccionada
        }

        fetch('agregar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito o realizar alguna acción en la interfaz
                alert('Producto agregado al carrito.');
            } else {
                // Mostrar mensaje de error o realizar alguna acción en la interfaz
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Mostrar mensaje de error o realizar alguna acción en la interfaz
            alert('Ocurrió un error al agregar al carrito.');
        });
    });
});
</script>

</body>
</html>