<template>
	<div class="row mt-3">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="card">
				<div class="card-header"><h5 class="m-0">Reset Password</h5></div>
				<div class="card-body">
					<div v-if="error">
						<div>
							<h5 class="text-center display-2">This page is not available</h5>
						</div>
					</div>
					<div v-else class="d-flex justify-content-center">
						<div class="form">
							<form @submit.prevent="resetPass">
								<div class="form-group">
									<input class="form-control" type="password" placeholder="New Password" v-model="new_password" required />
								</div>
								<div class="form-group">
									<input class="form-control" type="password" placeholder="Confirm New Password" v-model="confirm_password" required />
								</div>
								<div class="text-center mb-3">
									<button type="submit" class="custom-btn text-white h6 mt-2">Submit</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-2"></div>
	</div>
</template>

<script>
	import starRating from 'vue-star-rating';
	export default {
		data() {
			return {
				email: null,
				error: false,
				new_password: '',
				confirm_password: '',
			}
		},
		mounted() {
			this.validateToken();
		},
		methods: {
			resetPass() {
				if (this.new_password != this.confirm_password) {
					this.$noty.error("Password doesn't match");
					return;
				}
				const token = this.$route.params.token;
				axios.post('/api/password/reset', { password: this.new_password, token: token })
				.then(response => {
					if (response.data == 'success') {
						this.$noty.success('Password reset');
						this.$router.push('/')
					}
				})
			},
			validateToken() {
				const token = this.$route.params.token;
				axios.get(`/api/password/token/${token}`)
				.then(response => {
					const result = response.data;
					if (result) {
						this.error = false;
					} else {
						this.error = true;
					}
				})
			},
		},
		components: {
		},
		computed: {
		}
	}
</script>

<style scoped>
	.form {
		width: 60%;
	}
	body {
  		font-family: 'Raleway', sans-serif;
	}
	h5 {
		font-weight: bold;
  		font-size: 1.5em;
 		color: #999;
	}
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