
    <script>
        // 🔹 Configuración global del toast (notificaciones rápidas)
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

      

        // 🔹 Evento Buscar
        document.getElementById('btnBuscar').addEventListener('click', function(e) {
            e.preventDefault();

            const tipo = document.getElementById('selectTipo').value.trim();
            const anio = document.getElementById('selectAnio').value.trim();
            const search = document.getElementById('inputSearch').value.trim();

            // 🔸 Validar que tipo y año sean obligatorios
            if (!tipo || !anio) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos obligatorios',
                    html: 'Debes seleccionar <b>Tipo de Documento</b> y <b>Año</b> antes de buscar.',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#d33'
                });

                // Resaltar campos vacíos
                if (!tipo) document.getElementById('selectTipo').classList.add('border-red-500');
                if (!anio) document.getElementById('selectAnio').classList.add('border-red-500');
                return;
            }

            // Quitar color rojo si ya está correcto
            document.getElementById('selectTipo').classList.remove('border-red-500');
            document.getElementById('selectAnio').classList.remove('border-red-500');

            // Construir la URL con los filtros seleccionados
            const url = new URL(window.location.href);
            url.searchParams.set('tipo', tipo);
            url.searchParams.set('anio', anio);
            if (search) url.searchParams.set('search', search);

            // 🔸 Mostrar alerta de carga
            Swal.fire({
                title: 'Buscando...',
                html: 'Procesando tu búsqueda',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            // Redirigir después de un pequeño delay
            setTimeout(() => {
                window.location.href = url.toString();
            }, 400);
        });

        // 🔹 Evento Limpiar
        document.getElementById('btnLimpiar').addEventListener('click', function() {
            document.getElementById('selectTipo').value = '';
            document.getElementById('selectAnio').value = '';
            document.getElementById('inputSearch').value = '';

            Toast.fire({
                icon: 'info',
                title: 'Filtros limpiados'
            });

            setTimeout(() => {
                window.location.href = "{{ route('instrumentos_legales.index') }}";
            }, 800);
        });

        // 🔹 Al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const total = {{ $total ?? 0 }};
            const tieneFiltros = "{{ ($filtros['tipo'] ?? '') || ($filtros['anio'] ?? '') || ($filtros['search'] ?? '') ? 'true' : 'false' }}";

            // 🔸 Si no hay filtros, limpiar campos
            if (tieneFiltros === 'false') {
                document.getElementById('selectTipo').value = '';
                document.getElementById('selectAnio').value = '';
                document.getElementById('inputSearch').value = '';
            }

            // 🔸 Si hay filtros aplicados, mostrar resultado
            if (tieneFiltros === 'true') {
                if (total > 0) {
                    Toast.fire({
                        icon: 'success',
                        title: `Se encontraron ${total} documento${total > 1 ? 's' : ''}`
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin resultados',
                        html: 'No se encontraron documentos con los filtros aplicados.',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#d33'
                    });
                }
            }
        });
    </script> 

    
    <script>
      // 🔹 Si el usuario recarga la página (F5 o Ctrl+R), limpiar filtros
        if (performance.navigation.type === performance.navigation.TYPE_RELOAD) {
            window.location.href = "{{ route('instrumentos_legales.index') }}";
        }
</script>