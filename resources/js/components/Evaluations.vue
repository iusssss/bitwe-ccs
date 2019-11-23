<template>
	<div class="card">
		<div class="card-header"><h3>Evaluations</h3></div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<th>Evaluator</th>
						<th>Agent</th>
						<th>Score</th>
						<th></th>
					</thead>
					<tbody>
						<tr v-if="evaluations.length > 0" v-for="evaluation in evaluations">
							<td>{{ evaluation.evaluator.name }}</td>
							<td>{{ evaluation.agent.name }}</td>
							<td>{{ evaluation.score }}</td>
							<td class="d-flex justify-content-end">
								<button class="btn btn-primary mx-1" @click="view(evaluation)">
									<i class="fas fa-stream"></i>
								</button>
								<button class="btn btn-primary" @click="printScorecard(evaluation)">
									<i class="fas fa-print"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="modal fade" id="scorecard-view" tabindex="-1" role="dialog" aria-labelledby="scorecard-view" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <scorecardView v-if="evaluation" :evaluation="evaluation" />
            </div>
        </div>
	</div>
</template>
<script>
	import ScorecardView from './ScorecardView.vue';
	export default {
		components: { ScorecardView },
		data() {
			return {
				evaluations: [],
				evaluation: null
			}
		},
		mounted() {
			this.retrieveEvaluations();
		},
		methods: {
			printScorecard(evaluation) {

			},
			view(evaluation) {
				this.evaluation = evaluation;
				$('#scorecard-view').modal('show');
			},
			retrieveEvaluations() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.get('/api/evaluations')
				.then(response => {
					this.evaluations = response.data.data;
				})
			}
		}
	}
</script>