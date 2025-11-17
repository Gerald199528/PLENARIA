<!-- Leaflet -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>
// ====================================================================
// 1. ESTADO GLOBAL (usando 'var' para prevenir errores de redeclaración)
// ====================================================================
// Estas variables se reasignarán de forma segura en cada navegación.
var map = null;
var marker = null;
var selectedCoords = null;
var selectedAddress = null;
var osmLayer = null;
var satelliteLayer = null;
var currentLayer = 'osm'; // Mantener el estado de la capa actual

// ====================================================================
// 2. FUNCIÓN DE INICIALIZACIÓN (Solo se ejecuta al abrir el modal)
// ====================================================================

/**
 * Abre el modal y programa la inicialización del mapa.
 * NOTA: Eliminamos los listeners de DOMContentLoaded y livewire:updated
 * para evitar el bloqueo del dashboard y la doble inicialización.
 */
function openMapModal(){
    const modal = document.getElementById('default-modal');
    // Muestra el modal (clases de Tailwind: 'flex' en lugar de 'hidden')
    modal.classList.remove('hidden'); 
    modal.classList.add('flex');

    // Destruye cualquier instancia anterior (limpieza total)
    destroyMap();

    // Inicializa el mapa solo después de un breve retraso para asegurar que
    // el contenedor del modal esté visible y Leaflet pueda calcular su tamaño.
    setTimeout(()=>{
        initMap();
    }, 150); 
}

/**
 * Cierra el modal, destruye el mapa y resetea las variables de selección.
 */
function closeMapModal(){
    const modal = document.getElementById('default-modal');
    modal.classList.add('hidden'); 
    modal.classList.remove('flex');
    destroyMap();
    
    // Resetear las variables de selección
    marker = selectedCoords = selectedAddress = null;
}

/**
 * Destruye la instancia de Leaflet y limpia el contenedor.
 */
function destroyMap(){
    if(map) {
        map.off(); 
        map.remove(); 
    }
    
    // Limpiar el estado
    map = null;
    osmLayer = satelliteLayer = null;
    
    // Restaurar el mensaje de carga en el contenedor HTML
    const mapContainer = document.getElementById('map');
    if(mapContainer) mapContainer.innerHTML = '<div id="map-loading" class="absolute inset-0 flex items-center justify-center bg-gray-200 dark:bg-gray-600 z-10">Cargando mapa...</div>';
}

// ====================================================================
// 3. FUNCIONES DE MAPA (Leaflet)
// ====================================================================

function initMap(){
    const mapContainer = document.getElementById('map');
    if(!mapContainer) return;

    const initialLat=6.5, initialLng=-66.5; // Coordenadas de Venezuela
    map = L.map('map',{zoomControl:true, attributionControl:true}).setView([initialLat, initialLng],6);

    osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19, attribution:'© OpenStreetMap contributors'}).addTo(map);
    satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',{maxZoom:18, attribution:'© Esri'});

    map.on('load', ()=>document.getElementById('map-loading').style.display='none');
    map.on('click', e=>{ placeMarker(e.latlng.lat,e.latlng.lng); reverseGeocode(e.latlng.lat,e.latlng.lng); });

    centerOnUserLocation(); // Esto intentará obtener la ubicación al cargar el mapa
}

function centerOnUserLocation(){
    const locationButton = document.querySelector('button[onclick*="centerOnUserLocation"]');
    
    if(navigator.geolocation && map){
        // 1. Retroalimentación: Muestra el estado de carga y deshabilita el botón
        if (locationButton) {
            locationButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span class="hidden sm:inline">Buscando...</span>';
            locationButton.disabled = true;
        }

        const geoOptions = {
            // Permite usar una posición en caché de hasta 30 segundos (mejora la velocidad si se ha usado recientemente)
            maximumAge: 30000,
            // Espera un máximo de 8 segundos antes de fallar (mejora la UX al no colgarse)
            timeout: 8000,
            // Prioriza la velocidad sobre la máxima precisión (puede ser más rápido)
            enableHighAccuracy: false
        };

        const success = (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            map.setView([lat,lng],16);
            placeMarker(lat,lng);
            reverseGeocode(lat,lng);
            
            // 3. Restaurar botón
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
            alert(message);
            
            // 3. Restaurar botón
            if (locationButton) {
                locationButton.innerHTML = '<i class="fa-solid fa-location-arrow"></i> <span class="hidden sm:inline">Mi Ubicación</span>';
                locationButton.disabled = false;
            }
        };

        // 2. Ejecutar la solicitud con las opciones optimizadas
        navigator.geolocation.getCurrentPosition(success, error, geoOptions);
    } else {
         alert('Tu navegador no soporta geolocalización o el mapa no se ha inicializado.');
         if (locationButton) locationButton.disabled = false;
    }
}
function switchMapLayer(layer){
    if(!map) return;
    if(layer==='satellite'){
        if(map.hasLayer(osmLayer)) map.removeLayer(osmLayer);
        if(!map.hasLayer(satelliteLayer)) map.addLayer(satelliteLayer);
    } else {
        if(map.hasLayer(satelliteLayer)) map.removeLayer(satelliteLayer);
        if(!map.hasLayer(osmLayer)) map.addLayer(osmLayer);
    }
    currentLayer=layer;
}

