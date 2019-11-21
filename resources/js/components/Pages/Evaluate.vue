<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<criteria />
			</div>
			<div class="col-md-6">
				<callRecord />
			</div>
		</div>
	</div>
</template>
<script>
	import Criteria from '../Criteria.vue';
	import CallRecord from '../CallRecord.vue';
	export default {
		components: { Criteria, CallRecord },
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