<template>
    <div>
        <div class="modal-header">
            <h3 class="modal-title text-primary">Create Company Type</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="container">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" v-model="companytype.name">
                </div>
                <div class="form-group">
                    <textarea class="form-control" placeholder="Description (optional)" v-model="companytype.description"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="CreateCompanytype">Create</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</template>

<script>
    import Axios from 'Axios';
    export default {
        data() {
            return {
                companytype: {
                    name:'',
                    description:'',
                },
            }
        },
        methods: {
            CreateCompanytype() {
                Axios.post('/api/companytype', {
                    "name": this.companytype.name,
                    "description": this.companytype.description,
                }).then((response) => {
                    this.$emit('CompanytypeCreated', response.data.data);
                    this.$noty.success("Company Type Created!");

                    this.companytype.name = "";
                    this.companytype.description = "";
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