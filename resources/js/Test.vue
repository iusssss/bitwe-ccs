<template>
    <div class="container-fluid">
        <div class="form-group row">
            <label class="col-md-4 col-form-label text-md-right" for="worker-name">Name</label>
            <div class="col-md-6">
                <input name="worker-name" class="form-control" v-model="worker.name"> 
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4 col-form-label text-md-right" for="services">Skills</label>
            <div class="col-md-6">
                <select name="services" id="services" class="form-control" v-model="service_id">
                    <option v-for="service in this.services" :value="service.id">{{ service.name }}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6 offset-md-4">
                <button class="btn btn-primary" @click="CreateWorker()">
                    Submit
                </button>
            </div>
        </div>
    </div>
</template>
<script>
    import Axios from "axios";
    import config from './config.js';
    export default {
        data() {
            return {
                workers:null,
                worker: {
                    name:'',
                    skills:[],
                },
                token:"",
                workspace: null,
                services: [
                    {
                        id:1,
                        name:'AMS'
                    },
                    {
                        id:2,
                        name:'E-Trade'
                    },
                ],
                service_id:null
            }
        },
        mounted() {
            this.getToken();
        },
        methods: {
            getToken() {
                Axios.get("/workers")
                .then((response) => {
                    this.token = response.data;
                })
            },
            LoadWorkers(workspace, ref) {
                workspace.workers.fetch(function(error, workerList) {
                    if(error) {
                        console.log(error.code);
                        console.log(error.message);
                        return;
                    }
                    ref.workers = workerList.data;
                })
            },
            CreateWorker() {
                var params = {"FriendlyName":this.worker.name, "skills":this.worker.skills};
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
                    }
                );
            },
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
            }
        },
        watch: {
            token() {
                var ref = this;
                this.workspace = new Twilio.TaskRouter.Workspace(this.token);
                this.workspace.on("ready", function(workspace) {
                    ref.LoadWorkers(workspace, ref);
                });
            }
        }
    }
</script>
<style scoped>
</style>
