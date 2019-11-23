<template>
	<div>
        <div class="modal-header">
            <h3 class="modal-title text-primary">Add Client to {{ company.name }}</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        	<div class="container">
	            <div class="form-group">
	            	<input type="text" class="form-control" placeholder="Name" v-model="client.fullname">
	            </div>
	            <div class="form-group">
	            	<input type="email" class="form-control" placeholder="E-mail" v-model="client.email">
	            </div>
	            <div class="form-group">
	            	<input type="text" class="form-control" placeholder="Contact Number" v-model="client.number">
	            </div>
	        </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="CreateClient">Create</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				client: {
					fullname:'',
					email:'',
					number:'',
				},
			}
		},
		props: ['company'],
		methods: {
            CreateClient() {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
                axios.post('/api/client', {
            		"fullname": this.client.fullname,
					"email": this.client.email,
					"contactNumber": this.client.number,
					"company_id": this.company.id
            	}).then((response) => {
               		this.$noty.success(`Client Added to ${this.company.name}!`);

	                this.client.fullname = "";
	                this.client.email = "";
	                this.client.number = "";
            	})
            	.catch((err) => {
               		this.$noty.error(err.message);
            	})
            },
		},
	}
</script>