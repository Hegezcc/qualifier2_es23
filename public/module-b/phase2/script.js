const { createApp } = Vue

const app = createApp({
    data() {
        return {
            page: 'land',
            api: '/module-b/phase1/api/v1',
            land: {
                concerts: null,
                artist: '',
                location: '',
                date: '',
            },
            book: {
                concert: null,
                rows: [],
                selected: [],
            },
            details: {
                
            },
            tickets: {
                
            },
            retrieve: {

            }
        }
    },
    mounted() {
        fetch(`${this.api}/concerts`)
            .then(d => d.json())
            .then(d => this.land.concerts = d.concerts);
    },
    methods: {
        filter_clear() {
            this.land.artist = '';
            this.land.location = '';
            this.land.date = '';
        },
        select_card(id) {
            this.book.concert = this.land.concerts.find(c => c.id === id);

            fetch(`${this.api}/concerts/${id}/shows/${this.book.concert.shows[0].id}/seating`)
                .then(d => d.json())
                .then(d => this.book.rows = d.rows);

            this.page = 'book';
        },
        select_seat(r, s) {
            if (r.seats.unavailable.includes(s)) {
                return null
            }
            
            const key = `${r.id}-${s}`;

            console.log(key)

            if (this.book.selected.includes(key)) {
                this.book.selected = this.book.selected.filter(s => s !== key)
            } else {
                this.book.selected.push(key)
            }

            console.log(this.book.selected)
            this.$forceUpdate();
        }
    },
    computed: {
        locations() {
            if (this.land.concerts === null) {
                return [];
            }

            return [...new Set(this.land.concerts.map(c => c.location.name))]
        },
        artists() {
            if (this.land.concerts === null) {
                return [];
            }

            return [...new Set(this.land.concerts.map(c => c.artist))]
        },
        filtered() {
            if (this.land.concerts === null) {
                return [];
            }

            let concerts = [...this.land.concerts];

            if (this.land.artist !== '') {
                concerts = concerts.filter(c => c.artist == this.land.artist);
            }
            if (this.land.location !== '') {
                concerts = concerts.filter(c => c.location.name == this.land.location);
            }
            if (this.land.date !== '') {
                concerts = concerts.filter(c => c.shows.some(s => s.start.split('T')[0] == this.land.date));
            }

            return concerts;
        }
    }
}).mount('#app')