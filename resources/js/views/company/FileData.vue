<template>
	<div>
        <div class="modal-header">
            <h3 class="modal-title text-primary">File Content</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        	<div class="container">
	            <table class="table">
	            	<thead>
	            		<tr v-if="type == 0">
	            			<th>Name</th>
	            			<th>Address</th>
	            			<th>E-mail</th>
	            			<th>Contact Number</th>
	            		</tr>
	            		<tr v-else>
	            			<th>Name</th>
	            			<th>E-mail</th>
	            			<th>Contact Number</th>
	            		</tr>
	            	</thead>
	            	<tbody>
	            		<tr v-if="type == 0" v-for="data in fileData">
	            			<td>{{ data.name }}</td>
	            			<td>{{ data.address }}</td>
	            			<td>{{ data.email }}</td>
	            			<td>{{ data.contact_no }}</td>
	            		</tr>
	            		<tr v-else>
	            			<td>{{ data.fullname }}</td>
	            			<td>{{ data.email }}</td>
	            			<td>{{ data.contactNumber }}</td>
	            		</tr>
	            	</tbody>
	            </table>
	        </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="saveData">Save</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
			}
		},
		props: ['fileData', 'type'],
		methods: {
            saveData() {
            	if (this.type == 0) {
					axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
	            	axios.post('/api/company/fileSave', { companies: this.fileData })
	            	.then(response => {
	            		if (response.data == "success") {
	            			this.$emit("uploaded");
	            		}
	            	})
	            	.catch(error => {
	            	})
            	} else {
					axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
	            	axios.post('/api/client/fileSave', { clients: this.fileData })
	            	.then(response => {
	            		if (response.data == "success") {
	            			this.$emit("uploaded");
	            		}
	            	})
	            	.catch(error => {
	            	})
            	}
            }
		},
	}
</script>