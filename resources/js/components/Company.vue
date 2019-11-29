<template>
	<div class="card">
		<div class="card-header">
            <div class="float-left"><h3 class="text-primary">Companies</h3></div>
            <div class="text-right">
                <a @click="exportData" class="btn btn-primary mr-1">
                    <i class="fas fa-file-export add"></i>
                    Export
                </a>
                <button class="btn btn-primary" @click="adding = true">
                    <i class="fa fa-plus add"></i>
                    Add New
                </button>
            </div>
        </div>
        <div class="card-body">
			<div>
				<div class="d-flex justify-content-between">
					<fileUpload :name="'company'" @uploadFile="uploadFileCompany" @fileChange="handleFileUploadCompany" />
					<autocomplete @input="getCompanyText" :items="companiesName" :placeholder="'Search Company'" />
				</div>
                <serverErrors :serverErrors="serverErrors" />
				<table class="table">
					<thead>
						<tr>
							<th class="w-25">Name</th>
							<th class="w-25">Address</th>
							<th class="w-25">E-mail</th>
							<th class="w-25">Contact Number</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr v-if="adding">
							<td>
								<input class="form-control" v-model="company.name" type="" name="" placeholder="Name">
							</td>
							<td>
								<input class="form-control" v-model="company.address" type="" name="" placeholder="Address">
							</td>
							<td>
								<input class="form-control" v-model="company.email" type="" name="" placeholder="Email">
							</td>
							<td>
								<input class="form-control" v-model="company.contact_no" type="" name="" placeholder="Contact Number">
							</td>
							<td>
								<div class="d-flex justify-content-end">
									<button class="btn btn-primary mr-1" @click="createCompany">Create</button>
									<button class="close ml-3" @click="adding = false">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>	
							</td>
						</tr>
						<tr v-for="(_company, i) in companiesData">
							<td>
								<div v-if="editing && company.id == _company.id">
									<input class="form-control" v-model="company.name" type="" name="" placeholder="Name">
								</div>
								<div v-else>{{ _company.name }}</div>
							</td>
							<td>
								<div v-if="editing && company.id == _company.id">
									<input class="form-control" v-model="company.address" type="" name="" placeholder="Address">
								</div>
								<div v-else>{{ _company.address }}</div>
							</td>
							<td>
								<div v-if="editing && company.id == _company.id">
									<input class="form-control" v-model="company.email" type="" name="" placeholder="Email">
								</div>
								<div v-else>{{ _company.email }}</div>
							</td>
							<td>
								<div v-if="editing && company.id == _company.id">
									<input class="form-control" v-model="company.contact_no" type="" name="" placeholder="Contact Number">
								</div>
								<div v-else>{{ _company.contact_no }}</div>
							</td>
							<td>
								<div v-if="editing && company.id == _company.id" class="d-flex justify-content-end">
									<button class="btn btn-primary mr-1" @click="updateCompany">Save</button>
									<button class="close ml-3" @click="editing = false">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div v-else class="d-flex justify-content-end">
									<button class="btn btn-primary mr-1" title="View Clients" data-toggle="modal" data-target="#clientPopup" @click="getCompany(_company)"><i class="fa fa-users"></i></button>
									<button class="btn btn-primary mr-1" @click="editCompany(_company)"><i class="fa fa-edit"></i></button>
									<button class="btn btn-primary" @click="deleteCompany(_company, i)"><i class="fa fa-trash"></i></button>
								</div>	
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
        <div class="card-footer d-flex justify-content-center">
            <pagination :show-disabled="true" :data="companies" @pagination-change-page="getCompanyResults" :limit="10"></pagination>
        </div>
        <clients :clients="clients" :company="company" @addClient="addClient" @deleteClient="deleteClient" />
	</div>
