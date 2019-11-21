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
				callrecord: {}
			}
		},
		mounted() {
			this.$store.dispatch('retrieveSettings')
			.then(() => {
				this.voip = this.$store.state.settings[0];
				this.callrecord = this.$store.state.settings[1];
			})
		},
		methods: {
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
</style>