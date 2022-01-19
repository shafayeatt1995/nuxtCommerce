<template>
	<Dashboard v-if="dashboard" />
	<Frontend v-else />
</template>

<script>
	export default {
		data() {
			return {
				dashboard: "",
			};
		},

		created() {
			this.dashboard = this.$route.path.substring(0, 10) === "/dashboard";

			//Trigger Success Toast Message
			this.$nuxt.$on("success", (success) => {
				this.$toast.success(success);
			});

			//Trigger Error Toast Message
			this.$nuxt.$on("error", (error) => {
				this.$toast.error(
					error.response.data.errors
						? error.response.data.errors[
								Object.keys(error.response.data.errors)[0]
						  ][0]
						: error.response.data.error
						? error.response.data.error
						: error.response.data.message
						? error.response.data.message
						: "Something Wrong! Please try Again"
				);
			});

			//Trigger Error Toast Message
			this.$nuxt.$on("customError", (error) => {
				this.$toast.error(error);
			});

			//Trigger Info Toast Message
			this.$nuxt.$on("warning", (warning) => {
				this.$toast.warning(warning);
			});
		},
	};
</script>
