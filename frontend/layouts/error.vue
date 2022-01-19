<template>
	<Error />
</template>
<script>
	export default {
		head() {
			return {
				title: "Not Found - " + this.appName,
			};
		},
	};
</script>
