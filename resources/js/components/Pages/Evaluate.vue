<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4">
				<criteria />
			</div>
			<div class="col-md-4">
				<callRecord />
			</div>
			<div class="col-md-4">
				<evaluations />
			</div>
		</div>
	</div>
</template>
<script>
	import Criteria from '../Criteria.vue';
	import CallRecord from '../CallRecord.vue';
	import Evaluations from '../Evaluations.vue';
	export default {
		components: { Criteria, CallRecord, Evaluations },
		data() {
			return {
				criterias: []
			}
		},
		mounted() {
		},
		methods: {
		}
	}
</script>
<style scoped>
	
</style>