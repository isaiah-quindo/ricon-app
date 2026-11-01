<script>
    function shopOrder(config) {
        return {
            ...config,
            step: 'choose',
            size: null,
            quantity: 1,
            form: { full_name: '', email: '', mobile_number: '', notify_consent: false },
            errors: {},
            submitError: '',
            submitting: false,
            reference: '',

            peso(amount) {
                return '₱' + Number(amount).toLocaleString('en-PH');
            },

            lineLabel() {
                return this.quantity + ' × ' + this.name + (this.size ? ' (' + this.size + ')' : '');
            },

            continueToDetails() {
                if (this.hasSizes && !this.size) {
                    this.errors = { size: true };
                    return;
                }
                this.errors = {};
                this.step = 'details';
                this.$nextTick(() => document.getElementById(this.idPrefix + '-full_name')?.focus());
            },

            // Start a fresh reservation for this item, keeping the buyer's details filled in
            reset() {
                this.step = 'choose';
                this.size = null;
                this.quantity = 1;
                this.reference = '';
                this.errors = {};
            },

            validate() {
                const f = this.form;
                this.errors = {
                    full_name: !f.full_name.trim(),
                    email: !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email.trim()),
                    mobile_number: f.mobile_number.trim().length < 7,
                    notify_consent: !f.notify_consent,
                };
                return !Object.values(this.errors).some(Boolean);
            },

            async submit() {
                this.submitError = '';
                if (!this.validate()) return;

                this.submitting = true;
                try {
                    const payload = { ...this.form, quantity: this.quantity };
                    if (this.hasSizes) payload.size = this.size;

                    const res = await fetch(this.orderUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? "{{ csrf_token() }}",
                        },
                        body: JSON.stringify(payload),
                    });

                    if (res.status === 429) {
                        this.submitError = 'Too many attempts. Please wait a minute and try again.';
                    } else if (res.status === 422) {
                        const json = await res.json();
                        this.submitError = json.errors ? Object.values(json.errors)[0][0] : 'Please check your entries and try again.';
                    } else if (!res.ok) {
                        this.submitError = 'Something went wrong. Please try again.';
                    } else {
                        this.reference = (await res.json()).reference;
                        this.step = 'done';
                    }
                } catch (e) {
                    this.submitError = 'Something went wrong. Please check your connection and try again.';
                } finally {
                    this.submitting = false;
                }
            },
        };
    }
</script>
