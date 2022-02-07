<template>
	<div>
		<div class="section-header">
			<h1>Categories</h1>
			<nuxt-link :to="localePath('dashboard-admin-category-create')" class="btn btn-primary">Create Category</nuxt-link>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="action === 'delete' ? deleteCategory() : ''">
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
					<tbody class="text-center" v-else-if="categories.data && categories.data.length >= 1">
						<tr v-for="category in categories.data" :key="category.id">
							<th scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="category.id">
							</th>
							<td>{{category.name}}</td>
							<td>
								<button class="badge badge-success color-black" type="button" @click="changeStatus(category.id)" v-if="category.status">Active</button>
								<button class="badge badge-danger" type="button" @click="changeStatus(category.id)" v-else>Deactive</button>
							</td>
							<td>{{category.created_at | date}}</td>
							<td>
								<nuxt-link :to="localePath({name: 'dashboard-admin-category-edit-id', params:{id: category.id}})" class="btn btn-icon btn-primary mx-2 my-2">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</nuxt-link>
								<button class="btn btn-icon btn-danger my-2" @click="deleteCategory(category.id)">
									<i>
										<icon :icon="['fas', 'trash-alt']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No category found" />
						</td>
					</tbody>
				</table>
				<pagination :data="categories" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-categories",
		middleware: "admin",
		head() {
			return {
				title: `Categories - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				categories: {},
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
			//Get category
			getCategory() {
				this.$axios.get("category").then(
					(response) => {
						this.categories = response.data.categories;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("category?page=" + page).then((response) => {
					this.categories = response.data.categories;
				});
			},

			//Confirm Delete
			deleteCategory(id) {
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
									.post("delete-category", { idList: list })
									.then(
										(response) => {
											this.select = [];
											$nuxt.$emit("triggerCategory");
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
				this.categories.data.forEach((category) => {
					this.select.push(category.id);
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
					this.$axios.post("search-category", this.searchOption).then(
						(response) => {
							this.categories = response.data.categories;
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
					this.$axios.post(`status-category/${id}`).then(
						(response) => {
							$nuxt.$emit("triggerCategory");
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
			this.getCategory();
			this.$nuxt.$on("triggerCategory", () => {
				this.getCategory();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerCategory");
		},
	};
</script>
