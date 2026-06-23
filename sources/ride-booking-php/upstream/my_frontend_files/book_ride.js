// ============================================
// CONFIGURATION
// ============================================
const CONFIG = {
    NICOSIA_CENTER: [35.1856, 33.3823],
    CYPRUS_BOUNDS: [[34.50, 32.00], [35.80, 34.90]],
    SEARCH_DEBOUNCE: 300,
    MIN_DISTANCE: 0.3,
    BASE_RATES: {
        'simple': { base: 3.00, perKm: 0.80 },
        'luxury': { base: 5.00, perKm: 1.50 },
        'small-cargo': { base: 4.00, perKm: 1.00 },
        'large-cargo': { base: 6.00, perKm: 1.80 }
    }
};

// ============================================
// STATE
// ============================================
let map, pickupMarker, destMarker, routeLine;
let pickupData = null;
let destData = null;
let searchCache = {};
let searchTimeouts = {};
let lastSearchTime = 0;

// ============================================
// MAP INITIALIZATION
// ============================================
function initMap() {
    map = L.map('map', {
        maxBounds: L.latLngBounds(CONFIG.CYPRUS_BOUNDS),
        maxBoundsViscosity: 1.0
    }).setView(CONFIG.NICOSIA_CENTER, 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 18
    }).addTo(map);

    map.on('click', handleMapClick);
}

function handleMapClick(e) {
    if (!pickupData) {
        setLocation(e.latlng.lat, e.latlng.lng, 'Επιλεγμένη τοποθεσία', 'pickup');
    } else if (!destData) {
        setLocation(e.latlng.lat, e.latlng.lng, 'Επιλεγμένος προορισμός', 'dest');
    }
}

// ============================================
// MARKER MANAGEMENT
// ============================================
function setLocation(lat, lon, name, type) {
    const data = { lat, lon, name };
    
    if (type === 'pickup') {
        pickupData = data;
        updateMarker('pickup', lat, lon, '🟢', '#10b981');
        document.getElementById('pickupSearch').value = name.split(',')[0];
        document.getElementById('pickupSearch').classList.add('border-green-500', 'bg-green-50');
        document.getElementById('pickupDisplay').textContent = name;
    } else {
        destData = data;
        updateMarker('dest', lat, lon, '🔴', '#ef4444');
        document.getElementById('destSearch').value = name.split(',')[0];
        document.getElementById('destSearch').classList.add('border-red-500', 'bg-red-50');
        document.getElementById('destDisplay').textContent = name;
    }

    updateFormFields();
    
    if (pickupData && destData) {
        document.getElementById('selectedInfo').classList.remove('hidden');
        document.getElementById('resetBtn').classList.remove('hidden');
        drawRoute();
    }
}

function updateMarker(type, lat, lon, emoji, color) {
    const marker = type === 'pickup' ? pickupMarker : destMarker;
    
    if (marker) {
        map.removeLayer(marker);
    }

    const icon = L.divIcon({
        html: `<div style="background: ${color}; color: white; width: 35px; height: 35px; 
                border-radius: 50%; display: flex; align-items: center; justify-content: center; 
                font-size: 20px; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                ${emoji}</div>`,
        className: '',
        iconSize: [35, 35]
    });

    const newMarker = L.marker([lat, lon], { 
        icon, 
        draggable: true 
    }).addTo(map);

    newMarker.on('dragend', function() {
        const pos = this.getLatLng();
        setLocation(pos.lat, pos.lng, `Νέα θέση (${pos.lat.toFixed(4)}, ${pos.lng.toFixed(4)})`, type);
    });

    if (type === 'pickup') {
        pickupMarker = newMarker;
    } else {
        destMarker = newMarker;
    }

    map.setView([lat, lon], 14, { animate: true });
}

