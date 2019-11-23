<template>
	<div class="modal-content">
        <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body" id="scorecard-viewer">
	    	<div class="px-5">
		        <h3 class="modal-title text-primary mb-2 mt-3">Scorecard</h3>
		        <hr>
		    	<div class="row">
		    		<div class="col-md-6">
				    	<h5 class="">
				    		Agent: <strong>{{ evaluation.agent.name }}</strong>
				    	</h5>
				    	<h5 class="mb-3">
				    		Evaluated by: <strong>{{ evaluation.evaluator.name }}</strong>
				    	</h5>
			    	</div>
			    	<div class="col-md-6 d-flex justify-content-end">
			    		<h5>Evaluated on: <strong>{{ evaluation.created_at }}</strong></h5>
			    	</div>
		    	</div>
		    	<table class="table">
		    		<thead>
		    			<tr>
		    				<th class="w-75"><h5>QUESTION</h5></th>
		    				<th class="w-25"><h5>COMMENT</h5></th>
		    				<th><h5>ANSWER</h5></th>
		    				<th class="w-25"><h5>CONFIDENCE</h5></th>
		    			</tr>
		    		</thead>
		    		<tbody v-for="criteria in criterias">
		    			<tr class="text-info">
		    				<td class="" colspan="3">{{ criteria.name }}</td>
		    				<td class="text-right">{{ criteria.percentage }}%</td>
		    			</tr>
		    			<tr v-for="content in criteria.content">
		    				<td>{{ content.question }}</td>
		    				<td>{{ content.comment}}</td>
		    				<td class="text-center">{{ toEng(Boolean(content.answer)) }}</td>
		    				<td class="text-center">{{ content.confidence }} / 10</td>
		    			</tr>
		    		</tbody>
		    		<tbody>
		    			<tr class="criteria">
		    				<td colspan="4" class="text-right">Score: <span class="text-primary">{{ evaluation.score }}</span></td>
		    			</tr>
		    		</tbody>
		    	</table>
		    </div>
	    </div>
	    <div class="modal-footer">
	    	<button class="btn btn-primary" @click="printScorecard">Print</button>
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	    </div>
    </div>
</template>
<script>
	export default {
		props:['evaluation'],
		data() {
			return {
			}
		},
		methods: {
			printScorecard() {
				this.$htmlToPaper('scorecard-viewer');
			},
			toEng(bool) {
				if (bool)
					return 'Yes';
				return 'No'
			}
		},
		computed: {
			criterias() {
				const details = this.evaluation.details.map(x => {
					return {
						name: x.criteria_name, 
						percentage: x.criteria_percentage,
						content: {
							question: x.question,
							comment: x.comment,
							answer: x.answer,
							confidence: x.confidence
						}
					}
				});
				const result = Array.from(new Set(details.map(x => x.name)))
								.map(name => {
									return {
										name: name,
										percentage: details.find(x => x.name == name).percentage,
										content: []
									};
								});
				for (let i = 0; i < result.length; i++) {
					let content = [];
					for (let j = 0; j < details.length; j++) {
						if (details[j].name == result[i].name) {
							content.push(details[j].content);
						}
					}
					result[i].content = content;
				}
				return result;
			},
		}
	}
</script>
<style scoped>
</style>