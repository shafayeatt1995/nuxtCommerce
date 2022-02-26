<template>
	<div>
		<div class="section-header">
			<h1>products</h1>
			<nuxt-link :to="localePath('dashboard-seller-product-create')" class="btn btn-primary">Create Product</nuxt-link>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-end flex-lg-row flex-column">
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
				<table class="table table-striped text-center table-responsive-md">
					<thead>
						<tr>
							<th scope="col">Name</th>
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
					<tbody class="text-center" v-else-if="products.data && products.data.length >= 1">
						<tr v-for="product in products.data" :key="product.id">
							<td>{{product.name}}</td>
							<td>
								<button class="badge badge-success color-black" type="button" @click="changeStatus(product.id)" v-if="product.status">Active</button>
								<button class="badge badge-danger" type="button" @click="changeStatus(product.id)" v-else>Deactive</button>
							</td>
							<td>{{product.created_at | date}}</td>
							<td>
								<nuxt-link :to="localePath({name: 'dashboard-seller-product-edit-id', params:{id: product.id}})" class="btn btn-icon btn-primary mx-2 my-2">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</nuxt-link>
								<button class="btn btn-icon btn-danger my-2" @click="deleteProduct(product.id)">
									<i>
										<icon :icon="['fas', 'trash-alt']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No product found" />
						</td>
					</tbody>
				</table>
				<pagination :data="products" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-products",
		middleware: "seller",
		head() {
			return {
				title: `products - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				products: {},
				searchOption: {
					keyword: "",
					collum: "name",
				},
				loading: true,
			};
		},
		methods: {
			//Get Product
			getProduct() {
				this.$axios.get("product").then(
					(response) => {
						this.products = response.data.products;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("product?page=" + page).then((response) => {
					this.products = response.data.products;
				});
			},

			//Confirm Delete
			deleteProduct(id) {
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
								this.$axios.post(`delete-category/${id}`).then(
									(response) => {
										$nuxt.$emit("triggerProduct");
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

			search() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$axios.post("search-category", this.searchOption).then(
						(response) => {
							this.products = response.data.products;
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
						this.$axios.post("search-category", this.searchOption).then(
							(response) => {
								this.products = response.data.products;
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
					this.$axios.post(`status-product/${id}`).then(
						(response) => {
							$nuxt.$emit("triggerProduct");
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
			this.getProduct();
			this.$nuxt.$on("triggerProduct", () => {
				this.getProduct();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerProduct");
		},
	};
</script>
