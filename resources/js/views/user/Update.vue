<template>
	<div>
        <div class="modal-header">
            <h3 class="modal-title text-primary">New Agent</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        	<div class="container">
		        	<h5 class="text-primary">Account</h5>
		            <div class="form-group">
		            	<input type="text" class="form-control" placeholder="Name" v-model="user.name" disabled>
		            </div>
	            <h5 class="text-primary">Handled Services</h5>
	            <div class="checkbox" v-for="service in services">
		        	<label>
		        		<input type="checkbox" :id="service.name" :value="service.name" v-model="worker.skills">
		        		<span class="text-muted ml-1">{{ service.name }}</span>
		        	</label>
	        	</div>
	        </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="CreateUser">Create</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
	</div>
</template>

<script>
	import Axios from 'Axios';
	export default {
		data() {
			return {
				user: {
					name:'',
					email:'',
					password:'',
					confirmPassword:''
				},
				worker: {
					skills:[],
				},
				services: [{"name":"AMS"},{"name":"E-trade"},{"name":"GoFast"}],
				token: ''
			}
		},
		methods: {
            getToken() {
                Axios.get("api/admin")
                .then((response) => {
                    this.token = response.data;
                })
            },
            CreateUser() {
            	if (this.user.password !== this.user.confirmPassword) {
            		this.$noty.error("Password and confirm password must match");
            		return;
            	}
            	//worker
                var params = {"FriendlyName":this.user.name, "Attributes":{"skills":this.worker.skills, "languages": ["filipino", "english"]}};
                var ref = this;
                this.workspace.workers.create(
                    params,
                    function(error, worker) {
                        if(error) {
                            console.log(error.code);
                            console.log(error.message);
                            return;
                        }
                        console.log("FriendlyName: "+worker.friendlyName);
                        Axios.post('api/user', {
		            		"name": ref.user.name,
		            		"email": ref.user.email,
		            		"password": ref.user.password,
		            		"worker_sid": worker.sid
		            	}).then((response) => {
		            		console.log(response);
		               		ref.$noty.success("New Agent Created!");
		           			ref.$emit('UserCreated', {name:ref.user.name, email:ref.user.email});

			                ref.user.name = "";
			                ref.user.email = "";
			                ref.user.password = "";
			                ref.user.confirmPassword = ""; 
			                ref.worker.skills = [];
		            	}).catch((err) => {
		            		console.log(err.message);
		            	})
                    }
                );
            },
		},
		mounted() {
			this.getToken();
		},
        watch: {
            token() {
                var ref = this;
                this.workspace = new Twilio.TaskRouter.Workspace(this.token);
                // this.workspace.on("ready", function(workspace) {
                //     ref.LoadWorkers(workspace, ref);
                // });
            }
        }
	}
</script>