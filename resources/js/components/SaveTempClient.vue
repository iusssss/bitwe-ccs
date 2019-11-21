<template>
	<tempClient :tempClient="tempClient" @clientSaved="clientSaved" />
</template>
<script>
	import TempClient from '../views/client/TempClient.vue';
	export default {
		components: { TempClient },
		props:['tempClient'],
		methods: {
			clientSaved(client) {
				this.$emit('clientSaved', client);
			}
		}
	}
</script>