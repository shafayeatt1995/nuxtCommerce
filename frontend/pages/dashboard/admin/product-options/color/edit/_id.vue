<template>
	<div>
		<div class="section-header">
			<h1>Edit Color</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit">
				<div class="col-lg-12">
					<div class="bg-white p-3 min-h100 rounded card-primary">
						<h3 class="text-center">General Information</h3>
						<div class="form-group">
							<label for="name">Color Name</label>
							<input type="text" class="form-control" id="name" v-model="form.name">
							<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
						</div>
						<div class="form-group">
							<label for="code">Color Code</label>
							<input type="color" class="form-control" id="code" v-model="form.code">
							<input type="text" class="form-control mt-2" v-model="form.code">
							<p class="invalid-feedback" v-if="errors.code">{{errors.code[0]}}</p>
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
								<span v-else>Update Color</span>
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
		name: "edit-color",
		middleware: "admin",
		head() {
			return {
				title: `Edit Color - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					name: "",
					code: "",
					status: "",
				},
				errors: {},
				click: true,
				loading: false,
			};
		},

		methods: {
			// Get Color
			editColor() {
				this.$axios.get(`edit-color/${this.$route.params.id}`).then(
					(response) => {
						this.form.name = response.data.color.name;
						this.form.code = response.data.color.code;
						this.form.status = response.data.color.status;
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
						.post(`update-color/${this.$route.params.id}`, this.form)
						.then(
							(response) => {
								$nuxt.$emit("success", response.data);
								this.click = true;
								this.$router.push(
									this.localePath(
										"dashboard-admin-product-options-color"
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
			this.editColor();
		},
	};
</script>