<template>
	<div class="container-fluid">
		<div class="row mb-3">
			<div class="col-md-6">
				<div class="card">
                    <div class="card-header">
                        <div class="float-left"><h3 class="text-primary">Accounts</h3></div>
                        <div class="text-right">
                            <a @click="exportData" class="btn btn-primary mr-1">
                                <i class="fas fa-file-export add"></i>
                                Export
                            </a>
                            <router-link class="btn btn-primary" to="/manage/register" data-toggle="modal" data-target="#popup">
                                <i class="fa fa-plus add"></i>
                                Add New
                            </router-link>
                        </div>
                    </div>
                    <div class="card-body">
                        <users :users="users.data" :workspace="workspace" @editUser="editUser" @deleteUser="deleteUser" />
                    </div>
                    <div class="card-footer d-flex justify-content-center">
                        <pagination :limit="10" :show-disabled="true" :data="users" @pagination-change-page="getUserResults"></pagination>
                    </div>
				</div>
			</div>
			<div class="col-md-6">
                <company @AddClient="AddClient" @uploadFile="showFileData" @uploadFileClient="showFileData" />
            </div>
		</div>
        <div class="row mb-3">
            <div class="col-md-6">
                <services :services="services" @ServiceAdded="AddService" @deleteService="deleteService" />
            </div>            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left"><h3 class="text-primary">Email Template</h3></div>
                    </div>
                    <div class="card-body">
                        <emailTemplate :emailTemplates="email_template" @save="UpdateEmail" />
                    </div>
                </div>
                <!-- <div class="card">
                    <div class="card-header">
                        <div class="float-left"><h3 class="text-primary">FAQs</h3></div>
                        <div class="text-right">
                            <router-link class="btn btn-primary" to="/manage/subjectCreate" data-toggle="modal" data-target="#popup">
                                <i class="fa fa-plus add"></i>
                                Add New
                            </router-link>
                        </div>
                    </div>
                    <div class="card-body">
                        <transactionSubjects :subjects="subjects" />
                    </div>
                </div> -->
            </div>
        </div>
        <!-- <div class="row mb-3">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left"><h3 class="text-primary">Email Template</h3></div>
                    </div>
                    <div class="card-body">
                        <emailTemplate :emailTemplates="email_template" @save="UpdateEmail" />
                    </div>
                </div>
            </div>
        </div> -->
        <div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="popup" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <router-view 
                        @UserCreated="AddUser" @SubjectAdded="AddSubject" @uploaded="fileUploaded" @userUpdate="userUpdate" @setUserToNull="user = null"
                        :companytypes="companytypes" :company="company" :services="services" :token="token" :workspace="workspace" :fileData="fileData" :type="fileUploadType" :currentUser="user">
                    </router-view>
                </div>
            </div>
        </div>
	</div>