</template>
<script>
    import Autocomplete from './Autocomplete.vue';
	import FileUpload from './FileUpload.vue';
	import Clients from './Clients.vue';
	export default {
		components: { FileUpload, Clients, Autocomplete },
		data() {
			return {
				adding: false,
				editing: false,
				companies: {},
				clients:[],
				company: {
					name:'',
					address:'',
					email:'',
					contact_no:'',
				},
				fileName: null,
				file: null,
				fileData: null,
				serverErrors: [],
				searchText: null,
			}
		},
		mounted() {
			this.LoadCompanies();
		},
		methods: {
			exportData() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios({
		            url: '/api/companies/export',
		            method: 'GET',
		            responseType: 'blob',
				})
				.then(response => {
					const url = window.URL.createObjectURL(new Blob([response.data]));
					const link = document.createElement('a');
					link.href = url;
					link.setAttribute('download', 'companies' + '.xls');
					document.body.appendChild(link);
					link.click();
				});
			},
			// exportExcel() {
			// 	axios.get('/api/companies/export')
			// 	.then(response => {
			// 		window.url(response);
			// 	});
			// },
			getCompanyText(text) {
				this.searchText = text;
			},
			updateCompany() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.put(`/api/company/${this.company.id}`, this.company)
				.then((response) => {
               		this.$noty.success("Company updated");
               		this.editing = false;
               		this.serverErrors = [];
            	})
            	.catch(error => {
					this.serverErrors = Object.values(error.response.data.errors);
            	})
			},
			editCompany(company) {
				this.editing = true; 
				this.company = company;
			},
			createCompany() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
                axios.post('/api/company', {
            		"name": this.company.name,
            		"email": this.company.email,
            		"address": this.company.address,
            		"contact_no": this.company.contact_no,
            	}).then((response) => {
            		const company = response.data.data;
               		this.$noty.success("Company added");
               		this.companies.data.push(company)
               		this.adding = false;
               		this.serverErrors = [];
            	})
            	.catch(error => {
					this.serverErrors = Object.values(error.response.data.errors);
            	})
            },
            getCompanyResults(page = 1) {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
                axios.get('/api/companies?page=' + page)
                .then(response => {
                    this.companies = response.data;
                });
            },
            LoadCompanies() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
                axios.get("/api/companies")
                .then((response) => {
                    this.companies = response.data;
                })
            },
			deleteClient(i) {
				this.clients.splice(i, 1);
			},
			deleteCompany(company, i) {
				if (confirm(`Are you sure you want to remove ${company.name}?`)) {
					axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
					axios.delete(`api/company/${company.id}`)
					.then(response => {
						this.companies.data.splice(i, 1);
						this.$noty.success(`${company.name} successfully deleted`);
					})
				}
			},
			uploadFileCompany() {
				if (this.file == null) {
					this.$noty.error("No file selected");
					return
				}
				let formData = new FormData();
				formData.append('file', this.file);
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.post('/api/company/fileUpload', formData, {
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}).then(response => {
					this.fileData = response.data;
					let data = [];
					for (let i = 0; i < this.fileData.length; i++) {
						let company = {};
						company.name = this.fileData[i][0];
						company.address = this.fileData[i][1];
						company.email = this.fileData[i][2];
						company.contact_no = this.fileData[i][3];
						data.push(company);
					}
					this.$emit('uploadFile', {data,type:0});
				})
			},
			handleFileUploadCompany(file) {
				this.file = file;
				this.fileName = this.file.name;
				this.LoadCompanies();
			},
			getCompany(company) {
				this.company = company;
				this.loadClients();
			},
			addClient(client) {
				this.clients.push(client);
			},
			loadClients() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.get(`/api/clients/company/${this.company.id}`)
				.then((response) => {
					this.clients = response.data.data;
				})
			},
		},
		computed: {
			companiesName() {
				if (Object.entries(this.companies).length > 0)
					return this.$store.state.companies.map(x => x.name);
			},
			companiesData() {
				if (this.searchText) {
					const company = this.$store.state.companies.find(x => x.name == this.searchText)
					if (company) {
						return [company];
					} else
						return this.companies.data;
				}
				return this.companies.data;
			}
		},
	}
</script>
<style scoped>
	.add {
		height: 100%;
		padding-right: 4px;
		border-right: solid 1px white;
	}
	.custom-file-label::after {
		color: white;
		background-color: rgb(45,39,83);
	}
	label {
		text-overflow: ellipsis;
		display: inline-block;
		overflow: hidden;
		width: 300px;
		white-space: nowrap;
		vertical-align: middle;
    }
</style>