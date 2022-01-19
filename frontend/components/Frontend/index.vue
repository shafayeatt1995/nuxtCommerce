<template>
	<div>
		<FrontendHeader />
		<Nuxt />
		<FrontendFooter />
	</div>
</template>
<script>
	export default {
		head() {
			return {
				link: [
					{
						rel: "stylesheet",
						href: `${process.env.URL}frontend/css/bootstrap.min.css`,
					},
					{
						rel: "stylesheet",
						href: `${process.env.URL}frontend/css/style.css`,
					},
					{
						rel: "stylesheet",
						href: `${process.env.URL}frontend/css/responsive.css`,
					},
				],
			};
		},
	};
</script>