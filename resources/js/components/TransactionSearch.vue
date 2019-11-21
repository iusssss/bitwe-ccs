<template>
	<div class="card">
		<div class="card-header">
			<span class="text-secondary h4">Search</span>
		</div>
		<div class="card-body">
			<div class="form-group row">
                <div class="col-md-8">
                    <autocomplete @input="getClientText" :items="clientsFullname" :placeholder="'Client'" />
                </div>
                <div class="col-md-4">
                    <button @click="showProfile" class="btn btn-primary showprofile">Show Profile</button>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-8">
                    <autocomplete @input="getCompanyText" :items="companyName" :placeholder="'Company'" />
                    <!-- <autocomplete v-on:input="getSearchText" :items="clientsFullname" :placeholder="'Company'" /> -->
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-8">
                    <!-- <autocomplete @input="getTicket" :placeholder="'Ticket #'" /> -->
                    <input class="form-control" type="" name="" placeholder="Ticket #" v-model="filter.ticket">
                </div>
            </div>
            <div class="form-group row">
                <button @click="search" class="btn btn-primary col-md-2 ml-3">Search</button>
            </div>
		</div>
	</div>
</template>

<script>
    import Autocomplete from './Autocomplete.vue';
	export default {
		data() {
			return {
                filter: {
    				name: '',
    				company: '',
                    ticket: null
                },
                ids: {
                    client: '',
                    company: '',
                    subject: '',
                }
			}
		},
        props: [
            'clients',
            'subjects',
            'companies'
        ],
        methods: {
            search() {
                if (this.filter.ticket) {
                    this.$store.dispatch('searchTicket', this.filter.ticket)
                    .then(response => {

                    })
                    .catch(error => {
                        this.$noty.error("Ticket doesn't exist");
                    });
                    return;
                }
                if (this.filter.company && this.filter.client == '')
                    this.ids.client = this.clients.find(x => x.fullname == this.filter.name).id;
                // else
                //     this.ids.client = "";
                // if (this.filter.company)
                //     this.ids.company = this.companies.find(x => x.name == this.filter.company).id;
                // else
                //     this.ids.company = "";
                // if (this.filter.subject)
                //     this.ids.subject = this.subjects.find(x => x.issue == this.filter.subject).id;
                // else
                //     this.ids.subject = "";
                // this.$emit('search', this.ids);
            },
            getClientText(search) {
                this.filter.name = search;
            },
            getCompanyText(search) {
                this.filter.company = search;
            },
            showProfile() {
                this.$emit('showProfile', this.filter.name)
            }
        },
        components: {
            Autocomplete
        },
        computed: {
            clientsFullname() {
                if (this.$store.state.clients)
                    return this.$store.state.clients.map(x => x.fullname);
            },  
            companyName() {
                if (this.$store.state.companies)
                    return this.$store.state.companies.map(x => x.name);
            },
            subjectsIssue() {
                if (this.subjects)
                    return this.subjects.map(x => x.issue);
            }
        }
	}
</script>

<style scoped>
    .showprofile {
        width: 100%;
    }
    
</style>