<template>
	<div class="card">
		<div class="card-header">
			<div class="float-left">
				<select class="text-secondary h4 m-0 select-left align-left" id="filter" v-model="transaction_filter" @change="getFilter">
                    <option value="0">All Tickets</option>
                    <option value="1">My Tickets</option>
                    <option v-if="$store.getters.clientSelected" :value="'2'" selected="true">
                    	{{ $store.state.selected_client.fullname }}'s Tickets
	                </option>
                    <option v-if="$store.getters.ticketSelected" :value="'3'" selected="true">
                    	Ticket #: {{ $store.state.selected_ticket.id }}
	                </option>
                </select>
			</div>
			<div class="text-right mt-1">
				<div class="d-inline">
					<select class="text-secondary select-right" id="filter" v-model="status_filter">
	                    <option value="all">All Status</option>
                        <option v-for="status in ticketStatuses" :value="status.name">{{ status.name }}</option>
	                </select>
				</div>
			</div>
		</div>
		<div class="card-body">
			<!-- <div v-if="loading" class="d-flex justify-content-center"><i class="fa fa-4x fa-spin fa-spinner text-muted"></i></div> -->
			<div class="panel-group" id="item" v-if="$store.state.tickets">
				<transactionItem :from="'home'" v-for="ticket in filteredStatus" :key="ticket.id" :ticket="ticket" />
			</div>
		</div>
	</div>
</template>

<script>
	import transactionItem from "./TransactionItem";

	export default {
		data() {
			return {
				transaction_filter: 0,
				status_filter: 'all',
			}
		},
		props: [
			'ticketStatuses'
		],
		methods: {
			getFilter() {
				if (this.transaction_filter == 1)
					this.$store.dispatch('retrieveTicketsByUser')
			},
			getService(id) {
				return this.services.filter(s => s.id == id);
			},
			getClient(id) {
				return this.clients.filter(c => c.id == id);
			}
		},
		mounted() {
			this.$store.dispatch('retrieveTicketsByUser')
		},
		components: {
			transactionItem
		},
		computed: {
			filteredTickets() {
				if (this.transaction_filter == 0)
					return this.$store.state.tickets;
				else if (this.transaction_filter == 1) {
					return this.$store.state.ticketsByUser;
					// return this.$store.state.tickets.filter(ticket => ticket.agent.id == this.$store.state.user.id)
				}
				else if (this.clientSelected && this.transaction_filter == 2)
					return this.$store.state.tickets.filter(ticket => ticket.client.id == this.clientSelected)
				else if (this.transaction_filter == 3)
					return [this.$store.state.selected_ticket];
			},
			filteredStatus() {
				if (this.status_filter == 'all')
					return this.filteredTickets
				else if (this.status_filter == 'Open')
					return this.filteredTickets.filter(ticket => ticket.updates[ticket.updates.length - 1].status.name == this.status_filter)
				else if (this.status_filter == 'Pending')
					return this.filteredTickets.filter(ticket => ticket.updates[ticket.updates.length - 1].status.name == this.status_filter)
				else if (this.status_filter == 'Resolved')
					return this.filteredTickets.filter(ticket => ticket.updates[ticket.updates.length - 1].status.name == this.status_filter)
				else if (this.status_filter == 'Closed')
					return this.filteredTickets.filter(ticket => ticket.updates[ticket.updates.length - 1].status.name == this.status_filter)
			},
			clientSelected() {
				if (this.$store.getters.clientSelected) {
					return this.$store.state.selected_client.id;
				}
				else {
					return 0;
				}
			}
		}
	}
</script>

<style scoped>
	#filter {
		border: none;
		background-color: transparent;
	}
	.select-left {
		text-align-last: left;
	}
	.select-left option {
		direction: ltr;
	}
	.select-right {
		text-align-last: right;
	}
	option {
		direction: rtl;
	}
</style>