// ============================================
// ROUTING
// ============================================
async function drawRoute() {
    if (!pickupData || !destData) return;

    const dist = calculateDistance(pickupData.lat, pickupData.lon, destData.lat, destData.lon);
    if (dist < CONFIG.MIN_DISTANCE) {
        showAlert('Η απόσταση είναι πολύ μικρή. Επιλέξτε διαφορετικά σημεία.', 'warning');
        return;
    }

    if (routeLine) {
        map.removeLayer(routeLine);
    }

    const routeInfoEl = document.getElementById('routeInfo');
    routeInfoEl.classList.remove('hidden');
    routeInfoEl.innerHTML = '<p class="text-center py-4">⏳ Υπολογισμός διαδρομής...</p>';

    try {
        const url = `https://router.project-osrm.org/route/v1/driving/${pickupData.lon},${pickupData.lat};${destData.lon},${destData.lat}?overview=full&geometries=geojson`;
        
        const response = await fetch(url);
        if (!response.ok) throw new Error('Routing failed');
        
        const data = await response.json();
        
        if (!data.routes || data.routes.length === 0) {
            throw new Error('No route found');
        }

        const coords = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
        routeLine = L.polyline(coords, {
            color: '#3b82f6',
            weight: 5,
            opacity: 0.8
        }).addTo(map);

        map.fitBounds(routeLine.getBounds(), { padding: [50, 50] });

        const distanceKm = (data.routes[0].distance / 1000).toFixed(2);
        const durationMin = Math.ceil(data.routes[0].duration / 60);
        
        updateRouteInfo(distanceKm, durationMin, false);

    } catch (error) {
        console.error('Routing error:', error);
        
        const straightDist = calculateDistance(pickupData.lat, pickupData.lon, destData.lat, destData.lon);
        const estimatedTime = Math.ceil(straightDist * 2.5);
        
        updateRouteInfo(straightDist.toFixed(2), estimatedTime, true);
    }
}

