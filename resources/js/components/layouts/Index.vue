<template>
	<div id="app">
		<navbar />
		<router-view></router-view>
	</div>
</template>

<script>
	import navbar from './Navbar.vue'
	export default {
		data() {
			return {
				created: false,
			}
		},
		components: {
			navbar
		},
        created() {
			this.$store.dispatch('retrievePrivilege').then(() => { this.created = true; });
			this.$store.dispatch('retrieveSettings');
        },
	}
</script>