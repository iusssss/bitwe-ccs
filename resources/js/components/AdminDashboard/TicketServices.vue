<template>
	<div class="card">
		<div class="card-header">
			<h5>Services</h5>
		</div>
		<div class="row card-body">
			<div class="col-md-4">
				<div class="card">
					<div class="card-body">
						<h4 class="text-muted">
							Top 3 Inquired Service
						</h4>
						<button class="btn btn-muted float-right toggle" @click="toggleView">
							{{ toggle }}
						</button>
						<doughnutChart :hidden="toggle != 'Chart'" :passedData="topServices" />
						<table :hidden="toggle == 'Chart'" class="table">
							<thead>
								<tr>
									<th>Service</th>
									<th>Tickets</th>
								</tr>
							</thead>
							<tbody v-if="topServices">
								<tr v-for="(val, i) in topServices.datasets[0].data">
									<td>{{ topServices.labels[i] }}</td>
									<td>{{ val }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="card">
					<div class="card-body">
						<lineChart :passedData="data" />
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import LineChart from '../LineChart.vue';
	import DoughnutChart from '../DoughnutChart.vue';
	export default {
		props: ['filter'],
		data() {
			return {
				toggle: "Chart",
				thisYear: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',],
				thisWeek: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
				services: null,
				perMonth: null,
				perDayInMonth: null,
				perWeek: null,
				thisDay: null,
				backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9",
					"#c45850", 'rgba(100,255,100,1)', 'rgba(100,100,255,1)',
	            	'rgba(255,100,100,1)', 'rgba(46, 204, 113, 1)', 
	            	'rgba(52, 152, 219, 1)', 'rgba(155, 89, 182, 1)', 
	            	'rgba(241, 196, 15, 1)', 'rgba(230, 126, 34, 1)',
	                'rgba(231, 76, 60, 1)'
	            ]
			}
		},
		components: {
			LineChart,
			DoughnutChart
		},
		mounted() {
			this.getServices();
			this.getTicketsPerMonth();
			this.getTicketsPerDayInMonth();
			this.getTicketsPerWeek();
			this.getTicketsThisDay();
		},
		methods: {
			toggleView() {
				this.toggle = this.toggle == "Chart" ? "Table" : "Chart";
			},
			getServices() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.get('/api/services')
				.then((response) => {
					this.services = response.data.data;
				})
			},
			getTicketsPerMonth() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.get('/api/ticketsPerMonth')
				.then((response) => {
					this.perMonth = response.data;
				})
			},
			getTicketsPerDayInMonth() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.get('/api/ticketsPerDayInMonth')
				.then((response) => {
					this.perDayInMonth = response.data;
				})
			},
			getTicketsPerWeek() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.get('/api/ticketsPerWeek')
				.then((response) => {
					this.perWeek = response.data;
				})
			},
			getTicketsThisDay() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.get('/api/ticketsThisDay')
				.then((response) => {
					this.thisDay = response.data;
				})
			}
		},
		computed: {
			data() {
				if (this.filter == 0)
					return this.servicesPerMonth;
				else if (this.filter == 1)
					return this.servicesPerDayInMonth;
				else if (this.filter == 2)
					return this.servicesPerWeek;
				else if (this.filter == 3)
					return this.servicesThisDay;
			},
			thisMonth() {
				const year = new Date().getFullYear();
				const month = new Date().getMonth() + 1;
				const days = new Date(year, month, 0).getDate();
				let arr = [];
				for(var i = 0; i < days; i++) {
					arr.push(i + 1);
				}
				return arr;
			},
			hours() {
				let arr = [];
				for(var i = 0; i < 24; i++) {
					arr.push(i);
				}
				return arr;
			},
			servicesThisDay() {
				if (this.services && this.thisDay) {
					let datasets = [];
					let labels = [];
					for (let i = 0; i < this.services.length; i++) {
						let item = { label: "", data: [] };
						item.label = this.services[i].name; 
						for (let j = 0; j < this.hours.length; j++) {
							let val = this.thisDay.filter(x => x.hour == this.hours[j] && x.name == item.label);
							item.data.push(val.length == 0 ? 0 : val[0].total);
							item.borderColor = this.backgroundColor[i];
							item.fill = false;
						}
						datasets.push(item);
					}
					labels = this.hours;
					return { datasets, labels };
				}
			},
			servicesPerWeek() {
				if (this.services && this.perWeek) {
					let datasets = [];
					let labels = [];
					for (let i = 0; i < this.services.length; i++) {
						let item = { label: "", data: [] };
						item.label = this.services[i].name; 
						for (let j = 0; j < this.thisWeek.length; j++) {
							let val = this.perWeek.filter(x => x.day == this.thisWeek[j] && x.name == item.label);
							item.data.push(val.length == 0 ? 0 : val[0].total);
							item.borderColor = this.backgroundColor[i];
							item.fill = false;
						}
						datasets.push(item);
					}
					labels = this.thisWeek;
					return { datasets, labels };
				}
			},
			servicesPerDayInMonth() {
				if (this.services && this.perDayInMonth) {
					let datasets = [];
					let labels = [];
					for (let i = 0; i < this.services.length; i++) {
						let item = { label: "", data: [] };
						item.label = this.services[i].name; 
						for (let j = 0; j < this.thisMonth.length; j++) {
							let val = this.perDayInMonth.filter(x => x.day == this.thisMonth[j] && x.name == item.label);
							item.data.push(val.length == 0 ? 0 : val[0].total);
							item.borderColor = this.backgroundColor[i];
							item.fill = false;
						}
						datasets.push(item);
					}
					labels = this.thisMonth;
					return { datasets, labels };
				}
			},
			servicesPerMonth() {
				if (this.services && this.perMonth) {
					let datasets = [];
					let labels = [];
					for (let i = 0; i < this.services.length; i++) {
						let item = { label: "", data: [] };
						//	Set label equals to a service. e.g. AMS
						item.label = this.services[i].name; 
						for (let j = 0; j < this.thisYear.length; j++) {
							// set val to a month e.g. Jan if the service is equal to current service in loop
							let val = this.perMonth.filter(x => x.month == this.thisYear[j] && x.name == item.label);
							// sets value to 0 if null
							item.data.push(val.length == 0 ? 0 : val[0].total);
							item.borderColor = this.backgroundColor[i];
							item.fill = false;
						}
						datasets.push(item);
						labels = this.thisYear;
					}
					return { datasets, labels };
				}
			},
			topServices() {
				if (this.data) {
					let datasets = [];
					let obj = { data:[] };
					let labels = [];
					let arr = this.data;
					for (let i = 0; i < arr.datasets.length; i++) {
						labels.push(arr.datasets[i].label); 
						let sum = 0;
						for (let j = 0; j < arr.datasets[i].data.length; j++) {
							sum += arr.datasets[i].data[j];
						}
						obj.data.push(sum);
						obj.backgroundColor = ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"]
					}
					obj.data.sort(function(a, b){return b-a});
					datasets.push(obj);
					datasets[0].data.splice(3,datasets[0].data.length - 3);
					return {datasets, labels};
				}
			}
		}
	}
</script>
<style scoped>
	h4 {
		text-align: center;
		font-weight: bold;
		font-size: 1.5em;
	}
	.toggle {
		border-color: rgb(184,186,183) !important;
		position: relative;
		bottom: 40px;
	}
</style>