function updateRouteInfo(distanceKm, durationMin, isFallback) {
    const routeInfoEl = document.getElementById('routeInfo');
    
    routeInfoEl.innerHTML = `
        ${isFallback ? '<div class="bg-yellow-100 text-yellow-800 text-xs p-2 rounded mb-2 text-center">⚠️ Χρησιμοποιείται εκτίμηση ευθείας γραμμής</div>' : ''}
        <div class="flex flex-wrap gap-4 justify-around text-center">
            <div>
                <p class="text-xs text-gray-600 font-semibold">Απόσταση</p>
                <p class="text-2xl font-bold text-blue-700">
                    <span id="distance_km">${distanceKm}</span> km
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-600 font-semibold">Διάρκεια</p>
                <p class="text-2xl font-bold text-purple-700">
                    ~<span id="estimated_time">${durationMin}</span> min
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-600 font-semibold">Κόστος</p>
                <p class="text-2xl font-bold text-green-700">
                    €<span id="estimated_cost">0.00</span>
                </p>
            </div>
        </div>
    `;

    document.getElementById('distance').value = distanceKm;
    calculateCost(distanceKm);
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// ============================================
// COST CALCULATION
// ============================================
function calculateCost(distanceKm) {
    const serviceType = document.getElementById('serviceType').value;
    
    setTimeout(() => {
        const costElement = document.getElementById('estimated_cost');
        const costHiddenElement = document.getElementById('estimated_cost_hidden');
        
        if (!costElement || !costHiddenElement) return;

        if (!serviceType) {
            costElement.textContent = '0.00';
            costHiddenElement.value = '0.00';
            return;
        }

        const rate = CONFIG.BASE_RATES[serviceType];
        const cost = (rate.base + parseFloat(distanceKm) * rate.perKm).toFixed(2);
        
        costElement.textContent = cost;
        costHiddenElement.value = cost;
    }, 100);
}

// ============================================
// SMART SEARCH WITH RATE LIMIT RESPECT
// ============================================

// Parse coordinates
function parseCoordinates(query) {
    const coordPattern = /^\s*(-?\d+\.?\d*)[,\s]+(-?\d+\.?\d*)\s*$/;
    const match = query.match(coordPattern);
    
    if (match) {
        const lat = parseFloat(match[1]);
        const lon = parseFloat(match[2]);
        
        if (lat >= 34.5 && lat <= 35.8 && lon >= 32.0 && lon <= 34.9) {
            return { lat, lon };
        }
    }
    return null;
}

// Normalize text - IMPROVED
function normalizeText(text) {
    if (!text) return '';
    return text.toLowerCase().trim()
        .replace(/ά/g, 'α').replace(/έ/g, 'ε').replace(/ή/g, 'η')
        .replace(/ί/g, 'ι').replace(/ό/g, 'ο').replace(/ύ/g, 'υ')
        .replace(/ώ/g, 'ω').replace(/ς/g, 'σ')
        .replace(/s$/, ''); // Remove trailing 's' for better matching
}

// Parse address
function parseAddress(query) {
    const trimmed = query.trim();
    const numberMatch = trimmed.match(/\d+[α-ωa-z]?$/i);
    
    if (numberMatch) {
        return {
            street: trimmed.substring(0, numberMatch.index).trim(),
            number: numberMatch[0],
            full: trimmed
        };
    }
    
    return { street: trimmed, number: null, full: trimmed };
}

// Rate limit helper - ensures 1 request per second
async function waitForRateLimit() {
    const now = Date.now();
    const timeSinceLastSearch = now - lastSearchTime;
    
    if (timeSinceLastSearch < 1100) {
        await new Promise(resolve => setTimeout(resolve, 1100 - timeSinceLastSearch));
    }
    
    lastSearchTime = Date.now();
}

// Single search call with rate limit
async function searchNominatim(params) {
    await waitForRateLimit();
    
    const url = new URL('https://nominatim.openstreetmap.org/search');
    Object.keys(params).forEach(key => {
        url.searchParams.set(key, params[key]);
    });
    
    try {
        const response = await fetch(url, {
            headers: { 'Accept-Language': 'el,en' }
        });
        
        if (response.ok) {
            return await response.json();
        }
    } catch (error) {
        console.warn('Search failed:', error);
    }
    
    return [];
}

// Reverse geocode
async function reverseGeocode(lat, lon) {
    await waitForRateLimit();
    
    try {
        const url = new URL('https://nominatim.openstreetmap.org/reverse');
        url.searchParams.set('format', 'json');
        url.searchParams.set('lat', lat);
        url.searchParams.set('lon', lon);
        url.searchParams.set('addressdetails', '1');
        
        const response = await fetch(url, {
            headers: { 'Accept-Language': 'el,en' }
        });
        
        if (response.ok) {
            const data = await response.json();
            return [data];
        }
    } catch (error) {
        console.error('Reverse geocode error:', error);
    }
    return [];
}

// Check relevance - BALANCED: Strict but finds results
function isRelevant(item, query, parsed) {
    const address = item.address || {};
    const normalizedQuery = normalizeText(parsed.street);
    
    // Get road name and first part of display name
    const road = normalizeText(address.road || '');
    const firstPart = normalizeText(item.display_name.split(',')[0].trim());
    
    // Primary check: road name
    if (road) {
        // Exact match
        if (road === normalizedQuery) return true;
        
        // Starts with
        if (road.startsWith(normalizedQuery) || normalizedQuery.startsWith(road)) return true;
        
        // Contains (for longer queries)
        if (normalizedQuery.length >= 5 && road.includes(normalizedQuery)) return true;
        if (normalizedQuery.length >= 5 && normalizedQuery.includes(road) && road.length >= 4) return true;
        
        // Fuzzy: check with/without last char
        if (normalizedQuery.length >= 6) {
            const queryMinusOne = normalizedQuery.slice(0, -1);
            const roadMinusOne = road.slice(0, -1);
            if (queryMinusOne === roadMinusOne || road.startsWith(queryMinusOne) || queryMinusOne.startsWith(road)) {
                return true;
            }
        }
        
        // Multi-word: all words must be in road
        if (normalizedQuery.includes(' ')) {
            const words = normalizedQuery.split(' ').filter(w => w.length >= 3);
            if (words.length > 0 && words.every(w => road.includes(w))) return true;
        }
    }
    
    // Secondary check: first part of display name (only if no road)
    if (!road && firstPart) {
        if (firstPart === normalizedQuery) return true;
        if (firstPart.startsWith(normalizedQuery) || normalizedQuery.startsWith(firstPart)) return true;
        if (normalizedQuery.length >= 5 && firstPart.includes(normalizedQuery)) return true;
    }
    
    return false;
}

// Calculate relevance
function calculateRelevance(item, query, parsed) {
    let score = 0;
    const address = item.address || {};
    const normalizedQuery = normalizeText(parsed.street);
    
    const road = normalizeText(address.road || '');
    if (road === normalizedQuery) score += 1000;
    else if (road.startsWith(normalizedQuery)) score += 500;
    else if (road.includes(normalizedQuery)) score += 200;
    
    if (parsed.number && address.house_number === parsed.number) score += 300;
    
    if (address.house_number) score += 100;
    if (address.road) score += 80;
    
    const distFromCenter = Math.abs(parseFloat(item.lat) - 35.1856) + 
                          Math.abs(parseFloat(item.lon) - 33.3823);
    score -= distFromCenter * 20;
    
    return score;
}

// Smart sequential search - WITH PROGRESSIVE CACHING
async function smartSearch(query) {
    const parsed = parseAddress(query);
    const allResults = [];
    const seenIds = new Set();
    
    // Check if we have cached results from shorter query
    const normalizedQuery = normalizeText(parsed.street);
    for (let len = normalizedQuery.length - 1; len >= 5; len--) {
        const shorterQuery = normalizedQuery.substring(0, len);
        if (searchCache[shorterQuery]) {
            // Filter cached results for current query
            const cached = searchCache[shorterQuery];
            cached.forEach(item => {
                if (isRelevant(item, query, parsed)) {
                    const id = `${item.lat}_${item.lon}`;
                    if (!seenIds.has(id)) {
                        seenIds.add(id);
                        item.relevance = calculateRelevance(item, query, parsed);
                        allResults.push(item);
                    }
                }
            });
            
            if (allResults.length >= 3) {
                allResults.sort((a, b) => b.relevance - a.relevance);
                return allResults.slice(0, 10);
            }
        }
    }
    
    // Strategy 1: Main search
    const data1 = await searchNominatim({
        format: 'json',
        q: parsed.full,
        addressdetails: '1',
        countrycodes: 'cy',
        limit: '15'
    });
    
    data1.forEach(item => {
        const id = `${item.lat}_${item.lon}`;
        if (!seenIds.has(id) && isRelevant(item, query, parsed)) {
            seenIds.add(id);
            item.relevance = calculateRelevance(item, query, parsed);
            allResults.push(item);
        }
    });
    
    // If enough results, return
    if (allResults.length >= 3) {
        allResults.sort((a, b) => b.relevance - a.relevance);
        return allResults.slice(0, 10);
    }
    
    // Strategy 2: Broader search
    const data2 = await searchNominatim({
        format: 'json',
        q: `${parsed.street}, Cyprus`,
        addressdetails: '1',
        limit: '10'
    });
    
    data2.forEach(item => {
        const id = `${item.lat}_${item.lon}`;
        if (!seenIds.has(id) && isRelevant(item, query, parsed)) {
            seenIds.add(id);
            item.relevance = calculateRelevance(item, query, parsed);
            allResults.push(item);
        }
    });
    
    allResults.sort((a, b) => b.relevance - a.relevance);
    return allResults.slice(0, 10);
}

// Format result
function formatResult(item) {
    const address = item.address || {};
    let primary = '';
    let secondary = '';
    
    if (address.road && address.house_number) {
        primary = `${address.road} ${address.house_number}`;
    } else if (address.road) {
        primary = address.road;
    } else if (address.suburb || address.neighbourhood) {
        primary = address.suburb || address.neighbourhood;
    } else {
        primary = item.display_name.split(',')[0];
    }
    
    const parts = [];
    if (address.suburb && !primary.includes(address.suburb)) {
        parts.push(address.suburb);
    }
    if (address.city || address.town) {
        parts.push(address.city || address.town);
    }
    
    secondary = parts.length > 0 ? parts.join(', ') : item.display_name;
    
    return { primary, secondary };
}

// ============================================
// SEARCH SETUP
// ============================================
function setupSearch(inputId, resultsId, loadingId, type) {
    const input = document.getElementById(inputId);
    const results = document.getElementById(resultsId);
    const loading = document.getElementById(loadingId);

    input.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (searchTimeouts[type]) {
            clearTimeout(searchTimeouts[type]);
        }

        if (query.length < 2) {
            results.style.display = 'none';
            return;
        }

        loading.classList.remove('hidden');
        results.innerHTML = '<div class="p-3 text-center text-gray-500">🔍 Αναζήτηση...</div>';
        results.style.display = 'block';

        searchTimeouts[type] = setTimeout(() => {
            performSearch(query, results, loading, type);
        }, CONFIG.SEARCH_DEBOUNCE);
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest(`#${inputId}`) && !e.target.closest(`#${resultsId}`)) {
            results.style.display = 'none';
        }
    });
}

