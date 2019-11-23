<template>
	<div class="card">
		<div class="card-header">
            <div class="float-left"><h3 class="text-primary">Criterias</h3></div>
            <div class="text-right">
                <button class="btn btn-primary" @click="addCriteria">
                    <i class="fa fa-plus add"></i> |
                    Add New
                </button>
            </div>
        </div>
		<div class="card-body">
            <serverErrors :serverErrors="serverErrors" />
			<table class="table">
				<thead>
					<tr>
						<th class="w-75">Name</th>
						<th class="w-25">Percentage</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr v-if="adding">
						<td>
							<div class="form-group">
								<input class="form-control" type="" name="" placeholder="Name" v-model="criteria.name">
							</div>
						</td>
						<td>
							<div class="input-group">
								<input class="form-control" type="" name="" placeholder="Percentage" v-model="criteria.percentage">
								<div class="input-group-append">
                        			<div class="input-group-text">
                        				%
                        			</div>
								</div>
							</div>
						</td>
						<td>
							<div class="d-flex justify-content-end">
								<button class="btn btn-primary mr-1" @click="saveCriteria">Create</button>
								<button class="close ml-3" @click="adding = false">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>	
						</td>
					</tr>
					<tr v-for="(_criteria, i) in criterias">
						<td>
							<div v-if="editing && criteria.id == _criteria.id">
								<input class="form-control" v-model="criteria.name" type="" name="" placeholder="Name">
							</div>
							<div v-else>{{ _criteria.name }}</div>
						</td>
						<td>
							<div v-if="editing && criteria.id == _criteria.id">
								<input class="form-control" v-model="criteria.percentage" type="" name="" placeholder="Percentage">
							</div>
							<div v-else>{{ _criteria.percentage }}%</div>
						</td>
						<td>
							<div v-if="editing && criteria.id == _criteria.id" class="d-flex justify-content-end">
								<button class="btn btn-primary mr-1" @click="updateCritera">Save</button>
								<button class="close ml-3" @click="editing = false">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div v-else class="d-flex justify-content-end">
	                            <button class="btn btn-primary" data-toggle="modal" data-target="#popup" @click="getCriteria(_criteria)">
	                                <i class="fas fa-question"></i>
	                            </button>
								<button class="btn btn-primary mx-1" @click="editCriteria(_criteria)"><i class="fa fa-edit"></i></button>
								<button class="btn btn-primary" @click="deleteCriteria(_criteria.id, i)"><i class="fa fa-trash"></i></button>
							</div>	
						</td>
					</tr>
				</tbody>
				<h5 v-if="criterias && criterias.length > 0">Total: {{ total }}%</h5>
				<small v-if="total < 100" class="text-danger">The overall percentage should equal 100</small>
			</table>
		</div>
		<div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="popup" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
			        <questions :criteria="selectedCriteria" />
                </div>
            </div>
        </div>
	</div>
</template>
<script>
	import Questions from './Questions.vue';
	export default {
		components: { Questions },
		data() {
			return {
				serverErrors: [],
				criterias: null,
				criteria: {
					name: '',
					percentage: null,
				},
				selectedCriteria: null,
				adding: false,
				editing: false,
			}
		},
		mounted() {
			this.retrieveCriterias();
		},
		methods: {
			updateCritera() {
				if (this.total > 100) {
					this.$noty.error("The percentage is over 100");
					return;
				}
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.put(`/api/criteria/${this.criteria.id}`, this.criteria)
				.then((response) => {
					this.$noty.success("Criteria updated");
					this.editing = false;
					this.serverErrors = [];
				})
				.catch(error => {
					this.serverErrors = Object.values(error.response.data.errors);
				})
			},
			editCriteria(criteria) {
				this.editing = true;
				this.criteria = criteria;
			},
			deleteCriteria(id, i) {
				if (confirm('Are you sure?')) {
					axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
					axios.delete(`/api/criteria/${id}`)
					.then(response => {
						this.$noty.success('Successfully deleted');
						this.criterias.splice(i, 1);
					})
				}
			},
			getCriteria(criteria) {
				this.selectedCriteria = criteria;
			},
			addCriteria() {
				this.criteria = {};
				this.adding = true;
			},
			saveCriteria() {
				if (parseFloat(this.criteria.percentage) + parseFloat(this.total) > 100) {
					this.$noty.error("The percentage is over 100");
					return;
				}
				let newCriteria = {};
				newCriteria.name = this.criteria.name;
				newCriteria.percentage = this.criteria.percentage;
				this.createCriteria(newCriteria);
			},
			createCriteria(criteria) {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.post('/api/criteria', criteria)
				.then((response) => {
					this.$noty.success("New criteria was successfully added");
					this.criterias.push(response.data);
					this.adding = false;
					this.criteria.name = '';
					this.criteria.percentage = null;
					this.serverErrors = [];
				})
				.catch(error => {
					this.serverErrors = Object.values(error.response.data.errors);
				})
			},
			retrieveCriterias() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.get('/api/criterias')
				.then(response => {
					this.criterias = response.data;
				})
			}
		},
		computed: {
			total() {
				if (this.criterias) {
					if (this.criterias.length > 0) {
						const total = this.criterias
									.reduce((a, b) => ({
										percentage: parseFloat(a.percentage) + parseFloat(b.percentage)
									}));
						return total.percentage;
					}
				}
			}
		}
	}
</script>