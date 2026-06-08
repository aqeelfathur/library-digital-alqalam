function sipusChat() {
    return {
        buka:      false,
        loading:   false,
        inputTeks: '',
        pesan:     [],
        history:   [],

        init() {
            this.$watch('pesan', () => this.scrollBawah());
            this.$watch('loading', () => this.scrollBawah());
        },

        async kirimPesan() {
            const teks = this.inputTeks.trim();
            if (!teks || this.loading) return;

            this.inputTeks = '';
            this.$refs.inputArea.style.height = 'auto';
            this.pesan.push({ role: 'user', text: teks });
            this.history.push({ role: 'user', text: teks });
            this.loading = true;

            try {
                const resp = await fetch('/sipus/pesan', {
                    method:  'POST',
                    headers: {
                        'Content-Type':  'application/json',
                        'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':        'application/json',
                    },
                    body: JSON.stringify({
                        message: teks,
                        history: this.history.slice(-10),
                    }),
                });

                const data = await resp.json();

                if (data.success) {
                    this.pesan.push({ role: 'bot', text: data.answer });
                    this.history.push({ role: 'model', text: data.answer });
                } else {
                    this.pesan.push({ role: 'bot', text: 'Maaf, terjadi kesalahan. Coba lagi ya.' });
                }
            } catch (e) {
                this.pesan.push({ role: 'bot', text: 'Tidak dapat terhubung ke server.' });
            }

            this.loading = false;
        },

        kirimSuggestion(btn) {
            const teks = btn.textContent.trim().replace(/^[\p{Emoji}\s]+/u, '').trim();
            this.inputTeks = teks;
            this.kirimPesan();
        },

        scrollBawah() {
            this.$nextTick(() => {
                const el = document.getElementById('sipus-messages');
                if (el) el.scrollTop = el.scrollHeight;
            });
        },

        autoResize(el) {
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight, 96) + 'px';
        },

        formatTeks(teks) {
            return teks
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/^[-•]\s+(.+)$/gm, '<li>$1</li>')
                .replace(/(<li>.*<\/li>\n?)+/gs, m => `<ul class="list-disc pl-4 mt-1 space-y-0.5">${m}</ul>`)
                .replace(/\n/g, '<br>');
        },

        escapeHtml(teks) {
            return teks.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        },
    }
}

window.sipusChat = sipusChat;