async function performSearch(query, resultsEl, loadingEl, type) {
    const cacheKey = normalizeText(query);
    
    if (searchCache[cacheKey]) {
        displayResults(searchCache[cacheKey], resultsEl, type, query);
        loadingEl.classList.add('hidden');
        return;
    }

    try {
        const coords = parseCoordinates(query);
        let data;
        
        if (coords) {
            resultsEl.innerHTML = '<div class="p-3 text-center text-blue-500">📍 Αναζήτηση συντεταγμένων...</div>';
            data = await reverseGeocode(coords.lat, coords.lon);
        } else {
            // Show hint for short queries
            if (query.trim().length < 5) {
                resultsEl.innerHTML = '<div class="p-3 text-center"><p class="text-gray-500 text-sm">🔍 Αναζήτηση...</p><p class="text-xs text-blue-600 mt-1">Περισσότεροι χαρακτήρες = Καλύτερα αποτελέσματα</p></div>';
            }
            data = await smartSearch(query);
        }
        
        searchCache[cacheKey] = data;
        displayResults(data, resultsEl, type, query);
    } catch (error) {
        console.error('Search error:', error);
        resultsEl.innerHTML = '<div class="p-3 text-center text-red-500">❌ Σφάλμα αναζήτησης</div>';
    } finally {
        loadingEl.classList.add('hidden');
    }
}

