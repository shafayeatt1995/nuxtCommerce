<template>
	<div>
		<div class="section-header">
			<h1>States</h1>
			<nuxt-link :to="localePath('dashboard-admin-address-state-create')" class="btn btn-primary">Create State</nuxt-link>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="action === 'delete' ? deleteState() : ''">
						<select class="form-control" v-model="action">
							<option value="">Select a option</option>
							<option value="delete">Delete selected items</option>
						</select>
						<button type="submit" class="btn btn-primary">Apply Action</button>
					</form>
					<form class="d-flex mb-3" @submit.prevent="search">
						<input class="form-control" type="text" placeholder="Search..." v-model="searchOption.keyword" @keyup="instantSearch">
						<select class="form-control" v-model="searchOption.collum">
							<option value="name">Search by State</option>
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
							<th scope="col">Country</th>
							<th scope="col">State</th>
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
					<tbody class="text-center" v-else-if="states.data && states.data.length >= 1">
						<tr v-for="state in states.data" :key="state.id">
							<th scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="state.id">
							</th>
							<td>{{state.country.name}}</td>
							<td>{{state.name}}</td>
							<td>
								<button class="badge badge-success color-black" type="button" @click="changeStatus(state.id)" v-if="state.status">Active</button>
								<button class="badge badge-danger" type="button" @click="changeStatus(state.id)" v-else>Deactive</button>
							</td>
							<td>{{state.created_at | date}}</td>
							<td>
								<nuxt-link :to="localePath({name: 'dashboard-admin-address-state-edit-id', params:{id: state.id}})" class="btn btn-icon btn-primary mx-2 my-2">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</nuxt-link>
								<button class="btn btn-icon btn-danger my-2" @click="deleteState(state.id)">
									<i>
										<icon :icon="['fas', 'trash-alt']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No state found" />
						</td>
					</tbody>
				</table>
				<pagination :data="states" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-state",
		middleware: "admin",
		head() {
			return {
				title: `State - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				states: {},
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
			//Get state
			getState() {
				this.$axios.get("state").then(
					(response) => {
						this.states = response.data.states;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("state?page=" + page).then((response) => {
					this.states = response.data.states;
				});
			},

			//Confirm Delete
			deleteState(id) {
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
									.post("delete-state", { idList: list })
									.then(
										(response) => {
											this.select = [];
											$nuxt.$emit("triggerState");
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
				this.states.data.forEach((state) => {
					this.select.push(state.id);
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
					this.$axios.post("search-state", this.searchOption).then(
						(response) => {
							this.states = response.data.states;
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
						this.$axios.post("search-state", this.searchOption).then(
							(response) => {
								this.states = response.data.states;
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
					this.$axios.post(`status-state/${id}`).then(
						(response) => {
							$nuxt.$emit("triggerState");
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
			this.getState();
			this.$nuxt.$on("triggerState", () => {
				this.getState();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerState");
		},
	};
</script>