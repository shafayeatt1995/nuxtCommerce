<template>
	<div>
		<img src="~/assets/images/not-found.svg" class="img-fluid">
		<h3 class="text-center">{{message}}</h3>
	</div>

</template>
<script>
	export default {
		props: {
			message: "",
		},
	};
</script>