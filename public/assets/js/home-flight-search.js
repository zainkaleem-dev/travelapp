(function () {
    function plusDaysIso(days) {
        const dt = new Date();
        dt.setDate(dt.getDate() + days);
        return dt.toISOString().slice(0, 10);
    }

    function getConfig() {
        return window.flightSearchConfig || { apiUrl: '/api/flights-search', reviewUrl: '/review-booking.html' };
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function normalizeIata(value) {
        const raw = String(value || '').trim().toUpperCase();
        if (!raw) return '';
        const bracket = raw.match(/\(([A-Z]{3})\)/);
        if (bracket) return bracket[1];
        const direct = raw.match(/\b([A-Z]{3})\b/);
        if (direct) return direct[1];
        const cleaned = raw.replace(/[^A-Z]/g, '');
        return cleaned.length >= 3 ? cleaned.slice(0, 3) : '';
    }

    function toIsoDate(value) {
        const raw = String(value || '').trim();
        if (!raw) return plusDaysIso(7);
        if (/^\d{4}-\d{2}-\d{2}$/.test(raw)) return raw;
        const parsed = new Date(raw);
        if (Number.isNaN(parsed.getTime())) return plusDaysIso(7);
        return parsed.toISOString().slice(0, 10);
    }

    function formatTime(dateTime) {
        if (!dateTime) return '--:--';
        const dt = new Date(dateTime);
        if (Number.isNaN(dt.getTime())) return '--:--';
        return dt.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
    }

    function formatDateLabel(isoDate) {
        const dt = new Date(isoDate);
        if (Number.isNaN(dt.getTime())) return isoDate;
        return dt.toLocaleDateString(undefined, {
            weekday: 'short',
            month: 'short',
            day: 'numeric'
        });
    }

    function formatDuration(duration) {
        if (!duration) return '--';
        const h = (duration.match(/(\d+)H/) || [])[1];
        const m = (duration.match(/(\d+)M/) || [])[1];
        const hours = h ? `${h}h` : '';
        const mins = m ? ` ${m}m` : '';
        return `${hours}${mins}`.trim() || duration;
    }

    function renderFlightCard(offer, dictionaries, reviewUrl) {
        const itinerary = Array.isArray(offer?.itineraries) ? offer.itineraries[0] : null;
        const segments = Array.isArray(itinerary?.segments) ? itinerary.segments : [];
        const first = segments[0] || {};
        const last = segments.length ? segments[segments.length - 1] : {};
        const depCode = first?.departure?.iataCode || '--';
        const arrCode = last?.arrival?.iataCode || '--';
        const depAt = first?.departure?.at || '';
        const arrAt = last?.arrival?.at || '';
        const carrierCode = first?.carrierCode || '';
        const carrierName = dictionaries?.carriers?.[carrierCode] || carrierCode || 'Airline';
        const flightNo = carrierCode && first?.number ? `${carrierCode} - ${first.number}` : '--';
        const stops = Math.max(segments.length - 1, 0);
        const stopText = stops === 0 ? 'Non Stop' : `${stops} Stop${stops > 1 ? 's' : ''}`;
        const duration = formatDuration(itinerary?.duration);
        const price = offer?.price?.grandTotal || offer?.price?.total || '--';
        const currency = offer?.price?.currency || '';

        return `
            <div class="col-12 mb-3" data-aos="fade-up">
                <div class="row g-0 border theme-border-radius theme-box-shadow p-2 align-items-center theme-bg-white">
                    <div class="col-12 col-md-3">
                        <div class="d-flex">
                            <div class="d-flex flex-column ms-2">
                                <span class="font-small d-inline-flex mb-0 align-middle">${escapeHtml(carrierName)}</span>
                                <span class="font-small d-inline-flex mb-0 align-middle">${escapeHtml(flightNo)}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="fw-bold">${escapeHtml(formatTime(depAt))}</div>
                        <div class="font-small">${escapeHtml(depCode)}</div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="font-small">${escapeHtml(duration)}</div>
                        <span class="stops"></span>
                        <div class="font-small">${escapeHtml(stopText)}</div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="fw-bold">${escapeHtml(formatTime(arrAt))}</div>
                        <div class="font-small">${escapeHtml(arrCode)}</div>
                    </div>
                    <div class="col-12 col-md-3 text-center mt-md-0 mt-2">
                        <div class="fw-bold">${escapeHtml(currency)} ${escapeHtml(price)}</div>
                        <button type="button" class="btn-select btn btn-effect" onclick="window.location.href='${reviewUrl}';">
                            <span class="font-small">Select</span>
                        </button>
                    </div>
                </div>
            </div>`;
    }

    window.searchFlightsOneway = async function () {
        const cfg = getConfig();
        const resultsSection = document.getElementById('home-oneway-results');
        const list = document.getElementById('api-flight-results-list');
        const countEl = document.getElementById('api-search-count');
        const routeEl = document.getElementById('api-search-route');
        const dateEl = document.getElementById('api-search-date');
        const button = document.getElementById('onewaySearchBtn');
        const dummy = document.getElementById('dummy-flight-results');

        if (!resultsSection || !list) return;

        resultsSection.classList.remove('d-none');
        if (dummy) dummy.classList.add('d-none');
        resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });

        const origin = normalizeIata(document.getElementById('onewayOrigin')?.value);
        const destination = normalizeIata(document.getElementById('onewayDestination')?.value);
        const departureDate = toIsoDate(document.getElementById('datepicker')?.value);
        const adultsValue = document.querySelector('#oneway input[name="onewayAdult"]')?.value;
        const childrenValue = document.querySelector('#oneway input[name="onewayChild"]')?.value;
        const infantsValue = document.querySelector('#oneway input[name="onewayInfant"]')?.value;
        const selectedClass = document.querySelector('#oneway input[name="class"]:checked')?.value || '';

        const adults = Math.max(parseInt(adultsValue || '1', 10) || 1, 1);
        const children = Math.max(parseInt(childrenValue || '0', 10) || 0, 0);
        const infants = Math.max(parseInt(infantsValue || '0', 10) || 0, 0);

        const travelClassMap = {
            Economy: 'ECONOMY',
            Special: 'PREMIUM_ECONOMY',
            Business: 'BUSINESS',
            First: 'FIRST'
        };

        const travelClass = travelClassMap[selectedClass] || '';

        if (!origin || !destination) {
            list.innerHTML = '<div class="col-12"><div class="alert alert-warning mb-3">Please enter valid origin and destination IATA codes (example: DXB, LHR).</div></div>';
            if (countEl) countEl.textContent = 'Showing 0 of 0 flights.';
            return;
        }

        if (button) button.disabled = true;
        list.innerHTML = '<div class="col-12"><div class="alert alert-info mb-3">Searching flights...</div></div>';

        try {
            const params = new URLSearchParams({
                originLocationCode: origin,
                destinationLocationCode: destination,
                departureDate: departureDate,
                adults: String(adults),
                max: '20'
            });

            if (children > 0) params.set('children', String(children));
            if (infants > 0) params.set('infants', String(infants));
            if (travelClass) params.set('travelClass', travelClass);

            const response = await fetch(`${cfg.apiUrl}?${params.toString()}`, {
                headers: { Accept: 'application/json' }
            });

            const payload = await response.json();
            if (!response.ok || payload.error) {
                throw new Error(payload.message || payload.error || 'Flight search failed.');
            }

            const flights = Array.isArray(payload.data) ? payload.data : [];
            const dictionaries = payload.dictionaries || {};

            if (routeEl) routeEl.textContent = `${origin} to ${destination}`;
            if (dateEl) dateEl.textContent = formatDateLabel(departureDate);
            if (countEl) countEl.textContent = `Showing ${flights.length} of ${flights.length} flights.`;

            if (!flights.length) {
                list.innerHTML = '<div class="col-12"><div class="alert alert-warning mb-3">No flights found for selected route/date.</div></div>';
                return;
            }

            list.innerHTML = flights.map((offer) => renderFlightCard(offer, dictionaries, cfg.reviewUrl)).join('');
        } catch (error) {
            list.innerHTML = `<div class="col-12"><div class="alert alert-danger mb-3">${escapeHtml(error.message || 'Unable to fetch flights right now.')}</div></div>`;
            if (countEl) countEl.textContent = 'Showing 0 of 0 flights.';
        } finally {
            if (button) button.disabled = false;
        }
    };
})();

