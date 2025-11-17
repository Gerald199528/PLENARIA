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

    // Solicitar confirmación antes de enviar
    document.getElementById('formularioDerechoPalabra').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        
        Swal.fire({
            title: "¿Deseas enviar tu solicitud?",
            html: "Por favor verifica que todos los datos sean correctos.",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí, enviar",
            denyButtonText: "Cancelar",
            allowOutsideClick: false,
            allowEscapeKey: false
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

    // Mostrar Toast de éxito después de procesar
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            Toast.fire({
                icon: "success",
                title: "{{ session('success') }}"
            });
            
            // Limpiar el formulario SOLO si es éxito
            document.getElementById('formularioDerechoPalabra').reset();
            const submitBtn = document.getElementById('formularioDerechoPalabra').querySelector('button[type="submit"]');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane text-white"></i> <span class="text-white">Enviar Solicitud</span>';
            document.getElementById('participacion').scrollIntoView({ behavior: 'smooth' });
        });
    @endif

    // Mostrar errores después de procesar
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
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
                html: '<ul style="text-align: left;">' + erroresServidor.map(e => '<li>' + e + '</li>').join('') + '</ul>',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#ef4444'
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