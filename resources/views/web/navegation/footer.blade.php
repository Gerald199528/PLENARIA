<!-- Footer -->
<footer class="bg-gray-800 text-gray-300 py-16">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-4 gap-8 mb-12">
            <!-- Sección Logo y Descripción -->
            <div data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="bg-primary p-2 rounded-lg">
                        <i class="fas fa-landmark text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">PLENARIA</h3>
                        <p class="text-gray-400 text-sm">Servicio y Transparencia</p>
                    </div>
                </div>
                <p class="mb-6 leading-relaxed text-justify text-sm">
                    "Con Plenaria, garantizamos el acceso digital simple e inmediato a todos los documentos legales y acuerdos de diferentes Concejos Municipales de Venezuela."
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i class="fab fa-facebook text-lg"></i></a>
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i class="fab fa-twitter text-lg"></i></a>
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i class="fab fa-linkedin text-lg"></i></a>
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors"><i class="fab fa-instagram text-lg"></i></a>
                </div>
            </div>

            <!-- Enlaces Rápidos -->
            <div data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-white font-bold text-lg mb-6">Enlaces Rápidos</h3>
                <ul class="space-y-3">
                    <li><a href="#inicio" class="hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-primary text-xs"></i>Inicio</a></li>
                    <li><a href="#nosotros" class="hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-primary text-xs"></i>Sobre Nosotros</a></li>
                    <li><a href="{{ route('instrumentos_legales.index') }}" class="hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-primary text-xs"></i>Documentos Legales</a></li>
                    <li><a href="#noticias" class="hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-primary text-xs"></i>Noticias</a></li>
                    <li><a href="#participacion" class="hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-primary text-xs"></i>Participación</a></li>
                </ul>
            </div>

            <!-- Información de Contacto -->
            <div data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-white font-bold text-lg mb-6">Contacto</h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt text-primary mr-3 mt-1 text-sm"></i>
                        <span class="text-sm">Dirección, Ciudad, Venezuela</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-phone text-primary mr-3 mt-1 text-sm"></i>
                        <a href="tel:+58" class="text-sm hover:text-white transition-colors">+584129765723</a>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-envelope text-primary mr-3 mt-1 text-sm"></i>
                        <a href="mailto:info@plenaria.gob.ve" class="text-sm hover:text-white transition-colors">info@plenaria.gob.ve</a>
                    </li>
                </ul>
            </div>

            <!-- Información Adicional -->
            <div data-aos="fade-up" data-aos-delay="400">
                <h3 class="text-white font-bold text-lg mb-6">Legal</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-sm hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-primary text-xs"></i>Términos de Servicio</a></li>
                    <li><a href="#" class="text-sm hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-primary text-xs"></i>Política de Privacidad</a></li>
                    <li><a href="#" class="text-sm hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-primary text-xs"></i>Cookies</a></li>
                    <li><a href="#" class="text-sm hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-primary text-xs"></i>Aviso Legal</a></li>
                </ul>
            </div>
        </div>

        <!-- Línea divisoria y pie de página -->
        <div class="border-t border-gray-700 pt-8 text-center" data-aos="fade-up" data-aos-delay="500">
            <p class="text-sm">&copy; 2025 PLENARIA - Todos los derechos reservados.</p>
            <p class="text-xs text-white mt-1">Desarrollado por <span class="font-semibold">NEXA 2.0</span></p>
        </div>
    </div>
</footer>