<template>
	<div class="panel panel-default card">
		<div class="panel-heading">
			<div class="card-header t-item">
				<h6 class="panel-title card-title m-0">
					<a data-toggle="collapse" data-parent="#item" class="d-flex justify-content-between" :href="'#item' + ticket.id">
						<span class="align-self-start">Ticket #: {{ ticket.id }}</span>
						<span class="align-self-start"><timeago :datetime="ticket.created_at" :auto-update="60" /></span>
						<span class="align-self-start">Status: 
							<span  v-if="status" :class="status">{{ status }}</span>
						</span>
					</a>
				</h6>
			</div>
		</div>
		<div :id="'item' + ticket.id" class="panel-collapse collapse in">
			<div class="panel-body card-body">
				<div class="row">
					<div class="col-md-6">
						<h5 class="text-primary m-0">Ticket Info</h5>
						<hr class="m-1">
						<p class="text-muted">Service:<span class="text-info"> {{ ticket.service.name }}</span></p>
						<p class="text-muted">Type:<span class="text-info"> {{ ticket.type }}</span></p>
						<p class="text-muted">Assigned Agent:<span v-if="ticket.agent" class="text-info"> {{ ticket.agent.name }}</span></p>
					</div>
					<div class="col-md-6" v-if="ticket.client">
						<h5 class="text-primary m-0">Client Info</h5>
						<hr class="m-1">
						<p class="text-muted">Client:<span class="text-info"> {{ ticket.client.fullname }}</span></p>
						<p class="text-muted">Contact #:<span class="text-info"> {{ ticket.client.contactNumber }}</span></p>
						<p class="text-muted">E-mail #:<span class="text-info"> {{ ticket.client.email }}</span></p>
						<p class="text-muted">Company:<span class="text-info"> {{ ticket.client.company.name }}</span></p>
					</div>
				</div>
				<h5 class="text-primary mt-3">Additional Info</h5>
				<hr class="m-1">
				<div class="row">
					<div class="col-md-6">
						<span class="text-muted">{{ ticket.type }}: <span class="text-primary">{{ ticket.issue }}</span></span><br>
						<span class="text-muted">Actions Taken:</span>
						<div v-for="update in updates">
							<p v-if="update.action_taken" class="font-weight-bold text-muted row" :class="(update.resolution) ? 'Resolved' : ''">
								<span class="actions col-md-5 font-weight-normal text-muted">
									{{ update.created_at }}: 
								</span>
								<span class="actions col-md-6">
								 {{ update.action_taken }} 
								 </span>
							</p>
						</div>
					</div>
					<div class="col-md-6" v-if="updates.length > 0">
						<div v-if="ticket.agent">
							<div v-if="(status == 'Pending' && ticket.agent.id == $store.state.user.id) || from == 'dashboard'" class="d-flex mt-3 justify-content-end">
								<button class="btn btn-info text-white" @click="endorse">Endorse for reassigning</button>
							</div>
						</div>
						<div v-if="updates[updates.length - 1].resolvedBy">
							<span class="text-muted">Resolved By: </span>
							<span class="text-primary">{{ updates[updates.length - 1].resolvedBy.name }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
			}
		},
		props: [
			'ticket',
			'from'
		],
		mounted() {
			// if (!this.ticket.client) {
			// 	this.$store.dispatch('retrieveTempClient', this.ticket.id)
			// 	.then((response) => {
			// 		this.ticket.client = response;
			// 		this.ticket.client.company = { name: response.company };
			// 		console.log(this.ticket);
			// 	});
			// }
		},
		methods: {
			endorse() {
				axios.post('/api/endorseTicket', { ticket_id: this.ticket.id, user_id: this.ticket.agent.id })
				.then(response => {
					if (response.data == "error") {
						this.$noty.error('Ticket has already been endorsed for reassigning')
						return
					}
					this.$emit('endorsed', response.data);
					this.$noty.success('Ticket endorsed to be reassign')
				})
			},
		},
		computed: {
			updates() {
				let updates = [];
				for (let i = 0; i < this.ticket.updates.length; i++) {
					updates.push(this.ticket.updates[i]);
				}
				return updates;
			},
			status() {
				if (this.updates.length > 0)
					return this.updates[this.updates.length - 1].status.name;
				else 
					return null;
			},
		}
	}
</script>

<style scoped>
	.actions {
		padding: 0;
	}
	p {
		margin: 0;
	}
	.t-item {
		background-color: rgba(238,238,238,.8) !important;
		background-image: none !important;
	}
	.t-item * {
		color: rgb(150,150,150) !important;
	}
	.Open {
		color: #e74c3c !important;
	}
	.Pending {
		color: #e67e22 !important;
	}
	.pending-bg {
		background-color: #e67e22 !important;
	}
	.Resolved {
		color: #2ecc71 !important;
	}
	.Closed {
		color: #95a5a6 !important;
	}
</style>