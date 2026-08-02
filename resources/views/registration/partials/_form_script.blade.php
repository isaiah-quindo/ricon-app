{{--
    Alpine component shared by individual and group registration.

    $minParticipants      — floor the remove button and validation respect (1 or 5)
    $maxParticipants      — ceiling for "add another person"
    $initialParticipants  — how many blank cards to render on first load
    $allowDiscountCode    — individual only; the group page has no code field
--}}
@php
    $minParticipants     = $minParticipants ?? 1;
    $maxParticipants     = $maxParticipants ?? 20;
    $initialParticipants = $initialParticipants ?? 1;
    $allowDiscountCode   = $allowDiscountCode ?? false;
    $collectOrganizer    = $collectOrganizer ?? false;
    $nationalities = ['Afghan','Albanian','Algerian','American','Andorran','Angolan','Argentinian','Armenian','Australian','Austrian','Azerbaijani','Bahamian','Bahraini','Bangladeshi','Barbadian','Belarusian','Belgian','Belizean','Beninese','Bhutanese','Bolivian','Bosnian','Botswanan','Brazilian','British','Bruneian','Bulgarian','Burkinabe','Burundian','Cambodian','Cameroonian','Canadian','Cape Verdean','Central African','Chadian','Chilean','Chinese','Colombian','Comorian','Congolese','Costa Rican','Croatian','Cuban','Cypriot','Czech','Danish','Djiboutian','Dominican','Dutch','East Timorese','Ecuadorian','Egyptian','Emirati','Equatorial Guinean','Eritrean','Estonian','Ethiopian','Fijian','Filipino','Finnish','French','Gabonese','Gambian','Georgian','German','Ghanaian','Greek','Grenadian','Guatemalan','Guinean','Guyanese','Haitian','Honduran','Hungarian','Icelandic','Indian','Indonesian','Iranian','Iraqi','Irish','Israeli','Italian','Ivorian','Jamaican','Japanese','Jordanian','Kazakhstani','Kenyan','Kiribati','Kuwaiti','Kyrgyz','Laotian','Latvian','Lebanese','Lesothan','Liberian','Libyan','Liechtensteiner','Lithuanian','Luxembourgish','Macedonian','Malagasy','Malawian','Malaysian','Maldivian','Malian','Maltese','Marshallese','Mauritanian','Mauritian','Mexican','Micronesian','Moldovan','Monacan','Mongolian','Montenegrin','Moroccan','Mozambican','Namibian','Nauruan','Nepalese','New Zealander','Nicaraguan','Nigerian','Nigerien','North Korean','Norwegian','Omani','Pakistani','Palauan','Palestinian','Panamanian','Papua New Guinean','Paraguayan','Peruvian','Polish','Portuguese','Qatari','Romanian','Russian','Rwandan','Salvadoran','Samoan','San Marinese','Sao Tomean','Saudi','Senegalese','Serbian','Seychellois','Sierra Leonean','Singaporean','Slovak','Slovenian','Solomon Islander','Somali','South African','South Korean','South Sudanese','Spanish','Sri Lankan','Sudanese','Surinamese','Swazi','Swedish','Swiss','Syrian','Taiwanese','Tajik','Tanzanian','Thai','Togolese','Tongan','Trinidadian','Tunisian','Turkish','Turkmen','Tuvaluan','Ugandan','Ukrainian','Uruguayan','Uzbek','Vanuatuan','Venezuelan','Vietnamese','Yemeni','Zambian','Zimbabwean'];
@endphp

<script type="application/json" id="race-categories-data">
    {!! json_encode($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'distance_km' => $c->distance_km, 'price' => (float) $c->price])->values()) !!}
</script>

<script type="application/json" id="registration-bootstrap">
    {!! json_encode([
        'oldParticipants'     => array_values((array) old('participants', [])),
        'oldOrganizer'        => (array) old('organizer', []),
        'errors'              => $errors->messages(),
        'oldPaymentMethod'    => old('payment_method', ''),
        'oldDiscountCode'     => old('discount_code', ''),
        'nationalities'       => $nationalities,
        'minParticipants'     => $minParticipants,
        'maxParticipants'     => $maxParticipants,
        'initialParticipants' => $initialParticipants,
        'allowDiscountCode'   => $allowDiscountCode,
        'collectOrganizer'    => $collectOrganizer ?? false,
    ]) !!}
</script>

