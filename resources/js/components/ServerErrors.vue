<template>
    <div v-if="serverErrors.length > 0" class="alert alert-danger">
        <div v-for="error in serverErrors">
            {{ error[0] }}
        </div>
    </div>
</template>
<script>
	export default {
		props:['serverErrors']
	}
</script>