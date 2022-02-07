<template>
	<div>
		<div class="section-header">
			<h1>Material</h1>
			<nuxt-link :to="localePath('dashboard-admin-product-options-material-create')" class="btn btn-primary">Create Material</nuxt-link>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="action === 'delete' ? deleteMaterial() : ''">
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
				<table class="table table-striped text-center table-responsive-lg">
					<thead>
						<tr>
							<th scope="col">
								<input class="form-check-input" type="checkbox" @click="select.length >= 1 ? deselectall():selectAll()" :checked="select.length >= 1">
							</th>
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
					<tbody class="text-center" v-else-if="materials.data && materials.data.length >= 1">
						<tr v-for="material in materials.data" :key="material.id">
							<th scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="material.id">
							</th>
							<td>{{material.name}}</td>
							<td>
								<button class="badge badge-success color-black" type="button" @click="changeStatus(material.id)" v-if="material.status">Active</button>
								<button class="badge badge-danger" type="button" @click="changeStatus(material.id)" v-else>Deactive</button>
							</td>
							<td>{{material.created_at | date}}</td>
							<td>
								<nuxt-link :to="localePath({name: 'dashboard-admin-product-options-material-edit-id', params:{id: material.id}})" class="btn btn-icon btn-primary mx-2 my-2">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</nuxt-link>
								<button class="btn btn-icon btn-danger my-2" @click="deleteMaterial(material.id)">
									<i>
										<icon :icon="['fas', 'trash-alt']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No material found" />
						</td>
					</tbody>
				</table>
				<pagination :data="materials" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-materials",
		middleware: "admin",
		head() {
			return {
				title: `Material - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				materials: {},
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
			//Get Material
			getMaterial() {
				this.$axios.get("material").then(
					(response) => {
						this.materials = response.data.materials;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("material?page=" + page).then((response) => {
					this.materials = response.data.materials;
				});
			},

			//Confirm Delete
			deleteMaterial(id) {
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
									.post("delete-material", { idList: list })
									.then(
										(response) => {
											this.select = [];
											$nuxt.$emit("triggerMaterial");
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
				this.materials.data.forEach((material) => {
					this.select.push(material.id);
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
					this.$axios.post("search-material", this.searchOption).then(
						(response) => {
							this.materials = response.data.materials;
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
					this.$axios.post(`status-material/${id}`).then(
						(response) => {
							$nuxt.$emit("triggerMaterial");
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
			this.getMaterial();
			this.$nuxt.$on("triggerMaterial", () => {
				this.getMaterial();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerMaterial");
		},
	};
</script>