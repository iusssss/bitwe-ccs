<template>
	<div class="modal-content">
        <div class="modal-header">
	        <h3 class="modal-title text-primary">Scorecard</h3>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body">
	    	<div class="row">
	    		<div class="col-md-6">
			    	<h5 class="mb-3">
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
		    				<th class="w-75">QUESTION</th>
		    				<th class="w-25">COMMENT</th>
		    				<th>ANSWER</th>
		    				<th class="w-25">CONFIDENCE</th>
		    			</tr>
		    		</thead>
		    		<tbody v-for="criteria in criterias">
		    			<tr class="criteria">
		    				<td colspan="3">{{ criteria.name }}</td>
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
		    				<td colspan="4" class="text-right">Score: {{ evaluation.score }}</td>
		    			</tr>
		    		</tbody>
		    	</table>
	    </div>
	    <div class="modal-footer">
	    	<button class="btn btn-primary">Print</button>
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
	.criteria {
		font-weight: bolder;
		background-color: rgb(209,210,212);
	}
	thead {
		background-color: rgb(69,70,71);
		color: white;
	}
</style>