function displayResults(data, resultsEl, type, query = '') {
    resultsEl.innerHTML = '';

    if (!data || data.length === 0) {
        const shortQuery = query.trim().length < 5;
        resultsEl.innerHTML = `
            <div class="p-3 text-center">
                <p class="text-gray-500 mb-2">Δεν βρέθηκαν αποτελέσματα</p>
                ${shortQuery ? '<p class="text-xs text-blue-600">💡 Δοκιμάστε με περισσότερους χαρακτήρες για καλύτερα αποτελέσματα</p>' : ''}
            </div>
        `;
        resultsEl.style.display = 'block';
        return;
    }

    data.forEach(item => {
        const div = document.createElement('div');
        div.className = 'result-item';
        
        const formatted = formatResult(item);

        div.innerHTML = `
            <div class="font-semibold text-sm text-gray-800">${formatted.primary}</div>
            <div class="text-xs text-gray-500 truncate">${formatted.secondary}</div>
        `;

        div.addEventListener('click', () => {
            setLocation(parseFloat(item.lat), parseFloat(item.lon), item.display_name, type);
            resultsEl.style.display = 'none';
        });

        resultsEl.appendChild(div);
    });

    resultsEl.style.display = 'block';
}

// ============================================
// FORM MANAGEMENT
// ============================================
function updateFormFields() {
    if (pickupData) {
        document.getElementById('pick_up_latitude').value = pickupData.lat;
        document.getElementById('pick_up_longitude').value = pickupData.lon;
        document.getElementById('pick_up_address').value = pickupData.name;
    }

    if (destData) {
        document.getElementById('destination_latitude').value = destData.lat;
        document.getElementById('destination_longitude').value = destData.lon;
        document.getElementById('destination_address').value = destData.name;
    }

    const submitBtn = document.getElementById('submitBtn');
    if (pickupData && destData) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

// ============================================
// FORM SUBMISSION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('driveRequestForm');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!pickupData || !destData) {
            showAlert('Παρακαλώ επιλέξτε σημείο αναχώρησης και προορισμό', 'error');
            return;
        }

        const serviceType = document.getElementById('serviceType').value;
        if (!serviceType) {
            showAlert('Παρακαλώ επιλέξτε τύπο υπηρεσίας', 'error');
            return;
        }

        const distance = parseFloat(document.getElementById('distance').value);
        if (distance < CONFIG.MIN_DISTANCE) {
            showAlert('Η απόσταση είναι πολύ μικρή', 'error');
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const submitLoading = document.getElementById('submitLoading');
        
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                showAlert('✅ Το αίτημά σας υποβλήθηκε επιτυχώς!', 'success');
                setTimeout(() => {
                    window.location.href = 'my_rides.php';
                }, 2000);
            } else {
                throw new Error('Server error');
            }

        } catch (error) {
            console.error('Submit error:', error);
            showAlert('❌ Σφάλμα υποβολής. Παρακαλώ δοκιμάστε ξανά.', 'error');
            
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            submitLoading.classList.add('hidden');
        }
    });
});

