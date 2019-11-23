<template>
	<div class="container d-flex justify-content-center">
		<div class="card w-75">
			<div class="card-body d-flex justify-content-center">
				<div class="w-50">		
					<div class="mt-3">
						<h1 class="font-weight-bolder text-center">System Settings</h1>
					</div>
					<hr>
					<div class="text-center mb-3">
						<div class="mb-3">
							<div class="custom-control custom-switch d-inline mx-5">
								<input type="checkbox" class="custom-control-input" id="voip" v-model="voip.state" style="font-size: 10em;">
								<label class="custom-control-label" for="voip">
									<h5 class="font-weight-bolder text-muted">VoIP</h5>
								</label>
							</div>
							<div class="custom-control custom-switch d-inline">
								<input type="checkbox" class="custom-control-input" id="callrecord" v-model="callrecord.state" style="font-size: 10em;">
								<label class="custom-control-label" for="callrecord">
									<h5 class="font-weight-bolder text-muted">Call Recording</h5>
								</label>
							</div>
						</div>
						<button type="submit" class="custom-btn text-white h6 mt-2" @click="saveSetting">Save</button>
					</div>
					<div class="mt-3">
						<h1 class="font-weight-bolder text-center">Database</h1>
					</div>
					<hr>
					<div class="text-center mb-3">
						<div class="row d-flex justify-content-center">
							<table class="table table-hover text-left">
  								<thead>
  									<tr>
  										<th>Paths</th>
  									</tr>
  								</thead>
  								<tbody>
  									<tr v-for="_path in paths" :class="{'table-active': path == _path}">
  										<td @click="getPath(_path)">{{ _path }}</td>
  									</tr>
  								</tbody>
  							</table>
							<button :disabled="loading" type="submit" class="mx-1 btn btn-primary" @click="backup">
								<buttonLoading :loading="loading" :loadingText="'Backing up...'" :defaultText="'Backup'" />
							</button>
							<button :disabled="restoring" type="submit" class="mx-1 btn btn-secondary" @click="restore">
								<buttonLoading :loading="restoring" :loadingText="'Restoring...'" :defaultText="'Restore'" />
							</button>
						</div>
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
				voip: {},
				callrecord: {},
				loading: false,
				restoring: false,
				paths: [],
				path: null,
			}
		},
		mounted() {
			this.getRestorePaths();
			this.$store.dispatch('retrieveSettings')
			.then(() => {
				this.voip = this.$store.state.settings[0];
				this.callrecord = this.$store.state.settings[1];
			})
		},
		methods: {
			getPath(path) {
				this.path = path;
			},
			getRestorePaths() {
				axios.get('/api/restorePaths')
				.then(response => {
					this.paths = response.data;
					this.path = this.paths[0];
				})
			},
			backup() {
				this.loading = true;
				axios.post('/api/backup')
				.then(response => {
					this.loading = false;
					this.$noty.success('Database backup successful')
					this.getRestorePaths();
				})
			},
			restore() {
				if (confirm("Are you sure you want to restore your database from this backup?")) {
					this.restoring = true;
					axios.post('/api/restore', { path: this.path })
					.then(response => {
						this.restoring = false;
						console.log(response);
						this.$noty.success('Database restored')
					})
					.catch(error => {
						this.restoring = false;
						this.$noty.error(Object.values(error.response.data.errors));
					})
				}
			},
			saveSetting() {
				this.$store.dispatch('updateSettings', this.voip)
				.then(response => {
					this.$store.dispatch('updateSettings', this.callrecord)
					.then(response => {
						this.$noty.success("Settings Updated");
						this.$store.dispatch('retrieveSettings')
					})
					.catch(error => {
						this.$noty.error("A problem has occured")
					})
				})
				.catch(error => {
					this.$noty.error("A problem has occured")
				})
			}
		}
	}
</script>
<style scoped>
	h1 {
		color: rgb(22,15,64);
	}
	input {
		background-color: rgb(244,248,247);
		border: none;
		padding: 30px;
	}
	::placeholder {
		color: rgb(201,203,202);
	}
	.custom-btn {
		padding: 15px 100px;
		background-color: rgb(22,15,64);
		border: none;
		border-radius: 50px;
	}
	td:hover {
		cursor: pointer;
	}
</style>