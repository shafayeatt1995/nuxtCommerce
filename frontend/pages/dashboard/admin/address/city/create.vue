<template>
	<div>
		<div class="section-header">
			<h1>Create City</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit">
				<div class="col-lg-12">
					<div class="bg-white p-3 min-h100 rounded card-primary">
						<h3 class="text-center">General Information</h3>
						<div class="form-group">
							<label for="status">Country</label>
							<select class="form-control" id="status" v-model="form.countryId" @change="form.countryId ? changeCountry() : ''">
								<option value="">Select a Country</option>
								<option :value="country.id" v-for="country in countries" :key="country.id">{{country.name}}</option>
							</select>
							<p class="invalid-feedback" v-if="errors.countryId">{{errors.countryId[0]}}</p>
						</div>
						<div class="form-group">
							<label for="status">State</label>
							<select class="form-control" id="status" v-model="form.stateId" :disabled="form.countryId && states.length < 1">
								<option value="">Select a state</option>
								<option :value="state.id" v-for="state in states" :key="state.id">{{state.name}}</option>
							</select>
							<p class="invalid-feedback" v-if="errors.stateId">{{errors.stateId[0]}}</p>
						</div>
						<div class="form-group">
							<label for="name">City</label>
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
						<button type="submit" class="btn btn-primary">
							<transition name="fade" mode="out-in">
								<Spiner v-if="loading" />
								<span v-else>Create City</span>
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
		name: "create-city",
		middleware: "admin",
		head() {
			return {
				title: `Create city - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					countryId: "",
					stateId: "",
					name: "",
					status: true,
				},
				countries: [],
				states: [],
				errors: {},
				click: true,
				loading: false,
			};
		},

		methods: {
			getCountryList() {
				this.$axios.get("country-list").then(
					(response) => {
						this.countries = response.data.countries;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},

			//Change country
			changeCountry() {
				this.form.stateId = "";
				this.$axios.get(`state-list/${this.form.countryId}`).then(
					(response) => {
						this.states = response.data.states;
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
					this.errors = {};
					this.$axios.post("create-city", this.form).then(
						(response) => {
							$nuxt.$emit("success", response.data);
							this.click = true;
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
			this.getCountryList();
		},
	};
</script>
