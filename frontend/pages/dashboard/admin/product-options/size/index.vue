<template>
	<div>
		<div class="section-header">
			<h1>Size</h1>
			<nuxt-link :to="localePath('dashboard-admin-product-options-size-create')" class="btn btn-primary">Create Size</nuxt-link>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="action === 'delete' ? deleteSize() : ''">
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
				<table class="table table-striped text-center table-responsive-md">
					<thead>
						<tr>
							<th scope="col">
								<input class="form-check-input" type="checkbox" @click="select.length >= 1 ? deselectall():selectAll()" :checked="select.length >= 1">
							</th>
							<th scope="col">Category</th>
							<th scope="col">Name</th>
							<th scope="col">Status</th>
							<th scope="col">Create At</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody class="text-center" v-if="loading">
						<td colspan="8">
							<Loader />
						</td>
					</tbody>
					<tbody class="text-center" v-else-if="sizes.data && sizes.data.length >= 1">
						<tr v-for="size in sizes.data" :key="size.id">
							<th scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="size.id">
							</th>
							<td>{{size.category.name}}
								<i>
									<icon :icon="['fas', 'arrow-right']"></icon>
								</i>
								{{size.sub_category.name}}
							</td>
							<td>{{size.name}}</td>
							<td>
								<button class="badge badge-success color-black" type="button" @click="changeStatus(size.id)" v-if="size.status">Active</button>
								<button class="badge badge-danger" type="button" @click="changeStatus(size.id)" v-else>Deactive</button>
							</td>
							<td>{{size.created_at | date}}</td>
							<td>
								<nuxt-link :to="localePath({name: 'dashboard-admin-product-options-size-edit-id', params:{id: size.id}})" class="btn btn-icon btn-primary mx-2 my-2">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</nuxt-link>
								<button class="btn btn-icon btn-danger my-2" @click="deleteSize(size.id)">
									<i>
										<icon :icon="['fas', 'trash-alt']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No size found" />
						</td>
					</tbody>
				</table>
				<pagination :data="sizes" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-sizes",
		middleware: "admin",
		head() {
			return {
				title: `Size - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				sizes: {},
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
			//Get Size
			getSize() {
				this.$axios.get("size").then(
					(response) => {
						this.sizes = response.data.sizes;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("size?page=" + page).then((response) => {
					this.sizes = response.data.sizes;
				});
			},

			//Confirm Delete
			deleteSize(id) {
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
									.post("delete-size", { idList: list })
									.then(
										(response) => {
											this.select = [];
											$nuxt.$emit("triggerSize");
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
				this.sizes.data.forEach((size) => {
					this.select.push(size.id);
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
					this.$axios.post("search-size", this.searchOption).then(
						(response) => {
							this.sizes = response.data.sizes;
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
						this.$axios.post("search-size", this.searchOption).then(
							(response) => {
								this.sizes = response.data.sizes;
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

			//Change Status
			changeStatus(id) {
				if (this.click) {
					this.click = false;
					this.$axios.post(`status-size/${id}`).then(
						(response) => {
							$nuxt.$emit("triggerSize");
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
			this.getSize();
			this.$nuxt.$on("triggerSize", () => {
				this.getSize();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerSize");
		},
	};
</script>