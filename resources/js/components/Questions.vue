<template>
	<div>
		<div class="modal-header">
	        <h3 v-if="criteria" class="modal-title text-primary">{{ criteria.name }}</h3>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	        </button>
	    </div>
	    <div class="modal-body">
            <serverErrors :serverErrors="serverErrors" />
	    	<table class="table">
				<thead>
					<tr>
						<th class="w-75">Question</th>
						<th class="text-right">
							<button class="btn btn-primary" @click="addQuestion">
			                    <i class="fa fa-plus add"></i> |
			                    Add New
			                </button>
			            </th>
					</tr>
				</thead>
				<tbody>
					<tr v-if="adding">
						<td>
							<div class="form-group">
								<input class="form-control" type="" name="" placeholder="Question" v-model="question">
							</div>
						</td>
						<td>
							<div class="d-flex justify-content-end">
								<button class="btn btn-primary mr-1" @click="createQuestion">Create</button>
								<button class="close ml-3" @click="adding = false">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>	
						</td>
					</tr>
					<tr v-for="(_question, i) in questions">
						<td>
							<div v-if="editing && question.id == _question.id">
								<input class="form-control" v-model="question.question" type="" name="" placeholder="Question">
							</div>
							<div v-else>{{ _question.question }}</div>
						</td>
						<td>
							<div v-if="editing && question.id == _question.id" class="d-flex justify-content-end">
								<button class="btn btn-primary mr-1" @click="updateQuestion">Save</button>
								<button class="close ml-3" @click="editing = false">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div v-else class="d-flex justify-content-end">
								<button class="btn btn-primary mx-1" @click="editQuestion(_question)"><i class="fa fa-edit"></i></button>
								<button class="btn btn-primary" @click="deleteQuestion(_question.id, i)"><i class="fa fa-trash"></i></button>
							</div>	
						</td>
					</tr>
				</tbody>
			</table>
	    </div>
	    <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="adding = false">Close</button>
	    </div>
	</div>
</template>
<script>
	export default {
		props: ['criteria'],
		data() {
			return {
				serverErrors: [],
				questions: {},
				question: '',
				adding: false,
				editing: false
			}
		},
		mounted() {
			let ref = this;
            $("#popup").on("hidden.bs.modal", function () {
            	ref.adding = false;
            });
			this.retrieveQuestions();
		},
		methods: {
			updateQuestion() {
				if (this.total > 100) {
					this.$noty.error("The percentage is over 100");
					return;
				}
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.put(`/api/question/${this.question.id}`, this.question)
				.then((response) => {
					this.$noty.success("Question updated");
					this.editing = false;
					this.serverErrors = [];
				})
				.catch(error => {
					this.serverErrors = Object.values(error.response.data.errors);
				})
			},
			editQuestion(question) {
				this.editing = true;
				this.question = question;
			},
			deleteQuestion(id, i) {
				if (confirm("Are you sure?")) {
					axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
					axios.delete(`/api/question/${id}`)
					.then(response => {
						this.$noty.success("Question deleted");
						this.questions.splice(i, 1);
					})
					.catch(error => {
						this.$noty.error(error);
					})
				}
			},
			addQuestion() {
				this.question = '';
				this.adding = true;
			},
			createQuestion() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.post('/api/question', {
					criteria_id: this.criteria.id,
					question: this.question
				})
				.then(response => {
					this.$noty.success("success");
					this.questions.push(response.data);
					this.question = '';
					this.adding = false;
					this.serverErrors = [];
				})	
				.catch(error => {
					this.serverErrors = Object.values(error.response.data.errors);
				})
			},
			retrieveQuestions() {
				if (this.criteria) {
					axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
					axios.get(`/api/questions/${this.criteria.id}`)
					.then(response => {
						this.questions = response.data;
					})
				}
			},
		},
		watch: {
			criteria() {
				this.retrieveQuestions();
			}
		}
	}
</script>
<style scoped>
</style>