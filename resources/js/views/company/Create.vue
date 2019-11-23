<template>
	<div>
        <div class="modal-header">
            <h3 class="modal-title text-primary">Add Company</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        	<div class="container">
	            <div class="form-group">
	            	<input type="text" class="form-control" placeholder="Name" v-model="company.name">
	            </div>
	            <div class="form-group">
	            	<textarea class="form-control" placeholder="Address" v-model="company.address"></textarea>
	            </div>
	            <div class="form-group">
	            	<input type="email" class="form-control" placeholder="E-mail" v-model="company.email">
	            </div>
	            <div class="form-group">
	            	<input type="contact_no" class="form-control" placeholder="Contact Number" v-model="company.contact_no">
	            </div>
	        </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="CreateCompany">Create</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				company: {
					name:'',
					address:'',
					email:'',
					contact_no:'',
				},
			}
		},
		methods: {
            createCompany() {
                Axios.post('/api/company', {
            		"name": this.company.name,
            		"email": this.company.email,
            		"address": this.company.address,
            		"contact_no": this.company.contact_no,
            	}).then((response) => {
           			this.$emit('CompanyAdded', response.data.data);
               		this.$noty.success("Company Added!");

	                this.company.name = "";
	                this.company.email = "";
	                this.company.address = "";
	                this.company.contact_no = "";
            	})
            	.catch((err) => {
            		console.log(err.message);
            	})
            },
		},
		mounted() {
		},
        watch: {
        }
	}
</script>