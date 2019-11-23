<template>
	<div class="container-fluid">
		<div class="position-fixed filters w-100 d-flex justify-content-end" style="pointer-events: none;">
			<div class="card mb-3 mr-4">
				<div class="card-body d-flex justify-content-between"style="pointer-events: auto;">
					<h5 class="">Dashboard</h5>
					<select class="text-secondary h5 m-0 select-right" id="filter" v-model="filter">
						<option v-for="filter in filters" :value="filter.id">
							{{ filter.name }}
						</option>
	                </select>
				</div>
			</div>
		</div>
		<div class="row main-row">
			<div class="col-md-8">
				<ticketStatus :filter="filter" />
			</div>
			<div class="col-md-4">
				<Csat :filter="filter" />
			</div>
		</div>
		<div class="row main-row">
			<div class="col-md-12">
				<callHandlingChart :filter="filter" />
			</div>
		</div>
		<div class="row main-row">
			<div class="col-md-12">
				<ticketServices :filter="filter" />
			</div>
		</div>
	</div>
</template>
<script>
	import Csat from '../AgentDashboard/Csat'
	import TicketStatus from '../AdminDashboard/TicketStatus'
	import TicketServices from '../AdminDashboard/TicketServices'
	import CallHandlingChart from '../AdminDashboard/CallHandlingChart'
	export default {
		components: {
			Csat,
			TicketStatus,
			TicketServices,
			CallHandlingChart
		},
		data() {
			return {
				filters: [
					{
						id: 0,
						name: 'This Year'
					},
					{
						id: 1,
						name: 'This Month'
					},
					{
						id: 2,
						name: 'This Week'
					},
					{
						id: 3,
						name: 'Today'
					},
				],
				filter: 0,
			}
		},
		mounted() {
			this.$store.dispatch('retrieveTicketsThisYear');
		},
		methods: {
		},
		computed: {
		}
	}
</script>
<style>
	.filters {
		z-index: 100;
	}
	.filters .card {
		width: 18%;
		background-color: rgba(255,255,255,1) !important;
	}
	#filter {
		border: none;
		background-color: transparent;
	}
	.select-right {
		text-align-last: right;
	}
	option {
		direction: rtl;
	}
	h5 {
		margin: 0;
	}
	.main-row {
		margin-bottom: 30px;
	}
</style>