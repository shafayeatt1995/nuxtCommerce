<template>
	<div>
		<div class="section-header">
			<h1>Pending New Products</h1>
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
							<th scope="col">Price</th>
							<th scope="col">Discount Price</th>
							<th scope="col">Pending</th>
							<th scope="col">Visibility</th>
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
								<span v-if="parseFloat(product.variations.reduce((total, variation) => {return (variation.price < total.price) ? variation : total;}).price).toFixed(2) < parseFloat(product.variations.reduce((total, variation)=> Math.max(total, variation.price), 0)).toFixed(2)">
									{{defaultCurrencyIcon}}{{product.variations.reduce((total, variation)=> {return (total.price > variation.price) ? variation : total;}).price | currency }}
									-
								</span>
								{{defaultCurrencyIcon}}{{product.variations.reduce((total, variation)=> Math.max(total, variation.price), 0) | currency}}
							</td>
							<td>
								{{product.variations.some(variation => variation.discount === true) ? 'This product has discount' : 'This product has no discount' }}
							</td>
							<td>
								<span class="badge badge-danger" v-if="product.suspend">Suspended</span>
								<span class="badge badge-warning color-black" v-else-if="product.pending">Pending</span>
								<span class="badge badge-success color-black" v-else>Approved</span>
							</td>
							<td>
								<span class="badge badge-success color-black" v-if="product.status">Active</span>
								<span class="badge badge-danger" v-else>Hide</span>
							</td>
							<td>
								{{product.created_at | normalDate}}
							</td>
							<td>
								<button class="btn btn-primary" type="button" @click="viewProduct(product)">
									<i>
										<icon :icon="['fas', 'eye']"></icon>
									</i>
								</button>
								<button class="btn btn-success" type="button" @click="approveProduct(product.id)">
									<i>
										<icon :icon="['fas', 'check']"></icon>
									</i>
								</button>
								<button class="btn btn-danger" type="button" @click="deleteProduct(product.id)">
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

		<transition name="fadeDelay" mode="out-in">
			<DashboardModal v-if="dashboardModal === 'product'" customClass="modal-lg modal-dialog-scrollable" title="Product Details">
				<div slot="body">
					<p>Product Image</p>
					<div class="image-frame grid">
						<img :data-src="url + image.image" v-for="(image, key) in productModal.product_images" :alt="productModal.name" class="img-fluid" :key="`image-${key}`" v-lazy-load>
					</div>
					<table class="table table-striped text-center table-responsive-md">
						<thead>
							<tr>
								<th scope="col">Name</th>
								<th scope="col">Information</th>
							</tr>
						</thead>
						<tbody class="text-center">
							<tr>
								<td>Product Name</td>
								<td>{{productModal.name}}</td>
							</tr>
							<tr>
								<td>Store Name</td>
								<td>{{productModal.store.name}}</td>
							</tr>
							<tr>
								<td>Category</td>
								<td>
									{{productModal.category.name}}
									<i>
										<icon :icon="['fas', 'arrow-right']"></icon>
									</i>
									{{productModal.sub_category.name}}
									<i>
										<icon :icon="['fas', 'arrow-right']"></icon>
									</i>
									{{productModal.child_category.name}}
								</td>
							</tr>
							<tr>
								<td>Commission</td>
								<td>
									<span v-if="productModal.sub_category.commission.type === null">Free</span>
									<span v-else-if="productModal.sub_category.commission.type === true">Fixed</span>
									<span v-else>Percent</span>
									<i>
										<icon :icon="['fas', 'arrow-right']"></icon>
									</i>
									{{productModal.sub_category.commission.commission}}{{productModal.sub_category.commission.type === false ? '%' : ''}}
								</td>
							</tr>
							<tr>
								<td>Brand</td>
								<td>
									<img :data-src="url + productModal.brand.logo" :alt="productModal.brand.name" class="img-fluid max-w150 max-h150" v-lazy-load>
									<span class="d-block">
										{{productModal.brand.name}}
									</span>
								</td>
							</tr>
							<tr>
								<td>Features</td>
								<td>
									<ul>
										<li class="text-left" v-for="(feature, key) in JSON.parse(productModal.features)" :key="`features-${key}`">{{feature}}</li>
									</ul>
								</td>
							</tr>
							<tr>
								<td>specifications</td>
								<td>
									<ul>
										<li class="d-flex justify-content-around" v-for="(specification, key) in JSON.parse(productModal.specifications)" :key="`specification-${key}`">
											<span>
												{{specification.title}}
											</span>
											<span>
												{{specification.name}}
											</span>
										</li>
									</ul>
								</td>
							</tr>
							<tr>
								<td>Items</td>
								<td>
									<ul>
										<li class="text-left" v-for="(item, key) in JSON.parse(productModal.items)" :key="`item-${key}`">{{item}}</li>
									</ul>
								</td>
							</tr>
							<tr>
								<td>Weight</td>
								<td>{{productModal.weight}}</td>
							</tr>
							<tr>
								<td>Rating</td>
								<td>
									<i>
										<icon :icon="['fas', 'star']"></icon>
									</i>
									{{productModal.rating}}
								</td>
							</tr>
							<tr>
								<td>Tags</td>
								<td>
									<ul class="d-flex grid-gap-5 justify-content-center">
										<li class="badge badge-primary" v-for="(tag, key) in JSON.parse(productModal.tags)" :key="`tag-${key}`">{{tag}}</li>
									</ul>
								</td>
							</tr>
							<tr>
								<td>Meta Content</td>
								<td class="text-left">{{productModal.meta}}</td>
							</tr>
							<tr>
								<td>Visibility</td>
								<td>
									<span class="badge badge-success color-black" v-if="productModal.status">Active</span>
									<span class="badge badge-danger" v-else>Hide</span>
								</td>
							</tr>
							<tr>
								<td>Pending</td>
								<td>
									<span class="badge badge-warning color-black" v-if="productModal.status">Pending</span>
									<span class="badge badge-success color-blavk" v-else>Approve</span>
								</td>
							</tr>
							<tr>
								<td>Suspend</td>
								<td>
									<span class="badge badge-daanger color-black" v-if="productModal.suspend">Yes</span>
									<span class="badge badge-success color-black" v-else>No</span>
								</td>
							</tr>
							<tr>
								<td>Meta Content</td>
								<td>{{productModal.meta}}</td>
							</tr>
						</tbody>
					</table>
					<hr>
					<h3 class="text-center">Variations</h3>
					<table class="table table-striped text-center table-responsive-md">
						<thead>
							<tr>
								<th scope="col">Color</th>
								<th scope="col">Size</th>
								<th scope="col">Price</th>
								<th scope="col">Discount</th>
								<th scope="col">invertory</th>
							</tr>
						</thead>
						<tbody class="text-center">
							<tr v-for="(variation, key) in productModal.variations" :key="key">
								<td>
									{{variation.color.name}}
									<span class="color-bar" :style="`background: ${variation.color.code}`"></span>
								</td>
								<td>{{variation.size.name}}</td>
								<td>{{variation.price}}</td>
								<td>
									<div v-if="variation.special_price !== null">
										<p><b>Price:</b> {{variation.special_price}}</p>
										<p><b>Date:</b> {{variation.start_date | normalDate}}
											<i>
												<icon :icon="['fas', 'arrow-right']"></icon>
											</i>
											{{variation.end_date | normalDate}}
										</p>
									</div>
									<p v-else>
										No Discount
									</p>
								</td>
								<td>
									<div v-if="variation.quantity !== null">
										<p>SKU: {{variation.sku}}</p>
										<p>Qty: {{variation.quantity}}</p>
									</div>
									<p v-else>
										No Inventory
									</p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div slot="footer-btn">
					<button class="btn btn-success color-black" type="button" @click="approveProduct(productModal.id)">
						Approve
					</button>
				</div>
			</DashboardModal>
		</transition>
	</div>
