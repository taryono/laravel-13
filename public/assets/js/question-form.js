// resources/js/question-form.js
// Pastikan Alpine.js sudah di-load sebelum file ini.

document.addEventListener('alpine:init', () => {
    Alpine.data('questionForm', (config = {}) => ({
        // ====== STATE ======
        typeMap: config.typeMap || {},
        typeId: String(config.initialTypeId || ''),
        typeCode: '',
        useStimulus: !!config.useStimulus,

        // Data dinamis — setiap item punya `_uid` agar key stabil saat reorder
        options: [],
        pairs: [],
        aliases: [''],

        // Untuk single choice, simpan index benar secara eksplisit
        singleCorrectIndex: -1,

        _uidCounter: 0,

        // ====== INIT ======
        init() {
            // Bangun state awal dari server (mode edit)
            this.hydrate(config);

            // Watch perubahan tipe
            this.$watch('typeId', (val, oldVal) => {
                if (val === oldVal) return;
                this.onTypeChange(val);
            });

            // Set typeCode awal tanpa reset data
            this.typeCode = this.typeMap[this.typeId] || '';

            // Sinkronkan singleCorrectIndex dari data awal
            if (this.typeCode === 'single_choice' || this.typeCode === 'true_false') {
                const idx = this.options.findIndex(o => o.is_correct);
                this.singleCorrectIndex = idx >= 0 ? idx : -1;
            }
        },

        hydrate(cfg) {
            const opts = Array.isArray(cfg.initialOptions) ? cfg.initialOptions : [];
            const prs = Array.isArray(cfg.initialPairs) ? cfg.initialPairs : [];
            const als = Array.isArray(cfg.initialAliases) ? cfg.initialAliases : [];

            this.options = opts.map((o, i) => ({
                _uid: this.nextUid(),
                label: o.label ?? '',
                text: o.text ?? '',
                is_correct: !!o.is_correct,
                order_index: typeof o.order_index === 'number' ? o.order_index : i,
            }));

            this.pairs = prs.map(p => ({
                _uid: this.nextUid(),
                left: p.left ?? '',
                right: p.right ?? '',
            }));

            this.aliases = als.length ? als.map(a => String(a ?? '')) : [''];
            this.useStimulus = !!cfg.useStimulus;
        },

        // ====== HELPERS ======
        nextUid() {
            return ++this._uidCounter;
        },

        get isChoiceType() {
            return ['single_choice', 'multi_choice', 'true_false'].includes(this.typeCode);
        },

        // ====== TYPE CHANGE ======
        onTypeChange(val) {
            const newCode = this.typeMap[val] || '';
            if (newCode === this.typeCode) return;

            this.typeCode = newCode;

            // Reset semua data dinamis
            this.options = [];
            this.pairs = [];
            this.aliases = [''];
            this.singleCorrectIndex = -1;

            if (newCode === 'true_false') {
                this.options = [
                    { _uid: this.nextUid(), label: 'B', text: 'Benar', is_correct: false, order_index: 0 },
                    { _uid: this.nextUid(), label: 'S', text: 'Salah', is_correct: false, order_index: 1 },
                ];
            } else if (['single_choice', 'multi_choice', 'ordering'].includes(newCode)) {
                this.addOption();
                this.addOption();
            } else if (newCode === 'matching') {
                this.addPair();
                this.addPair();
            }
        },

        // ====== OPTIONS ======
        addOption() {
            const labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const used = new Set(this.options.map(o => o.label));
            let label = '';
            for (const ch of labels) {
                if (!used.has(ch)) { label = ch; break; }
            }

            this.options.push({
                _uid: this.nextUid(),
                label,
                text: '',
                is_correct: false,
                order_index: this.options.length,
            });
        },

        removeOption(i) {
            if (i < 0 || i >= this.options.length) return;
            this.options.splice(i, 1);
            this.reindexOptions();

            // Perbaiki singleCorrectIndex
            if (this.singleCorrectIndex === i) this.singleCorrectIndex = -1;
            else if (this.singleCorrectIndex > i) this.singleCorrectIndex--;
        },

        moveOption(i, dir) {
            const t = i + dir;
            if (t < 0 || t >= this.options.length) return;
            [this.options[i], this.options[t]] = [this.options[t], this.options[i]];
            this.reindexOptions();

            // Ikut pindahkan index benar
            if (this.singleCorrectIndex === i) this.singleCorrectIndex = t;
            else if (this.singleCorrectIndex === t) this.singleCorrectIndex = i;
        },

        reindexOptions() {
            this.options.forEach((o, idx) => {
                o.order_index = idx;
                // Hanya auto-relabel untuk tipe pilihan
                if (['single_choice', 'multi_choice', 'true_false'].includes(this.typeCode)) {
                    if (this.typeCode !== 'true_false') {
                        o.label = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[idx] || '';
                    }
                }
            });
        },

        setSingleCorrect(i) {
            this.singleCorrectIndex = i;
            this.options.forEach((o, idx) => { o.is_correct = (idx === i); });
        },

        // ====== PAIRS ======
        addPair() {
            this.pairs.push({ _uid: this.nextUid(), left: '', right: '' });
        },
        removePair(i) {
            if (i < 0 || i >= this.pairs.length) return;
            this.pairs.splice(i, 1);
        },

        // ====== ALIASES ======
        addAlias() {
            this.aliases.push('');
        },
        removeAlias(i) {
            if (i < 0 || i >= this.aliases.length) return;
            // Minimal 1 alias tersisa
            if (this.aliases.length === 1) {
                this.aliases = [''];
                return;
            }
            this.aliases.splice(i, 1);
        },
    }));
});