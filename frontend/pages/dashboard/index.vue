<template>
	<div>
		<div class="section-header">
			<h1>Dashboard</h1>
		</div>

		<div class="section-body">
			<div class="row">
				<div class="col-lg-6 offset-lg-3 bg-white shadow">
					<h1>This is section body</h1>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "user-dashboard",
		auth: true,
		head() {
			return {
				title: `Dashboard - ${this.appName}`,
			};
		},
	};
</script>