<template>
	<div class="card">
		<div class="card-header">
            <div class="float-left"><h3 class="text-primary">Call Recordings</h3></div>
            <div class="text-right">
                <!-- <button class="btn btn-primary" @click="evaluateRecording" data-toggle="modal" data-target="#scorecard">
                    <i class="fa fa-plus add"></i> |
                    Evaluate
                </button> -->
            </div>
        </div>
		<div class="card-body">
            <serverErrors :serverErrors="serverErrors" />
			<div v-if="loading" class="fa-3x mt-3 d-flex justify-content-center">
				<i class="fas fa-spinner fa-pulse"></i>
			</div>
			<p v-if="loading" class="d-flex justify-content-center text-muted mt-2">retrieving call recordings</p>
			<div v-else class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>Agent</th>
							<th>Date</th>
							<th>Duration</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="record in recordings">
							<td><div>{{ record.user.name }}</div></td>
							<td>
								<timeago :datetime="record.dateCreated.date" :auto-update="60" />
							</td>
							<td>{{ record.duration }}</td>
							<td class="d-flex justify-content-end">
								<button class="btn btn-primary mr-1" @click="open(record)"><i class="fas fa-marker"></i></button>
								<button class="btn btn-primary" @click="deleteRecord(record.id)"><i class="fa fa-trash"></i></button>
							</td>
						</tr>
					</tbody>	
				</table>
			</div>
			<div >
			</div>
		</div>
		<div class="modal fade" id="scorecard" tabindex="-1" role="dialog" aria-labelledby="scorecard" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
			        <evaluationForm :criterias="criterias" :user="user" :recordSid="recordSid" />
                </div>
            </div>
        </div>
	</div>
</template>
<script>
	import EvaluationForm from './EvaluationForm';
	export default {
		components: {EvaluationForm},
		data() {
			return {
				serverErrors: [],
				criterias: null,
				recordings: [],
				url: 'https://api.twilio.com/2010-04-01/Accounts/AC37f84432f9b1d446e2b066a42d3e8a12/Recordings/',
				loading: true,
				recordWindow: null,
				user: null,
				recordSid: null,
			}
		},
		mounted() {
			this.getRecordings();
			this.$store.dispatch('retrieveUsers');
		},
		methods: {
			deleteRecord(id) {
				if (confirm("Are you sure you want to delete this record?")) {
					axios.delete(`/api/callRecord/${id}`)
					.then(response => {
						this.loading = false;
						console.log(response);
						this.$noty.success("Call recording deleted");
					})
					.catch(error => {
						this.loading = false;
						this.$noty.error("An error has occured while deleting the recording")
					})
				}
			},
			open(record) {
				this.recordWindow = window.open(this.url + record.id, "", `width=500,height=100,top=200,left=50`);
            	this.retrieveCriteria();
            	this.user = record.user;
            	this.recordSid = record.id;
				$('#scorecard').modal('show');
			},
			getRecordings() {
				axios.get('/api/callRecords')
				.then(response => {
					this.recordings = response.data;
					for (let i = 0; i < this.recordings.length; i++) {
						this.$store.dispatch('getUserByPhone', this.recordings[i].callTo)
						.then(response => {
							this.recordings[i].user = response;
							this.loading = false;
						});
					}
				})
				.catch(error => {
					this.loading = false;
					this.serverErrors.push(['Error when retrieving Call Recordings. Refresh the page'])
				})
			},
			retrieveCriteria() {
				axios.get('/api/criterias')
				.then(response => {
					this.criterias = response.data;
				})
				.catch(error => {
				})
			},
			evaluateRecording() {
            	this.retrieveCriteria();
			},
		},
		computed: {
		}
	}
</script>