<template>
    <div class="card">
        <div class="card-header">
            <span class="text-secondary h4">Client Profile</span>
        </div>
        <div class="card-body">
            <!-- <div v-if="loading" class="d-flex justify-content-center"><i class="fa fa-4x fa-spin fa-spinner text-muted"></i></div> -->
            <div v-if="!$store.getters.clientSelected">
                <div>
                    <h2 class="text-info text-center m-1">No Client Found</h2>
                    <hr>
                </div>
            </div>
            <div v-else class="row">
                <div class="col-md-12">
                    <h2 class="text-info text-center m-1">{{ $store.state.selected_client.fullname }}</h2>
                    <hr>
                </div>
                <div class="col-md-6">
                    <h5 class="text-info text-center m-1">Contact</h5>
                    <hr>
                    <div class="m-1">
                        <p class="text-muted">Contact #:<span class="text-info"> {{ $store.state.selected_client.contactNumber }}</span></p>
                        <p class="text-muted">Email:<span class="text-info"> {{ $store.state.selected_client.email }}</span></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="text-info text-center m-1">Company</h5>
                    <hr>
                    <div v-if="loading" class="d-flex justify-content-center"><i class="fa fa-4x fa-spin fa-spinner text-muted"></i></div>
                    <div v-else class="m-1">
                        <p class="text-muted">Name:<span class="text-info"> {{ $store.state.selected_client.company.name }}</span></p>
                        <p class="text-muted">E-mail:<span class="text-info"> {{ $store.state.selected_client.company.email }}</span></p>
                        <p class="text-muted">Address<span class="text-info"> {{ $store.state.selected_client.company.address }}</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-top-0">
            <div class="d-flex justify-content-end">
                <button class="btn btn-light">
                    <span class="text-black-50" @click="$store.commit('clearSelectedClient')">clear</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                company: {},
                loading: false
            }
        },
        props: [
        ],
        methods: {
        },
        computed: {
        }, 
    }
</script>

<style scoped>
    hr {
        margin: 0;
    }
    p {
        margin: 0;
    }
</style>
