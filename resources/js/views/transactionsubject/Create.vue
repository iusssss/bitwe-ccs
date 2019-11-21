<template>
	<div>
        <div class="modal-header">
            <h3 class="modal-title text-primary">Add FAQ</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        	<div class="container">
	            <div class="form-group">
                    <select name="services" id="services" class="form-control" v-model="subject.service_id">
                        <option value="">Select a Service...</option>
                        <option v-for="service in services" :value="service.id">{{ service.name }}</option>
                    </select>
                </div>
	            <div class="form-group">
	            	<input type="text" class="form-control" placeholder="Issue" v-model="subject.issue">
	            </div>
	            <div class="form-group">
	            	<textarea class="form-control" placeholder="Resolution" v-model="subject.resolution"></textarea>
	            </div>
	        </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="CreateSubject">Create</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
	</div>
</template>

<script>
	import Axios from 'Axios';
	export default {
		data() {
			return {
				subject: {
					issue:'',
					resolution:'',
					service_id:'',
				},
			}
		},
		props: ['services'],
		methods: {
            CreateSubject() {
                Axios.post('/api/transactionSubject', {
            		"issue": this.subject.issue,
					"resolution": this.subject.resolution,
					"service_id": this.subject.service_id
            	}).then((response) => {
           			this.$emit('SubjectAdded', response.data.data);
               		this.$noty.success(`FAQ Added`);

               		this.subject.issue = '';
					this.subject.resolution = '';
					this.subject.service_id = '';
            	})
            	.catch((err) => {
               		this.$noty.error(err.message);
            	})
            },
		},
	}
</script>