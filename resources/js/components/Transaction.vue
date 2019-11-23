<template>
    <div class="card">
        <div class="card-header"><span class="text-secondary h4">{{ ticketExist ? 'Update' : 'Create' }} Ticket</span></div>
        <div class="card-body">
        <!-- <div class="card-body" :class="{'disabledDiv' : $store.getters.workerAvailable}"> -->
            <!-- <div v-if="loading" class="d-flex justify-content-center"><i class="fa fa-4x fa-spin fa-spinner text-muted"></i></div>
            <div v-else> -->
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" v-model="ticketExist" @change="removeFromTicket">
                        </div>
                    </div>
                    <input :disabled="!ticketExist" type="text" class="form-control" placeholder="Ticket #" v-model="ticket.id">
                    <div class="input-group-append">
                        <div class="input-group-text p-0">
                            <button :disabled="!ticketExist" class="searchBtn" @click="searchTicket"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <router-link class="btn btn-muted ml-2" to="/home/tempClient" data-toggle="modal" data-target="#clientPopup">
                        <i class="fas fa-user"></i>
                    </router-link>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" v-model="allowEmail">
                        </div>
                    </div>
                    <input :disabled="!allowEmail" type="text" class="form-control" placeholder="E-mail" v-model="email">
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="services">Services</label>
                    <div class="col-md-6">
                        <select name="service" id="service" class="form-control" v-model="ticket.service_id">
                            <option v-for="service in services" :value="service.id">{{ service.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="status">Status</label>
                    <div class="col-md-6">
                        <select name="status" id="status" class="form-control" v-model="ticket.status">
                            <option v-for="status in ticketStatuses" v-if="status.id != 4" :value="status.id">{{ status.name }}</option>
                        </select>
                        <!-- <select name="status" id="status" class="form-control" v-model="transaction.status">
                            <option v-for="status in statuses" :value="status">{{ status }}</option>
                        </select> -->
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="status">Type</label>
                    <div class="col-md-6">
                        <select name="status" id="status" class="form-control" v-model="ticket.type">
                            <option v-for="type in types">{{ type }}</option>
                        </select>
                        <!-- <select name="status" id="status" class="form-control" v-model="transaction.status">
                            <option v-for="status in statuses" :value="status">{{ status }}</option>
                        </select> -->
                    </div>
                </div>
                <!-- <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="subject">Subject</label>
                    <div class="col-md-6">
                        <select name="subject" id="subject" class="form-control" v-model="transaction.subject_id">
                            <option v-for="subject in filterSubjects" :value="subject.id">{{ subject.issue }}</option>
                        </select>
                    </div>
                </div> -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="issue">{{ ticket.type != 'Request' ? 'Issue' : 'Request' }}</label>
                    <div class="col-md-6">
                        <textarea name="issue" class="form-control" v-model="ticket.issue"></textarea> 
                    </div>
                </div>
                <!-- <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="resolution">Resolution</label>
                    <div class="col-md-6">
                        <textarea name="resolution" class="form-control" disabled>{{ getResolution }}</textarea>
                    </div>
                </div> -->
                <div class="input-group row mb-3">
                    <label class="col-md-3 col-form-label text-md-right" for="remark">Action Taken</label>
                    <textarea name="remark" class="ml-2 form-control" v-model="ticket.action_taken"></textarea>
                    <div class="input-group-append">
                        <div class="input-group-text p-0">
                            <button :disabled="ticket.status != 3" class="searchBtn" @click="copyResolution"><i class="text-muted fa fa-copy"></i></button>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right" for="resolution">Resolution</label>
                    <div class="col-md-6">
                        <textarea v-model="ticket.resolution" name="resolution" class="form-control" :disabled="(ticket.status != 3)"></textarea>
                    </div>
                </div>
                <div v-if="sendingEmail" class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <i class="fas fa-spinner fa-pulse"></i>
                        <span class="text-muted">sending email</span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button :disabled="creatingTicket || currentStatus == 4" class="btn btn-primary" @click="createTicket()">
                            <span v-if="creatingTicket">
                                <i class="fas fa-spinner fa-pulse"></i>
                                submitting...
                            </span>
                            <span v-else>{{ (!ticketExist) ? 'Create' : 'Save' }}</span>
                        </button>
                        <!-- <button class="btn btn-primary" @click="test">
                            test
                        </button> -->
                    </div>
                </div>
            <!-- </div> -->
        </div>
        <div class="modal fade" id="clientPopup" tabindex="-1" role="dialog" aria-labelledby="clientPopup" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <router-view>
                    </router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Axios from 'axios';
    import moment from 'moment';
    import router from '../router';

    export default {
        data() {
            return {
                email: null,
                ticket: {
                    id: null,
                    issue: '',
                    action_taken: '',
                    resolution: '',
                    status: 2,
                    service_id: null,
                    type: 'Request'
                },
                currentStatus: 2,
                types: ['Request', 'Incident'],
                lastTicketNo: null,
                ticketExist: false,
                allowEmail: false,
                service: {},
                subject: {},
                creatingTicket: false,
                sendingEmail: false,
            }
        },
        props: [
            'services',
            'subjects',
            'client',
            'ticketStatuses'
        ],
        methods: {
            removeFromTicket() {
                if (!this.ticketExist) {
                    delete this.ticket.agent;
                }
            },
            copyResolution() {
                this.ticket.resolution = this.ticket.action_taken;
            },
            searchTicket() {
                this.$store.dispatch('searchTicket', this.ticket.id)
                .then(ticket => {
                    this.ticket.agent = ticket.agent,
                    this.ticket.service_id = ticket.service.id;
                    this.ticket.issue = ticket.issue;
                    this.ticket.action_taken = ticket.updates[ticket.updates.length - 1].action_taken;
                    this.ticket.resolution = ticket.updates[ticket.updates.length - 1].resolution;
                    this.currentStatus = ticket.updates[ticket.updates.length - 1].status.id;
                    this.ticket.status = this.currentStatus;
                    this.ticket.type = ticket.type;
                    if (ticket.client)
                        this.$store.commit('searchPhone', ticket.client);
                    else 
                        this.$store.dispatch('retrieveTempClient', this.ticket.id);
                })
                .catch(error => {
                    this.$noty.error("Ticket doesn't exist");
                })
            },
            createTicket() {
                if (this.currentStatus == 4) {
                    this.$noty.error("This ticket is already closed");
                    return;
                }
                if (!this.$store.getters.clientSelected && !this.$store.getters.tempClientFilled) {
                    this.$noty.error("No selected client. If client is not registered, please set a temporary client");
                    return;
                }
                this.creatingTicket = true;
                this.$store.dispatch('getLastTicket')
                .then(lastTicket => {
                    if (!this.ticketExist) {
                        this.ticket.id = this.generateNewTicket(lastTicket);
                        this.createNewTicket()
                        .then(response => {
                            if (response.data == 'duplicate') {
                                this.createTicket();
                                return;
                                //  this.$noty.error('Network problem, please try again.');
                                //  this.creatingTicket = false;
                                // return;
                            }
                            this.createTicketUpdate();
                        })
                        .catch(error => {
                            // this.$noty.error('Network problem, please try again.');
                            this.creatingTicket = false;
                            return;
                        });
                        if (!this.$store.getters.clientSelected)
                            this.$store.dispatch('createTempClient', this.ticket.id);
                    } else {
                        if (this.ticket.agent) {
                            if (this.ticket.agent.id != this.$store.state.user.id && this.currentStatus != 1) {
                                this.$noty.error("This ticket is assigned to another agent");
                                this.creatingTicket = false;
                                return;
                            }
                        }
                        this.$store.dispatch('searchTicket', this.ticket.id)
                        .then(() => {
                            this.createTicketUpdate();
                        })
                        .catch(error => {
                            this.$noty.error("Ticket doesn't exist");
                            this.creatingTicket = false;
                        })
                    }
                });
            },
            createNewTicket() {
                return new Promise((resolve, reject) => {
                    this.$store.dispatch('createTicket', {
                        ticketId: this.ticket.id,
                        type: this.ticket.type,
                        startTime: this.$store.state.ticketStartTime,
                        touchPoint: 'Phone',
                        issue: this.ticket.issue,
                        client_id: (this.$store.getters.clientSelected) ? this.$store.state.selected_client.id : null,
                        service_id: this.ticket.service_id,
                    })
                    .then(response => {
                        resolve(response);
                    })
                    .catch(error => {
                        reject(error);
                    })
                })
            },
            createTicketUpdate() {
                this.creatingTicket = true;
                this.$store.dispatch('createTicketUpdate', {
                    ticket_id: this.ticket.id,
                    status: this.ticket.status,
                    action_taken: this.ticket.action_taken,
                    resolution: this.ticket.resolution,
                    resolvedBy: (this.ticket.status == 3) ? this.$store.state.user.id : null,
                    timeElapsed: this.$store.getters.timeElapsed
                }).then((response) => {
                    const notif = this.ticketExist ? 'Ticket Updated' : 'Ticket Created';
                    this.$noty.success(notif);
                    this.creatingTicket = false;

                    if (this.allowEmail)
                        this.sendEmail();
                    else
                        this.clear();
                    if (this.$store.getters.tempClientFilled)
                        this.$store.commit('setTempClient', null);
                    if (this.$store.getters.voipAllowed) {
                        this.$store.dispatch('stopTime');
                        this.$store.dispatch('goAvailable');
                    } else
                        this.$store.commit('goAvailable', 'Reserved');
                }).catch(error => {
                    this.creatingTicket = false;
                });
            },
            generateNewTicket(lastTicket) {
                let now = this.formatDate(new Date);
                let lastId = '1';
                if (!lastTicket)
                    return now + lastId.padStart(4,'0');
                else 
                    lastTicket = lastTicket.toString();
                let lastDate = lastTicket.substring(0, 6);
                if (lastDate == now) {
                    lastId = (parseInt(lastTicket.substring(7)) + 1).toString();
                } 
               return now + lastId.padStart(4,'0');
            },
            sendEmail() {
                console.log("emailed");
                if (!this.email || this.email == '') {
                    this.$noty.error('No email address specified')
                    this.clear();
                    return;
                }
                this.sendingEmail = true;
                this.$store.dispatch("sendMail", {
                    status_id: this.ticket.status,
                    issue: this.ticket.issue,
                    type: this.ticket.type,
                    ticketNo: this.ticket.id,
                    fullname: (this.$store.getters.clientSelected) ? this.$store.state.selected_client.fullname : this.$store.state.tempClient.fullname,
                    email: this.email,
                })
                .then((response) => {
                    this.sendingEmail = false;
                    // this.ticket.id = null;
                    this.clear();
                    this.$noty.success(`Email was sent to ${this.email}.`);
                });
            },
            formatDate(date) {
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();

                return year.toString().substring(2) + month.toString().padStart(2,'0') + day.toString().padStart(2,'0');
            },
            //End Ticket
            clear() {
                console.log("cleared");
                // this.$store.commit('clearSelectedClient');
                this.ticket.action_taken = "";
                this.ticket.id = null;
                this.ticket.issue = "";
                this.ticket.resolution = "";
                this.ticket.service_id = 1;
                this.ticket.status = 2;
                this.ticket.type = "Request";
                this.ticketExist = false;
                delete this.ticket.agent;
            },
        },
        created() {
        },
        mounted() {
            $("#clientPopup").on("hidden.bs.modal", function () {
                router.go(-1);
            });
            this.filterSubjects;
            this.getResolution;
        },
        computed: {
            emailC() {
                if (this.$store.getters.clientSelected)
                    return this.$store.state.selected_client.email;
                else if (this.$store.getters.tempClientFilled)
                    return this.$store.state.tempClient.email;
                return '';
            },
            filterSubjects() {
                let subj = this.subjects.filter(s => s.service_id === this.ticket.service_id);
                if (subj.length > 0)
                    this.ticket.subject_id = subj[0].id;
                if (this.ticket.service_id) 
                    return this.subjects.filter(s => s.service_id === this.ticket.service_id);
            },
            getResolution() {
                if (this.ticket.subject_id)
                    return this.subjects.find(s => s.id === this.ticket.subject_id).resolution;
                else
                    return "";
            },
            // timeElapsed() {
            //     return moment().hour(this.touchpoint.hour).minute(this.touchpoint.minute).second(this.touchpoint.second).format("HH:mm:ss");
            // },
            // getTouchpoint() {
            //     return false;
            //     return Object.keys(this.touchpoint).length === 0;
            // },
            // clientExist() {
            //     return Object.keys(this.client).length > 0;
            // }
        },
        watch: {
            emailC() {
                this.email = this.emailC;
            },
            services() {
                if (this.services.length > 0)
                    this.ticket.service_id = this.services[0].id;
            }
        }
    }
</script>

<style scoped>
    option {
        direction: ltr !important;
    }
    .input-group {
        width: 65%;
        margin: auto;
    }
    .disabledDiv {
        pointer-events: none;
        opacity: 0.3;
    }
    .searchBtn {
        border: none;
        background-color: transparent;
        padding: 5px 10px;
    }
    .fa-search {
        color: gray;
    }
</style>
