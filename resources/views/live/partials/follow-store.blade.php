{{--
    Which runners this viewer follows. It's a per-viewer convenience, so it lives in this
    browser only (localStorage). Storage can be blocked, e.g. in private browsing, so every
    read and write is wrapped in try/catch; following then just lasts until the tab closes.
--}}
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('follow', {
        key: @js('live:follow:' . $slug),
        bibs: [],

        // Alpine calls a store's init() once, before the page's components start.
        init() {
            try {
                this.bibs = JSON.parse(localStorage.getItem(this.key)) || [];
            } catch {
                this.bibs = [];
            }
        },

        has(bib) {
            return this.bibs.includes(bib);
        },

        toggle(bib) {
            this.bibs = this.has(bib) ? this.bibs.filter(b => b !== bib) : [...this.bibs, bib];
            try {
                localStorage.setItem(this.key, JSON.stringify(this.bibs));
            } catch {}
        },
    });
});
</script>
