{{--
    Guest cart, kept in localStorage so it survives page loads without an account.
    Only variant ids and quantities are stored; names, prices and stock always come
    from the server catalog rendered into this page.
--}}
<script>
    window.SHOP_CART_KEY = 'ricon_shop_cart_v1';

    document.addEventListener('alpine:init', () => {
        Alpine.store('cart', {
            catalog: @js((object) $catalog),
            maxQty: @js(config('shop.max_quantity')),
            items: [],
            open: false,
            notice: '',

            init() {
                this.load();
                this.reconcile();
                // Keep tabs in sync
                window.addEventListener('storage', (e) => {
                    if (e.key === window.SHOP_CART_KEY) { this.load(); this.reconcile(); }
                });
            },

            load() {
                try {
                    const parsed = JSON.parse(localStorage.getItem(window.SHOP_CART_KEY) || '[]');
                    this.items = Array.isArray(parsed) ? parsed.filter(i => i && i.id && i.qty > 0) : [];
                } catch (e) {
                    this.items = [];
                }
            },

            save() {
                try { localStorage.setItem(window.SHOP_CART_KEY, JSON.stringify(this.items)); } catch (e) {}
            },

            // Drop items that no longer exist or are sold out, and clamp to what's left
            reconcile() {
                const before = this.count();
                this.items = this.items
                    .filter(i => this.catalog[i.id] && this.limit(i.id) > 0)
                    .map(i => ({ id: i.id, qty: Math.min(i.qty, this.limit(i.id)) }));
                if (this.count() < before) {
                    this.notice = 'Some items in your cart sold out or ran low, so your cart was updated.';
                }
                this.save();
            },

            limit(id) {
                const v = this.catalog[id];
                return v ? Math.min(v.stock, this.maxQty) : 0;
            },

            qtyOf(id) {
                return this.items.find(i => i.id === id)?.qty || 0;
            },

            // How many more of this variant can still go in the cart
            available(id) {
                return Math.max(0, this.limit(id) - this.qtyOf(id));
            },

            add(id, qty) {
                const take = Math.min(qty, this.available(id));
                if (take <= 0) return 0;
                const line = this.items.find(i => i.id === id);
                if (line) line.qty += take; else this.items.push({ id, qty: take });
                this.save();
                this.open = true;
                return take;
            },

            setQty(id, qty) {
                const line = this.items.find(i => i.id === id);
                if (!line) return;
                line.qty = Math.max(1, Math.min(qty, this.limit(id)));
                this.save();
            },

            remove(id) {
                this.items = this.items.filter(i => i.id !== id);
                this.save();
            },

            clear() {
                this.items = [];
                this.save();
            },

            lines() {
                return this.items
                    .filter(i => this.catalog[i.id])
                    .map(i => ({ ...this.catalog[i.id], qty: i.qty, total: this.catalog[i.id].price * i.qty }));
            },

            count() {
                return this.items.reduce((sum, i) => sum + i.qty, 0);
            },

            subtotal() {
                return this.lines().reduce((sum, l) => sum + l.total, 0);
            },

            options(line) {
                return [line.color, line.size ? 'Size ' + line.size : null].filter(Boolean).join(' · ') || 'One size';
            },

            peso(amount) {
                return '₱' + Number(amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
        });
    });
</script>
