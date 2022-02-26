<template>
	<div>
		<div class="section-header">
			<h1>Brands</h1>
			<nuxt-link :to="localePath('dashboard-admin-brand-create')" class="btn btn-primary">Create Brand</nuxt-link>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="action === 'delete' ? deleteBrand() : ''">
						<select class="form-control" v-model="action">
							<option value="">Select a option</option>
							<option value="delete">Delete selected items</option>
						</select>
						<button type="submit" class="btn btn-primary">Apply Action</button>
					</form>
					<form class="d-flex mb-3" @submit.prevent="search">
						<input class="form-control" type="text" placeholder="Search..." v-model="searchOption.keyword" @keyup="instantSearch">
						<select class="form-control" v-model="searchOption.collum">
							<option value="name">Search by name</option>
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
							<th scope="col">Logo</th>
							<th scope="col">Name</th>
							<th scope="col">Create At</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody class="text-center" v-if="loading">
						<td colspan="8">
							<Loader />
						</td>
					</tbody>
					<tbody class="text-center" v-else-if="brands.data && brands.data.length >= 1">
						<tr v-for="brand in brands.data" :key="brand.id">
							<th scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="brand.id">
							</th>
							<td><img :src="url + brand.logo" :alt="brand.name" class="img-fluid"></td>
							<td>{{brand.name}}</td>
							<td>{{brand.created_at | date}}</td>
							<td>
								<nuxt-link :to="localePath({name: 'dashboard-admin-brand-edit-id', params:{id: brand.id}})" class="btn btn-icon btn-primary mx-2 my-2">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</nuxt-link>
								<button class="btn btn-icon btn-danger my-2" @click="deleteBrand(brand.id)">
									<i>
										<icon :icon="['fas', 'trash-alt']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No brand found" />
						</td>
					</tbody>
				</table>
				<pagination :data="brands" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-brands",
		middleware: "admin",
		head() {
			return {
				title: `Brands - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				brands: {},
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
			//Get Plan
			getBrand() {
				this.$axios.get("brand").then(
					(response) => {
						this.brands = response.data.brands;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("brand?page=" + page).then((response) => {
					this.brands = response.data.brands;
				});
			},

			//Confirm Delete
			deleteBrand(id) {
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
									.post("delete-brand", { idList: list })
									.then(
										(response) => {
											this.select = [];
											$nuxt.$emit("triggerBrand");
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
				this.brands.data.forEach((brand) => {
					this.select.push(brand.id);
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
					this.$axios.post("search-brand", this.searchOption).then(
						(response) => {
							this.brands = response.data.brands;
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
						this.$axios.post("search-brand", this.searchOption).then(
							(response) => {
								this.brands = response.data.brands;
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
		},

		created() {
			this.getBrand();
			this.$nuxt.$on("triggerBrand", () => {
				this.getBrand();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerBrand");
		},
	};
</script>