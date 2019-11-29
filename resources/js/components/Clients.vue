<template>
	<div class="modal fade" id="clientPopup" tabindex="-1" role="dialog" aria-labelledby="popup" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            	<div class="modal-header">
        			<h3 class="modal-title text-primary">Clients - <span class="break-text">{{ company.name }}</span></h3>
		            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                <span aria-hidden="true">&times;</span>
		            </button>
            	</div>
            	<div class="modal-body">
					<div class="d-flex justify-content-center">
						<fileUpload :name="'client'" @uploadFile="uploadFileClient" @fileChange="handleFileUploadClient" />
					</div>
                	<serverErrors :serverErrors="serverErrors" />
					<div class="table-responsive">
	            		<table class="table">
	            			<thead>
								<tr>
									<th class="w-25">Full Name</th>
									<th class="w-25">E-mail</th>
									<th class="w-25">Contact Number</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr v-if="adding">
									<td>
		            					<input type="text" class="form-control" placeholder="Name" v-model="client.fullname">
									</td>
									<td>
		            					<input type="email" class="form-control" placeholder="E-mail" v-model="client.email">
									</td>
									<td>
		            					<input type="text" class="form-control" placeholder="Contact Number" v-model="client.contactNumber">
									</td>
									<td>
										<div class="d-flex justify-content-end">
											<button class="btn btn-primary mr-1" @click="createClient">Create</button>
											<button class="close ml-3" @click="adding = false">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
									</td>
								</tr>
								<tr v-for="(_client, i) in clients">
									<td>
										<div v-if="editing && client.id == _client.id">
											<input type="text" class="form-control" placeholder="Name" v-model="client.fullname">
										</div>
										<div v-else>{{ _client.fullname }}</div>
									</td>
									<td>
										<div v-if="editing && client.id == _client.id">
											<input type="email" class="form-control" placeholder="E-mail" v-model="client.email">
										</div>
										<div v-else>{{ _client.email }}</div>
									</td>
									<td>
										<div v-if="editing && client.id == _client.id">
											<input type="text" class="form-control" placeholder="Contact Number" v-model="client.contactNumber">
										</div>
										<div v-else>{{ _client.contactNumber }}</div>
									</td>
									<td>
										<div v-if="editing && client.id == _client.id"class="d-flex justify-content-end">
											<button class="btn btn-primary mr-1" @click="updateClient">Save</button>
											<button class="close ml-3" @click="editing = false">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>	
										<div v-else class="d-flex justify-content-end">
											<button class="btn btn-primary mr-1" @click="editClient(_client)"><i class="fa fa-edit"></i></button>
											<button class="btn btn-primary" @click="deleteClient(_client, i)"><i class="fa fa-trash"></i></button>
										</div>	
									</td>
								</tr>
							</tbody>
	            		</table>
	            	</div>
            		<button class="btn btn-primary" @click="adding = true"><span class="fa fa-plus add"></span> Add Client</button>
	                <a :href='`/api/clients/export/${company.id}`' class="btn btn-primary mr-1">
	                    <i class="fas fa-file-export add"></i>
	                    Export
	                </a>
            	</div>
		        <div class="modal-footer">
		            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        </div>
            </div>
        </div>
    </div>
</template>
<script>
	import FileUpload from './FileUpload.vue';
	export default {
		components: { FileUpload },
		props: ['clients', 'company'],
		data() {
			return {
				fileClient: null,
				fileNameClient: null,
				fileDataClient: null,
				client: {},
				adding: false,
				editing: false,
				serverErrors: []
			}
		},
		methods: {
			updateClient() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.put(`/api/client/${this.client.id}`, this.client)
				.then((response) => {
               		this.$noty.success(`Client updated`);
               		this.editing = false;
               		this.serverErrors = [];
            	})
            	.catch(error => {
					this.serverErrors = Object.values(error.response.data.errors);
            	})
			},
			editClient(client) {
				this.editing = true;
				this.client = client;
			},
			deleteClient(client, i) {
				if (confirm(`Are you sure you want to remove ${client.fullname}?`)) {
					axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
					axios.delete(`api/client/${client.id}`)
					.then(response => {
						this.$emit('deleteClient', i);
						this.$noty.success(`${client.fullname} deleted`);
					})
				}
			},
			createClient() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.post('/api/client', {
            		"fullname": this.client.fullname,
					"email": this.client.email,
					"phone_number": this.client.number,
					"company_id": this.company.id
            	}).then((response) => {
               		this.$emit('addClient', response.data.data);
               		this.$noty.success(`Client added to ${this.company.name}!`);
               		this.serverErrors = [];
	                this.client.fullname = "";
	                this.client.email = "";
	                this.client.number = "";
            	})
            	.catch(error => {
					this.serverErrors = Object.values(error.response.data.errors);
            	})
			},
			uploadFileClient() {
				if (this.fileClient == null) {
					this.$noty.error("No file selected");
					return
				}
                $('#clientPopup').modal('hide');
				let formData = new FormData();
				formData.append('file', this.fileClient);
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.post('/api/client/fileUpload', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}).then(response => {
					this.fileData = response.data;
					let data = [];
					for (let i = 0; i < this.fileData.length; i++) {
						let client = {};
						client.fullname = this.fileData[i][0];
						client.email = this.fileData[i][1];
						client.contactNumber = this.fileData[i][2];
						client.company_id = this.company.id;
						data.push(client);
					}
					this.$emit('uploadFileClient', {data, type:1});
				})
			},
			handleFileUploadClient(file) {
				this.fileClient = file;
				this.fileNameClient = this.fileClient.name;
			},
			AddClient() {
				$('#clientPopup').modal('toggle');
				this.$emit('AddClient', this.company);
				this.company = {};
			},
		},
		watch: {
			clients() {
				this.adding = false;
				this.editing = false;
				this.serverErrors = [];
			}
		}
	}
</script>
<style scoped>
	.add {
		height: 100%;
		padding-right: 4px;
		border-right: solid 1px white;
	}
</style>