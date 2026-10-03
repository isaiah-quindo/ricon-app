@extends('layouts.public')
@section('title', $course['title'] . ' Route')
@section('og_title', $course['title'] . ' Route Map. The Great Cordillera 100')
@section('og_description', 'Explore the ' . $course['title'] . ' course in 2D and 3D, with the full elevation profile and aid station locations.')

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/maplibre-gl/5.24.0/maplibre-gl.min.css">
<style>
    :root { --accent: {{ $accent }}; --accent-rgb: {{ str_replace(',', ' ', $accentRgb) }}; }

    .maplibregl-ctrl-group { background: #1a1a1a; border: 1px solid rgba(255,255,255,.1); }
    .maplibregl-ctrl-group button + button { border-top-color: rgba(255,255,255,.1); }
    .maplibregl-ctrl-group button .maplibregl-ctrl-icon { filter: invert(1); }
    .maplibregl-popup-content { background: #1a1a1a; color: #e5e7eb; border-radius: .75rem; padding: .875rem 1rem; box-shadow: 0 10px 30px rgba(0,0,0,.5); }
    .maplibregl-popup-tip { border-top-color: #1a1a1a !important; border-bottom-color: #1a1a1a !important; }
    .maplibregl-popup-close-button { color: #9ca3af; font-size: 18px; padding: 0 6px; }
    .maplibregl-cooperative-gesture-screen { font-size: 15px; }

    /* Slim dark scrollbar for the aid station list, in the course color while hovered or dragged.
       Chrome drops the ::-webkit styles when scrollbar-color is set, so that's Firefox only. */
    @supports not selector(::-webkit-scrollbar) {
        .station-scroll { scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.18) transparent; }
        .station-scroll:hover { scrollbar-color: rgb(var(--accent-rgb) / .7) transparent; }
    }
    .station-scroll::-webkit-scrollbar { width: 6px; }
    .station-scroll::-webkit-scrollbar-track { background: transparent; margin: 8px 0; }
    .station-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.18); border-radius: 9999px; }
    .station-scroll:hover::-webkit-scrollbar-thumb { background: rgb(var(--accent-rgb) / .7); }
    .station-scroll::-webkit-scrollbar-thumb:active { background: var(--accent); }
</style>
@endsection

@section('content')
{{-- ========================================================
         HERO
    ======================================================== --}}
<section class="bg-[#0d0d0d] pt-32 pb-12">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <a href="{{ route('race-category.' . $slug) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Back to {{ $course['title'] }}
        </a>
        <p class="text-[color:var(--accent)] text-sm font-semibold uppercase tracking-wider mb-2">The Great Cordillera 100</p>
        <h1 class="text-4xl md:text-6xl font-black text-white leading-tight mb-4">
            {{ $course['title'] }} <span class="text-[color:var(--accent)]">Route</span>
        </h1>
        @if ($course['provisional'] ?? false)
            <p class="text-gray-400">Provisional route. Details may change before race day.</p>
        @endif
    </div>
</section>


{{-- ========================================================
         STATS BAR
    ======================================================== --}}
<div class="bg-[color:var(--accent)]">
    <div class="mx-auto px-8" style="max-width:1280px;">
        <div class="grid grid-cols-2 md:grid-cols-6 md:divide-x divide-white/20">
            <div class="py-8 px-6 first:pl-0">
                <p class="text-white text-xs uppercase tracking-wider mb-1">{{ $course['distance_label'] ?? 'Distance' }}</p>
                <p class="text-white font-black text-2xl">{{ $course['distance'] }}</p>
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Est. Elevation Gain</p>
                <p class="text-white font-black text-2xl">{{ $course['elevation_gain'] }}</p>
            </div>
            <div class="py-8 pr-6 pl-0 md:pl-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Cutoff Time</p>
                <p class="text-white font-black text-2xl">{{ $course['cutoff_time'] }}</p>
                @if (! empty($course['cutoff_note']))
                    <p class="text-white/80 text-xs mt-1">{{ $course['cutoff_note'] }}</p>
                @endif
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Race Date</p>
                <p class="text-white font-black text-2xl">{{ $course['race_date'] }}</p>
            </div>
            <div class="py-8 pr-6 pl-0 md:pl-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Gunstart</p>
                <p class="text-white font-black text-2xl">{{ $course['gunstart'] }}</p>
                @if (! empty($course['gunstart_note']))
                    <p class="text-white/80 text-xs mt-1">{{ $course['gunstart_note'] }}</p>
                @endif
            </div>
            <div class="py-8 px-6">
                <p class="text-white text-xs uppercase tracking-wider mb-1">Start & Finish</p>
                <p class="text-white font-black text-2xl">{{ $course['start_finish'] }}</p>
            </div>
        </div>
    </div>
</div>


<div x-data="courseMap" class="bg-[#111111]">
    <section class="pt-16 pb-20">
        <div class="w-full px-4 md:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-5">
                <div>
                    <p class="text-[color:var(--accent)] text-sm font-semibold uppercase tracking-wider mb-2">Course Map</p>
                    <h2 class="text-2xl md:text-3xl font-bold text-white">Explore the course</h2>
                </div>
                <div class="inline-flex rounded-lg bg-white/5 border border-white/10 p-1" role="group" aria-label="Map view">
                    <button type="button" @click="setMode('3d')" :aria-pressed="mode === '3d'"
                        :class="mode === '3d' ? 'bg-[color:var(--accent)] text-white' : 'text-gray-300 hover:text-white'"
                        class="px-4 py-1.5 rounded-md text-sm font-semibold transition-colors">3D</button>
                    <button type="button" @click="setMode('2d')" :aria-pressed="mode === '2d'"
                        :class="mode === '2d' ? 'bg-[color:var(--accent)] text-white' : 'text-gray-300 hover:text-white'"
                        class="px-4 py-1.5 rounded-md text-sm font-semibold transition-colors">2D</button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_340px] gap-6 items-start">

                {{-- ========================================================
                         MAP + ELEVATION PROFILE
                    ======================================================== --}}
                <div class="min-w-0">
                    <div class="relative rounded-2xl overflow-hidden border border-white/10 bg-[#1a1a1a]">
                        <div id="route-map" class="w-full h-[65vh] min-h-[420px] max-h-[760px] scroll-mt-24"></div>

                        <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-[#1a1a1a] text-gray-400 text-sm">
                            Loading course…
                        </div>
                        <div x-show="error" x-cloak class="absolute inset-0 flex items-center justify-center bg-[#1a1a1a] text-gray-400 text-sm px-6 text-center" x-text="error"></div>

                        {{-- Position readout, driven by hovering the route, the graph or a station --}}
                        <div x-show="hover" x-cloak
                            class="absolute left-3 bottom-3 rounded-lg bg-black/75 backdrop-blur px-3 py-2 text-sm text-white pointer-events-none">
                            <span class="font-bold" x-text="hover && 'KM ' + hover.km.toFixed(1)"></span>
                            <span class="text-gray-400 mx-1.5">·</span>
                            <span x-text="hover && hover.ele.toLocaleString() + ' m'"></span>
                            <span class="text-gray-400 mx-1.5">·</span>
                            <span :class="hover && hover.grade >= 0 ? 'text-[color:var(--accent)]' : 'text-sky-400'" x-text="hover && (hover.grade > 0 ? '+' : '') + hover.grade + '%'"></span>
                        </div>
                    </div>
                    <p class="text-gray-500 text-xs mt-3">
                        Hover the route or the elevation graph to follow the course. In 3D, right-click and drag (or use two fingers) to tilt and rotate.
                    </p>

                    <div class="mt-6 rounded-2xl border border-white/10 bg-[#1a1a1a] p-5 md:p-6">
                        <div class="flex flex-wrap items-baseline justify-between gap-2 mb-4">
                            <h2 class="text-lg font-bold text-white">Elevation Profile</h2>
                            <p class="text-xs text-gray-500">Click the graph to jump the map to that point.</p>
                        </div>
                        <div class="relative h-56 md:h-72">
                            <canvas id="elevation-chart" aria-label="{{ $course['title'] }} elevation profile" role="img"></canvas>
                        </div>
                    </div>

                    {{-- GPX download: QR for scanning from a computer, button for people already on their phone --}}
                    @if (! empty($course['gpx_url']))
                        <div class="mt-6 rounded-2xl border border-white/10 bg-[#1a1a1a] p-5 md:p-6 flex flex-col sm:flex-row sm:items-center gap-5 md:gap-6">
                            @if (! empty($course['gpx_qr']))
                                <img src="{{ $course['gpx_qr'] }}" alt="QR code to download the GPX files"
                                    class="hidden sm:block w-32 h-32 flex-shrink-0 rounded-xl bg-white p-2 object-contain">
                            @endif
                            <div class="flex-1">
                                <h2 class="text-lg font-bold text-white">Download the GPX</h2>
                                <p class="text-sm text-gray-400 mt-1 max-w-xl">
                                    Load the course onto your GPS watch or phone app.
                                    <span class="hidden sm:inline">Scan the code with your phone, or open the folder.</span>
                                    The folder has the GPX files for all four distances.
                                </p>
                                <a href="{{ $course['gpx_url'] }}" target="_blank" rel="noopener"
                                    class="inline-flex items-center gap-2 mt-4 py-2.5 px-5 rounded-lg bg-[color:var(--accent)] hover:brightness-110 text-white text-sm font-bold transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                    Open GPX folder
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ========================================================
                         AID STATIONS SIDEBAR
                    ======================================================== --}}
                <aside class="rounded-2xl border border-white/10 bg-[#1a1a1a] flex flex-col lg:sticky lg:top-24 lg:max-h-[calc(100vh-7rem)]">
                    <div class="px-5 pt-5 pb-4 border-b border-white/10">
                        <h2 class="text-lg font-bold text-white">Aid Stations</h2>
                        @if (! empty($course['aid_stations']))
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-xs text-gray-400">
                                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-white border-2 border-[color:var(--accent)]"></span> Aid station</span>
                                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-sky-500 border-2 border-white"></span> Water station</span>
                            </div>
                            @if (! empty($course['finish_cutoff']))
                                <p class="text-xs text-gray-400 mt-2">Finish cutoff <span class="text-white font-semibold">{{ $course['finish_cutoff'] }}</span></p>
                            @endif
                        @endif
                    </div>

                    @if (empty($course['aid_stations']))
                        <div class="p-6 text-center">
                            <p class="text-white font-semibold mb-1">Aid station locations will be announced soon.</p>
                            <p class="text-gray-400 text-sm">We'll mark every station on the map and the elevation graph once they're final.</p>
                        </div>
                    @else
                        <div class="station-scroll overflow-y-auto overscroll-contain p-3 space-y-3">
                            <template x-for="(s, i) in stations" :key="s.code">
                                <button type="button" @click="focusStation(i)"
                                    @mouseenter="showPosition(s.i, true)" @mouseleave="clearPosition()"
                                    :aria-label="'Show ' + s.code + ' ' + s.name + ' on the map'"
                                    :class="active === i
                                        ? 'bg-[rgb(var(--accent-rgb)/0.1)] border-[color:var(--accent)] shadow-lg'
                                        : 'bg-[#222222] border-white/10 hover:bg-[#282828] hover:border-[rgb(var(--accent-rgb)/0.6)] hover:-translate-y-0.5 hover:shadow-lg hover:shadow-black/40'"
                                    class="group w-full text-left rounded-xl border p-4 cursor-pointer transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--accent)]">
                                    <div class="flex items-center gap-2">
                                        <span class="flex-shrink-0 w-6 h-6 rounded-full text-[11px] font-black flex items-center justify-center border-2"
                                            :class="s.water ? 'bg-sky-500 text-white border-white' : 'bg-white text-black border-[color:var(--accent)]'" x-text="s.num"></span>
                                        <p class="text-[11px] font-bold uppercase tracking-wider" :class="s.water ? 'text-sky-400' : 'text-[color:var(--accent)]'" x-text="s.code"></p>
                                        <p class="ml-auto text-xs text-gray-400 whitespace-nowrap">KM <span class="text-white font-semibold" x-text="s.km.toFixed(2)"></span></p>
                                    </div>
                                    <p class="text-white font-bold leading-snug mt-2" x-text="s.name"></p>
                                    <template x-if="s.intermediate_cutoff">
                                        <p class="inline-block mt-1.5 text-[11px] font-bold uppercase tracking-wider text-white bg-[color:var(--accent)] rounded px-2 py-0.5">Intermediate cutoff</p>
                                    </template>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        <span x-text="s.ele.toLocaleString() + ' m'"></span>
                                        <template x-if="s.cutoff"><span> · Cutoff <span class="text-white" x-text="s.cutoff"></span></span></template>
                                    </p>
                                    <template x-if="s.food">
                                        <p class="text-xs text-gray-300 mt-2" x-text="s.food"></p>
                                    </template>
                                    <template x-if="s.services.length">
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            <template x-for="svc in s.services" :key="svc">
                                                <span class="text-[11px] text-gray-300 bg-white/5 border border-white/10 rounded-full px-2 py-0.5" x-text="svc"></span>
                                            </template>
                                        </div>
                                    </template>
                                    <p class="text-[11px] text-gray-500 mt-2">
                                        Next: <span x-text="s.nextName"></span>,
                                        <span class="text-gray-300" x-text="s.toNextKm.toFixed(2) + ' km'"></span>
                                        <span class="text-[color:var(--accent)]" x-text="'+' + s.toNextGain.toLocaleString() + ' m'"></span>
                                        <span class="text-sky-400" x-text="'-' + s.toNextLoss.toLocaleString() + ' m'"></span>
                                    </p>                                </button>
                            </template>
                        </div>
                    @endif
                </aside>

            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/maplibre-gl/5.24.0/maplibre-gl.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    const TRACK_URL = @json($trackUrl);
    const STATIONS = @json($course['aid_stations']);
    const ACCENT = @json($accent);
    const ACCENT_RGB = @json($accentRgb);
    const TERRAIN_EXAGGERATION = 1.4;

    // Map and chart live outside Alpine's reactive state; proxying them breaks both libraries.
    let map, chart, pts = [], posMarker, stationMarkers = [], routeBounds;

    // In 3D, camera moves leave the map aimed at sea level, so in these mountains the view
    // lands far off target (a compact course can drop out of view entirely). After a move,
    // glide the camera up to the real ground height at the center. It waits for the map to
    // go idle: until the terrain tiles load, MapLibre keeps resetting the height to zero.
    // `fallbackEle` is the track's own height there, in case the terrain still isn't known.
    function liftToGround(center, fallbackEle) {
        map.once('idle', () => {
            const from = map.getCenterElevation();
            const to = map.queryTerrainElevation(center) ?? fallbackEle * TERRAIN_EXAGGERATION;
            const start = performance.now();
            const step = (now) => {
                const t = Math.min(1, (now - start) / 350);
                map.setCenterElevation(from + (to - from) * (1 - (1 - t) ** 3));
                if (t < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        });
    }

    // Spacing for km markers and graph ticks, so short courses still get a few.
    function kmStep() {
        const total = pts[pts.length - 1][3];
        return total > 50 ? 10 : total > 15 ? 5 : 2;
    }

    // Elevation gain/loss with a 5 m threshold, matching the course:build command.
    function climb(from, to) {
        let gain = 0, loss = 0, ref = pts[from][2];
        for (let i = from; i <= to; i++) {
            const e = pts[i][2];
            if (e - ref >= 5) { gain += e - ref; ref = e; }
            else if (ref - e >= 5) { loss += ref - e; ref = e; }
        }
        return [Math.round(gain), Math.round(loss)];
    }

    function indexAtKm(km) {
        let lo = 0, hi = pts.length - 1;
        while (lo < hi) {
            const mid = (lo + hi) >> 1;
            if (pts[mid][3] < km) lo = mid + 1; else hi = mid;
        }
        return lo;
    }

    // Nearest track point, optionally only between two indexes.
    function nearestIndex(lng, lat, from = 0, to = pts.length - 1) {
        const k = Math.cos(lat * Math.PI / 180);
        let best = from, bestD = Infinity;
        for (let i = from; i <= to; i++) {
            const dx = (pts[i][0] - lng) * k, dy = pts[i][1] - lat;
            const d = dx * dx + dy * dy;
            if (d < bestD) { bestD = d; best = i; }
        }
        return best;
    }

    // Grade over roughly 100 m either side of the point.
    function gradeAt(i) {
        const a = indexAtKm(Math.max(0, pts[i][3] - 0.1));
        const b = indexAtKm(Math.min(pts[pts.length - 1][3], pts[i][3] + 0.1));
        const run = (pts[b][3] - pts[a][3]) * 1000;
        return run > 0 ? Math.round((pts[b][2] - pts[a][2]) / run * 100) : 0;
    }

    function dot(className, text) {
        const el = document.createElement('div');
        el.className = className;
        if (text !== undefined) el.textContent = text;
        return el;
    }

    Alpine.data('courseMap', () => ({
        mode: '3d',
        active: null,
        loading: true,
        error: null,
        hover: null,
        stations: [],

        async init() {
            try {
                const res = await fetch(TRACK_URL);
                if (!res.ok) throw new Error(res.status);
                pts = (await res.json()).points;
            } catch (e) {
                this.loading = false;
                this.error = 'The course map could not be loaded. Please refresh the page.';
                return;
            }

            this.stations = this.resolveStations();
            this.buildMap();
            this.buildChart();
        },

        // Place each station on the track. Coordinates are only matched within a few km
        // of the official km, since the course passes some places twice.
        resolveStations() {
            const placed = STATIONS.map(s => {
                let i = indexAtKm(s.km);
                if (s.lat != null && s.lng != null) {
                    i = nearestIndex(s.lng, s.lat, indexAtKm(s.km - 4), indexAtKm(s.km + 4));
                }
                return {
                    ...s,
                    i,
                    ele: Math.round(pts[i][2]),
                    water: s.code.toUpperCase().startsWith('WS'),
                    num: s.code.replace(/\D/g, ''),
                    services: s.services || [],
                };
            }).sort((a, b) => a.km - b.km);

            return placed.map((s, n) => {
                const next = placed[n + 1];
                const to = next ? next.i : pts.length - 1;
                const [gain, loss] = climb(s.i, to);
                return {
                    ...s,
                    nextName: next ? `${next.code} ${next.name}` : 'Finish',
                    toNextKm: next ? next.km - s.km : pts[to][3] - pts[s.i][3],
                    toNextGain: gain,
                    toNextLoss: loss,
                };
            });
        },

        buildMap() {
            const line = { type: 'Feature', geometry: { type: 'LineString', coordinates: pts.map(p => [p[0], p[1]]) } };
            const first = [pts[0][0], pts[0][1]];
            const bounds = routeBounds = pts.reduce((b, p) => b.extend([p[0], p[1]]), new maplibregl.LngLatBounds(first, first));

            map = new maplibregl.Map({
                container: 'route-map',
                bounds,
                fitBoundsOptions: { padding: 40 },
                maxPitch: 80,
                cooperativeGestures: true,
                attributionControl: { compact: true },
                style: {
                    version: 8,
                    sources: {
                        satellite: {
                            type: 'raster',
                            tiles: ['https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'],
                            tileSize: 256,
                            maxzoom: 19,
                            attribution: 'Imagery © Esri, Maxar, Earthstar Geographics',
                        },
                        dem: {
                            type: 'raster-dem',
                            tiles: ['https://s3.amazonaws.com/elevation-tiles-prod/terrarium/{z}/{x}/{y}.png'],
                            tileSize: 256,
                            maxzoom: 15,
                            encoding: 'terrarium',
                            attribution: 'Terrain © Mapzen, AWS',
                        },
                    },
                    layers: [
                        { id: 'satellite', type: 'raster', source: 'satellite' },
                    ],
                },
            });

            map.addControl(new maplibregl.NavigationControl({ visualizePitch: true }), 'top-right');
            map.addControl(new maplibregl.FullscreenControl(), 'top-right');
            map.addControl(new maplibregl.ScaleControl({ unit: 'metric' }), 'bottom-right');

            map.on('load', () => {
                map.addSource('route', { type: 'geojson', data: line });
                map.addLayer({ id: 'route-casing', type: 'line', source: 'route',
                    layout: { 'line-join': 'round', 'line-cap': 'round' },
                    paint: { 'line-color': '#000', 'line-width': 7, 'line-opacity': 0.55 } });
                map.addLayer({ id: 'route-line', type: 'line', source: 'route',
                    layout: { 'line-join': 'round', 'line-cap': 'round' },
                    paint: { 'line-color': ACCENT, 'line-width': 4 } });
                // Wide invisible line so the route is easy to hover.
                map.addLayer({ id: 'route-hit', type: 'line', source: 'route',
                    paint: { 'line-color': '#000', 'line-width': 18, 'line-opacity': 0 } });

                this.addMarkers();
                this.loading = false;
                this.setMode(this.mode, true);
            });

            map.on('mousemove', 'route-hit', e => {
                map.getCanvas().style.cursor = 'crosshair';
                this.showPosition(nearestIndex(e.lngLat.lng, e.lngLat.lat), true);
            });
            map.on('mouseleave', 'route-hit', () => {
                map.getCanvas().style.cursor = '';
                this.clearPosition();
            });
        },

        addMarkers() {
            const start = pts[0], end = pts[pts.length - 1];
            const loop = Math.hypot(start[0] - end[0], start[1] - end[1]) < 0.0005;

            const flag = (p, label) => new maplibregl.Marker({
                element: dot('px-2 py-1 rounded-md bg-black text-white text-[11px] font-bold uppercase tracking-wider border border-white/30 shadow', label),
                anchor: 'bottom', offset: [0, -6],
            }).setLngLat([p[0], p[1]]).addTo(map);

            flag(start, loop ? 'Start / Finish' : 'Start');
            if (!loop) flag(end, 'Finish');

            const total = end[3];
            const step = kmStep();
            for (let km = step; km < total - step / 5; km += step) {
                const p = pts[indexAtKm(km)];
                new maplibregl.Marker({
                    element: dot('min-w-[22px] h-[18px] px-1 rounded-full bg-white/90 text-black text-[10px] font-bold flex items-center justify-center shadow', km),
                }).setLngLat([p[0], p[1]]).addTo(map);
            }

            stationMarkers = this.stations.map(s => {
                const p = pts[s.i];
                const html = document.createElement('div');
                html.append(
                    dot('text-[11px] font-bold uppercase tracking-wider ' + (s.water ? 'text-sky-400' : 'text-[color:var(--accent)]'), s.code),
                    dot('font-bold text-white', s.name),
                    dot('text-xs text-gray-400 mt-0.5', `KM ${s.km.toFixed(1)} · ${s.ele.toLocaleString()} m` + (s.cutoff ? ` · Cutoff ${s.cutoff}` : '')),
                );
                if (s.intermediate_cutoff) html.append(dot('text-[11px] font-bold uppercase tracking-wider text-[color:var(--accent)] mt-1', 'Intermediate cutoff'));
                if (s.food) html.append(dot('text-xs text-gray-300 mt-1.5', s.food));
                if (s.services.length) html.append(dot('text-xs text-gray-500 mt-1', s.services.join(' · ')));

                return new maplibregl.Marker({
                    element: dot('w-7 h-7 rounded-full text-xs font-black flex items-center justify-center border-2 shadow-lg cursor-pointer '
                        + (s.water ? 'bg-sky-500 text-white border-white' : 'bg-white text-black border-[color:var(--accent)]'), s.num),
                })
                    .setLngLat([p[0], p[1]])
                    .setPopup(new maplibregl.Popup({ offset: 16, maxWidth: '260px' }).setDOMContent(html))
                    .addTo(map);
            });

            posMarker = new maplibregl.Marker({
                element: dot('w-4 h-4 rounded-full bg-[color:var(--accent)] border-[3px] border-white shadow-lg pointer-events-none'),
            }).setLngLat([start[0], start[1]]);
        },

        buildChart() {
            const ctx = document.getElementById('elevation-chart');
            const eles = pts.map(p => p[2]);
            const yMin = Math.max(0, Math.floor(Math.min(...eles) / 200) * 200 - 200);
            const stations = this.stations;

            // Aid station lines and the hover crosshair, drawn over the profile.
            const overlay = {
                id: 'courseOverlay',
                afterDatasetsDraw(c) {
                    const { ctx: g, chartArea: { top, bottom }, scales: { x } } = c;
                    const r = c.width < 500 ? 6.5 : 8;
                    g.save();
                    stations.forEach(s => {
                        const px = x.getPixelForValue(pts[s.i][3]);
                        g.strokeStyle = 'rgba(255,255,255,.35)';
                        g.setLineDash([3, 3]);
                        g.beginPath(); g.moveTo(px, top + 10); g.lineTo(px, bottom); g.stroke();
                        g.setLineDash([]);
                        g.fillStyle = s.water ? '#0ea5e9' : '#fff';
                        g.beginPath(); g.arc(px, top + 2, r, 0, Math.PI * 2); g.fill();
                        g.fillStyle = s.water ? '#fff' : '#000';
                        g.font = `bold ${r < 8 ? 8 : 10}px Figtree, sans-serif`;
                        g.textAlign = 'center'; g.textBaseline = 'middle';
                        g.fillText(s.num, px, top + 2.5);
                    });
                    const active = c.getActiveElements()[0];
                    if (active) {
                        g.strokeStyle = 'rgba(255,255,255,.6)';
                        g.beginPath(); g.moveTo(active.element.x, top); g.lineTo(active.element.x, bottom); g.stroke();
                    }
                    g.restore();
                },
            };

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [{
                        data: pts.map(p => ({ x: p[3], y: p[2] })),
                        borderColor: ACCENT,
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: ACCENT,
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                        fill: true,
                        backgroundColor: c => {
                            const area = c.chart.chartArea;
                            if (!area) return `rgba(${ACCENT_RGB},.25)`;
                            const grad = c.chart.ctx.createLinearGradient(0, area.top, 0, area.bottom);
                            grad.addColorStop(0, `rgba(${ACCENT_RGB},.45)`);
                            grad.addColorStop(1, `rgba(${ACCENT_RGB},.03)`);
                            return grad;
                        },
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    layout: { padding: { top: 12 } },
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        x: {
                            type: 'linear', min: 0, max: pts[pts.length - 1][3],
                            // Whole-step ticks only, so the track's odd end distance isn't labelled.
                            ticks: { color: '#9ca3af', stepSize: kmStep(), maxRotation: 0, callback: v => v % kmStep() === 0 ? v + ' km' : '' },
                            grid: { color: 'rgba(255,255,255,.06)' },
                        },
                        y: {
                            min: yMin,
                            ticks: { color: '#9ca3af', callback: v => v.toLocaleString() + ' m' },
                            grid: { color: 'rgba(255,255,255,.06)' },
                        },
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            displayColors: false,
                            backgroundColor: 'rgba(0,0,0,.85)',
                            callbacks: {
                                title: items => 'KM ' + items[0].parsed.x.toFixed(1),
                                label: item => Math.round(item.parsed.y).toLocaleString() + ' m',
                            },
                        },
                    },
                    onHover: (e, active) => {
                        if (active.length) this.showPosition(active[0].index, false);
                    },
                    onClick: (e, active) => {
                        if (!active.length) return;
                        const p = pts[active[0].index];
                        map.easeTo({ center: [p[0], p[1]], zoom: Math.max(map.getZoom(), 14), duration: 800 });
                    },
                },
                plugins: [overlay],
            });

            ctx.addEventListener('mouseleave', () => this.clearPosition());
        },

        // Moves the map dot and readout; from the map it also drives the chart tooltip.
        showPosition(i, fromMap) {
            const p = pts[i];
            if (posMarker) {
                posMarker.setLngLat([p[0], p[1]]);
                if (!posMarker._map) posMarker.addTo(map);
            }
            this.hover = { km: p[3], ele: Math.round(p[2]), grade: gradeAt(i) };

            if (fromMap && chart) {
                const el = chart.getDatasetMeta(0).data[i];
                chart.setActiveElements([{ datasetIndex: 0, index: i }]);
                chart.tooltip.setActiveElements([{ datasetIndex: 0, index: i }], { x: el.x, y: el.y });
                chart.update('none');
            }
        },

        clearPosition() {
            posMarker?.remove();
            this.hover = null;
            if (chart) {
                chart.setActiveElements([]);
                chart.tooltip.setActiveElements([], { x: 0, y: 0 });
                chart.update('none');
            }
        },

        // `frame` fits the whole course in the tilted view (on load); a toggle keeps the current spot.
        setMode(mode, frame = false) {
            this.mode = mode;
            if (!map) return;

            if (mode === '3d') {
                map.setTerrain({ source: 'dem', exaggeration: TERRAIN_EXAGGERATION });
                map.dragRotate.enable();
                map.touchZoomRotate.enableRotation();
                if (frame) {
                    map.fitBounds(routeBounds, { padding: 60, pitch: 62, bearing: -20, duration: 1200 });
                    const c = routeBounds.getCenter();
                    const avgEle = pts.reduce((s, p) => s + p[2], 0) / pts.length;
                    map.once('moveend', () => liftToGround([c.lng, c.lat], avgEle));
                } else {
                    map.easeTo({ pitch: 62, bearing: map.getBearing() || -20, duration: 1200 });
                }
            } else {
                map.setTerrain(null);
                map.dragRotate.disable();
                map.touchZoomRotate.disableRotation();
                map.easeTo({ pitch: 0, bearing: 0, duration: 800 });
            }
        },

        focusStation(n) {
            const s = this.stations[n];
            const p = pts[s.i];
            this.active = n;
            document.getElementById('route-map').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            const center = [p[0], p[1]];
            map.flyTo({ center, zoom: this.mode === '3d' ? 13.5 : 14.5, duration: 1200 });
            if (this.mode === '3d') map.once('moveend', () => liftToGround(center, p[2]));

            stationMarkers.forEach((m, k) => {
                // Some places are passed twice (AS2/AS6), so lift the chosen pin above its twin.
                m.getElement().style.zIndex = k === n ? '2' : '';
                if (k === n && !m.getPopup().isOpen()) m.togglePopup();
                if (k !== n && m.getPopup().isOpen()) m.togglePopup();
            });
            this.showPosition(s.i, true);
        },
    }));
});
</script>
@endpush
