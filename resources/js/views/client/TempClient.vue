<template>
	<div>
        <div class="modal-header">
            <h3 class="modal-title text-primary">{{ saving ? 'Save Client' : 'Add a temporary Client' }}</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        	<div class="container">
        		<div v-if="saving">
        			<h5 class="text-primary">Assign to client</h5>
        			<hr class="my-2">
                	<autocomplete :initialText="initialText" @input="getClientText" :items="clientsName" :placeholder="'Client'" />
                	<button class="btn btn-primary my-2" @click="assignToClient">Assign</button>
        		</div>
        		<h5 class="text-primary mt-3">Register client</h5>
        		<hr class="my-2">
	            <div class="form-group">
                	<autocomplete :initialText="client.company" @input="getCompanyText" :items="companiesName" :placeholder="'Company'" />
	            </div>
	            <div class="form-group">
	            	<input type="text" class="form-control" placeholder="Name" v-model="client.fullname">
	            </div>
	            <div class="form-group">
	            	<input type="email" class="form-control" placeholder="E-mail" v-model="client.email">
	            </div>
	            <div class="form-group">
	            	<input type="text" class="form-control" placeholder="Contact Number" v-model="client.contactNumber">
	            </div>
	        </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="setTempClient">Done</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
	</div>
</template>

<script>
    import Autocomplete from '../../components/Autocomplete.vue';
	import Axios from 'Axios';
	export default {
		props: ['tempClient'],
        components: {
            Autocomplete
        },
		data() {
			return {
				client: {
					company:'',
					fullname: '',
					email: '',
					contactNumber: '',
				},
				saving: false,
				existingClient: '',
				initialText: '',
			}
		},
		mounted() {
			const tempClient = this.$store.state.tempClient;
			this.$store.dispatch('retrieveCompanies');
			this.$store.dispatch('retrieveClients');
			if (tempClient) {
				this.client = tempClient;
			}
		},
		methods: {
			getClientText(text) {
				this.existingClient = text;
			},
			getCompanyText(text) {
				this.client.company = text;
			},
			setTempClient() {
				if (!this.saving) {
					this.$store.commit('setTempClient', this.client);
					$('#clientPopup').modal('hide');
				}
				else {
					const company = this.$store.state.companies.find(x => x.name == this.client.company);
					if (!company) {
						this.$noty.error("Company doesn't exist")
						return
					}
					this.saveClient(company)
					.then((response) => {
						this.deleteTempClient();
						this.$store.dispatch('updateTicket', { 
							ticketId: this.tempClient.ticket_id,
							client_id: response.id 
						})
						this.$emit('clientSaved', this.tempClient);
					})
					$('#clientPopup').modal('hide');
				}
			},
			assignToClient() {
				const client = this.$store.state.clients.find(x => x.fullname == this.existingClient);
				if (!client) {
					this.$noty.error("Client doesn't exist")
					return;
				}
				this.deleteTempClient();
				this.$store.dispatch('updateTicket', { 
					ticketId: this.tempClient.ticket_id,
					client_id: client.id
				})
				this.$noty.success("Client assigned");
				this.$emit('clientSaved', this.tempClient);
				$('#clientPopup').modal('hide');
				this.initialText = '';
			},
			saveClient(company) {
				return new Promise((resolve, reject) => {
					axios.post('/api/client', {
						fullname: this.client.fullname,
						email: this.client.email,
						phone_number: this.client.contactNumber,
						company_id: company.id
					})
					.then(response => {
						this.$noty.success("Client saved");
						resolve(response.data.data);
					})
				})
			},
			deleteTempClient() {
				axios.delete(`/api/tempClient/${this.tempClient.id}`)
				.then(response => {
				})
			}
		},
		computed: {
			companiesName() {
				if (this.$store.state.companies)
					return this.$store.state.companies.map(c => c.name)
			},
			clientsName() {
				if (this.$store.state.clients)
					return this.$store.state.clients.map(c => c.fullname)
			}
		},
		watch: {
			tempClient() {
				this.client.company = this.tempClient.company;
				this.client.fullname = this.tempClient.fullname;
				this.client.email = this.tempClient.email;
				this.client.contactNumber = this.tempClient.contactNumber;
				this.saving = true;
			}
		}
	}
</script>