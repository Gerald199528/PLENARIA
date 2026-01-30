
     <script>
        // script copair enlace 
        document.addEventListener('DOMContentLoaded', function() {
            const copyBtn = document.getElementById('copyLinkBtn');
            const copyMsg = document.getElementById('copyMsg');
            const copyIcon = document.getElementById('copyIcon');
            const checkIcon = document.getElementById('checkIcon');
            const link = "{{ Request::url() }}";

            copyBtn.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(link);

                    // Animación mejorada
                    copyIcon.classList.add('opacity-0', 'scale-0');
                    checkIcon.classList.remove('opacity-0', 'scale-0');
                    copyBtn.disabled = true;
                    copyMsg.classList.remove('hidden');

                  
                    setTimeout(() => {
                        checkIcon.classList.add('opacity-0', 'scale-0');
                        copyIcon.classList.remove('opacity-0', 'scale-0');
                        copyBtn.disabled = false;
                        copyMsg.classList.add('hidden');
                    }, 3000);
                } catch (err) {
                    console.error('Error al copiar:', err);
                    copyMsg.textContent = '❌ Error al copiar el enlace';
                    copyMsg.classList.remove('hidden', 'text-green-600', 'bg-green-50', 'border-green-200');
                    copyMsg.classList.add('text-red-600', 'bg-red-50', 'border-red-200');
                    
                    setTimeout(() => {
                        copyMsg.classList.add('hidden');
                    }, 3000);
                }
            });
        });
        </script>
