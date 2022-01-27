<template>
	<div>
		<div class="section-header">
			<h1>Create Category</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit">
				<div class="col-lg-12">
					<div class="bg-white p-3 min-h100 card card-primary">
						<h3 class="text-center">General Information</h3>
						<div class="form-group">
							<label for="name">Category Name</label>
							<input type="text" class="form-control" id="name" v-model="form.name">
							<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
						</div>
						<div class="form-group">
							<label for="qrCode">QR-Code</label>
							<select class="form-control" id="qrCode" v-model="form.status">
								<option :value="true">Enable</option>
								<option :value="false">Disable</option>
							</select>
							<p class="invalid-feedback" v-if="errors.qrCode">{{errors.qrCode[0]}}</p>
						</div>
						<button type="submit" class="btn btn-primary">
							<transition name="fade" mode="out-in">
								<Spiner v-if="loading" />
								<span v-else>Create Category</span>
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
		name: "create-caterory",
		head() {
			return {
				title: `Create Caterory - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					name: "",
					status: true,
				},
				errors: {},
				click: true,
				loading: false,
			};
		},

		methods: {
			//Submit Form
			submit() {
				if (this.click) {
					this.click = false;
					this.$axios.post("create-category", this.form).then(
						(response) => {
							$nuxt.$emit("success", response.data);
							this.click = true;
							this.$router.push(
								this.localePath("dashboard-admin-category")
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
	};
</script>