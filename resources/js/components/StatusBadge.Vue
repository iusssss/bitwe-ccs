<template>
	<div>
		<span :class="setBGof(status)" class="status"></span> <span :class="setColorOf(status)">{{ status }}</span>
	</div>
</template>
<script>
	export default {
		props: ['status'],
		methods: {
			setColorOf(status) {
				return {
					'text-success': status == "Available",
					'text-muted': status == "Offline",
					'text-danger': status == "Busy" || status == "Reserved" || status == "WrapUp",
					'text-warning' : status == "OnBreak"
				}
			},
			setBGof(status) {
				return {
					'bg-success': status == "Available",
					'bg-secondary': status == "Offline",
					'bg-danger': status == "Busy" || status == "Reserved" || status == "WrapUp",
				}
			}
		}
	}
</script>
<style scoped>
	.status {
		background-color: red;
		height: 10px;
 		width: 10px;
		border-radius: 50%;
		display: inline-block;
	}
</style>