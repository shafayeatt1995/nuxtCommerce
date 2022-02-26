<template>
	<div>
		<div class="section-header">
			<h1>City</h1>
			<nuxt-link :to="localePath('dashboard-admin-address-city-create')" class="btn btn-primary">Create City</nuxt-link>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="action === 'delete' ? deleteCity() : ''">
						<select class="form-control" v-model="action">
							<option value="">Select a option</option>
							<option value="delete">Delete selected items</option>
						</select>
						<button type="submit" class="btn btn-primary">Apply Action</button>
					</form>
					<form class="d-flex mb-3" @submit.prevent="search">
						<input class="form-control" type="text" placeholder="Search..." v-model="searchOption.keyword" @keyup="instantSearch">
						<select class="form-control" v-model="searchOption.collum">
							<option value="name">Search by City</option>
						</select>
						<button type="submit" class="btn btn-primary">
							<i>
								<icon :icon="['fas', 'search']"></icon>
							</i>
						</button>
					</form>
				</div>
				<table class="table table-striped text-center table-responsive-lg">
					<thead>
						<tr>
							<th scope="col">
								<input class="form-check-input" type="checkbox" @click="select.length >= 1 ? deselectall():selectAll()" :checked="select.length >= 1">
							</th>
							<th scope="col">Country</th>
							<th scope="col">State</th>
							<th scope="col">City</th>
							<th scope="col">status</th>
							<th scope="col">Create At</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody class="text-center" v-if="loading">
						<td colspan="8">
							<Loader />
						</td>
					</tbody>
					<tbody class="text-center" v-else-if="cities.data && cities.data.length >= 1">
						<tr v-for="city in cities.data" :key="city.id">
							<th scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="city.id">
							</th>
							<td>{{city.country.name}}</td>
							<td>{{city.state.name}}</td>
							<td>{{city.name}}</td>
							<td>
								<button class="badge badge-success color-black" type="button" @click="changeStatus(city.id)" v-if="city.status">Active</button>
								<button class="badge badge-danger" type="button" @click="changeStatus(city.id)" v-else>Deactive</button>
							</td>
							<td>{{city.created_at | date}}</td>
							<td>
								<nuxt-link :to="localePath({name: 'dashboard-admin-address-city-edit-id', params:{id: city.id}})" class="btn btn-icon btn-primary mx-2 my-2">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</nuxt-link>
								<button class="btn btn-icon btn-danger my-2" @click="deleteCity(city.id)">
									<i>
										<icon :icon="['fas', 'trash-alt']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No city found" />
						</td>
					</tbody>
				</table>
				<pagination :data="cities" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-city",
		middleware: "admin",
		head() {
			return {
				title: `City - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				cities: {},
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
			//Get city
			getCity() {
				this.$axios.get("city").then(
					(response) => {
						this.cities = response.data.cities;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("city?page=" + page).then((response) => {
					this.cities = response.data.cities;
				});
			},

			//Confirm Delete
			deleteCity(id) {
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
									.post("delete-city", { idList: list })
									.then(
										(response) => {
											this.select = [];
											$nuxt.$emit("triggerCity");
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
				this.cities.data.forEach((city) => {
					this.select.push(city.id);
				});
			},

			//Deselect all data
			deselectall() {
				this.select = [];
			},

			//Search item
			search() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$axios.post("search-city", this.searchOption).then(
						(response) => {
							this.cities = response.data.cities;
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
						this.$axios.post("search-city", this.searchOption).then(
							(response) => {
								this.cities = response.data.cities;
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

			changeStatus(id) {
				if (this.click) {
					this.click = false;
					this.$axios.post(`status-city/${id}`).then(
						(response) => {
							$nuxt.$emit("triggerCity");
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
			this.getCity();
			this.$nuxt.$on("triggerCity", () => {
				this.getCity();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerCity");
		},
	};
</script>