</template>
<script>
	export default {
		name: "pending-products",
		middleware: "admin",
		head() {
			return {
				title: `Pending Products - ${this.appName}`,
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
				productModal: {},
				loading: true,
			};
		},
		methods: {
			//Get Product
			getProduct() {
				this.$axios.get("product-pending").then(
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
				this.$axios.get("product-pending?page=" + page).then((response) => {
					this.products = response.data.products;
				});
			},

			//Approve Product
			approveProduct(id) {
				if (this.click) {
					this.click = false;
					this.$swal
						.fire({
							title: "Are you sure?",
							text: "You want to approve this product",
							icon: "warning",
							showCancelButton: true,
							confirmButtonColor: "#6777ef",
							cancelButtonColor: "#fc544b",
							confirmButtonText: "Yes, Approve it!",
						})
						.then((result) => {
							if (result.isConfirmed) {
								this.$axios.post(`approve-product/${id}`).then(
									(response) => {
										$nuxt.$emit("triggerPendingProduct");
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
								this.$axios.post(`delete-product/${id}`).then(
									(response) => {
										$nuxt.$emit("triggerPendingProduct");
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

			//Search Pending Product
			search() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$axios
						.post("search-product-pending", this.searchOption)
						.then(
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
						this.$axios
							.post("search-product-pending", this.searchOption)
							.then(
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

			//View Product
			viewProduct(product) {
				this.productModal = product;
				this.$store.dispatch("dashboardModal", "product");
			},
		},

		created() {
			this.getProduct();
			this.$nuxt.$on("triggerPendingProduct", () => {
				this.getProduct();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerPendingProduct");
		},
	};
</script>
