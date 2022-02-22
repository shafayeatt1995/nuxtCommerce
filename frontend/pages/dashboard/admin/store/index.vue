<template>
	<div>
		<div class="section-header">
			<h1>Store</h1>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<div class="d-flex flex-wrap mb-3 grid-gap-5">
						<button type="button" class="btn btn-primary" @click="getMyStore('all')">All Store ({{all}})</button>
						<button type="button" class="btn btn-success" @click="getMyStore('active')">Active Store ({{active}})</button>
						<button type="button" class="btn btn-warning" @click="getMyStore('pending')">Pending Store ({{pending}})</button>
						<button type="button" class="btn btn-danger" @click="getMyStore('suspend')">Suspend Store ({{suspend}})</button>
					</div>
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
							<th scope="col">Logo</th>
							<th scope="col">Owner Name</th>
							<th scope="col">Store Name</th>
							<th scope="col">Phone</th>
							<th scope="col">Status</th>
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
							<td>
								<img :src="url + store.logo" :alt="store.name" class="img-fluid max-h250 max-w250">
							</td>
							<td>{{store.user.name}}</td>
							<td>{{store.name}}</td>
							<td>{{store.phone}}</td>
							<td>
								<span class="badge badge-warning color-black" v-if="store.pending">Pending</span>
								<button class="badge badge-danger" type="button" @click="changeStatus(store.id)" v-else-if="store.suspend">Suspend</button>
								<button class="badge badge-success color-black" type="button" @click="changeStatus(store.id)" v-else>Active</button>
							</td>
							<td>
								<nuxt-link :to="localePath({name: 'dashboard-admin-store-edit-id', params: {id: store.id }})" class="btn btn-icon btn-primary my-2" @click="editStore(store.id)">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</nuxt-link>
								<button class="btn btn-icon btn-success my-2" @click="viewStore(store)">
									<i>
										<icon :icon="['fas', 'eye']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No  store found" />
						</td>
					</tbody>
				</table>
				<pagination :data="stores" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>

			</div>
		</div>
		<transition name="fade" mode="out-in">
			<DashboardModal v-if="dashboardModal">
				<table class="table table-striped text-center table-responsive-md">
					<thead>
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Information</th>
						</tr>
					</thead>
					<tbody class="text-center">
						<tr>
							<td>Store Logo</td>
							<td><img :src="url + storeModal.logo" :alt="storeModal.name" class="img-fluid max-h150 max-w250"></td>
						</tr>
						<tr>
							<td>Store Owner Name</td>
							<td>{{storeModal.user.name}}</td>
						</tr>
						<tr>
							<td>Store Name</td>
							<td>{{storeModal.name}}</td>
						</tr>
						<tr>
							<td>Store Email</td>
							<td>{{storeModal.user.email}}</td>
						</tr>
						<tr>
							<td>Store Phone</td>
							<td>{{storeModal.phone}}</td>
						</tr>
						<tr>
							<td>Store Address</td>
							<td>{{storeModal.address}}</td>
						</tr>
						<tr>
							<td>Store Status</td>
							<td>
								<span class="badge badge-warning color-black" v-if="storeModal.pending">Pending</span>
								<button class="badge badge-danger" type="button" @click="changeStatus(storeModal.id)" v-else-if="storeModal.suspend">Suspend</button>
								<button class="badge badge-success color-black" type="button" @click="changeStatus(storeModal.id)" v-else>Active</button>
							</td>
						</tr>
					</tbody>
				</table>
			</DashboardModal>
		</transition>
	</div>
</template>
<script>
	export default {
		name: "all-store",
		middleware: "admin",
		head() {
			return {
				title: `Store - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				stores: {},
				storeModal: {},
				storeStatus: "all",
				active: "",
				all: "",
				pending: "",
				suspend: "",
				searchOption: {
					keyword: "",
					collum: "name",
				},
				modal: false,
				loading: true,
			};
		},
		methods: {
			//Get store
			getStore() {
				this.$axios.get("store-all").then(
					(response) => {
						this.stores = response.data.stores;
						this.active = response.data.active;
						this.all = response.data.all;
						this.pending = response.data.pending;
						this.suspend = response.data.suspend;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios
					.get(`store-${this.storeStatus}?page=${page}`)
					.then((response) => {
						this.stores = response.data.stores;
					});
			},

			// Open Modal
			viewStore(store) {
				this.storeModal = store;
				this.$store.dispatch("dashboardModal", true);
			},

			// Change Store status
			changeStatus(id) {
				if (this.click) {
					this.click = false;
					this.$axios.post(`store-status/${id}`).then(
						(response) => {
							$nuxt.$emit("triggerStore");
							this.click = true;
						},
						(error) => {
							$nuxt.$emit("error", error);
							this.click = true;
						}
					);
				}
			},

			search() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$axios.post("search-store", this.searchOption).then(
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

			//Get Status wise store
			getMyStore(name) {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.storeStatus = name;
					this.$axios.get(`store-${name}`).then(
						(response) => {
							this.stores = response.data.stores;
							this.loading = false;
							this.click = true;
						},
						(error) => {
							$nuxt.$emit("error", error);
							this.loading = false;
							this.click = true;
						}
					);
				}
			},
		},

		created() {
			this.getStore();
			this.$nuxt.$on("triggerStore", () => {
				this.getStore();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerStore");
		},
	};
</script>
