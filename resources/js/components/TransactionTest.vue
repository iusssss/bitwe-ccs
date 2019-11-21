<template>
    <div class="card">
        <div class="card-header"><span class="text-secondary h4">Create Transaction</span></div>
            <div class="card-body"> <!-- v-bind:class="{'disabledDiv' : getTouchpoint}" -->
                <!-- <div v-if="loading" class="d-flex justify-content-center"><i class="fa fa-4x fa-spin fa-spinner text-muted"></i></div>
                <div v-else> -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="checkbox" v-model="ticketExist">
                            </div>
                        </div>
                        <input :disabled="!ticketExist" type="text" class="form-control" placeholder="Ticket #" v-model="ticketNo">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <button :disabled="!ticketExist" class="searchBtn"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right" for="services">Services</label>
                        <div class="col-md-6">
                            <select name="service" id="service" class="form-control" v-model="transaction.service_id">
                                <option v-for="service in services" :value="service.id">{{ service.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right" for="subject">Subject</label>
                        <div class="col-md-6">
                            <select name="subject" id="subject" class="form-control" v-model="transaction.subject_id">
                                <option v-for="subject in filteredSubjects" :value="subject.id">{{ subject.issue }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right" for="issue">Issue <span class="text-danger">(Optional)</span></label>
                        <div class="col-md-6">
                            <textarea name="issue" class="form-control" v-model="transaction.issue"></textarea> 
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right" for="resolution">Resolution</label>
                        <div class="col-md-6">
                            <textarea name="resolution" class="form-control" disabled>{{ resolution }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right" for="remark">Remark <span class="text-danger">(Optional)</span></label>
                        <div class="col-md-6">
                            <textarea name="remark" class="form-control" v-model="transaction.resolution"></textarea> 
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right" for="status">Status</label>
                        <div class="col-md-6">
                            <select name="status" id="status" class="form-control" v-model="transaction.status">
                                <option v-for="status in statuses" :value="status">{{ status }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <button :disabled="loading" class="btn btn-primary" @click="createTransaction()">
                                {{ (loading) ? 'Submitting ...' : 'Submit' }}
                            </button>
                        </div>
                    </div>
                <!-- </div> -->
            </div>
        </div>
</template>
<script>
    import Axios from 'axios';
    import moment from 'moment'

    export default {
        data() {
            return {
                transaction: {
                    issue: '',
                    resolution: '',
                    status: 'Pending',
                    service_id: null,
                    subject_id: null,
                },
                statuses: [
                    'Open',
                    'Pending',
                    'Resolved',
                ],
                ticketExist: false,
                ticketNo: null,
                loading: false,
                status: ""
            }
        },
        props: [
            'services',
            'subjects',
        ],
        watch: {
            services() {
                this.transaction.service_id = this.services[0].id;
            },
            subjects() {
                this.transaction.subject_id = this.subjects[0].id
            },
            transaction() {
                this.subject_id = this.filteredSubjects[0];
            }
        },
        computed: {
            filteredSubjects() {
                let subj = this.subjects.filter(s => s.service_id === this.service_id);
                if (subj.length > 0)
                    this.transaction.subject_id = subj[0].id;
                if (this.transaction.service_id) 
                    return this.subjects.filter(s => s.service_id === this.transaction.service_id);
            },
            resolution() {
                return this.subjects.find(s => s.id == this.transaction.subject_id).resolution;
            }
        }
    }
</script>

<style scoped>
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
        padding: 0;
    }
    .fa-search {
        color: gray;
    }
</style>