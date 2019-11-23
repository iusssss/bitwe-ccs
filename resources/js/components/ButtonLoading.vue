<template>
	<div>
		<span v-if="loading">
	        <i class="fas fa-spinner fa-pulse"></i>
	        {{ loadingText }}
	    </span>
		<span v-else>
			{{ defaultText }}
		</span>
	</div>
</template>
<script>
	export default {
		props: ['loading', 'loadingText', 'defaultText']
	}
</script>