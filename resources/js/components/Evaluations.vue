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
						<th>Date</th>
						<th></th>
					</thead>
					<tbody>
						<tr v-if="evaluations.data.length > 0" v-for="evaluation in evaluations.data">
							<td>{{ evaluation.evaluator.name }}</td>
							<td>{{ evaluation.agent.name }}</td>
							<td>{{ evaluation.score }}</td>
							<td>{{ evaluation.created_at }}</td>
							<td class="d-flex justify-content-end">
								<button class="btn btn-primary mx-1" @click="view(evaluation)">
									<i class="fas fa-stream"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
        		<pagination :show-disabled="true" :data="evaluations" @pagination-change-page="retrieveEvaluations" :limit="10"></pagination>
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
				evaluations: {},
				evaluation: null
			}
		},
		mounted() {
			this.retrieveEvaluations();
		},
		methods: {
			view(evaluation) {
				this.evaluation = evaluation;
				$('#scorecard-view').modal('show');
			},

			retrieveEvaluations(page = 1) {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.get('/api/evaluations?page=' + page)
				.then(response => {
					this.evaluations = response.data;
				})
			}
		}
	}
</script>