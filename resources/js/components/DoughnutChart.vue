<script>
	import { Doughnut } from 'vue-chartjs'
	export default {
		extends: Doughnut,
		data() {
			return {
				text: '4.3',
				data: {
					datasets: [{
						data: [10,5,2,7,1],
						backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"]
					}],
					labels: [
					],
				},
				options: {
					maintainAspectRatio: false,
					elements: {
						center: {
							text: '',
							color: '#FF6384',
							fontStyle: 'Arial',
							sidePadding: 20
						}
					}
				},
			}
		},
		props: [
			'passedData',
		],
		mounted() {
		},
		watch: {
			passedData() {
				this.renderChart(this.passedData, this.options);
			}
		}
	}
</script>
<style scoped>
	canvas {
		height:200px !important;
	}
</style>