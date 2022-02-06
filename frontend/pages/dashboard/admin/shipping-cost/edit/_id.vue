<template>
	<div>
		<div class="section-header">
			<h1>Edit Shipping Cost Rules</h1>
		</div>

		<div class="section-body">
			<div class="row">
				<div class="col-lg-12">
					<div class="bg-white p-3 min-h100 rounded card-primary">
						<h3 class="text-center">General Information</h3>
						<div class="form-group">
							<label for="name">Shipping Cost Rules Name</label>
							<input type="text" class="form-control" id="name" v-model="form.name">
							<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
						</div>
						<div class="d-block">
							<h4 class="mb-3" v-if="form.rules.length < 1">No Rules</h4>
							<table class="table text-center table-responsive-md" v-else>
								<thead>
									<tr>
										<th scope="col">#</th>
										<th scope="col">From Weight</th>
										<th scope="col">To Weight</th>
										<th scope="col">Price</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody class="text-center">
									<tr v-for="(rule, key) in form.rules" :key="key">
										<td>{{key + 1}}</td>
										<td>{{rule.fromWeight}} KG</td>
										<td>{{rule.toWeight}} KG</td>
										<td>{{rule.price}}</td>
										<td>
											<button type="button" class="close float-none" @click="removeRule(key)">
												<i>
													<icon :icon="['fas', 'times']"></icon>
												</i>
											</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<form class="row" @submit.prevent="addRules">
							<div class="col-lg-4">
								<div class="form-group">
									<label for="from">From Weight (KG)</label>
									<input type="number" step="0.001" max="999" class="form-control" id="from" v-model="fromWeight" required>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label for="to">To Weight (KG)</label>
									<input type="number" step="0.001" max="999" class="form-control" id="to" v-model="toWeight" required>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label for="price">Price</label>
									<input type="number" step="0.01" class="form-control" id="price" v-model="price" required>
								</div>
							</div>
							<div class="col-lg-12">
								<button type="submit" class="btn btn-primary w-100 mb-5">Add Rules</button>
							</div>
						</form>
						<button type="button" class="btn btn-primary" @click="submit">
							<transition name="fade" mode="out-in">
								<Spiner v-if="loading" />
								<span v-else>Update Rule</span>
							</transition>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "create-rule",
		head() {
			return {
				title: `Create Shipping Cost Rules - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					name: "",
					rules: [],
				},
				fromWeight: "",
				toWeight: "",
				price: "",
				errors: {},
				click: true,
				loading: false,
			};
		},

		methods: {
			//Get shipping cost information
			getCost() {
				this.$axios.get(`edit-shipping-cost/${this.$route.params.id}`).then(
					(response) => {
						this.form.name = response.data.cost.name;
						this.form.rules = JSON.parse(response.data.cost.rules);
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
					this.loading = true;
					this.errors = {};
					this.$axios
						.post(
							`update-shipping-cost/${this.$route.params.id}`,
							this.form
						)
						.then(
							(response) => {
								this.$router.push(
									this.localePath("dashboard-admin-shipping-cost")
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

			//Add Rules
			addRules() {
				let newRules = {
					fromWeight: this.fromWeight,
					toWeight: this.toWeight,
					price: this.price,
				};
				this.form.rules.push(newRules);
				this.fromWeight = "";
				this.toWeight = "";
				this.price = "";
			},

			//Remove Rule
			removeRule(key) {
				this.form.rules.splice(key, 1);
			},
		},

		created() {
			this.getCost();
		},
	};
</script>