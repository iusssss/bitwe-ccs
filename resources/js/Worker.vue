<template>
	<div class="container-fluid">
		{{ workerProps.activityName }}
		<button @click="offline">Offline</button>
		<br>
		<div v-for="task in reservations">
			<p>{{ task }}</p>
		</div>
	</div>
</template>

<script>
	import Axios from 'Axios';
	export default {
		data() {
			return {
				token: "",
				worker: {},
				workerProps: {},
				workerSid: 'WKf5710c598cd9f083445b4a35967c386c',
				activityList: {},
				activitySids: {},
				reservations: {},
			}
		},
		methods: {
			getToken() {
				Axios.get(`api/workers/${this.workerSid}`)
				.then((response) => {
					this.token = response.data;
				})
			},
			fetchActivities() {
				var ref = this;
				this.worker.activities.fetch(function(error, activityList) {
					ref.activityList = activityList.data;
					for (var i = 0; i < ref.activityList.length; i++) {
						ref.activitySids[ref.activityList[i].friendlyName] = ref.activityList[i].sid;
					}
				})
			},
			fetchReservations() {
				var ref = this;
				var params = {"ReservationStatus":"pending"};
				this.worker.fetchReservations(function(error, reservations) {
					ref.reservations = reservations.data;
				},
				params
				);
			},
			registerTaskRouterCallbacks() {
				var ref = this; 
				this.worker.on('activity.update', function(worker) {
					ref.workerProps = worker;
				})
				this.worker.on('reservation.created', function(reservation) {
					ref.reservations.push(reservation);
				})
			},
			offline() {
				this.worker.update("ActivitySid", this.activitySids.Away);

			}
		},
		mounted() {
			this.getToken();
		},
		watch: {
			token() {
				var ref = this;
				this.worker = new Twilio.TaskRouter.Worker(this.token);
				this.worker.on('ready', function(worker) {
					ref.workerProps = worker;
				})
				this.fetchActivities();
				this.fetchReservations();
				this.registerTaskRouterCallbacks();
			}
		}
	}
</script>