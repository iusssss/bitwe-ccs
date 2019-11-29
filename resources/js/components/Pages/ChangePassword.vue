<template>
	<div class="container d-flex justify-content-center">
		<div class="card w-75">
			<div class="card-body d-flex justify-content-center">
				<div class="w-50">		
					<div class="mt-3">
						<h1 class="font-weight-bolder text-center">Change Password</h1>
					</div>
					<hr>
                	<serverErrors :serverErrors="serverErrors" />
					<form @submit.prevent>
						<div class="form-group">
							<input class="form-control" type="password" placeholder="Current Password" v-model="current_password" required />
						</div>
						<div class="form-group">
							<input class="form-control" type="password" placeholder="New Password" v-model="new_password" required />
						</div>
						<div class="form-group">
							<input class="form-control" type="password" placeholder="Confirm New Password" v-model="confirm_password" required />
						</div>
						<div class="text-center mb-3">
							<button :disabled="loading" type="submit" class="custom-btn text-white h6 mt-2" @click="checkCurrentPassword">
								<buttonLoading :loading="loading" :loadingText="'Changing Password...'" :defaultText="'Submit'" />
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				current_password: null,
				new_password: null,
				confirm_password: null,
				loading: false,
				serverErrors: [],
			}
		},
		methods: {
			checkCurrentPassword() {
				if (!this.new_password) {
					this.$noty.error("All field is required");
					return;
				}
				if (this.new_password != this.confirm_password) {
					this.$noty.error("Password doesn't match");
					return;
				}
				this.loading = true;
				axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$store.state.token;
				axios.post('/api/checkCurrentPassword', { current_password: this.current_password })
				.then(response => {
					if (response.data) {
						axios.put(`/api/user/changepass/${this.$store.state.user.id}`, { password: this.new_password })
						.then(response => {
							this.loading = false;
							this.serverErrors = [];
							this.$noty.success("Password successfully changed");
							this.$store.dispatch('destroyToken')
							.then(response => {
								this.$router.push('/login');
							});
						})
						.catch(error => {
							this.serverErrors = Object.values(error.response.data.errors);
							this.loading = false;
						})
					} else {
						this.loading = false;
						this.serverErrors = [];
						this.$noty.error("Current password is incorrect");
					}
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
	.custom-btn:disabled {
		background: #dddddd;
	}
</style>