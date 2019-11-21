<template>
	<div class="card">
		<div class="card-header"><h5>Call Center Status</h5></div>
		<div class="card-body">
			<table class="table">
				<thead>
					<tr>
						<th scope="col">CS</th>
						<th scope="col">Handled Services</th>
						<th scope="col">Status</th>
					</tr>
				</thead>
				<tbody v-if="!loading">
					<tr v-if="users" v-for="(user, index) in users">
						<td>{{ user.name }}</td>
						<td>
							<div v-if="user.worker">
								<span v-for="(skill, i) in user.worker.attributes.skills">
									{{ skill }}<span v-if="i < user.worker.attributes.skills.length - 1" >, </span>
								</span>
							</div>
						</td>
						<td>
							<div v-if="user.worker">
								<statusBadge :status="user.worker.activityName" />
							</div>
						</td>
					</tr>
				</tbody>
				<div v-else>
					Loading...
				</div>
			</table>
		</div>
	</div>
</template>
<script>
	import StatusBadge from '../StatusBadge'
	export default {
		data() {
			return {
				loading: true
			}
		},
		mounted() {
			this.loading = true;
			this.$store.dispatch('retrieveUsers');
			this.$store.dispatch('retrieveTwilioToken')
			.then(response => {
				const token = response;
				this.$store.dispatch('retrieveWorkspace', token)
				.then(workspace => {
					var ref = this;
					workspace.on("ready", function(workspace) {
						ref.$store.dispatch('retrieveWorkers');
					});
				})
				.then(() => {
					this.loading = false;
				})
			})
		},
		methods: {
			assignWorkerToUser(users, workers) {
				if (workers) {
					for (var i = 0; i < users.length; i++) {
						for (var j = 0; j < workers.length; j++) {
							if (users[i].worker_sid == workers[j].sid)
								users[i].worker = workers[j];
						}
					}
				}
			}
		},
		computed: {
			users() {
				let users = [];
				if (this.$store.state.users) {
					users = this.$store.state.users.filter(function(value) {
							return value.worker_sid != null;
						});
				}
				this.assignWorkerToUser(users, this.workers);
				return users;
			},
			workers() {
				return this.$store.state.workers;
			}
		},
		components: {
			StatusBadge
		}
	}
</script>
<style scoped>
    .disabledDiv {
        pointer-events: none;
        opacity: 0.3;
    }
</style>