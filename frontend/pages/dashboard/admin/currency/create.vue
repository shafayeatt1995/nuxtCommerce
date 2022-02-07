<template>
	<div>
		<div class="section-header">
			<h1>Create Currency</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit">
				<div class="col-lg-12">
					<div class="bg-white p-3 min-h100 rounded card-primary">
						<h3 class="text-center">General Information</h3>
						<div class="form-group">
							<label for="name">Currency Name <small>(Make sure all are capital letter)</small></label>
							<input type="text" class="form-control" id="name" v-model="form.name">
							<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
						</div>
						<div class="form-group">
							<label for="country">Currency Country</label>
							<input type="text" class="form-control" id="country" v-model="form.country">
							<p class="invalid-feedback" v-if="errors.country">{{errors.country[0]}}</p>
						</div>
						<div class="form-group">
							<label for="symble">Currency Symble</label>
							<input type="text" class="form-control" id="symble" v-model="form.symble">
							<p class="invalid-feedback" v-if="errors.symble">{{errors.symble[0]}}</p>
						</div>
						<div class="form-group">
							<label for="rate">Currency Exchange Rate</label>
							<input type="number" class="form-control" id="rate" step="0.01" v-model="form.rate">
							<p class="invalid-feedback" v-if="errors.rate">{{errors.rate[0]}}</p>
						</div>
						<div class="form-group">
							<label for="status">Status</label>
							<select class="form-control" id="status" v-model="form.status">
								<option :value="true">Enable</option>
								<option :value="false">Disable</option>
							</select>
							<p class="invalid-feedback" v-if="errors.status">{{errors.status[0]}}</p>
						</div>
						<button type="submit" class="btn btn-primary">
							<transition name="fade" mode="out-in">
								<Spiner v-if="loading" />
								<span v-else>Create Currency</span>
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
		name: "create-currency",
		middleware: "admin",
		head() {
			return {
				title: `Create Currency - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					name: "",
					country: "",
					symble: "",
					rate: "",
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
					this.loading = true;
					this.errors = {};
					this.$axios.post("create-currency", this.form).then(
						(response) => {
							this.$router.push(
								this.localePath("dashboard-admin-currency")
							);
							$nuxt.$emit("success", response.data);
							this.loading = false;
							this.click = true;
						},
						(error) => {
							this.errors = error.response.data.errors;
							this.loading = false;
							this.click = true;
						}
					);
				}
			},
		},
	};
</script>