<template>
	<div>
		<div class="section-header">
			<h1>Set Shipping Cost Rule</h1>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-end flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="search">
						<input class="form-control" type="text" placeholder="Search..." v-model="searchOption.keyword" @keyup="instantSearch">
						<select class="form-control" v-model="searchOption.collum">
							<option value="name">Search by name</option>
							<option value="country">Search by state</option>
						</select>
						<button type="submit" class="btn btn-primary">
							<i>
								<icon :icon="['fas', 'search']"></icon>
							</i>
						</button>
					</form>
				</div>
				<table class="table text-center table-striped table-responsive-md">
					<thead>
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Shipping Cost Rules</th>
							<th scope="col">Last Update</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody class="text-center" v-if="loading">
						<td colspan="8">
							<Loader />
						</td>
					</tbody>
					<tbody class="text-center" v-else-if="states.data && states.data.length >= 1">
						<tr v-for="state in states.data" :key="state.id">
							<td>{{state.name}}</td>
							<td>
								<div v-if="state.id === rules.stateId">
									<form>
										<select class="form-control" v-model="rules.rulesId">
											<option value="">Select a rules</option>
											<option :value="cost.id" v-for="(cost, key) in costs" :key="`cost${key}`">{{cost.name}}</option>
										</select>
									</form>
								</div>
								<div v-else>
									<span class="badge badge-danger" v-if="state.shpipping_cost_rule === null">Not Set</span>
									<span class="badge badge-success color-black" v-else>
										Shipping Cost Rules: {{state.shpipping_cost_rule.shipping_cost.name}}
									</span>
								</div>
							</td>
							<td>
								<span v-if="state.shpipping_cost_rule">{{state.shpipping_cost_rule.updated_at | date}}</span>
								<span v-else>Shipping cost not set</span>
							</td>
							<td v-if="state.id !== rules.stateId">
								<button type="button" class="btn btn-icon btn-primary mx-2 my-2" @click="rulesForm(state.id)">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</button>
							</td>
							<td v-else>
								<button class="btn btn-icon btn-success my-2" @click="submit">
									<i>
										<icon :icon="['fas', 'check']"></icon>
									</i>
								</button>
								<button type="button" class="btn btn-icon btn-danger mx-2 my-2" @click="rulesForm(state.id)">
									<i>
										<icon :icon="['fas', 'times']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No State found" />
						</td>
					</tbody>
				</table>
				<pagination :data="states" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "set-shipping-cost",
		middleware: "admin",
		head() {
			return {
				title: `Set Shipping Cost - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				states: {},
				costs: [],
				select: "",
				rules: {
					stateId: "",
					rulesId: "",
				},
				searchOption: {
					keyword: "",
					collum: "name",
				},
				loading: true,
			};
		},
		methods: {
			//Get state
			getState() {
				this.$axios.get("state-rules").then(
					(response) => {
						this.states = response.data.states;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("state-rules?page=" + page).then((response) => {
					this.states = response.data.states;
				});
			},

			//  Get Shipping Cost Rules
			getRules() {
				this.$axios.get("shipping-cost-list").then(
					(response) => {
						this.costs = response.data.costs;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},

			//Search Rulse
			search() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$axios
						.post("search-shipping-cost", this.searchOption)
						.then(
							(response) => {
								this.states = response.data.states;
								this.loading = false;
								this.click = true;
							},
							(error) => {
								$nuxt.$emit("error", error);
								this.click = true;
							}
						);
				}
			},
			instantSearch() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					setTimeout(() => {
						this.$axios
							.post("search-shipping-cost", this.searchOption)
							.then(
								(response) => {
									this.states = response.data.states;
									this.loading = false;
									this.click = true;
								},
								(error) => {
									$nuxt.$emit("error", error);
									this.click = true;
								}
							);
					}, 500);
				}
			},

			//Set Rules Form
			rulesForm(id) {
				if (this.rules.stateId === id) {
					this.rules.stateId = "";
					this.rules.rulesId = "";
				} else {
					this.rules.stateId = id;
				}
			},

			//Submit Rules
			submit() {
				if (this.click) {
					this.click = false;
					this.$axios.post("update-shipping-cost-rules", this.rules).then(
						(response) => {
							$nuxt.$emit("triggerShippingCostRules");
							this.rules.stateId = "";
							this.rules.rulesId = "";
							this.click = true;
						},
						(error) => {
							$nuxt.$emit("error", error);
							this.click = true;
						}
					);
				}
			},
		},

		created() {
			this.getState();
			this.getRules();
			this.$nuxt.$on("triggerShippingCostRules", () => {
				this.getState();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerShippingCostRules");
		},
	};
</script>