<script>
    // Keep in sync with RegistrationGroup::TIERS.
    const TIERS = [
        { min: 10, percentage: 10 },
        { min: 5, percentage: 5 },
    ];

    const REQUIRED_FIELDS = [
        'race_category_id', 'first_name', 'last_name', 'sex', 'birthdate', 'email',
        'mobile_number', 'nationality', 'address', 'shirt_size',
        'emergency_contact_name', 'emergency_contact_number',
    ];

    function registrationForm() {
        const categories = JSON.parse(document.getElementById('race-categories-data').textContent);
        const boot = JSON.parse(document.getElementById('registration-bootstrap').textContent);
        const NATIONALITIES = boot.nationalities;

        let uid = 0;
        const blank = (seed = {}) => ({
            _id: ++uid,
            _open: true,
            race_category_id: '', first_name: '', last_name: '', sex: '', birthdate: '',
            email: '', mobile_number: '', nationality: '', affiliation: '', address: '',
            shirt_size: '', emergency_contact_name: '', emergency_contact_number: '',
            ...seed,
        });

        // On a fresh load the group page opens with several blank cards; only the first
        // is expanded so the page stays scannable. A validation bounce reopens them all
        // so nothing that needs fixing is hidden.
        const seeded = boot.oldParticipants.length
            ? boot.oldParticipants.map(p => blank(p))
            : Array.from({ length: boot.initialParticipants }, (_, i) => blank({ _open: i === 0 }));

        return {
            reviewing: false,
            sizeGuideOpen: false,
            submitting: false,
            showWaiver: false,
            minParticipants: boot.minParticipants,
            maxParticipants: boot.maxParticipants,
            allowDiscountCode: boot.allowDiscountCode,
            maxBirthdate: new Date().toISOString().slice(0, 10),

            participants: seeded,
            payment_method: boot.oldPaymentMethod,
            waiver_agreed: false,
            terms_agreed: false,
            fileName: '',

            collectOrganizer: boot.collectOrganizer,
            organizer: {
                name: boot.oldOrganizer.name || '',
                email: boot.oldOrganizer.email || '',
                mobile: boot.oldOrganizer.mobile || '',
                team: boot.oldOrganizer.team || '',
            },
            organizerSameAsFirst: false,

            discount_code: boot.oldDiscountCode,
            appliedDiscount: null,
            discountError: '',
            discountChecking: false,

            serverErrors: boot.errors,
            clientErrors: {},
            showClientErrorBanner: false,
            clientErrorMessage: '',

            _lastSignature: '',
            _quoteSeq: 0,

            init() {
                this._lastSignature = this.categorySignature;

                // While "I am also participant 1" is ticked, the organizer fields track
                // participant 1 as it is edited rather than only copying once.
                if (this.collectOrganizer) {
                    this.$watch('participants', () => {
                        if (this.organizerSameAsFirst) this.syncOrganizer();
                    });
                }
                // A code is scoped to one race category, so changing the category has to
                // re-check it. Only relevant on the individual page.
                if (!this.allowDiscountCode) return;
                this.$watch('participants', () => {
                    const signature = this.categorySignature;
                    if (signature === this._lastSignature) return;
                    this._lastSignature = signature;
                    if (this.appliedDiscount) this.refreshQuote();
                });
            },

            // ---- categories & money -------------------------------

            categoryFor(i) {
                return categories.find(c => c.id === this.participants[i]?.race_category_id) || null;
            },

            categoryName(i) {
                return this.categoryFor(i)?.name || '';
            },

            priceFor(i) {
                return Number(this.categoryFor(i)?.price || 0);
            },

            get categorySignature() {
                return this.participants.map(p => p.race_category_id).join('|');
            },

            get hasAnyCategory() {
                return this.participants.some(p => p.race_category_id);
            },

            get subtotal() {
                return this.participants.reduce((sum, _, i) => sum + this.priceFor(i), 0);
            },

            get groupPercentage() {
                const tier = TIERS.find(t => this.participants.length >= t.min);
                return tier ? tier.percentage : 0;
            },

            get groupDiscount() {
                return round2(this.subtotal * this.groupPercentage / 100);
            },

            // Derived from live participant state rather than read off the last quote:
            // the server round-trip lags edits, so trusting it here showed a stale total
            // (and could land out of order). Mirrors GroupPricing::codeDiscount(), which
            // stays authoritative on submit.
            get codeDiscount() {
                // A code can cover several categories, so match against the whole set.
                const categoryIds = this.appliedDiscount?.race_category_ids ?? [];
                if (!categoryIds.length) return 0;

                const percentage = Number(this.appliedDiscount.percentage || 0);
                let total = 0;
                this.participants.forEach((p, i) => {
                    if (categoryIds.includes(p.race_category_id)) {
                        total += round2(this.priceFor(i) * percentage / 100);
                    }
                });
                return round2(total);
            },

            // A code and a group discount can never both apply: only the individual page
            // offers a code, and a party of one never reaches a tier.
            get discountSource() {
                if (this.codeDiscount > 0) return 'code';
                return this.groupDiscount > 0 ? 'group' : 'none';
            },

            get discountTotal() {
                if (this.discountSource === 'group') return this.groupDiscount;
                if (this.discountSource === 'code') return this.codeDiscount;
                return 0;
            },

            get totalDue() {
                return Math.max(0, round2(this.subtotal - this.discountTotal));
            },

            get nextTier() {
                const count = this.participants.length;
                const upcoming = [...TIERS].reverse().find(t => count < t.min);
                return upcoming ? { needed: upcoming.min - count, percentage: upcoming.percentage } : null;
            },

            get tierProgress() {
                const top = TIERS[0].min;
                return Math.min(100, (this.participants.length / top) * 100);
            },

            // ---- participants -------------------------------------

            get canRemove() {
                return this.participants.length > this.minParticipants;
            },

            get canAdd() {
                return this.participants.length < this.maxParticipants;
            },

            // These three are called from x-for bindings, which Alpine re-evaluates once
            // more while tearing a removed item down. At that point participants[i] is
            // already gone, so each one has to tolerate a missing entry.
            participantLabel(i) {
                const p = this.participants[i];
                if (!p) return '';
                const name = [p.first_name, p.last_name].filter(Boolean).join(' ').trim();
                // No "you" for participant 1 on the group form: whoever is booking is
                // captured in the organizer block and need not be racing at all.
                if (this.participants.length === 1) return name || 'Your details';
                return name || 'Participant ' + (i + 1);
            },

            participantSubLabel(i) {
                const p = this.participants[i];
                if (!p) return '';
                const parts = [];
                if (this.categoryName(i)) parts.push(this.categoryName(i));
                if (p.shirt_size) parts.push('Shirt ' + p.shirt_size);
                if (!parts.length) return this.participants.length === 1 ? '' : 'Tap to fill in details';
                return parts.join(' · ');
            },

            isComplete(i) {
                const p = this.participants[i];
                if (!p) return false;
                return REQUIRED_FIELDS.every(f => !!p[f]);
            },

            toggleCard(i) {
                this.participants[i]._open = !this.participants[i]._open;
            },

            addParticipant() {
                if (!this.canAdd) return;
                const inherited = this.sharedValues();
                this.participants.forEach(p => p._open = false);
                this.participants.push(blank(inherited));
                this.showClientErrorBanner = false;
                this.$nextTick(() => {
                    window.HSStaticMethods?.autoInit();
                    const cards = this.$el.querySelectorAll('[data-participant-card]');
                    cards[cards.length - 1]?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            },

            removeParticipant(i) {
                if (!this.canRemove) return;
                this.participants.splice(i, 1);
                delete this.clientErrors[i];
            },

            // Fields a group commonly shares. Offered as "Apply to everyone" on each
            // card, and inherited by anyone added later (see addParticipant).
            get shareableFields() {
                return ['affiliation', 'address', 'emergency_contact_name', 'emergency_contact_number'];
            },

            /** Pushes one card's values out to the rest of the party. */
            copyToAll(from, fields) {
                const source = this.participants[from];
                if (!source) return;
                this.participants.forEach((p, i) => {
                    if (i === from) return;
                    fields.forEach(field => { p[field] = source[field]; });
                });
            },

            copyAddressToAll(from = 0) {
                this.copyToAll(from, ['address']);
            },

            copyAffiliationToAll(from = 0) {
                this.copyToAll(from, ['affiliation']);
            },

            copyEmergencyContactToAll(from = 0) {
                this.copyToAll(from, ['emergency_contact_name', 'emergency_contact_number']);
            },

            /**
             * Values every existing participant already agrees on, which a new member
             * should start with too. Without this, adding a sixth person after using
             * "Apply to everyone" would silently leave them blank.
             */
            sharedValues() {
                const shared = {};
                this.shareableFields.forEach(field => {
                    const first = this.participants[0]?.[field];
                    if (first && this.participants.every(p => p[field] === first)) {
                        shared[field] = first;
                    }
                });
                return shared;
            },

            // ---- organizer ----------------------------------------

            syncOrganizer() {
                if (!this.organizerSameAsFirst) return;
                const first = this.participants[0];
                if (!first) return;
                this.organizer.name = [first.first_name, first.last_name].filter(Boolean).join(' ').trim();
                this.organizer.email = first.email || '';
                this.organizer.mobile = first.mobile_number || '';
                if (!this.organizer.team) this.organizer.team = first.affiliation || '';
            },

            organizerError(field) {
                if (this.clientErrors.organizer?.[field]) return 'This field is required.';
                return this.serverErrors['organizer.' + field]?.[0] || '';
            },

            // ---- errors -------------------------------------------

            serverHas(key) {
                return !!this.serverErrors[key];
            },

            fieldError(i, field) {
                if (this.clientErrors[i]?.[field]) return 'This field is required.';
                return this.serverErrors['participants.' + i + '.' + field]?.[0] || '';
            },

            validate() {
                this.clientErrors = {};

                if (this.collectOrganizer) {
                    const missing = ['name', 'email', 'mobile'].filter(f => !this.organizer[f]);
                    if (missing.length) {
                        this.clientErrors.organizer = Object.fromEntries(missing.map(f => [f, true]));
                        this.clientErrorMessage = 'Please say who is registering this group.';
                        return false;
                    }
                }

                this.participants.forEach((p, i) => {
                    REQUIRED_FIELDS.forEach(field => {
                        if (!p[field]) {
                            this.clientErrors[i] = this.clientErrors[i] || {};
                            this.clientErrors[i][field] = true;
                        }
                    });
                });

                const firstBad = this.participants.findIndex((_, i) => this.clientErrors[i]);
                if (firstBad !== -1) {
                    this.participants[firstBad]._open = true;
                    this.clientErrorMessage = this.participants.length === 1
                        ? 'Some details are still missing. Fields needing attention are highlighted below.'
                        : 'Some details are missing for ' + this.participantLabel(firstBad) + '. Fields needing attention are highlighted below.';
                    return false;
                }

                if (!this.fileName) {
                    this.clientErrors.proof = true;
                    this.clientErrorMessage = 'Please upload your proof of payment.';
                    return false;
                }

                if (!this.waiver_agreed || !this.terms_agreed) {
                    this.clientErrors.waiver = !this.waiver_agreed;
                    this.clientErrors.terms = !this.terms_agreed;
                    this.clientErrorMessage = 'Please agree to the liability waiver and the rules and conditions.';
                    return false;
                }

                return true;
            },

            // ---- discount code (individual page only) -------------

            async applyDiscount() {
                this.discountError = '';
                if (!this.hasAnyCategory) { this.discountError = 'Choose a race category first.'; return; }
                if (!this.discount_code) { this.discountError = 'Enter a code.'; return; }
                await this.fetchQuote();
            },

            async refreshQuote() {
                if (!this.discount_code || !this.hasAnyCategory) { this.removeDiscount(); return; }
                await this.fetchQuote();
            },

            async fetchQuote() {
                // Edits can outpace the round-trip, so only the newest reply wins.
                const seq = ++this._quoteSeq;
                const isStale = () => seq !== this._quoteSeq;

                this.discountChecking = true;
                try {
                    const res = await fetch("{{ route('registration.validateDiscount') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            code: this.discount_code,
                            race_category_id: this.participants[0]?.race_category_id,
                            email: this.participants[0]?.email || null,
                        }),
                    });
                    const json = await res.json();
                    if (isStale()) return;

                    if (!json.valid) {
                        // Shown even on an automatic refresh: if a category change knocked
                        // the code out, saying so beats it disappearing silently.
                        this.appliedDiscount = null;
                        this.discountError = json.message || 'Code is not valid.';
                        return;
                    }
                    this.appliedDiscount = json;
                    this.discountError = '';
                } catch (e) {
                    if (!isStale()) this.discountError = 'Could not validate code. Please try again.';
                } finally {
                    if (!isStale()) this.discountChecking = false;
                }
            },

            removeDiscount() {
                this.appliedDiscount = null;
                this.discount_code = '';
                this.discountError = '';
            },

            // ---- formatting ---------------------------------------

            formatPHP(value) {
                return '₱' + Number(value || 0).toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                });
            },

            titleCase(value) {
                return value ? value.charAt(0).toUpperCase() + value.slice(1) : '—';
            },

            formatDate(value) {
                if (!value) return '—';
                return new Date(value + 'T00:00:00').toLocaleDateString('en-PH', {
                    year: 'numeric', month: 'long', day: 'numeric',
                });
            },

            filterNationalities(query) {
                const q = (query || '').toLowerCase().trim();
                if (!q) return NATIONALITIES.slice(0, 60);
                return NATIONALITIES.filter(n => n.toLowerCase().includes(q)).slice(0, 60);
            },

            // ---- navigation ---------------------------------------

            showReview() {
                if (!this.validate()) {
                    this.showClientErrorBanner = true;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return;
                }
                this.showClientErrorBanner = false;
                this.reviewing = true;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            backToForm() {
                this.reviewing = false;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
        };
    }

    function round2(value) {
        return Math.round((Number(value) + Number.EPSILON) * 100) / 100;
    }
</script>
