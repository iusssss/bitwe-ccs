<template>
	<div class="container mt-5">
		<div class="card">
			<div class="card-body row">
				<div class="col-md-4 left-col bg-primary d-flex align-items-center
				justify-content-center">
					<div class="text-white text-center w-100">
						<h1 class="font-weight-bolder cdec">
							CDEC
						</h1>
						<!-- <h1 class="font-weight-bolder crm">CRM</h1> -->
						<h3>Contact Center Solution<br>& HelpDesk</h3>
					</div>
				</div>
				<div class="col-md-8 right-col">
					<div v-if="forgotPass" class="text-center">
						<h1 class="font-weight-bold title">Forgot Password</h1>
						<hr>
						<div v-if="success" class="alert alert-success text-left">{{ success }}</div>
						<serverErrors :serverErrors="serverErrors" />
						<form action="#" @submit.prevent="forgotPassword">
							<div class="form-group">
								<input type="email" class="form-control" name="email" placeholder="E-mail" v-model="email">
							</div>
							<button type="submit" class="custom-btn text-white h6 mt-2" :disabled="resetting">
								<buttonLoading :loading="resetting" :loadingText="'submitting...'" :defaultText="'Submit'" />
							</button>
							<div class="text-right">
								<a class="text-muted custom-link" @click="forgotPass=false; serverErrors=[]">Sign in</a>
							</div>
						</form>
					</div>
					<div v-else class="text-center">
						<h1 class="font-weight-bold title">Login</h1>
						<hr>
						<serverErrors :serverErrors="serverErrors" />
						<form action="#" @submit.prevent="login">
							<div class="form-group">
								<input type="" class="form-control" name="username" placeholder="E-mail" v-model="username">
							</div>
							<div class="form-group">
								<input type="password" class="form-control" name="password" placeholder="Password" v-model="password">
							</div>
							<button :disabled="logging" class="custom-btn text-white h6 mt-2">
								<buttonLoading :loading="logging" :loadingText="'Logging in...'" :defaultText="'Login'" />
							</button>
							<hr>
							<div class="text-right">
								<a class="text-muted custom-link" @click="forgotPass=true; serverErrors=[]">Forgot password?</a>
							</div>
						</form>
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
				username: '',
				password: '',
				serverErrors: [],
				forgotPass: false,
				email: '',
				success: null,
				resetting: false,
				logging: false,
			}
		},
	    // beforeCreate() {
	    //     document.body.className = 'login';
	    // },
	    // beforeDestroy() {
	    //     document.body.className = '';
	    // },
		methods: {
			forgotPassword() {
				this.resetting = true;
				axios.post('/api/password/email', { email: this.email })
				.then(response => {
					const result = response.data;
					if (!result) {
						this.resetting = false;
						this.serverErrors.push(["Email doesn't exist"]);
					}
					else {
						this.resetting = false;
						this.success = "Password reset link sent to your email";
						this.email = '';
						this.serverErrors = [];
					}
				})
			},
			login() {
				this.logging = true;
				this.$store.dispatch('retrieveToken', {
					username: this.username,
					password: this.password
				})
				.then(response => {
					this.$store.dispatch('retrieveUser')
					.then(response => {
						this.userId = response.id;
						this.$store.dispatch('retrievePrivilege');
						this.$router.push('/home');
						this.logging = false;
					})
					.catch(error => {
						this.logging = false;
					})
				})
				.catch(error => {
					// this.$noty.error("Incorrect e-mail or password");
					this.serverErrors.push([error.response.data]);

					this.password = '';
					this.logging = false;
				})
			}
		}
	}
</script>

<style scoped>
	.cdec {
		margin: 0;
	}
	.crm {
		font-size: 5em;
		letter-spacing: 20px;
		margin-left: 25px;
	}
	.card {
		min-height: 80vh;
	}
	.row {
		padding: 0;
		height: 100%;
	}
	.left-col {
		margin: 0;
		background-image: url("/images/loginbg.jpg");
		background-position: center;
		background-repeat: no-repeat;
		background-size: auto;
	}
	.right-col {
		padding: 10%;
	}
	.title {
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
	.custom-link {
		color: rgb(22,15,64);
		cursor: pointer;	
	}
</style>