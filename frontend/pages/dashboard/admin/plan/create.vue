<template>
	<div>
		<div class="section-header">
			<h1>Create Plan</h1>
		</div>

		<div class="section-body">
			<form class="row">
				<div class="col-lg-8">
					<div class="bg-white shadow p-3 rounded">
						<div class="form-group">
							<label for="name">Plan Name</label>
							<input type="text" class="form-control" id="name" v-model="form.name">
						</div>
						<div class="form-group">
							<label for="description">Plan Description</label>
							<input type="text" class="form-control" id="description" v-model="form.description">
						</div>
						<div class="form-group">
							<label for="description">Plan Price</label>
							<input type="number" class="form-control" id="description" step="0.01" v-model="form.price">
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="bg-white shadow p-3 rounded">
						<select class="form-control" v-model="form.duration">
							<option value="">Select a duration</option>
							<option value="7">One week (7 Days)</option>
							<option value="30">One Month (30 Days)</option>
							<option value="365">One year (365 Days)</option>
						</select>
					</div>
				</div>
			</form>
		</div>
	</div>
</template>
<script>
	export default {
		head() {
			return {
				title: `Create Plan - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					name: "",
					description: "",
					duration: "",
				},
			};
		},
	};
</script>