<template>
	<div>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-3" v-if="loggedIn">
			<a class="navbar-brand" href="#">CDEC</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-555"
			aria-controls="navbarSupportedContent-555" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent-555">
				<ul class="navbar-nav mr-auto">
					<li :class="currentPage.includes('home') ? 'active' : 'nav-item'" @click="link = 'Home'">
						<router-link class="nav-link" to="/home">Home</router-link>
					</li>
					<li v-if="$store.getters.isAdmin || $store.getters.isSystemAdmin" :class="currentPage.includes('admindashboard') ? 'active' : 'nav-item'">
						<router-link class="nav-link" to="/admindashboard">Dashboard</router-link>
					</li>
					<li v-if="$store.getters.isAgent" :class="currentPage.includes('agentdashboard') ? 'active' : 'nav-item'">
						<router-link class="nav-link" to="/agentdashboard">Dashboard</router-link>
					</li>
					<li v-if="$store.getters.isAdmin || $store.getters.isSystemAdmin" :class="currentPage.includes('manage') ? 'active' : 'nav-item'">
						<router-link class="nav-link" to="/manage">Manage</router-link>
					</li>
					<!-- <li :class="currentPage.includes('chart') ? 'active' : 'nav-item'">
						<router-link class="nav-link" to="/chart">Chart</router-link>
					</li> -->
					<li v-if="($store.getters.isAdmin || $store.getters.isSystemAdmin) && $store.getters.recordingAllowed" :class="currentPage.includes('evaluate') ? 'active' : 'nav-item'">
						<router-link class="nav-link" to="/evaluate">Evaluate</router-link>
					</li>
				</ul>
				<!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a v-if="$store.state.user" id="navbarDropdown" class="nav-link dropdown-toggle h5 m-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                			{{ $store.state.user.name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <router-link  v-if="$store.getters.isSystemAdmin" class="dropdown-item" to="/settings">Settings</router-link>
                            <router-link v-if="$store.getters.isAdmin" class="dropdown-item" to="/logs">Logs</router-link>
                            <router-link class="dropdown-item" to="/changepassword">Change Password</router-link>
                            <a class="dropdown-item" @click="logout">
                                logout
                            </a>
                        </div>
                    </li>
                </ul>
			</div>
		</nav>
	</div>
</template>

<script>
	export default {
		data() {
			return {
			}
		},
		mounted() {
		},
		methods: {
			logout() {
				console.log("logout");
				this.$store.dispatch('destroyToken')
				.then(response => {
					this.$router.push('/');
					this.$store.commit('goOffline');
				})
				.catch(error => {

				})
			}
		},
		computed: {
			currentPage() {
				return this.$route.path;
			},
			loggedIn() {
				return this.$store.getters.loggedIn;
			}
		}
	}
</script>

<style scoped>
	.dropdown-item {
		cursor: pointer;
	}
	.bg-dark {
    	background-color: rgba(22,15,64,.8) !important;
	    background-image: url(/images/loginbg.jpg);
	    background-position: center center;
	}
</style>