<!-- Leaflet -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>
// ====================================================================
// 1. ESTADO GLOBAL
// ====================================================================
var map = null;
var marker = null;
var selectedCoords = null;
var selectedAddress = null;
var osmLayer = null;
var satelliteLayer = null;
var currentLayer = 'osm';

// ====================================================================
// 2. FUNCIONES DE MODAL
// ====================================================================

function openMapModal(){
    const modal = document.getElementById('default-modal');
    modal.classList.remove('hidden'); 
    modal.classList.add('flex');

    destroyMap();

    // Esperar a que el modal esté visible antes de inicializar
    setTimeout(() => {
        initMap();
    }, 300); 
}

function closeMapModal(){
    const modal = document.getElementById('default-modal');
    modal.classList.add('hidden'); 
    modal.classList.remove('flex');
    destroyMap();
    
    marker = selectedCoords = selectedAddress = null;
}

function destroyMap(){
    if(map) {
        map.off(); 
        map.remove(); 
    }
    
    map = null;
    osmLayer = null;
    satelliteLayer = null;
    
    const mapContainer = document.getElementById('map');
    if(mapContainer) {
        mapContainer.innerHTML = '<div id="map-loading" class="absolute inset-0 flex items-center justify-center bg-gray-200 dark:bg-gray-600 z-10 rounded-lg"><span class="text-sm md:text-base">Cargando mapa...</span></div>';
    }
}

// ====================================================================
// 3. INICIALIZACIÓN DEL MAPA
// ====================================================================

function initMap(){
    const mapContainer = document.getElementById('map');
    if(!mapContainer) {
        console.error('Contenedor del mapa no encontrado');
        return;
    }

    // Venezuela: centro aproximado
    const initialLat = 6.5;
    const initialLng = -66.5;
    const initialZoom = 7;

    try {
        // Crear el mapa
        map = L.map('map', {
            zoomControl: true, 
            attributionControl: true
        }).setView([initialLat, initialLng], initialZoom);

        // Capa OSM mejorada con más detalle (CartoDB Positron)
        osmLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 20,
            minZoom: 1,
            attribution: '© CartoDB contributors',
            crossOrigin: 'anonymous'
        }).addTo(map);

        // Capa satélite (oculta por defecto)
        satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18, 
            attribution: '© Esri',
            crossOrigin: 'anonymous'
        });

        console.log('✓ Mapa inicializado correctamente');

        // Eventos del mapa
        map.on('load', () => {
            console.log('✓ Mapa cargado');
            const loadingDiv = document.getElementById('map-loading');
            if(loadingDiv) loadingDiv.style.display = 'none';
        });

        map.on('click', (e) => {
            placeMarker(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        // Forzar redimensionamiento del mapa (importante)
        map.invalidateSize();
        
        // Ocultar loading después de que el mapa esté visible
        setTimeout(() => {
            const loadingDiv = document.getElementById('map-loading');
            if(loadingDiv) loadingDiv.style.display = 'none';
        }, 1000);

        // Intentar obtener ubicación del usuario después de que el mapa esté listo
        setTimeout(() => {
            centerOnUserLocation();
        }, 500);

    } catch (error) {
        console.error('Error al inicializar el mapa:', error);
        alert('Error al cargar el mapa. Intenta recargar la página.');
    }
}

// ====================================================================
// 4. FUNCIONES DE MAPA
// ====================================================================

function centerOnUserLocation(){
    if(!navigator.geolocation || !map) {
        console.warn('Geolocalización no disponible');
        return;
    }

    const locationButton = document.querySelector('button[onclick*="centerOnUserLocation"]');
    
    if (locationButton) {
        locationButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span class="hidden sm:inline">Buscando...</span>';
        locationButton.disabled = true;
    }

    const geoOptions = {
        maximumAge: 30000,
        timeout: 8000,
        enableHighAccuracy: false
    };

    const success = (pos) => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        
        map.setView([lat, lng], 16);
        placeMarker(lat, lng);
        reverseGeocode(lat, lng);
        
        if (locationButton) {
            locationButton.innerHTML = '<i class="fa-solid fa-location-arrow"></i> <span class="hidden sm:inline">Mi Ubicación</span>';
            locationButton.disabled = false;
        }
    };

    const error = (err) => {
        let message = 'No se pudo obtener tu ubicación. Asegúrate de que la geolocalización esté permitida.';
        if (err.code === err.TIMEOUT) {
            message = 'La búsqueda de ubicación tardó demasiado. Intenta nuevamente.';
        }
        console.warn('Geolocation error:', message);
        
        if (locationButton) {
            locationButton.innerHTML = '<i class="fa-solid fa-location-arrow"></i> <span class="hidden sm:inline">Mi Ubicación</span>';
            locationButton.disabled = false;
        }
    };

    navigator.geolocation.getCurrentPosition(success, error, geoOptions);
}

