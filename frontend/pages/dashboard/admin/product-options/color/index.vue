<template>
	<div>
		<div class="section-header">
			<h1>Color</h1>
			<nuxt-link :to="localePath('dashboard-admin-product-options-color-create')" class="btn btn-primary">Create Color</nuxt-link>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="action === 'delete' ? deleteColor() : ''">
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
							<th scope="col">Color Plate</th>
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
					<tbody class="text-center" v-else-if="colors.data && colors.data.length >= 1">
						<tr v-for="color in colors.data" :key="color.id">
							<th scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="color.id">
							</th>
							<td>{{color.name}}</td>
							<td><span class="color-plate" :style="`background: ${color.code};`"></span>{{color.code}}</td>
							<td>
								<button class="badge badge-success color-black" type="button" @click="changeStatus(color.id)" v-if="color.status">Active</button>
								<button class="badge badge-danger" type="button" @click="changeStatus(color.id)" v-else>Deactive</button>
							</td>
							<td>{{color.created_at | date}}</td>
							<td>
								<nuxt-link :to="localePath({name: 'dashboard-admin-product-options-color-edit-id', params:{id: color.id}})" class="btn btn-icon btn-primary mx-2 my-2">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</nuxt-link>
								<button class="btn btn-icon btn-danger my-2" @click="deleteColor(color.id)">
									<i>
										<icon :icon="['fas', 'trash-alt']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No color found" />
						</td>
					</tbody>
				</table>
				<pagination :data="colors" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-colors",
		head() {
			return {
				title: `Color - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				colors: {},
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
			//Get Color
			getColor() {
				this.$axios.get("color").then(
					(response) => {
						this.colors = response.data.colors;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("color?page=" + page).then((response) => {
					this.colors = response.data.colors;
				});
			},

			//Confirm Delete
			deleteColor(id) {
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
									.post("delete-color", { idList: list })
									.then(
										(response) => {
											this.select = [];
											$nuxt.$emit("triggerColor");
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
				this.colors.data.forEach((color) => {
					this.select.push(color.id);
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
					this.$axios.post("search-color", this.searchOption).then(
						(response) => {
							this.colors = response.data.colors;
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

			//Change Status
			changeStatus(id) {
				if (this.click) {
					this.click = false;
					this.$axios.post(`status-color/${id}`).then(
						(response) => {
							$nuxt.$emit("triggerColor");
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
			this.getColor();
			this.$nuxt.$on("triggerColor", () => {
				this.getColor();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerColor");
		},
	};
</script>