// ============================================
// EVENT HANDLERS
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('serviceType').addEventListener('change', function() {
        const distance = document.getElementById('distance').value;
        if (distance) {
            calculateCost(distance);
        }
    });
});

document.getElementById('useLocationBtn').addEventListener('click', function() {
    if (!navigator.geolocation) {
        showAlert('Ο περιηγητής σας δεν υποστηρίζει geolocation', 'error');
        return;
    }

    this.textContent = '⏳ Εντοπισμός...';
    this.disabled = true;

    navigator.geolocation.getCurrentPosition(
        (position) => {
            setLocation(
                position.coords.latitude,
                position.coords.longitude,
                'Η τρέχουσα τοποθεσία μου',
                'pickup'
            );
            this.textContent = '✅ Χρήση τρέχουσας τοποθεσίας';
            this.disabled = false;
        },
        (error) => {
            console.error('Geolocation error:', error);
            showAlert('Δεν ήταν δυνατή η πρόσβαση στην τοποθεσία σας', 'error');
            this.textContent = '📍 Χρήση τρέχουσας τοποθεσίας';
            this.disabled = false;
        }
    );
});

document.getElementById('resetBtn').addEventListener('click', function() {
    if (confirm('Είστε σίγουροι ότι θέλετε να επαναφέρετε τη φόρμα;')) {
        location.reload();
    }
});

// ============================================
// ALERTS
// ============================================
function showAlert(message, type = 'info') {
    const container = document.getElementById('alertContainer');
    const colors = {
        success: 'bg-green-100 border-green-400 text-green-700',
        error: 'bg-red-100 border-red-400 text-red-700',
        warning: 'bg-yellow-100 border-yellow-400 text-yellow-700',
        info: 'bg-blue-100 border-blue-400 text-blue-700'
    };

    const alert = document.createElement('div');
    alert.className = `${colors[type]} border px-4 py-3 rounded-lg mb-4`;
    alert.textContent = message;

    container.innerHTML = '';
    container.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 5000);
}

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    setupSearch('pickupSearch', 'pickupResults', 'pickupLoading', 'pickup');
    setupSearch('destSearch', 'destResults', 'destLoading', 'dest');
});
