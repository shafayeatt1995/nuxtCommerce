<template>
	<!--Not found area start-->
	<div class="error">
		<div class="container text-center">
			<img :src="url+'images/404.svg'" alt="404 not found" class="img-fluid h-350" />
			<p>Page Not Found</p>
			<h5>We can't seem to find the page you're looking for.</h5>
			<nuxt-link :to="localePath('index')" class="big-button">Go To Home</nuxt-link>
		</div>
	</div>
	<!--Not found area end-->
</template>