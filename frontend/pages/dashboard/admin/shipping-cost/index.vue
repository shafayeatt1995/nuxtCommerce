<template>
	<div>
		<div class="section-header">
			<h1>Shipping Cost</h1>
			<nuxt-link :to="localePath('dashboard-admin-shipping-cost-create')" class="btn btn-primary">Create Shipping Cost</nuxt-link>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="action === 'delete' ? deleteCost() : ''">
						<select class="form-control" v-model="action">
							<option value="">Select a option</option>
							<option value="delete">Delete selected items</option>
						</select>
						<button type="submit" class="btn btn-primary">Apply Action</button>
					</form>
					<form class="d-flex mb-3" @submit.prevent="search">
						<input class="form-control" type="text" placeholder="Search..." v-model="searchOption.keyword">
						<select class="form-control" v-model="searchOption.collum">
							<option value="name">Search by name</option>
							<option value="country">Search by country</option>
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
							<th scope="col">
								<input class="form-check-input" type="checkbox" @click="select.length >= 1 ? deselectall():selectAll()" :checked="select.length >= 1">
							</th>
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
					<tbody class="text-center" v-else-if="costs.data && costs.data.length >= 1">
						<tr v-for="cost in costs.data" :key="cost.id">
							<td scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="cost.id">
							</td>
							<td>{{cost.name}}</td>
							<td class="py-3">
								<table>
									<tbody>
										<tr v-for="(rule, key) in JSON.parse(cost.rules)" :key="key + 100">
											<td>{{key + 1}})</td>
											<td>{{rule.fromWeight}} KG</td>
											<td>to</td>
											<td>{{rule.toWeight}} KG</td>
											<td>=</td>
											<td>$ {{rule.price}}</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td>{{cost.updated_at | date}}</td>
							<td>
								<nuxt-link :to="localePath({name: 'dashboard-admin-shipping-cost-edit-id', params:{id: cost.id}})" class="btn btn-icon btn-primary mx-2 my-2">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</nuxt-link>
								<button class="btn btn-icon btn-danger my-2" @click="deleteCost(cost.id)">
									<i>
										<icon :icon="['fas', 'trash-alt']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No Currency found" />
						</td>
					</tbody>
				</table>
				<pagination :data="costs" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-shipping-cost",
		head() {
			return {
				title: `Shipping Cost - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				costs: {},
				select: [],
				action: "",
				searchOption: {
					keyword: "",
					collum: "name",
				},
				loading: true,
			};
		},
		methods: {
			//Get shipping cost
			getCost() {
				this.$axios.get("shipping-cost").then(
					(response) => {
						this.costs = response.data.costs;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("shipping-cost?page=" + page).then((response) => {
					this.costs = response.data.costs;
				});
			},

			//Confirm Delete
			deleteCost(id) {
				if (this.click) {
					this.click = false;
					this.$swal
						.fire({
							title: "Are you sure?",
							text: "You won't be able to revert this!",
							icon: "warning",
							showCancelButton: true,
							confirmButtonColor: "#6777ef",
							cancelButtonColor: "#fc544b",
							confirmButtonText: "Yes, delete it!",
						})
						.then((result) => {
							if (result.isConfirmed) {
								let list = id ? [id] : this.select;
								this.$axios
									.post("delete-shipping-cost", { idList: list })
									.then(
										(response) => {
											this.select = [];
											$nuxt.$emit("triggerCost");
											$nuxt.$emit("success", response.data);
											this.click = true;
										},
										(error) => {
											$nuxt.$emit("error", error);
											this.click = true;
										}
									);
							} else {
								this.click = true;
							}
						});
				}
			},

			// Select All Data
			selectAll() {
				this.select = [];
				this.costs.data.forEach((cost) => {
					this.select.push(cost.id);
				});
			},

			//Deselect all data
			deselectall() {
				this.select = [];
			},

			search() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$axios
						.post("search-shipping-cost", this.searchOption)
						.then(
							(response) => {
								this.costs = response.data.costs;
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

			changeStatus(id) {
				if (this.click) {
					this.click = false;
					this.$axios.post(`status-shipping-cost/${id}`).then(
						(response) => {
							$nuxt.$emit("triggerCost");
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
			this.getCost();
			this.$nuxt.$on("triggerCost", () => {
				this.getCost();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerCost");
		},
	};
</script>