<template>
	<div class="card">
		<div class="card-header"><h5>Calls</h5></div>
		<div class="card-body row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<HorizontalBarChart :passedData="avgFulfillment('Request')" />
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<HorizontalBarChart :passedData="avgFulfillment('Incident')" />
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import HorizontalBarChart from '../HorizontalBarChart';
	import moment from 'moment';
	export default {
		props: ['filter'],
		data() {
			return {
				labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',],
				request: {
				    datasets: [
					    // {
					    // 	label: "Request Acknowledgement Time",
					    //     data: [3,10,4,7,16,21,6,17,20,12,7,1],
					    //     backgroundColor: 'rgba(29,17,57,0.6)',
			      //       },
					    {
					    	label: "Request Fulfillment",
					        data: [3,15,8,12,24,25,12,20,20,12,9,3],
					        backgroundColor: 'rgba(208,7,39,0.6)',
					    },
				    ],
			        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',],
				},
				incident: {
				    datasets: [
					    // {
					    // 	label: "Incident Acknowledgement Time",
					    //     data: [3,10,4,7,16,21,6,17,20,12,7,1],
					    //     backgroundColor: 'rgba(29,17,57,0.6)',
			      //       },
					    {
					    	label: "Incident Resolution Time",
					        data: [3,15,8,12,24,25,12,20,20,12,9,3],
					        backgroundColor: 'rgba(208,7,39,0.6)',
					    },
				    ],
			        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
				},
			}
		},
		components: { HorizontalBarChart },
		mounted() {
		},
		methods: {
			millisToMinutesAndSeconds(millis) {
				var minutes = Math.floor(millis / 60000);
				var seconds = ((millis % 60000) / 1000).toFixed(0);
				return minutes
			},
			tickets(_type) {
				if (this.$store.state.tickets) {
					let date = new Date();

					let startOfWeek = moment().startOf('isoweek').toDate();
					let endOfWeek = moment().endOf('isoweek').toDate();
					let tickets = this.$store.state.tickets.filter(ticket => ticket.updates[ticket.updates.length - 1].resolvedBy && ticket.type == _type);
					if (tickets) {
						if (this.filter == 0) {
							return tickets
						}
						else if (this.filter == 1) {
							return tickets.filter(ticket => new Date(ticket.updates[ticket.updates.length - 1].created_at).getMonth() + 1 == date.getMonth() + 1);
						}
						else if (this.filter == 2) {
							return tickets.filter(ticket => new Date(ticket.updates[ticket.updates.length - 1].created_at) >= startOfWeek && new Date(ticket.updates[ticket.updates.length - 1].created_at) <= endOfWeek);
						}
						else if (this.filter == 3) {
							return tickets.filter(ticket => new Date(ticket.updates[ticket.updates.length - 1].created_at).getDate() == date.getDate());
						}
					}
				}
			},
			avgFulfillment(_type) {
				if (this.tickets(_type)) {
					let a = [];
					for (let i = 0; i < this.label.length; i++) {
						a.push({sum:0, counter:0})
					}
					let incident = {};
					let object = {}
					for (let i = 0; i < this.tickets(_type).length; i++) {
						// acknowledgement time
						let ack = new Date(this.tickets(_type)[i].created_at);
						// fullfilment time
						let ful = new Date(this.tickets(_type)[i].updates[this.tickets(_type)[i].updates.length - 1].created_at);
						// difference between the two
						let diff = Math.abs(ful - ack);
						// gets the month number
						let index = 0;
						if (this.filter == 0)
							index = new Date(ack).getMonth();
						else if (this.filter == 1)
							index = new Date(ack).getDate();
						else if (this.filter == 2)
							index = ((new Date(ack).getDay() - 1) < 0) ? 6 : (new Date(ack).getDay() - 1);
						else if (this.filter == 3)
							index = new Date(ack).getHours();
						let obj = a[index];
						obj.sum += this.millisToMinutesAndSeconds(diff);
						obj.counter++;
						a[index] = obj;
					}
					let data = a.map(x => {
						if (x.counter != 0)
							return x.sum / x.counter
						else
							return 0;
					});
					incident.datasets = [];
					object.backgroundColor = "rgba(208,7,39,0.6)";
					object.data = data;
					object.label = _type + " Fulfillment";
					incident.datasets.push(object);
					incident.labels = this.label;
					return incident;
				}
				return {}
			},
		},
		computed: {
			label() {
				if (this.filter == 0) {
					let arr = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',]
					// this.request.labels = arr;
					return arr;
				}
				else if (this.filter == 1) {
					const year = new Date().getFullYear();
					const month = new Date().getMonth() + 1;
					const days = new Date(year, month, 0).getDate();
					let arr = [];
					for(var i = 0; i < days; i++) {
						arr.push(i + 1);
					}
					// this.request.labels = arr;
					return arr;
				}
				else if (this.filter == 2) {
					let arr = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
					// this.request.labels = arr;
					return arr;
				}
				else if (this.filter == 3) {
					let arr = [];
					for(var i = 0; i < 24; i++) {
						arr.push(i);
					}
					// this.request.labels = arr;
					return arr;
				}
			}
		}
	}
</script>
<style scoped>

</style>