function switchMapLayer(layer){
    if(!map) return;
    
    if(layer === 'satellite'){
        if(map.hasLayer(osmLayer)) map.removeLayer(osmLayer);
        if(!map.hasLayer(satelliteLayer)) map.addLayer(satelliteLayer);
        currentLayer = 'satellite';
    } else {
        if(map.hasLayer(satelliteLayer)) map.removeLayer(satelliteLayer);
        if(!map.hasLayer(osmLayer)) map.addLayer(osmLayer);
        currentLayer = 'osm';
    }
}

function placeMarker(lat, lng){
    if(marker) map.removeLayer(marker);
    
    marker = L.marker([lat, lng], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map);
    
    selectedCoords = {lat, lng};
    document.getElementById('coordsInfo').innerHTML = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
}

function reverseGeocode(lat, lng){
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
    .then(r => r.json())
    .then(data => {
        let addressComponents = [];
        const address = data.address;

        if (address.road) addressComponents.push(address.road);
        if (address.house_number) addressComponents.push(address.house_number);
        if (address.building) addressComponents.push(address.building);
        if (address.neighbourhood) addressComponents.push(address.neighbourhood);
        if (address.suburb) addressComponents.push(address.suburb);
        if (address.city || address.town) {
            addressComponents.push(address.city || address.town);
        } else if (address.village) {
            addressComponents.push(address.village);
        }

        if (addressComponents.length === 0) {
            selectedAddress = data.display_name || 'Ubicación seleccionada';
        } else {
            selectedAddress = addressComponents.join(', ');
        }
        
        if (!selectedAddress || selectedAddress.trim() === '') {
            selectedAddress = 'Ubicación seleccionada';
        }

        document.getElementById('coordsInfo').innerHTML = `<i class="fa-solid fa-location-dot text-red-500 mr-2"></i><strong>${selectedAddress}</strong><br>Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
    })
    .catch(() => {
        selectedAddress = 'Ubicación seleccionada';
        document.getElementById('coordsInfo').innerHTML = `<i class="fa-solid fa-location-dot text-red-500 mr-2"></i><strong>Ubicación seleccionada</strong><br>Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
    });
}

function searchAddress(){
    const val = document.getElementById('searchInput').value.trim();
    if(!val) { 
        alert('Por favor ingresa una dirección'); 
        return false; 
    }
    
    const searchButton = document.querySelector('button[onclick*="searchAddress"]');
    if (searchButton) {
        searchButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span class="hidden sm:inline">Buscando...</span>';
        searchButton.disabled = true;
    }

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(val)}&limit=1`)
    .then(r => r.json())
    .then(data => {
        if (searchButton) {
            searchButton.innerHTML = '<i class="fa-solid fa-search"></i> <span class="hidden sm:inline">Buscar</span>';
            searchButton.disabled = false;
        }

        if(data.length > 0){
            const lat = parseFloat(data[0].lat);
            const lng = parseFloat(data[0].lon);
            map.setView([lat, lng], 15); 
            placeMarker(lat, lng); 
            reverseGeocode(lat, lng);
        } else {
            alert('Dirección no encontrada');
        }
    })
    .catch(error => {
        if (searchButton) {
            searchButton.innerHTML = '<i class="fa-solid fa-search"></i> <span class="hidden sm:inline">Buscar</span>';
            searchButton.disabled = false;
        }
        alert('Error al buscar la dirección. Intenta de nuevo.');
        console.error('Search error:', error);
    });
    
    return false;
}

function confirmLocation(){
    if(!selectedCoords) {
        alert('Por favor selecciona una ubicación');
        return false;
    }
    
    const direccion = selectedAddress || `Lat: ${selectedCoords.lat.toFixed(6)}, Lng: ${selectedCoords.lng.toFixed(6)}`;
    
    try{
        const latRedondeada = Math.round(selectedCoords.lat * 1000000) / 1000000;
        const lngRedondeada = Math.round(selectedCoords.lng * 1000000) / 1000000;
        
        @this.set('direccion_fiscal', direccion);
        @this.set('latitud', latRedondeada);
        @this.set('longitud', lngRedondeada);
    } catch(e) {
        alert('Error al comunicarse con Livewire');
        console.error(e);
        return false;
    }
    
    closeMapModal();
}
</script>