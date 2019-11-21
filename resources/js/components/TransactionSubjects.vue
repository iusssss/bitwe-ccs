<template>
	<div>
		<table class="table">
			<thead>
				<tr>
					<th>Issue</th>
					<th>Resolution</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="subject in subjects">
					<td>{{ subject.issue }}</td>
					<td>{{ subject.resolution }}</td>
					<td>
						<div class="d-flex text-right">
							<button class="btn btn-primary mx-1"><i class="fa fa-edit"></i></button>
							<button class="btn btn-primary"><i class="fa fa-trash"></i></button>
						</div>	
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>
<script>
	export default {
		props: ['subjects'],
	}
</script>