<template>
	<div>
		<div class="modal-header">
	        <h3 class="modal-title text-primary">Scorecard</h3>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body">
	    	<h5 class="mb-3">Agent: <strong v-if="user">{{ user.name }}</strong></h5>
	    	<table class="table">
	    		<thead>
	    			<tr>
	    				<th class="w-75">QUESTION</th>
	    				<th class="w-25">COMMENT</th>
	    				<th>ANSWER</th>
	    				<th class="w-25">CONFIDENCE</th>
	    			</tr>
	    		</thead>
	    		<tbody v-for="(criteria, i) in criterias">
	    			<tr class="criteria">
	    				<td colspan="3">{{ criteria.name }}</td>
	    				<td class="text-right">{{ criteria.percentage }}%</td>
	    			</tr>
	    			<tr v-for="(criteria, j) in criteria.questions">
	    				<td>{{ criteria.question }}</td>
	    				<td>
	    					<!-- score.comment['question-'+criteria.id] -->
	    					<textarea v-model="criteria.comment" style="width: 200px;" class="form-control" placeholder="optional"></textarea>
	    				</td>
	    				<td>
	    					<!-- score.answer['question-'+criteria.id] -->
	    					<input v-model="criteria.answer" style="width: 100%;" type="checkbox" name="">
	    				</td>
	    				<td>
	    					<!-- score.confidence['question-'+criteria.id] -->
			    			<input :disabled="!criteria.answer" v-model="criteria.confidence" style="width: 130px;" class="form-control" type="" name="" placeholder="confidence / 10">
			    		</td>
	    			</tr>
	    		</tbody>
	    		<tbody>
	    			<tr class="criteria">
	    				<td colspan="4" class="text-right">Score: {{ score }}</td>
	    			</tr>
	    		</tbody>
	    	</table>
	    	<!-- <div v-for="criteria in criterias"></div> -->
	    </div>
	    <div class="modal-footer">
	        <button :disabled="loading" type="button" class="btn btn-primary" @click="submit">
	        	<span v-if="loading">
                    <i class="fas fa-spinner fa-pulse"></i>
                    submitting...
                </span>
                <span v-else>
	        		Done
                </span>
	        </button>
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	    </div>
	</div>
</template>
<script>
	export default {
		props: ['criterias', 'user', 'recordSid'],
		data() {
			return {
				loading: false,
				// score: {
				// 	comment: [],
				// 	answer: [],
				// 	confidence: [],
				// },
			}
		},
		mounted() {
		},
		methods: {
			deleteRecord() {
				axios.delete(`/api/callRecord/${this.recordSid}`)
				.then(response => {
					this.loading = false;
					console.log(response);
					this.$noty.success("Call recording deleted");
				})
				.catch(error => {
					this.loading = false;
					this.$noty.error("An error has occured while deleting the recording")
				})
			},
			submit() {
				if (confirm('Are you sure?')) {
					this.loading = true;
					this.initializeProps();
					axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
					axios.post('/api/scorecard', {criterias: this.criterias, score: this.score, agent_id: this.user.id})
					.then(response => {
						if (confirm("Delete recording?")) {
							this.deleteRecord();
						} else {
							this.loading = false;
							$('#scorecard').modal('hide');
						}
						this.$noty.success("Scorecard created");
					})
					.catch(error => {
						this.$noty.error("An error has occured");
					})
				}
			},
			initializeProps() {
				for (let i = 0; i < this.criterias.length; i++) {
					for (let j = 0; j < this.criterias[i].questions.length; j++) {
						let q = this.criterias[i].questions;
						if (!q[j].hasOwnProperty("comment")) {
							q[j].comment = '';
						}
						if (!q[j].hasOwnProperty("answer")) {
							q[j].answer = 0;
						}
						if (!q[j].hasOwnProperty("confidence")) {
							q[j].confidence = 0;
						}
					}
				}
			},
		},
		computed: {
			score() {
				if (this.criterias) {
					let score = 0;
					for (let i = 0; i < this.criterias.length; i++) {
						let percentage = '.' + this.criterias[i].percentage;
						for (let j = 0; j < this.criterias[i].questions.length; j++) {
							let q = this.criterias[i].questions;
							let confidence = 0;
							if (q[j].hasOwnProperty("confidence")) {
								confidence += parseInt(q[j].confidence) || 0;
							}
							// (con / (length * 10)) * 100 * .25
							score += (confidence / (q.length * 10)) * 100 * parseFloat(percentage); 
						}
					}
					return score.toFixed(2);
				}
			}
		}
	}
</script>
<style scoped>
	.criteria {
		font-weight: bolder;
		background-color: rgb(209,210,212);
	}
	thead {
		background-color: rgb(69,70,71);
		color: white;
	}
</style>