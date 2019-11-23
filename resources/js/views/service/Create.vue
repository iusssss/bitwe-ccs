<template>
    <div>
        <div class="modal-header">
            <h3 class="modal-title text-primary">Add Service</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="container">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" v-model="service.name">
                </div>
                <div class="form-group">
                    <textarea class="form-control" placeholder="Description (optional)" v-model="service.description"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="CreateService">Create</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                service: {
                    name:'',
                    description:'',
                },
            }
        },
        methods: {
            CreateService() {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
                axios.post('/api/service', {
                    "name": this.service.name,
                    "description": this.service.description,
                }).then((response) => {
                    this.$emit('ServiceAdded', response.data.data);
                    this.$noty.success("Service Added!");

                    this.service.name = "";
                    this.service.description = "";
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