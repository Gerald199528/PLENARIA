<script>    
    // Configurar Toast
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    // Función para obtener estilos responsive
    function getResponsiveStyles() {
        const isMobile = window.innerWidth < 768;
        return {
            width: isMobile ? '90%' : '500px',
            padding: isMobile ? '25px' : '30px',
            fontSize: isMobile ? '18px' : '14px'
        };
    }

    // Validar formulario antes de enviar
    function validarFormulario(form) {
        const cedula = form.querySelector('input[name="cedula"]').value.trim();
        const nombre = form.querySelector('input[name="nombre"]').value.trim();
        const apellido = form.querySelector('input[name="apellido"]').value.trim();
        const email = form.querySelector('input[name="email"]').value.trim();
        const telefonoMovil = form.querySelector('input[name="telefono_movil"]').value.trim();
        const whatsapp = form.querySelector('input[name="whatsapp"]').value.trim();
        const sesionId = form.querySelector('select[name="sesion_municipal_id"]').value;
        const motivoSolicitud = form.querySelector('textarea[name="motivo_solicitud"]').value.trim();
        const aceptaTerminos = form.querySelector('input[name="acepta_terminos"]').checked;

        let errores = [];

        if (!cedula) errores.push('* La cédula es requerida');
        if (!nombre) errores.push('* El nombre es requerido');
        if (!apellido) errores.push('* El apellido es requerido');
        if (!email) errores.push('* El correo es requerido');
        if (!telefonoMovil) errores.push('* El teléfono móvil es requerido');
        if (!whatsapp) errores.push('* El WhatsApp es requerido');
        if (!sesionId) errores.push('* Debe seleccionar una sesión municipal');
        if (!motivoSolicitud) errores.push('* El motivo de solicitud es requerido');
        if (!aceptaTerminos) errores.push('* Debe aceptar los términos y condiciones');

        return errores;
    }

    // Solicitar confirmación antes de enviar
    document.getElementById('formularioDerechoPalabra').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const styles = getResponsiveStyles();

        // Validar antes de mostrar confirmación
        const errores = validarFormulario(form);
        
        if (errores.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: '¡Oops!',
                html: '<ul style="text-align: left; font-size: ' + (window.innerWidth < 768 ? '14px' : '15px') + '; line-height: 1.8;">' + errores.map(e => '<li class="mb-3">' + e + '</li>').join('') + '</ul>',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#f59e0b',
                width: styles.width,
                padding: styles.padding,
                customClass: {
                    title: 'text-lg sm:text-2xl font-bold',
                    htmlContainer: 'text-sm sm:text-base',
                    confirmButton: 'px-6 sm:px-8 py-2 sm:py-3 text-sm sm:text-base font-semibold',
                    popup: 'rounded-lg sm:rounded-xl'
                }
            });
            return;
        }
        
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
                // Mostrar spinner
                submitBtn.innerHTML = '<div class="spinner mr-2"></div> <span class="text-white">Enviando...</span>';
                submitBtn.disabled = true;
                
                // Enviar formulario después de 1.5s
                setTimeout(() => {
                    form.submit();
                }, 1500);
            }
        });
    });

    // Mostrar alertas cuando la página se recarga
    document.addEventListener('DOMContentLoaded', function() {
        Swal.close();
    });

    // Mostrar Toast de éxito después de procesar - DRAGGABLE y RESPONSIVE
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            const styles = getResponsiveStyles();
            
            Swal.fire({
                title: "¡Solicitud enviada exitosamente!",
                html: "{{ session('success') }}",
                icon: "success",
                draggable: true,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#10b981',
                width: styles.width,
                padding: styles.padding,
                customClass: {
                    title: 'text-lg sm:text-2xl font-bold',
                    htmlContainer: 'text-sm sm:text-base',
                    confirmButton: 'px-6 sm:px-8 py-2 sm:py-3 text-sm sm:text-base font-semibold',
                    popup: 'rounded-lg sm:rounded-xl'
                }
            }).then(() => {
                // Limpiar el formulario SOLO si es éxito
                document.getElementById('formularioDerechoPalabra').reset();
                const submitBtn = document.getElementById('formularioDerechoPalabra').querySelector('button[type="submit"]');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane text-white"></i> <span class="text-white">Enviar Solicitud</span>';
                document.getElementById('participacion').scrollIntoView({ behavior: 'smooth' });
            });
        });
    @endif

    // Mostrar errores después de procesar - RESPONSIVE
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            const styles = getResponsiveStyles();
            
            // Resetear el botón si hay errores
            const btnEnviar = document.getElementById('formularioDerechoPalabra').querySelector('button[type="submit"]');
            btnEnviar.disabled = false;
            btnEnviar.innerHTML = '<i class="fas fa-paper-plane text-white"></i> <span class="text-white">Enviar Solicitud</span>';
            
            let erroresServidor = [
                @foreach($errors->all() as $error)
                    '{{ $error }}',
                @endforeach
            ];
            
            Swal.fire({
                icon: 'error',
                title: '¡Oops!',
                html: '<ul style="text-align: left; font-size: ' + (window.innerWidth < 768 ? '17px' : '14px') + '; line-height: 1.8;">' + erroresServidor.map(e => '<li class="mb-3"><span style="color: #ef4444; font-weight: bold; font-size: ' + (window.innerWidth < 768 ? '20px' : '16px') + ';">*</span> ' + e + '</li>').join('') + '</ul>',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#ef4444',
                draggable: true,
                width: styles.width,
                padding: styles.padding,
                customClass: {
                    title: 'text-lg sm:text-2xl font-bold',
                    htmlContainer: 'text-sm sm:text-base',
                    confirmButton: 'px-6 sm:px-8 py-2 sm:py-3 text-sm sm:text-base font-semibold',
                    popup: 'rounded-lg sm:rounded-xl'
                }
            }).then(() => {
                document.getElementById('participacion').scrollIntoView({ behavior: 'smooth' });
            });
        });
    @endif
</script>
<script>
    // Al cargar la página, si hay sesion_id en localStorage, la selecciona
    document.addEventListener('DOMContentLoaded', function() {
        const sesionId = localStorage.getItem('sesion_id');
        if (sesionId) {
            document.getElementById('sesion_municipal_id').value = sesionId;
            localStorage.removeItem('sesion_id'); // Limpiar después de usar
        }
    });
</script>