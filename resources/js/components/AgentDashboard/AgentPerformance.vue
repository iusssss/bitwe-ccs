<template>
	<div class="card">
		<div class="card-header"><h5>My Performance</h5></div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-4">
					<div class="card">
						<Csat :filter="filter" />
					</div>
				</div>
				<div class="col-md-8">
					<div class="card">
						<evaluations :filter="filter" />
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import Csat from '../AgentDashboard/Csat'
	import Evaluations from '../AgentDashboard/Evaluations'
	export default {
		props: ['filter'],
		components: {
			Csat,
			Evaluations
		},
	}
</script>