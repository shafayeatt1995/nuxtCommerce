<template>
	<div>
		<div class="section-header">
			<h1>Edit Material</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit">
				<div class="col-lg-12">
					<div class="bg-white p-3 min-h100 rounded card-primary">
						<h3 class="text-center">General Information</h3>
						<div class="form-group">
							<label for="name">Material Name</label>
							<input type="text" class="form-control" id="name" v-model="form.name">
							<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
						</div>
						<div class="form-group">
							<label for="status">Status</label>
							<select class="form-control" id="status" v-model="form.status">
								<option :value="true">Enable</option>
								<option :value="false">Disable</option>
							</select>
							<p class="invalid-feedback" v-if="errors.status">{{errors.status[0]}}</p>
						</div>
						<button type="submit" class="btn btn-primary mt-5">
							<transition name="fade" mode="out-in">
								<Spiner v-if="loading" />
								<span v-else>Update Material</span>
							</transition>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</template>
<script>
	export default {
		name: "edit-material",
		middleware: "admin",
		head() {
			return {
				title: `Edit Material - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					name: "",
					status: "",
				},
				errors: {},
				click: true,
				loading: false,
			};
		},

		methods: {
			// Get Material
			editMaterial() {
				this.$axios.get(`edit-material/${this.$route.params.id}`).then(
					(response) => {
						this.form.name = response.data.material.name;
						this.form.status = response.data.material.status;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},

			//Submit Form
			submit() {
				if (this.click) {
					this.click = false;
					this.$axios
						.post(`update-material/${this.$route.params.id}`, this.form)
						.then(
							(response) => {
								$nuxt.$emit("success", response.data);
								this.click = true;
								this.$router.push(
									this.localePath(
										"dashboard-admin-product-options-material"
									)
								);
							},
							(error) => {
								this.errors = error.response.data.errors;
								this.click = true;
							}
						);
				}
			},
		},

		created() {
			this.editMaterial();
		},
	};
</script>