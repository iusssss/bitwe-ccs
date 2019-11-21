<script>
	import { Line } from 'vue-chartjs'
	export default {
		extends: Line,
		data() {
			return {
				// data: {
				//     datasets: [
				// 	    {
				// 	    	label: "AMS",
				// 	        data: [3,10,4,1,16,21,12,17,20,12,7,1],
				// 	        backgroundColor: 'rgba(255,100,100,0.4)',
			 //            },
				// 	    {
				// 	    	label: "E-trade",
				// 	        data: [2,4,8,12,4,13,6,13,8,18,1,3],
				// 	        backgroundColor: 'rgba(100,100,255,0.4)',
				// 	    },
				// 	    {
				// 	    	label: "GoFast",
				// 	        data: [13,6,23,8,7,1,3,2,4,8,12,4],
				// 	        backgroundColor: 'rgba(100,255,100,0.4)',
				// 	    }
				//     ],
			 //        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',],
				// },
				options: {
                    maintainAspectRatio: false,
					scales: {
					}
				},
			}
		},
		props: ['passedData'],
		mounted() {
		},
		watch: {
			passedData() {
				this.renderChart(this.passedData, this.options);
			}
		}
	}
</script>