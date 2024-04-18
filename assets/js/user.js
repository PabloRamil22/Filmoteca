
// Función para mostrar la puntuación seleccionada en el recuadro
document.addEventListener("DOMContentLoaded", function() {
    const select = document.getElementById('puntuacion');
    const puntuacionSeleccionada = document.getElementById('puntuacion_seleccionada');

    select.addEventListener('change', function() {
        puntuacionSeleccionada.textContent = select.value;
    });
});
