<template>
	<div class="card">
		<div class="card-header"><h5>Agent Score</h5></div>
		<div class="card-body">
			<h3 class="text-center text-muted" :class="{'display-3': rated}">
				{{ rated ? score : "Not yet rated" }}
			</h3>
			<div class="d-flex justify-content-center">
				<star-rating :show-rating="false" :glow="10" :rounded-corners="true" :rating="score" :increment="0.01" :read-only="true" />
			</div>
		</div>
	</div>
</template>
<script>
	import starRating from 'vue-star-rating';
	import moment from 'moment';
	export default {
		props:['filter'],
		components: { starRating },
		data() {
			return {
				csats: 0
			}
		},
		mounted() {
			this.retrieveCsat();
		},
		methods: {
			retrieveCsat() {
				this.$store.dispatch('retrieveCsats')
				.then(response => {
					this.csats = response ? response : 0;
				});
			},
			getAvg(csats) {
				let score = 0;
				for (let i = 0; i < csats.length; i++) {
					score += csats[i].score;
				}
				return (score /= csats.length).toFixed(2);
			}
		},
		computed: {
			rated() {
				return this.score;
			},
			score() {
				let startOfWeek = moment().startOf('isoweek').toDate();
				let endOfWeek = moment().endOf('isoweek').toDate();
				let date = new Date();
				let csats = [];
				if (this.csats.length == 0)
					return 0;
				else if (this.filter == 0) {
					return this.getAvg(this.csats);
				} 
				else if (this.filter == 1) {
					csats = this.csats.filter(csat => new Date(csat.created_at).getMonth() + 1 == date.getMonth() + 1)
					return this.getAvg(csats);
				} 
				else if (this.filter == 2) {
					csats = this.csats.filter(csat => new Date(csat.created_at) >= startOfWeek && new Date(csat.created_at) <= endOfWeek);
					return this.getAvg(csats);
				} 
				else if (this.filter == 3) {
					csats = this.csats.filter(csat => new Date(csat.created_at).getDate() == date.getDate());
					return this.getAvg(csats);
				}
			}
		}
	}
</script>