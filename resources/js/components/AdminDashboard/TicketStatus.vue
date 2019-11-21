<template>
	<div class="card">
		<div class="card-header d-flex justify-content-between">
			<div>
				<h5>Tickets</h5>
			</div>
			<div>
                <a href="/api/tickets/export" class="btn btn-primary mr-1">
                    <i class="fas fa-file-export add"></i>
                    Export
                </a>
            </div>
		</div>
		<div class="row card-body">
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
	                    <a href="" @click="setTickets('Open')" class="text-header d-flex justify-content-center status-open" data-toggle="modal" data-target="#popup">
	                    	Open<br>{{ openTickets ? openTickets.length : 0 }}
	                    </a>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
	                    <a href="" @click="setTickets('Pending')" class="text-header d-flex justify-content-center status-pending" data-toggle="modal" data-target="#popup">
	                    	Pending<br>{{ pendingTickets ? pendingTickets.length : 0 }}
	                    </a>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
	                    <a href="" @click="setTickets('Resolved')" class="text-header d-flex justify-content-center status-resolved" data-toggle="modal" data-target="#popup">
	                    	Resolved<br>{{ resolvedTickets ? resolvedTickets.length : 0 }}
	                    </a>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
	                    <a href="" @click="setTickets('Closed')" class="text-header d-flex justify-content-center status-closed" data-toggle="modal" data-target="#popup">
	                    	Closed<br>{{ closedTickets ? closedTickets.length : 0 }}
	                    </a>
					</div>
				</div>
			</div>
			<h5 class="m-3 text-muted font-weight-bold">
				Total Tickets: {{ totalTickets }}
				<br>
				<a class="text-danger" href="" @click="setTickets('Unregistered')" data-toggle="modal" data-target="#popup">Unregistered Tickets: {{ unregisteredTickets.length }}</a>
			</h5>
		</div>
		<div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="popup" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
			        <div class="modal-header">
			            <h3 class="modal-title text-primary">{{ status }} Tickets</h3>
			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			                <span aria-hidden="true">&times;</span>
			            </button>
			        </div>
			        <div class="modal-body">
			        	<div class="container">
		        			<div v-if="tickets">
		        				<div v-if="tickets.length > 0">
									<div class="panel-group" id="item" v-if="$store.state.tickets">
										<div v-if="status=='Unregistered'">
											<table class="table">
												<thead>
													<th>Ticket #</th>
													<th>Company</th>
													<th>Client Name</th>
												</thead>
												<tbody>
													<tr v-for="(ticket, i) in unregTickets">
														<td data-toggle="modal" data-target="#clientPopup" class="link" @click="getTempClient(ticket, i)">{{ ticket.ticket_id }}</td>
														<td data-toggle="modal" data-target="#clientPopup" class="link" @click="getTempClient(ticket, i)">{{ ticket.company }}</td>
														<td data-toggle="modal" data-target="#clientPopup" class="link" @click="getTempClient(ticket, i)">{{ ticket.fullname }}</td>
													</tr>
												</tbody>
											</table>
										</div>
						        		<div v-else v-for="ticket in tickets">
					        				<transactionItem :ticket="ticket" />
						        		</div>
						        	</div>
					        	</div>
					        	<div v-else>
					        		<h5>No {{ status }} tickets.</h5>
					        	</div>
		        			</div>
			        	</div>
			        </div>
			        <div class="modal-footer">
			            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			        </div>
                </div>
            </div>
        </div>
		<div class="modal fade" id="clientPopup" tabindex="10" role="dialog" aria-labelledby="clientPopup" aria-hidden="true">
	        <div class="modal-dialog modal-lg" role="document">
	            <div class="modal-content">
					<SaveTempClient :tempClient="tempClient" @clientSaved="removeUnregTicket" />
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import SaveTempClient from '../SaveTempClient.vue';
	import moment from 'moment';
	import TransactionItem from '../TransactionItem.vue';
	export default {
		props: ['filter'],
		data() {
			return {
				status: null,
				tickets: null,
				unregTickets: null,
				tempClient: null,
			}
		},
		components: { TransactionItem, SaveTempClient },
		mounted() {
			this.retrieveUnregTickets();
            $("#clientPopup").on("hidden.bs.modal", function () {
                $('#popup').modal('show');
            });
		},
		methods: {
			removeUnregTicket(client) {
				this.unregTickets.splice(client.index, 1);
			},
			getTempClient(client, i) {
				this.tempClient = client;
				this.tempClient.index = i;
				$('#popup').modal('toggle');
			},
			retrieveUnregTickets() {
				axios.get('/api/unregistered')
				.then(response => {
					this.unregTickets = response.data;
				})
			},
			setTickets(status) {
				this.status = status;
				switch(status) {
					case 'Open' : this.tickets = this.openTickets
						break;
					case 'Pending' : this.tickets = this.pendingTickets
						break;
					case 'Resolved' : this.tickets = this.resolvedTickets
						break;
					case 'Closed' : this.tickets = this.closedTickets
						break;
					case 'Unregistered' : this.tickets = this.unregisteredTickets
						break;
				}
			}
		},
		computed: {
			filteredTickets() {
				let date = new Date();

				let startOfWeek = moment().startOf('isoweek').toDate();
				let endOfWeek   = moment().endOf('isoweek').toDate();
				// let d = new Date(this.$store.state.tickets[0].created_at);
				if (this.$store.state.tickets) {
					if (this.filter == 0) {
						return this.$store.state.tickets
					}
					else if (this.filter == 1) {
						return this.$store.state.tickets.filter(ticket => new Date(ticket.created_at).getMonth() + 1 == date.getMonth() + 1);
					}
					else if (this.filter == 2) {
						return this.$store.state.tickets.filter(ticket => new Date(ticket.created_at) >= startOfWeek && new Date(ticket.created_at) <= endOfWeek);
					}
					else if (this.filter == 3) {
						return this.$store.state.tickets.filter(ticket => new Date(ticket.created_at).getDate() == date.getDate());
					}
				}
			},
			totalTickets() {
				if (this.$store.state.tickets)
					return this.filteredTickets.length;
			},
			openTickets() {
				if (this.$store.state.tickets) {
					return this.filteredTickets.filter(ticket => ticket.updates[ticket.updates.length - 1].status.name == "Open");
				}
			},
			pendingTickets() {
				if (this.$store.state.tickets) {
					return this.filteredTickets.filter(ticket => ticket.updates[ticket.updates.length - 1].status.name == "Pending");
				}
			},
			resolvedTickets() {
				if (this.$store.state.tickets) {
					return this.filteredTickets.filter(ticket => ticket.updates[ticket.updates.length - 1].status.name == "Resolved");
				}
			},
			closedTickets() {
				if (this.$store.state.tickets) {
					return this.filteredTickets.filter(ticket => ticket.updates[ticket.updates.length - 1].status.name == "Closed");
				}
			},
			unregisteredTickets() {
				if (this.unregTickets) {
					let unreg = [];
					for (let i = 0; i < this.unregTickets.length; i++) {
						let ticket = this.unregTickets[i].ticket;
						ticket.client = {};
						ticket.client.company = {};
						ticket.client.fullname = this.unregTickets[i].fullname;
						ticket.client.email = this.unregTickets[i].email;
						ticket.client.contactNumber = this.unregTickets[i].contactNumber;
						ticket.client.company.name = this.unregTickets[i].company;
						unreg.push(ticket);
					}
					return unreg
				} else {
					return [];
				}
			},
		}
	}
</script>
<style scoped>
	.add {
		height: 100%;
		padding-right: 4px;
		border-right: solid 1px white;
	}
	.text-header {
		text-align: center;
		font-weight: bold;
		font-size: 2em;
	}
	.status-open {
		color: #e74c3c !important;
	}
	.status-pending {
		color: #e67e22 !important;
	}
	.status-resolved {
		color: #2ecc71 !important;
	}
	.status-closed {
		color: #95a5a6 !important;
	}
	.link:hover {
		cursor: pointer;
		text-decoration: underline;
		color: rgb(66,103,178);
	}
</style>