</template>
<script>
	import Company from '../Company.vue';
	import Services from '../Services.vue';
    import TransactionSubjects from '../TransactionSubjects.vue';
    import Users from '../Users.vue';
    import EmailTemplate from '../EmailTemplate.vue';
    import router from '../../router';
	export default {
		data() {
			return {
                company: {},
                companytypes: [],
                subjects: [],
				// workers:null,
                worker: {
                    name:'',
                    skills:[],
                },
                users: {},
                user: null,
                token:"",
                workspace: null,
                services: [],
                service_id:null,
                email_template:"",
                fileData: null,
                fileUploadType: 0,
			}
		},
		components: {Company, Services, TransactionSubjects, Users, EmailTemplate},
		created() {
			this.getToken();
            this.LoadUsers();
            this.LoadServices();
            this.LoadSubjects();
            this.LoadEmailTemplate();
		},
        mounted() {
            $("#popup").on("hidden.bs.modal", function () {
                // router.push('/manage');
                router.go(-1);
            });
            // this.$store.dispatch('retrieveTwilioToken')
            // .then(response => {
            //     const token = response;
            //     this.$store.dispatch('retrieveWorkspace', token)
            //     .then(workspace => {
            //         var ref = this;
            //         workspace.on("ready", function(workspace) {
            //             ref.$store.dispatch('retrieveWorkers');
            //         });
            //     })
            //     .then(() => {
            //         this.loading = false;
            //     })
            // })
        },
		methods: {
            exportData() {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
                axios({
                    url: '/api/users/export',
                    method: 'GET',
                    responseType: 'blob',
                })
                .then(response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'users' + '.xls');
                    document.body.appendChild(link);
                    link.click();
                });
            },
            deleteService() {
                this.LoadServices();
            },
            deleteUser() {
                this.LoadUsers();
            },
            getUserResults(page = 1) {
                axios.get('/api/users?page=' + page)
                .then(response => {
                    this.users = response.data;
                });
            },
            assignWorkerToUser(users, workers) {
                if (workers) {
                    for (var i = 0; i < users.length; i++) {
                        for (var j = 0; j < workers.length; j++) {
                            if (users[i].worker_sid == workers[j].sid)
                                users[i].worker = workers[j];
                        }
                    }
                }
            },
            userUpdate() {
                this.LoadUsers();
                this.user = null;
            },
            editUser(user) {
                this.user = user;
            },
            fileUploaded() {
                this.$noty.success('File successfully uploaded to the database');
                $('#popup').modal('hide');
            },
            showFileData(data) {
                this.fileData = data.data;
                this.fileUploadType = data.type;
                this.$router.push('/manage/fileData')
                $('#popup').modal('show');
            },
            getToken() {
                axios.get("/api/admin")
                .then((response) => {
                    this.token = response.data;
                })
            },
            LoadCompanyTypes() {
                axios.get("/api/companytypes")
                .then((response) => {
                    this.companytypes = response.data.data;
                })
            },
            LoadServices() {
                axios.get("/api/services")
                .then((response) => {
                    this.services = response.data.data;
                })
            },
            LoadEmailTemplate() {
                axios.get("/api/email")
                .then((response) => {
                    this.email_template = response.data.data;
                })
            },
            AddService(service) {
                this.services.push(service);
            },
            LoadSubjects() {
                axios.get("/api/transactionSubjects")
                .then((response) => {
                    this.subjects = response.data.data;
                })
            },
            AddSubject(subject) {
                this.subjects.push(subject);
            },
            LoadUsers() {
                axios.get("/api/users")
                .then((response) => {
                    this.users = response.data;
                })
            },
            // LoadWorkers(workspace, ref) {
            //     workspace.workers.fetch(function(error, workerList) {
            //         if(error) {
            //             console.log(error.code);
            //             console.log(error.message);
            //             return;
            //         }
            //         ref.workers = workerList.data;
            //         console.log(ref.workers);
            //     })
            // },
            UpdateWorker() {
                worker.update("WKxxx", "ActivitySid", "WAxxx",
                    function(error, worker) {
                        if(error) {
                            console.log(error.code);
                            console.log(error.message);
                            return;
                        }
                        console.log("FriendlyName: "+worker.friendlyName);
                    }
                );
            },
            AddUser(user) {
                this.LoadUsers();
            },
            AddClient(company) {
                router.push({ path: "/manage/clientCreate" })
                $("#popup").modal();
                this.company = company;
            },
            UpdateEmail(template) {
                //this.edit_email = false;
                axios.put(`api/email/${template.id}`, {
                    "subject":template.subject,
                    "body":template.body,
                    "edited_by":0
                }).then(() => {
                    this.$noty.success("Email Template Updated!");
                })
            },
        },
        computed: {
        },
        watch: {
            token() {
                var ref = this;
                this.workspace = new Twilio.TaskRouter.Workspace(this.token);
                this.workspace.on("ready", function(workspace) {
                // ref.LoadWorkers(ref.workspace, ref);
                });
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
	.card {
		height: 100%;
	}
</style>