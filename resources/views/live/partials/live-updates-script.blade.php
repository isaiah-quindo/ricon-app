{{--
    Shared by the race centre and runner pages: asks the server for fresh data on a timer.

    liveUpdates(seconds) returns state and methods to mix into an Alpine component. The
    component provides updatesUrl(), and can provide applyUpdates(data) for anything in the
    response that isn't HTML. Each key in data.regions is HTML for the element with the
    matching x-ref, rendered on the server with the same Blade partial as the first load.
--}}
<script>
window.liveUpdates = (seconds) => {
    // Kept outside Alpine's reactive state on purpose: an AbortController stops working
    // when Alpine wraps it in a Proxy.
    let inFlight = null;

    return {
        updatedAt: Date.now(),
        secondsAgo: 0,
        failed: false,
        loading: false,

        startUpdates() {
            setInterval(() => this.secondsAgo = Math.floor((Date.now() - this.updatedAt) / 1000), 1000);

            // No point refreshing a tab nobody is looking at.
            setInterval(() => {
                if (document.visibilityState === 'visible') this.fetchUpdates();
            }, seconds * 1000);

            // Coming back to a tab that sat in the background: refresh now rather than wait.
            document.addEventListener('visibilitychange', () => {
                if (document.visibilityState === 'visible' && this.secondsAgo >= seconds) this.fetchUpdates();
            });
        },

        async fetchUpdates() {
            inFlight?.abort(); // a newer request (say, a different checkpoint) replaces one still loading
            inFlight = new AbortController();
            this.loading = true;

            try {
                const response = await fetch(this.updatesUrl(), {
                    headers: { Accept: 'application/json' },
                    signal: inFlight.signal,
                });
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                const data = await response.json();

                for (const [name, html] of Object.entries(data.regions ?? {})) {
                    const region = this.$refs[name];
                    // Don't replace the part someone is tabbing through; it catches up next time.
                    if (region && !region.contains(document.activeElement)) region.innerHTML = html;
                }
                this.applyUpdates?.(data);

                this.updatedAt = Date.now();
                this.secondsAgo = 0;
                this.failed = false;
                this.loading = false;
            } catch (error) {
                if (error.name === 'AbortError') return;
                console.warn('Live update failed:', error);
                this.failed = true;
                this.loading = false;
            }
        },

        updatedText() {
            if (this.secondsAgo < 5) return 'just now';
            if (this.secondsAgo < 60) return `${this.secondsAgo} seconds ago`;
            return `${Math.floor(this.secondsAgo / 60)} min ago`;
        },
    };
};
</script>
