..<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<div class="card">
					<div class="card-header"><h5>Top 5 Active Agents</h5></div>
					<div class="card-body">
						<doughnut :passedData="[1, 3, 7, 2, 5]" :passedLabels="['Julius','Herchell','Joshua','Karell','Gabriel']" />
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="card">
					<div class="card-header"><h5 class="text-muted">Handled Services</h5></div>
					<div class="card-body">
						<!-- <lineChart /> -->
					</div>
				</div>
			</div>
		</div>
		<!-- end row -->
		<div class="row my-3">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header"><h5>Agents Status</h5></div>
					<div class="card-body">
						<userStatus />
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-header"><h5>Agent Score</h5></div>
					<div class="card-body">
						<h3 class="text-center display-3 text-muted">{{ agentCsat }}</h3>
						<div class="d-flex justify-content-center">
							<star-rating :show-rating="false" :glow="10" :rounded-corners="true" :rating="agentCsat" :increment="0.1" :read-only="true" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import UserStatus from '../AdminDashboard/UserStatus.vue';
	import Doughnut from '../DoughnutChart.vue';
	import LineChart from '../LineChart.vue';
	import starRating from 'vue-star-rating';
	export default {
		components: {
			UserStatus,
			Doughnut,
			LineChart,
			starRating
		},
		data() {
			return {
				csats: null
			}
		},
		mounted() {
			this.$store.dispatch('retrieveCsats', this.$store.state.user.id)
			.then(response => {
				this.csats = response;
			});
		},
		computed: {
			agentCsat() {
				let sum = 0;
				if (this.csats) {
					sum = this.csats.reduce((a, b) => ({ score: a.score + b.score })).score;
					return sum / this.csats.length;
				}
				return 0;
			}
		}
	}
</script>
<style scoped>
	.card {
		height: 100%;
	}
	h3 {
	}
	h5 {
		margin: 0;
	}
</style>