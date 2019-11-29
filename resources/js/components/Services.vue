<template>
	<div class="card">
        <div class="card-header">
            <div class="float-left"><h3 class="text-primary">Services</h3></div>
            <div class="text-right">
                <a href="/api/services/export" class="btn btn-primary mr-1">
                    <i class="fas fa-file-export add"></i>
                    Export
                </a>
                <button class="btn btn-primary" @click="adding = true; editing = false; service = {}">
                    <i class="fa fa-plus add"></i>
                    Add New
                </button>
            </div>
        </div>
        <div class="card-body">
			<div>
                <serverErrors :serverErrors="serverErrors" />
				<table class="table">
					<thead>
						<tr>
							<th class="w-25">Name</th>
							<th class="w-50">Description</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr v-if="adding">
							<td>
								<input class="form-control" v-model="service.name" placeholder="Name" />
							</td>
							<td>
								<textarea class="form-control" v-model="service.description" placeholder="Description"></textarea>
							</td>
							<td>
								<div class="d-flex justify-content-end">
									<button class="btn btn-primary mx-1" @click="createService">Create</button>
									<button class="close ml-3" @click="adding = false">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>	
							</td>
						</tr>
						<tr v-for="_service in services">
							<td>
								<div v-if="editing && service.id == _service.id">
									<input class="form-control" v-model="service.name" placeholder="Name" />
								</div>
								<div v-else>{{ _service.name }}</div>
							</td>
							<td>
								<div v-if="editing && service.id == _service.id">
									<textarea class="form-control" v-model="service.description" placeholder="Description"></textarea>
								</div>
								<div v-else>{{ _service.description }}</div>
							</td>
							<td>
								<div v-if="editing && service.id == _service.id">
									<div class="d-flex justify-content-end">
										<button class="btn btn-primary mx-1" @click="updateService">Save</button>
										<button class="close ml-3" @click="editing = false">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>	
								</div>
								<div v-else class="d-flex justify-content-end">
									<button class="btn btn-primary mx-1"><i class="fa fa-edit" @click="editService(_service)"></i></button>
									<button class="btn btn-primary" @click="deleteService(_service)"><i class="fa fa-trash"></i></button>
								</div>	
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: ['services'],
		data() {
			return {
                service: {
                    name:'',
                    description:'',
                },
                adding: false,
                editing: false,
                serverErrors: [],
			}
		},
		methods: {
			deleteService(service) {
				if (confirm(`Are you sure you want to delete service: ${service.name}?`)) {
					axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
					axios.delete(`/api/service/${service.id}`)
					.then(response => {
						this.$noty.success("Service deleted");
						this.$emit('deleteService');
					})
				}
			},
			updateService() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.put(`/api/service/${this.service.id}`, this.service)
				.then(response => {
					this.$noty.success("Service updated");
					this.editing = false;
					this.serverErrors = [];
				})
				.catch(error => {
					this.serverErrors = Object.values(error.response.data.errors);
				})
			},
			editService(service) {
				this.editing = true;
				this.adding = false;
				this.service = service;
			},
            createService() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
                axios.post('/api/service', {
                    "name": this.service.name,
                    "description": this.service.description,
                }).then((response) => {
                    this.$emit('ServiceAdded', response.data.data);
                    this.$noty.success("Service added");

                    this.service.name = "";
                    this.service.description = "";
                    this.adding = false;
					this.serverErrors = [];
                })
                .catch(error => {
					this.serverErrors = Object.values(error.response.data.errors);
                })
            },
        },
	}
</script>

<style scoped>
	.add {
		height: 100%;
		padding-right: 4px;
		border-right: solid 1px white;
	}
</style>