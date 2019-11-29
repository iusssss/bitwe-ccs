<template>
	<div>
        <div v-if="!edit_email">
            <table class="table table-responsive" style="overflow-x: scroll;">
				<thead>
					<tr>
						<th class="w-25">Status</th>
						<th class="w-25">Subject</th>
						<th class="w-50">Body</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(template, index) in emailTemplates">
						<td>{{ template.ticket_status.name }}</td>
						<td>{{ template.subject }}</td>
						<td v-html="template.body"></td>
						<td>
							<div class="float-right">
								<button class="btn btn-primary mx-1" @click="edit(index)"><i class="fa fa-edit"></i></button>
							</div>	
						</td>
					</tr>
				</tbody>
			</table>
        </div>
        <div v-else>
        	Status: <div class="text-primary d-inline">{{ template.ticket_status.name }}</div>
        	<hr>
        	<input class="form-control" name="" v-model="subject" placeholder="Subject"><br>
			<quillEditor v-model="body" />
			<button class="btn btn-primary mt-3" @click="save">
	            <i class="far fa-save"></i>
	            Save
	        </button>
        </div>
	</div>
</template>

<script>
	import 'quill/dist/quill.core.css'
	import 'quill/dist/quill.snow.css'
	import 'quill/dist/quill.bubble.css'
	import { quillEditor } from 'vue-quill-editor'
	export default {
		data() {
			return {
				body:'',
				template: null,
				edit_email: false,
			}
		},
		methods: {
			save() {
				this.edit_email = false;
				this.template.body = this.body;
				this.template.subject = this.subject;
				this.$emit("save", this.template);

				this.body = "";
				this.subject = "";
			},
			edit(index) {
				this.edit_email = true;
				this.template = this.emailTemplates[index];
				this.body = this.template.body;
				this.subject = this.template.subject;
			}
		},
		props:['emailTemplates'],
		components: {
			quillEditor
		},
		mounted() {
		},
	}
</script>