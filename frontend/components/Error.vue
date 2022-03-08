<template>
	<!--Not found area start-->
	<div class="error" :class="dashboard ? 'mt-50' : ''">
		<div class="container text-center">
			<img :src="url+'images/404.svg'" alt="404 not found" class="img-fluid h-350" />
			<h5 class="mt-3">We can't seem to find the page you're looking.</h5>
			<nuxt-link :to="localePath('dashboard')" class="btn btn-primary" v-if="dashboard">Go To Dashboard</nuxt-link>
			<nuxt-link :to="localePath('index')" class="big-button" v-else>Go To Home</nuxt-link>
		</div>
	</div>
	<!--Not found area end-->
</template>

<script>
	export default {
		props: {
			dashboard: false,
		},
	};
</script>