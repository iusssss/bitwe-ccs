<template>
	<div>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Name</th>
						<th>E-mail</th>
						<th>Privilege</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="user in users">
						<td>{{ user.name }}</td>
						<td>{{ user.email }}</td>
						<td>{{ user.privilege.toLowerCase() }}</td>
						<td>
							<div class="d-flex justify-content-end">
								<button class="btn btn-primary mx-1" @click="editUser(user)"><i class="fa fa-edit"></i></button>
								<button class="btn btn-primary" @click="deleteUser(user)"><i class="fa fa-trash"></i></button>
							</div>	
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</template>
<script>
	export default {
		props: ['users', 'workspace'],
		data() {
			return {

			}
		},
		mounted() {
		},
		methods: {
			editUser(user) {
				this.$emit('editUser', user);
				this.$router.push('/manage/register')
				$('#popup').modal('toggle');
			},
			deleteUser(user) {
				if(confirm(`Are you sure you want to delete user: ${user.name}?`)) {
					if (user.worker_sid) {
						let ref = this;
						this.workspace.workers.delete(user.worker_sid,
		                    function(error) {
		                        if(error) {
		                            console.log(error.code);
		                            console.log(error.message);
		                            return;
		                        }
		                        console.log("Worker deleted");
		                    }
		                );
					}
					axios.delete(`/api/user/${user.id}`)
					.then(response => {
						this.$noty.success("User successfully deleted");
						this.$emit('deleteUser');
					})
				}
			}
		},
 	}
</script>
<style scoped>
</style>