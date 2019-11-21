<script>
	import { HorizontalBar } from 'vue-chartjs'
	export default {
		extends: HorizontalBar,
		data() {
			return {
				// data: {
				//     datasets: [
				// 	    // {
				// 	    // 	label: "Request Acknowledgement Time",
				// 	    //     data: [3,10,4,7,16,21,6,17,20,12,7,1],
				// 	    //     backgroundColor: 'rgba(201,203,202, 0.6)',
			 //      //       },
				// 	    {
				// 	    	// label: "Request Fulfillment",
				// 	        data: [0,0,0,0,0,0,0,0,0,0,0,0],
				// 	        backgroundColor: 'rgba(208,7,39,0.6)',
				// 	    },
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
		props: [ 'passedData' ],
		mounted() {
			// this.renderChart(this.passedData, this.options);
		},
		watch: {
			passedData() {
				this.renderChart(this.passedData, this.options);
			}
		}
	}
</script>
