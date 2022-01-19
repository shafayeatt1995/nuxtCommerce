<template>
	<div>
		<h1>This is home Page</h1>
		{{appName}}
	</div>
</template>

<script>
	export default {
		auth: false,
		head() {
			return {
				title: `Home - ${this.appName}`,
			};
		},
	};
</script>
