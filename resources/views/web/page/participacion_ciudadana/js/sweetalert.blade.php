<script>
// Configurar estilos responsive
function getResponsiveStyles() {
    const isMobile = window.innerWidth < 768;
    return {
        width: isMobile ? '90%' : '500px',
        padding: isMobile ? '25px' : '30px',
    };
}

// Solicitar confirmación antes de enviar
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const btnEnviar = document.querySelector('#btnEnviar');
    const btnOriginalHTML = btnEnviar ? btnEnviar.innerHTML : '';

    if (form && btnEnviar) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const styles = getResponsiveStyles();

            // Confirmación de envío
            Swal.fire({
                title: "¿Deseas enviar tu solicitud?",
                html: "Por favor verifica que todos los datos sean correctos.",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Sí, enviar",
                denyButtonText: "Cancelar",
                allowOutsideClick: false,
                allowEscapeKey: false,
                width: styles.width,
                padding: styles.padding,
                customClass: {
                    title: 'text-lg sm:text-xl font-bold',
                    htmlContainer: 'text-sm sm:text-base',
                    confirmButton: 'px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base',
                    denyButton: 'px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar spinner y enviar
                    btnEnviar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Enviando...</span>';
                    btnEnviar.disabled = true;

                    setTimeout(() => {
                        form.submit();
                    }, 500);
                } else {
                    // Al cancelar, restaurar el botón original
                    btnEnviar.innerHTML = btnOriginalHTML;
                    btnEnviar.disabled = false;
                }
            });
        });
    }
});

// Mostrar éxito
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        const styles = getResponsiveStyles();

        Swal.fire({
            title: "¡Solicitud enviada exitosamente!",
            html: "{{ session('success') }}",
            icon: "success",
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#10b981',
            width: styles.width,
            padding: styles.padding,
            customClass: {
                title: 'text-lg sm:text-2xl font-bold',
                htmlContainer: 'text-sm sm:text-base',
                confirmButton: 'px-6 sm:px-8 py-2 sm:py-3 text-sm sm:text-base font-semibold',
                popup: 'rounded-lg sm:rounded-xl'
            },
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(() => {
            // Redirigir a la ruta de participación
            window.location.href = '{{ route("home") }}#participacion';
        });
    });
@endif

// Mostrar errores del servidor (validación del controlador)
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        const styles = getResponsiveStyles();
        const btnEnviar = document.querySelector('#btnEnviar');
        const btnOriginalHTML = btnEnviar ? btnEnviar.innerHTML : '';

        // Resetear botón
        if (btnEnviar) {
            btnEnviar.disabled = false;
            btnEnviar.innerHTML = '<i class="fas fa-paper-plane"></i> <span>Enviar Solicitud</span>';
        }

        let errores = @json($errors->all());

        Swal.fire({
            icon: 'error',
            title: '¡Errores de validación!',
            html: '<ul style="text-align: left; line-height: 1.8;">' +
                errores.map(e => '<li style="margin: 8px 0;">• ' + e + '</li>').join('') +
                '</ul>',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#ef4444',
            width: styles.width,
            padding: styles.padding,
            customClass: {
                title: 'text-lg sm:text-2xl font-bold',
                htmlContainer: 'text-sm sm:text-base',
                confirmButton: 'px-6 sm:px-8 py-2 sm:py-3 text-sm sm:text-base font-semibold',
                popup: 'rounded-lg sm:rounded-xl'
            },
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(() => {
            // Redirigir a la ruta de participación después de cerrar la alerta de errores
            window.location.href = '{{ route("home") }}#participacion';
        });
    });
@endif

// Seleccionar sesión desde localStorage si existe
document.addEventListener('DOMContentLoaded', function() {
    const sesionId = localStorage.getItem('sesion_id');
    if (sesionId) {
        const selectSesion = document.querySelector('select[name="sesion_municipal_id"]');
        if (selectSesion) {
            selectSesion.value = sesionId;
            localStorage.removeItem('sesion_id');
        }
    }
});

// Al cargar la página, si hay sesion_id en localStorage, la selecciona
document.addEventListener('DOMContentLoaded', function() {
    const sesionId = localStorage.getItem('sesion_id');
    if (sesionId) {
        document.getElementById('sesion_municipal_id').value = sesionId;
        localStorage.removeItem('sesion_id'); // Limpiar después de usar
    }
});
</script>
