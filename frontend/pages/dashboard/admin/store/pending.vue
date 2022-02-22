<template>
	<div>
		<div class="section-header">
			<h1>Store Request</h1>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="storeStatus">
						<select class="form-control" v-model="action" required>
							<option value="">Select a option</option>
							<option value="approve">Approve selected store</option>
							<option value="decline">Decline selected store</option>
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
							<th scope="col">Logo</th>
							<th scope="col">Name</th>
							<th scope="col">Phone</th>
							<th scope="col">Regestration Date</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody class="text-center" v-if="loading">
						<td colspan="8">
							<Loader />
						</td>
					</tbody>
					<tbody class="text-center" v-else-if="stores.data && stores.data.length >= 1">
						<tr v-for="store in stores.data" :key="store.id">
							<th scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="store.id">
							</th>
							<td>
								<img :src="url + store.logo" :alt="store.name" class="img-fluid max-h250 max-w250">
							</td>
							<td>{{store.name}}</td>
							<td>{{store.phone}}</td>
							<td>{{store.created_at | normalDate}}</td>
							<td>
								<button class="btn btn-icon btn-success my-2" @click="changeStoreStatus(store.id, true)">
									<i>
										<icon :icon="['fas', 'check']"></icon>
									</i>
								</button>
								<button class="btn btn-icon btn-danger my-2" @click="changeStoreStatus(store.id, false)">
									<i>
										<icon :icon="['fas', 'trash-alt']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No pending store request found" />
						</td>
					</tbody>
				</table>
				<pagination :data="stores" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-pending-store",
		middleware: "admin",
		head() {
			return {
				title: `Pending Store - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				stores: {},
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
			//Get store
			getPendingStore() {
				this.$axios.get("pending-store").then(
					(response) => {
						this.stores = response.data.stores;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("pending-store?page=" + page).then((response) => {
					this.categories = response.data.categories;
				});
			},

			//Change Status
			storeStatus() {
				if (this.click) {
					this.click = false;
					this.$swal
						.fire({
							title: "Are you sure?",
							icon: "warning",
							showCancelButton: true,
							confirmButtonColor: "#6777ef",
							cancelButtonColor: "#fc544b",
							confirmButtonText:
								this.action === "approve"
									? "Yes, approve it!"
									: "Yes, decline it!",
						})
						.then((result) => {
							if (result.isConfirmed) {
								let data = {
									idList: this.select,
									action: this.action,
								};
								this.$axios.post("change-store-status", data).then(
									(response) => {
										this.select = [];
										$nuxt.$emit("triggerPendingStore");
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

			//Change Status
			changeStoreStatus(id, status) {
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
							confirmButtonText: status
								? "Yes, approve it!"
								: "Yes, decline it!",
						})
						.then((result) => {
							if (result.isConfirmed) {
								let data = {
									idList: [id],
									action: status ? "approve" : "decline",
								};
								this.$axios.post("change-store-status", data).then(
									(response) => {
										$nuxt.$emit("triggerPendingStore");
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
				this.stores.data.forEach((store) => {
					this.select.push(store.id);
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
					this.$axios
						.post("search-pending-store", this.searchOption)
						.then(
							(response) => {
								this.stores = response.data.stores;
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
		},

		created() {
			this.getPendingStore();
			this.$nuxt.$on("triggerPendingStore", () => {
				this.getPendingStore();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerPendingStore");
		},
	};
</script>
