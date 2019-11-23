<template>
	<div>
        <div class="modal-header">
            <h3 class="modal-title text-primary">{{ currentUser ? currentUser.name : 'New User' }}</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        	<div class="container">
                <serverErrors :serverErrors="serverErrors" />
	        	<h5 class="text-primary">Account</h5>
	        	<hr>
                <form @submit.prevent @submit="createUser">
    	            <div class="form-group">
    	            	<input type="text" class="form-control" placeholder="Name" v-model="user.name" required>
    	            </div>
    	            <div class="form-group">
    	            	<input type="email" class="form-control" placeholder="E-mail" v-model="user.email" required>
    	            </div>
    	            <div v-if="!currentUser" class="form-group">
    	            	<input type="password" class="form-control" placeholder="Password" v-model="user.password" required>
    	            </div>
    	            <div v-if="!currentUser" class="form-group">
    	            	<input type="password" class="form-control" placeholder="Confirm Password" v-model="user.confirmPassword" required>
    	            </div>
                    <div class="mb-3">
        	            <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    +63
                                </div>
                            </div>
        	            	<input type="text" class="form-control" placeholder="Phone Number" v-model="user.phone" required>
        	            </div>
                        <small class="text-muted">e.g. 9335557591</small>
                    </div>
    	            <div class="row">
    	            	<div class="col-md-6">	
    			            <h5 class="text-primary">Privilege</h5>
    			            <hr>
    			            <div v-for="privilege in privileges">
    							<div class="custom-control custom-radio">
    								<input type="radio" class="custom-control-input" name="privilege" :id="privilege" v-model="user.privilege" :value="privilege.toLowerCase()">
    								<label class="custom-control-label" :for="privilege">{{ privilege }}</label>
    							</div>
    				        </div>
    		        	</div>
    	            	<div class="col-md-6">	
    			            <h5 class="text-primary">Handled Services</h5>
    			            <hr>
    			            <div v-for="service in services">
    							<div class="custom-control custom-checkbox">
    								<input type="checkbox" class="custom-control-input" :id="service.name" :value="service.name" v-model="worker.skills">
    								<label class="custom-control-label" :for="service.name">{{ service.name }}</label>
    							</div>
    		        		</div>
    	        		</div>
            		</div>
                </form>
	        </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="createUser">
	            {{ currentUser ? 'Save' : 'Create' }}
	        </button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
	</div>
</template>

<script>
	export default {
		props: ['services', 'workspace', 'currentUser'],
		data() {
			return {
                serverErrors: [],
				user: {
					id: this.currentUser ? this.currentUser.id : '',
					name: this.currentUser ? this.currentUser.name : '',
					email: this.currentUser ? this.currentUser.email : '',
					password:'',
					confirmPassword:'',
					privilege: this.currentUser ? this.currentUser.privilege : 'agent',
                    phone: '',
                    worker_sid: this.currentUser ? this.currentUser.worker_sid : '',
				},
				worker: {
					skills:[],
				},
				privileges: [
					'Agent',
					'Admin',
					'System Admin'
				],
			}
		},
		methods: {
            retrieveWorker(sid) {
                let ref = this;
                this.workspace.workers.fetch(sid,
                    function(error, worker) {
                        if(error) {
                            console.log(error.code);
                            console.log(error.message);
                            return;
                        }
                        let phone = worker.attributes.contact_uri;
                        let skills = worker.attributes.skills;
                        ref.user.phone = phone.slice(3);;
                        ref.worker.skills = skills;
                    }
                );
            },
            createUser() {
            	if (this.user.password !== this.user.confirmPassword) {
            		this.$noty.error("Password and confirm password must match");
            		return;
            	}
            	if (this.currentUser) {
                    this.updateUser()
                    .then(response => {
                        this.updateWorker();
                    })
            		return;
            	}
            	//worker
                this.saveUser()
                .then(response => {
                    this.createWorker();
                })
            },
            updateUser() {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
                return new Promise((resolve, reject) => {
                    axios.put(`/api/user/${this.user.id}`, this.user)
                    .then(response => {
                        if (this.currentUser)
                            this.$noty.success('User updated');
                        this.$emit('userUpdate');
                        resolve(response);
                    })
                    .catch(error => {
                        this.serverErrors = Object.values(error.response.data.errors);
                    })
                })
            },
            saveUser() {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
                return new Promise((resolve, reject) => {
                    axios.post('/api/register', {
                        "name": this.user.name,
                        "email": this.user.email,
                        "password": this.user.password,
                        "privilege": this.user.privilege.toLowerCase(),
                        "worker_sid": this.worker.sid,
                        "phone_number": this.user.phone
                    }).then((response) => {
                        console.log(response);
                        this.user.id = response.data.id;
                        this.$noty.success("New User Created!");
                        this.$emit('UserCreated', {name:this.user.name, email:this.user.email, privilege: this.user.privilege});

                        resolve(response);
                    }).catch(error => {
                        this.serverErrors = Object.values(error.response.data.errors);
                    })
                })
            },
            updateWorker() {
                let ref = this;
                this.workspace.workers.delete(this.user.worker_sid,
                    function(error) {
                        if(error) {
                            console.log(error.code);
                            console.log(error.message);
                            return;
                        }
                        console.log("Worker deleted");
                        ref.createWorker();
                    }
                );
            },
            createWorker() {
                var params = {
                	"FriendlyName":this.user.name, 
                	"Attributes":{
                		"skills":this.worker.skills,
                		"contact_uri":'+63' + this.user.phone
                	}
            	};
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
                		ref.worker = worker;
                        ref.user.worker_sid = ref.worker.sid;
                        ref.updateUser()
                        .then(() => {
                            ref.clear();
                        });
                    }
                );
            },
            clear() {
                this.user.name = "";
                this.user.email = "";
                this.user.password = "";
                this.user.confirmPassword = ""; 
                this.user.phone = "";
                this.worker.skills = [];
                this.serverErrors = [];
            }
		},
		mounted() {
            if (this.currentUser)
                this.retrieveWorker(this.user.worker_sid);
		},
		beforeDestroy() {
			this.$emit('setUserToNull');
		},
        watch: {
            token() {
                // var ref = this;
                // this.workspace = new Twilio.TaskRouter.Workspace(this.token);
                // this.workspace.on("ready", function(workspace) {
                //     ref.LoadWorkers(workspace, ref);
                // });
            }
        }
	}
</script>
<style scoped>
	hr {
		margin: 8px 0;
	}
</style>