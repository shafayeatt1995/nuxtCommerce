<template>
	<div>
		<div class="section-header">
			<h1>Currency</h1>
			<nuxt-link :to="localePath('dashboard-admin-currency-create')" class="btn btn-primary">Create Currency</nuxt-link>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<h4 class="w-100 text-center mb-3">Always keep the default currency exchange rate 1</h4>
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="action === 'delete' ? deleteCurrency() : ''">
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
				<table class="table table-striped text-center table-responsive-md">
					<thead>
						<tr>
							<th scope="col">
								<input class="form-check-input" type="checkbox" @click="select.length >= 1 ? deselectall():selectAll()" :checked="select.length >= 1">
							</th>
							<th scope="col">Currency</th>
							<th scope="col">Exchange Rate</th>
							<th scope="col">status</th>
							<th scope="col">Default</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody class="text-center" v-if="loading">
						<td colspan="8">
							<Loader />
						</td>
					</tbody>
					<tbody class="text-center" v-else-if="currencies.data && currencies.data.length >= 1">
						<tr v-for="currency in currencies.data" :key="currency.id">
							<th scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="currency.id">
							</th>
							<td>{{currency.country}} ({{currency.name}})</td>
							<td>{{currency.symble}} {{currency.rate | currency}}</td>
							<td>
								<button class="badge badge-success color-black" type="button" @click="changeStatus(currency.id)" v-if="currency.status">Active</button>
								<button class="badge badge-danger" type="button" @click="changeStatus(currency.id)" v-else>Deactive</button>
							</td>
							<td>
								<button class="badge badge-success color-black" type="button" v-if="currency.default">Default</button>
								<button class="badge badge-danger" type="button" @click="changeDefault(currency.id)" v-else>Make Default</button>
							</td>
							<td>
								<nuxt-link :to="localePath({name: 'dashboard-admin-currency-edit-id', params:{id: currency.id}})" class="btn btn-icon btn-primary mx-2 my-2">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</nuxt-link>
								<button class="btn btn-icon btn-danger my-2" @click="deleteCurrency(currency.id)">
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
				<pagination :data="currencies" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-currencies",
		middleware: "admin",
		head() {
			return {
				title: `Currencies - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				currencies: {},
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
			//Get Currency
			getCurrency() {
				this.$axios.get("currency").then(
					(response) => {
						this.currencies = response.data.currencies;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("currency?page=" + page).then((response) => {
					this.currencies = response.data.currencies;
				});
			},

			//Confirm Delete
			deleteCurrency(id) {
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
									.post("delete-currency", { idList: list })
									.then(
										(response) => {
											this.select = [];
											$nuxt.$emit("triggerCurrency");
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
				this.currencies.data.forEach((currency) => {
					this.select.push(currency.id);
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
					this.$axios.post("search-currency", this.searchOption).then(
						(response) => {
							this.currencies = response.data.currencies;
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
					this.$axios.post(`status-currency/${id}`).then(
						(response) => {
							$nuxt.$emit("triggerCurrency");
							this.click = true;
						},
						(error) => {
							$nuxt.$emit("error", error);
							this.click = true;
						}
					);
				}
			},

			changeDefault(id) {
				if (this.click) {
					this.click = false;
					this.$axios.post(`default-currency/${id}`).then(
						(response) => {
							$nuxt.$emit("triggerCurrency");
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
			this.getCurrency();
			this.$nuxt.$on("triggerCurrency", () => {
				this.getCurrency();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerCurrency");
		},
	};
</script>