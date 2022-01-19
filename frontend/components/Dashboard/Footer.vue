<template>
	<footer class="main-footer" :class="sidebar ? 'active':''">
		<div class="footer-left">
			Copyright &copy; 2022 <div class="bullet"></div> Powered By <a href="#">V-commerce</a>
		</div>
		<div class="footer-right">

		</div>
	</footer>
</template>
<script>
	export default {
		data() {
			return {
				sidebar: true,
			};
		},
		created() {
			this.$nuxt.$on("toggleSidebar", (data) => {
				this.sidebar = data;
			});
		},
	};
</script>