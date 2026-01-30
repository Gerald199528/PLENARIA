<!-- Leaflet CSS y JS -->
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />

<!-- Leaflet JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<!-- Leaflet MarkerCluster CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.1/MarkerCluster.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.1/MarkerCluster.Default.min.css" />

<!-- Leaflet MarkerCluster JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.1/leaflet.markercluster.js"></script>

<!-- Alternativa: Usar Google Maps API directamente (sin GoogleMutant) -->
<script src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY_AQUI"></script>

<!-- Sección de Localidad -->
<section id="localidad" class="py-8 md:py-16 lg:py-20 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 text-white relative overflow-hidden">
    <!-- Elementos decorativos mejorados -->
    <div class="absolute top-0 left-0 w-48 md:w-96 h-48 md:h-96 bg-gradient-to-br from-primary to-transparent opacity-20 rounded-full -translate-x-48 -translate-y-48 animate-pulse"></div>
    <div class="absolute bottom-0 right-0 w-40 md:w-80 h-40 md:h-80 bg-gradient-to-tl from-secondary to-transparent opacity-20 rounded-full translate-x-40 translate-y-40 animate-pulse" style="animation-delay: 1s;"></div>
    <div class="hidden md:block absolute top-1/2 left-1/4 w-64 h-64 bg-gradient-to-r from-accent to-transparent opacity-10 rounded-full blur-3xl"></div>
    
    <style>
        @keyframes slideInFromBottom {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInFromLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-in-bottom {
            animation: slideInFromBottom 0.8s ease-out forwards;
        }

        .animate-slide-in-left {
            animation: slideInFromLeft 0.8s ease-out forwards;
        }

        .contact-card {
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            transform: translateX(10px);
            background: rgba(255, 255, 255, 0.05);
        }

        .icon-box {
            transition: all 0.3s ease;
            min-width: 48px;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .contact-card:hover .icon-box {
            transform: scale(1.2) rotate(5deg);
            background: linear-gradient(135deg, #3b82f6, #a855f7);
        }

        #map {
            z-index: 1;
            border-radius: 1rem;
        }

        /* Estilos para popup de Google Maps */
        .leaflet-popup-content-wrapper {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 7px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .leaflet-popup-content {
            margin: 0;
            font-family: Roboto, Arial, sans-serif;
            font-size: 13px;
            color: #202124;
        }

        .leaflet-popup-tip {
            background-color: white;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .leaflet-popup-content p {
            margin: 4px 0;
        }

        .leaflet-popup-content b {
            color: #1f2937;
            font-size: 14px;
            display: block;
            margin-bottom: 6px;
        }

        .leaflet-popup-content a {
            color: #1e40af;
            text-decoration: none;
        }

        /* Zoom controls - estilo Google Maps */
        .leaflet-control-zoom {
            border-radius: 2px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
            background: white;
        }

        .leaflet-control-zoom a {
            background-color: white;
            border: 1px solid #dadce0;
            color: #3c4043;
            font-weight: 500;
            font-size: 16px;
            width: 36px !important;
            height: 36px !important;
        }

        .leaflet-control-zoom a:hover {
            background-color: #f8f9fa;
            color: #202124;
        }

        .leaflet-control-zoom-in, .leaflet-control-zoom-out {
            border: 1px solid #dadce0 !important;
        }

        /* Attribution - discreto */
        .leaflet-control-attribution {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 2px;
            font-size: 10px;
        }

        .leaflet-control-attribution a {
            color: #1e40af;
        }

        /* Info Box personalizado */
        .map-info-box {
            background: white;
            padding: 12px 16px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            position: absolute;
            bottom: 80px;
            left: 12px;
            z-index: 999;
            font-family: Roboto, Arial, sans-serif;
            max-width: 280px;
        }

        .map-info-box.hidden {
            display: none;
        }

        .map-info-title {
            font-weight: 600;
            color: #202124;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .map-info-text {
            color: #5f6368;
            font-size: 12px;
            line-height: 1.4;
        }

        /* Marker personalizado - estilo Google Maps */
        .custom-marker-icon {
            background: white;
            border: 3px solid #ea4335;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
            font-size: 18px;
            color: #ea4335;
        }

        /* Tooltip mejorado */
        .leaflet-tooltip {
            background: white;
            border: 1px solid #dadce0;
            color: #202124;
            border-radius: 4px;
            padding: 8px;
            font-family: Roboto, Arial, sans-serif;
            font-size: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        }

        /* Layer Control Personalizado */
        .leaflet-control-layers {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border: 1px solid #dadce0;
        }

        .leaflet-control-layers-toggle {
            background: white;
            border-radius: 4px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
            border: 1px solid #dadce0;
        }

        .leaflet-control-layers-toggle:hover {
            background-color: #f8f9fa;
        }

        .leaflet-control-layers-list {
            padding: 8px;
        }

        .leaflet-control-layers-selector {
            margin-right: 8px;
            cursor: pointer;
        }

        .leaflet-control-layers label {
            font-family: Roboto, Arial, sans-serif;
            font-size: 12px;
            color: #202124;
            margin-bottom: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .leaflet-control-layers label:hover {
            color: #1f2937;
        }

        /* Layer buttons personalizados */
        .map-layer-controls {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 999;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .map-layer-btn {
            background: white;
            border: 1px solid #dadce0;
            border-radius: 4px;
            padding: 8px 10px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            color: #3c4043;
            transition: all 0.3s ease;
            font-family: Roboto, Arial, sans-serif;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
            white-space: nowrap;
        }

        .map-layer-btn:hover {
            background: #f8f9fa;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        .map-layer-btn.active {
            background: #1e40af;
            color: white;
            border-color: #1e40af;
        }

        .map-layer-btn.active:hover {
            background: #1d3a9e;
            box-shadow: 0 2px 8px rgba(30, 64, 175, 0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .map-layer-btn {
                padding: 6px 8px;
                font-size: 11px;
            }

            .map-info-box {
                max-width: 220px;
                bottom: 60px;
                padding: 10px 12px;
            }

            .icon-box {
                min-width: 40px;
                width: 40px;
                height: 40px;
            }

            .contact-card:hover {
                transform: translateX(5px);
            }
        }

        @media (max-width: 640px) {
            .map-layer-btn i {
                display: none;
            }

            .map-info-box {
                max-width: 180px;
                padding: 8px 10px;
            }

            .map-info-title {
                font-size: 12px;
            }

            .map-info-text {
                font-size: 11px;
            }
        }
    </style>
    
    <div class="container mx-auto px-3 sm:px-4 md:px-6 relative z-10">
        <div class="text-center mb-8 md:mb-12 lg:mb-16" data-aos="fade-up">
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-3 md:mb-4 text-white">
                Localidad
            </h2>
            <div class="w-16 md:w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto mb-4 md:mb-6"></div>
            <p class="text-gray-300 text-sm sm:text-base md:text-lg max-w-3xl mx-auto px-2">
                Estamos aquí para servirle. Contáctenos para consultas, sugerencias o cualquier información que necesite
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 lg:gap-12">
            <!-- Información de Contacto -->
            <div class="lg:col-span-1" data-aos="fade-right">
                <div class="space-y-4 md:space-y-6">
                    <!-- Ubicación -->
                    <div class="contact-card bg-gradient-to-r from-gray-800 to-gray-700 p-4 md:p-6 rounded-xl md:rounded-2xl border border-gray-700 hover:border-primary/50 backdrop-blur-sm">
                        <div class="flex items-start space-x-3 md:space-x-4">
                            <div class="icon-box bg-gradient-to-br from-primary to-blue-600 rounded-lg flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-lg md:text-xl text-white"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-base md:text-xl mb-1 md:mb-2 text-white">Ubicación</h3>
                                <p class="text-gray-300 leading-relaxed text-xs md:text-sm break-words">
                                    @if($empresa && $empresa->direccion_fiscal)
                                        {{ $empresa->direccion_fiscal }}<br>
                                        @if($empresa->oficina_principal)
                                            {{ $empresa->oficina_principal }}<br>
                                        @endif
                                    @else
                                        Av. Principal, Centro Municipal<br>
                                        Edificio del Concejo, Piso 2<br>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div class="contact-card bg-gradient-to-r from-gray-800 to-gray-700 p-4 md:p-6 rounded-xl md:rounded-2xl border border-gray-700 hover:border-secondary/50 backdrop-blur-sm">
                        <div class="flex items-start space-x-3 md:space-x-4">
                            <div class="icon-box bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex-shrink-0">
                                <i class="fas fa-phone text-lg md:text-xl text-white"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-base md:text-xl mb-1 md:mb-2 text-white">Teléfonos</h3>
                                <p class="text-gray-300 leading-relaxed text-xs md:text-sm break-all">
                                    @if($empresa && $empresa->telefono_principal)
                                        {{ $empresa->telefono_principal }}<br>
                                    @endif
                                    @if($empresa && $empresa->telefono_secundario)
                                        {{ $empresa->telefono_secundario }}
                                    @else
                                        +58 212 555-1234<br>
                                        +58 212 555-5678 (Fax)
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="contact-card bg-gradient-to-r from-gray-800 to-gray-700 p-4 md:p-6 rounded-xl md:rounded-2xl border border-gray-700 hover:border-accent/50 backdrop-blur-sm">
                        <div class="flex items-start space-x-3 md:space-x-4">
                            <div class="icon-box bg-gradient-to-br from-red-500 to-pink-600 rounded-lg flex-shrink-0">
                                <i class="fas fa-envelope text-lg md:text-xl text-white"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-base md:text-xl mb-1 md:mb-2 text-white">Correo Electrónico</h3>
                                <p class="text-gray-300 leading-relaxed text-xs md:text-sm break-all">
                                    @if($empresa && $empresa->email_principal)
                                        <a href="mailto:{{ $empresa->email_principal }}" class="hover:text-primary transition-colors">{{ $empresa->email_principal }}</a><br>
                                    @endif
                                    @if($empresa && $empresa->email_secundario)
                                        <a href="mailto:{{ $empresa->email_secundario }}" class="hover:text-primary transition-colors">{{ $empresa->email_secundario }}</a>
                                    @else
                                        <a href="mailto:info@concejomunicipal.gob.ve" class="hover:text-primary transition-colors">info@concejomunicipal.gob.ve</a><br>
                                        <a href="mailto:secretaria@concejomunicipal.gob.ve" class="hover:text-primary transition-colors">secretaria@concejomunicipal.gob.ve</a>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Horario -->
                    <div class="contact-card bg-gradient-to-r from-gray-800 to-gray-700 p-4 md:p-6 rounded-xl md:rounded-2xl border border-gray-700 hover:border-yellow-500/50 backdrop-blur-sm">
                        <div class="flex items-start space-x-3 md:space-x-4">
                            <div class="icon-box bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg flex-shrink-0">
                                <i class="fas fa-clock text-lg md:text-xl text-white"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-base md:text-xl mb-1 md:mb-2 text-white">Horario de Atención</h3>
                                <p class="text-gray-300 leading-relaxed text-xs md:text-sm">
                                    @if($empresa && $empresa->horario_atencion)
                                        {{ $empresa->horario_atencion }}
                                    @else
                                        <span class="font-semibold">Lunes a Viernes:</span> 8:00 AM - 4:00 PM<br>
                                        <span class="font-semibold">Sábados:</span> 8:00 AM - 12:00 PM
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mapa -->
            <div class="lg:col-span-2" data-aos="fade-left" data-aos-delay="200">
                <div class="bg-white rounded-xl md:rounded-2xl overflow-hidden shadow-2xl h-full">
                    <div class="relative w-full min-h-96 md:min-h-[500px] lg:min-h-[700px]">
                        @if($empresa && $empresa->latitud && $empresa->longitud)
                            <div id="map" class="w-full h-full min-h-96 md:min-h-[500px] lg:min-h-[700px]"></div>
                            <div id="mapInfoBox" class="map-info-box hidden">
                                <div class="map-info-title">{{ $empresa->name }}</div>
                                <div class="map-info-text">{{ $empresa->direccion_fiscal }}</div>
                            </div>
                            <div class="map-layer-controls">
                                <button class="map-layer-btn active" id="satelliteBtn" onclick="switchToSatellite()">
                                    <i class="fas fa-satellite"></i> <span class="hidden sm:inline">Satélite</span>
                                </button>
                                <button class="map-layer-btn" id="mapBtn" onclick="switchToMap()">
                                    <i class="fas fa-map"></i> <span class="hidden sm:inline">Mapa</span>
                                </button>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const lat = {{ $empresa->latitud }};
                                    const lng = {{ $empresa->longitud }};
                                    const empresaName = "{{ $empresa->name }}";
                                    const empresaDireccion = "{{ $empresa->direccion_fiscal }}";
                                    
                                    // Crear mapa
                                    const map = L.map('map', {
                                        zoomControl: true,
                                        attributionControl: true
                                    }).setView([lat, lng], 16);
                                    
                                    // Capa OSM (Mapa normal)
                                    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        maxZoom: 19,
                                        attribution: '© OpenStreetMap',
                                        style: 'light'
                                    });
                                    
                                    // Capa Satélite (Esri World Imagery) - con zoom aumentado
                                    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                                        maxZoom: 28,
                                        maxNativeZoom: 18,
                                        attribution: '© Esri',
                                        className: 'satellite-layer'
                                    });
                                    
                                    // Por defecto: Satélite
                                    satelliteLayer.addTo(map);
                                    let currentLayer = 'satellite';
                                    
                                    // Crear icono personalizado tipo Google Maps
                                    const googleMapIcon = L.divIcon({
                                        className: 'custom-marker-icon',
                                        html: '<i class="fas fa-map-marker-alt"></i>',
                                        iconSize: [32, 32],
                                        iconAnchor: [16, 32],
                                        popupAnchor: [0, -32]
                                    });
                                    
                                    // Crear marcador
                                    const marker = L.marker([lat, lng], {
                                        icon: googleMapIcon,
                                        title: empresaName
                                    }).addTo(map);
                                    
                                    // Popup con contenido
                                    const popupContent = `<b>${empresaName}</b><br><span>${empresaDireccion}</span>`;
                                    marker.bindPopup(popupContent, {
                                        maxWidth: 300,
                                        className: 'custom-popup'
                                    });
                                    
                                    // Abrir popup al cargar
                                    marker.openPopup();
                                    
                                    // Mostrar info box al hacer hover en el marker
                                    marker.on('mouseover', function() {
                                        document.getElementById('mapInfoBox').classList.remove('hidden');
                                    });
                                    
                                    marker.on('mouseout', function() {
                                        if (!marker.isPopupOpen()) {
                                            document.getElementById('mapInfoBox').classList.add('hidden');
                                        }
                                    });
                                    
                                    // Evitar zoom excesivo
                                    map.setMinZoom(3);
                                    map.setMaxZoom(28);
                                    
                                    // Redimensionar mapa si es necesario
                                    setTimeout(function() {
                                        map.invalidateSize();
                                    }, 100);
                                    
                                    // Funciones globales para cambiar capas
                                    window.switchToSatellite = function() {
                                        if (currentLayer !== 'satellite') {
                                            map.removeLayer(osmLayer);
                                            satelliteLayer.addTo(map);
                                            currentLayer = 'satellite';
                                            
                                            document.getElementById('satelliteBtn').classList.add('active');
                                            document.getElementById('mapBtn').classList.remove('active');
                                        }
                                    };
                                    
                                    window.switchToMap = function() {
                                        if (currentLayer !== 'osm') {
                                            map.removeLayer(satelliteLayer);
                                            osmLayer.addTo(map);
                                            currentLayer = 'osm';
                                            
                                            document.getElementById('mapBtn').classList.add('active');
                                            document.getElementById('satelliteBtn').classList.remove('active');
                                        }
                                    };
                                });
                            </script>
                        @else
                            <div class="w-full h-full min-h-96 md:min-h-[500px] lg:min-h-[700px] flex items-center justify-center bg-gray-200">
                                <div class="text-center text-gray-600 px-4">
                                    <i class="fas fa-map-marker-alt text-3xl md:text-4xl mb-4"></i>
                                    <p class="text-base md:text-lg font-semibold">Ubicación no registrada</p>
                                    <p class="text-xs md:text-sm">Los datos de ubicación serán mostrados aquí cuando se registren</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>