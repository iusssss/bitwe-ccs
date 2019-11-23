<template>
	<div class="container">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<h1 class="text-muted"><strong>Logs</strong></h1>
					<hr>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Time</th>
								<th>User</th>
								<th>Description</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="log in logs.data">
								<td>
									<timeago :datetime="log.created_at" :auto-update="60" />
								</td>
								<td>{{ log.user.name }}</td>
								<td>{{ log.description }}</td>
							</tr>
						</tbody>
					</table>
					<div class="d-flex justify-content-center mt-2">
            			<pagination :show-disabled="true" :data="logs" @pagination-change-page="retrieveLogs" :limit="10"></pagination>
            		</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				logs: {}
			}
		},
		mounted() {
			this.retrieveLogs();
		},
		methods: {
			retrieveLogs(page = 1) {
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.get(`/api/logs?page=${page}`)
				.then(response => {
					this.logs = response.data;
				})
			}
		}
	}
</script>