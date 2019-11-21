<template>
	<div class="mb-3">
		<div class="input-group w-50">
			<div class="input-group-prepend">
				<button @click="uploadFile" style="background-color: rgb(45,39,83);" class="input-group-text text-white" id="inputGroupFileAddon01">Upload</button>
			</div>
			<div class="custom-file">
				<input type="file" :ref="name" class="custom-file-input" :id="name"
				aria-describedby="inputGroupFileAddon01" @change="handleFileUpload" accept=".csv">
				<label class="custom-file-label" :for="name">{{ fileName != null ? fileName : 'Choose file' }}</label>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props:['name'],
		data() {
			return {
				fileName: null,
			}
		},
		methods: {
			uploadFile() {
				this.$emit('uploadFile');
			},
			handleFileUpload() {
				var file = null;
				if (this.name == 'company')
					file = this.$refs.company.files[0];
				else if (this.name == 'client') 
					file = this.$refs.client.files[0];
				this.fileName = file.name;
				this.$emit('fileChange', file);
			}
		}
	}
</script>
<style scoped>
	.custom-file-label::after {
		color: white;
		background-color: rgb(45,39,83);
	}
	label {
		text-overflow: ellipsis;
		display: inline-block;
		overflow: hidden;
		width: 300px;
		white-space: nowrap;
		vertical-align: middle;
    }
</style>