function placeMarker(lat,lng){
    if(marker) map.removeLayer(marker);
    marker = L.marker([lat,lng],{icon:L.icon({
        iconUrl:'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl:'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize:[25,41], iconAnchor:[12,41], popupAnchor:[1,-34], shadowSize:[41,41]
    })}).addTo(map);
    selectedCoords={lat,lng};
    document.getElementById('coordsInfo').innerHTML=`Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
}

function reverseGeocode(lat,lng){
    // Utiliza un parámetro 'zoom=18' para obtener más detalle en la dirección
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
    .then(r=>r.json()).then(data=>{
        let addressComponents = [];
        const address = data.address;

        // 1. Prioridad: Calle, Número de casa o Edificio (más específico)
        if (address.road) addressComponents.push(address.road);
        if (address.house_number) addressComponents.push(address.house_number);
        if (address.building) addressComponents.push(address.building);
        
        // 2. Nivel intermedio: Barrio/Vecindario, Suburbio
        if (address.neighbourhood) addressComponents.push(address.neighbourhood);
        if (address.suburb) addressComponents.push(address.suburb);
        
        // 3. Nivel alto: Ciudad, Municipio, Pueblo
        if (address.city || address.town) {
            addressComponents.push(address.city || address.town);
        } else if (address.village) {
            addressComponents.push(address.village);
        }

        // 4. Fallback: Si no hay nada, usa el 'display_name' completo o 'Ubicación seleccionada'
        if (addressComponents.length === 0) {
            selectedAddress = data.display_name || 'Ubicación seleccionada';
        } else {
            // Unir los componentes de la dirección de forma limpia
            selectedAddress = addressComponents.join(', ');
        }
        
        // Asegurar que selectedAddress no esté vacío o nulo
        if (!selectedAddress || selectedAddress.trim() === '') {
            selectedAddress = 'Ubicación seleccionada';
        }

        document.getElementById('coordsInfo').innerHTML=`<i class="fa-solid fa-location-dot text-red-500 mr-2"></i><strong>${selectedAddress}</strong><br>Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
    }).catch(()=>{ 
        // En caso de fallo de red o del servidor de Nominatim
        selectedAddress='Ubicación seleccionada';
        document.getElementById('coordsInfo').innerHTML=`<i class="fa-solid fa-location-dot text-red-500 mr-2"></i><strong>Ubicación seleccionada</strong><br>Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
    });
}
function searchAddress(){
    const val = document.getElementById('searchInput').value.trim();
    if(!val){ 
        alert('Por favor ingresa una dirección'); 
        return false; 
    }
    
    // 1. Identificar el botón y mostrar el estado de carga
    const searchButton = document.querySelector('button[onclick*="searchAddress"]');
    if (searchButton) {
        searchButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span class="hidden sm:inline">Buscando...</span>';
        searchButton.disabled = true;
    }

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(val)}&limit=1`)
    .then(r => r.json())
    .then(data => {
        // Restaurar botón
        if (searchButton) {
            searchButton.innerHTML = '<i class="fa-solid fa-search"></i> <span class="hidden sm:inline">Buscar</span>';
            searchButton.disabled = false;
        }

        if(data.length > 0){
            const lat = parseFloat(data[0].lat), lng = parseFloat(data[0].lon);
            map.setView([lat,lng], 15); 
            placeMarker(lat,lng); 
            reverseGeocode(lat,lng);
        } else {
            alert('Dirección no encontrada');
        }
    })
    .catch(error => {
        // Restaurar botón en caso de error de red
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
    if(!selectedCoords){ alert('Por favor selecciona una ubicación'); return false; }
    const direccion = selectedAddress||`Lat: ${selectedCoords.lat.toFixed(6)}, Lng: ${selectedCoords.lng.toFixed(6)}`;
    try{
        // Redondear las coordenadas a 6 decimales antes de enviar
        const latRedondeada = Math.round(selectedCoords.lat * 1000000) / 1000000;
        const lngRedondeada = Math.round(selectedCoords.lng * 1000000) / 1000000;
        
        // Enviar datos a Livewire
        @this.set('direccion_fiscal', direccion);
        @this.set('latitud', latRedondeada);
        @this.set('longitud', lngRedondeada);
    }catch(e){ alert('Error al comunicarse con Livewire'); return false; }
    closeMapModal();
}
</script>