<template>
    <div class="container-fluid">
    	<div class="row mb-3">
            <div class="col-md-5">
                <transactionSearch @showProfile="showClientProfile" @search="searchTransactions" :subjects="subjects" />
            </div>
            <div class="col-md-4">
                <clientInformation />
            </div>
            <div class="col-md-3">
<!--             	<touchpoint v-on:startTransaction="getTouchpoint" @resetWatch="touchpoint={}" :twilio="twilio" ref="touchpointComponent" @acceptReservation="acceptReservation" @goAvailable="goAvailable" /> -->
                    <touchpointNew />
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-7">
            	<transactionHistory :subjects="subjects" :services="services" :ticketStatuses="ticketStatuses" />
            </div>
            <div class="col-md-5">
                <transaction :subjects="subjects" :services="services" 
                :ticketStatuses="ticketStatuses" />
            </div>
        </div>
        <!-- <div class="row mb-3">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <span class="text-white">Call Simulator</span>
                    </div>
                    <div class="card-body">
                        <input v-model="skill" name="" placeholder="Digit">
                        <button class="btn btn-primary" @click="call">Call</button>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
	import config from '../../config.js';
    import ClientInformation from '../ClientInformation.vue';
	import Transaction from '../Transaction.vue';
    import TransactionTest from '../TransactionTest.vue';
	import TransactionHistory from '../TransactionHistory.vue';
    import TransactionSearch from '../TransactionSearch.vue';
	import Touchpoint from '../Touchpoint.vue';
    import TouchpointNew from '../TouchpointNew.vue';
	import Axios from "axios";

	export default {
		name: 'app',
		components: {
			Transaction,
            TransactionTest,
            TransactionSearch,
			TransactionHistory,
			Touchpoint,
            ClientInformation,
            TouchpointNew,
		},
		data() { 
			return {
                skill: 1,
				services: [],
				subjects: [],
                // client: {},
                // transactions: [],
                ticketStatuses: [],
                search: {
                    name: '',
                    company: '',
                    subject: ''
                },
                //TWILIO
                token: "",
                twilioClient: {},
                activityList: {},
                activitySids: {},
                twilio: {
                    worker: {},
                    reservation: {}
                },
                callFrom: "",
			}
		},
		created() {
			this.getServices();
			this.getSubjects();
            this.$store.dispatch("retrieveClients");
            this.$store.dispatch("retrieveTickets");
            this.getTicketStatuses();
		},
        mounted() {
            this.retrieveTwilioToken();
            // this.onTicketCreated();
        },
		methods: {
            call() {
                axios.post(`/api/call/createTask/${this.skill}`)
                .then(response => {
                    console.log('task created');
                })
            },
            onTicketCreated() {
            },
			getServices() {
                Axios.get(`api/services`)
                    .then(response => {
                        this.services = response.data.data;
                    })
            },
            getSubjects() {
                Axios.get('api/transactionSubjects')
                    .then(response => {
                        this.subjects = response.data.data;
                    })
            },
            getTicketStatuses() {
                Axios.get('api/ticketStatuses')
                .then(response => {
                    this.ticketStatuses = response.data.data;
                    this.loading = false;
                })
            },
            searchTransactions(search) {
                Axios.get(`api/search?client=${search.client}&company=${search.company}&subject=${search.subject}`)
                .then(response => {
                    this.transactions = response.data.data;
                    this.loading = false;
                })
            },
            showClientProfile(client) {
                this.$store.state.selected_client = this.$store.state.clients.find(c => c.fullname === client); 
            },
            getTouchpoint(touchpoint) {
                this.touchpoint = touchpoint;
            },
            //TWILIO
            retrieveTwilioToken() {
                this.$store.dispatch('retrieveTwilioWorkerToken')
                .then((response) => {
                    this.$store.dispatch('retrieveTwilioClient', this.$store.state.twilioWorkerToken)
                    .then(response => {
                        this.fetchReservations();
                        this.fetchActivities();
                        this.registerTaskRouterCallbacks();
                    })
                    .catch(error => {
                        this.retrieveTwilioToken();
                    })
                })
            },
            fetchReservations() {
                this.$store.dispatch('fetchReservation');
            },
            acceptReservation() {
                this.$store.dispatch('acceptReservation');
            },
            fetchActivities() {
                this.$store.dispatch('fetchActivities');
            },
            goAvailable() {
                this.$store.dispatch('goAvailable');
            },
            registerTaskRouterCallbacks() {
                this.$store.dispatch('registerTaskRouterCallbacks');
                if (this.$store.state.callFrom)
                    this.searchPhone(this.$store.state.callFrom);
            },
		},
        watch: {
        }
	}
</script>

<style>
.btn-primary {
    background-color: rgba(22,15,64,.9) !important;
    border-color: rgba(22,15,64,.9) !important;
}
.modal-header {
    background-color: rgba(22,15,64,.9) !important;
    background-image: url(/images/loginbg.jpg);
    background-position: center center;
}
.modal-header * {
    color: white !important;
}
.card {
    height: 100%;
}
.card-header {
    background-color: rgba(22,15,64,.9) !important;
    background-image: url(/images/loginbg.jpg);
    background-position: center center;
}
.card-header *:not(option) {
    color: white !important;
}
.card-header select {
    color: white !important;
}
.page-link {
    background-color: rgba(22,15,64,.9) !important;
    color: white !important;
}
.page-item.active .page-link {
}
option {
    color: black !important;
}
</style>

