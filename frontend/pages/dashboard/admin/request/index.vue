<template>
	<div>
		<div class="section-header">
			<h1>All Requests</h1>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="action === 'delete' ? deleteRequest() : ''">
						<select class="form-control" v-model="action" required>
							<option value="">Select a option</option>
							<option value="delete">Delete selected items</option>
						</select>
						<button type="submit" class="btn btn-primary">Apply Action</button>
					</form>
					<div class="d-flex flex-wrap mb-3 grid-gap-5">
						<button type="button" class="btn btn-primary" @click="getStatusRequest('all')">All Request ({{all}})</button>
						<button type="button" class="btn btn-success" @click="getStatusRequest('active')">Active Request ({{active}})</button>
						<button type="button" class="btn btn-warning" @click="getStatusRequest('pending')">Pending Request ({{pending}})</button>
						<button type="button" class="btn btn-danger" @click="getStatusRequest('reject')">Reject Request ({{reject}})</button>
					</div>
				</div>
				<table class="table table-striped text-center table-responsive-lg">
					<thead>
						<tr>
							<th scope="col">
								<input class="form-check-input" type="checkbox" @click="select.length >= 1 ? deselectall():selectAll()" :checked="select.length >= 1">
							</th>
							<th scope="col">Requesting Name</th>
							<th scope="col">Message</th>
							<th scope="col">Name</th>
							<th scope="col">Logo</th>
							<th scope="col">Status</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody class="text-center" v-if="loading">
						<td colspan="8">
							<Loader />
						</td>
					</tbody>
					<tbody class="text-center" v-else-if="requests.data && requests.data.length >= 1">
						<tr v-for="request in requests.data" :key="request.id">
							<th scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="request.id">
							</th>
							<td>{{request.user.name}}</td>
							<td>{{request.message}}</td>
							<td>{{request.name}}</td>
							<td>
								<img :src="url + request.image" :alt="request.name" class="img-fluid max-w250" v-if="request.image">
								<span v-else>No image</span>
							</td>
							<td>
								<span class="badge badge-warning color-black" v-if="request.status === null">Pending</span>
								<span class="badge badge-success color-black" v-else-if="request.status">Approved</span>
								<span class="badge badge-danger" v-else>Rejected</span>
							</td>
							<td>
								<button class="btn btn-icon btn-success my-2" @click="approveRequest(request.id)">
									<i>
										<icon :icon="['fas', 'check']"></icon>
									</i>
								</button>
								<button class="btn btn-icon btn-danger my-2" @click="rejectRequest(request.id)">
									<i>
										<icon :icon="['fas', 'times']"></icon>
									</i>
								</button>
								|
								<button class="btn btn-icon btn-danger my-2" @click="deleteRequest(request.id)">
									<i>
										<icon :icon="['fas', 'trash-alt']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No request found" />
						</td>
					</tbody>
				</table>
				<pagination :data="requests" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-request",
		middleware: "admin",
		head() {
			return {
				title: `All Request - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				requests: {},
				select: [],
				requestStatus: "all",
				all: "",
				active: "",
				pending: "",
				reject: "",
				action: "",
				loading: true,
			};
		},
		methods: {
			//Get request
			getMyRequest() {
				this.$axios.get(`request/${this.requestStatus}`).then(
					(response) => {
						this.requests = response.data.requests;
						this.all = response.data.all;
						this.active = response.data.active;
						this.pending = response.data.pending;
						this.reject = response.data.reject;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios
					.get(`request/${this.requestStatus}?page=${page}`)
					.then((response) => {
						this.requests = response.data.requests;
					});
			},

			//Get Status Wise request
			getStatusRequest(name) {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.requestStatus = name;
					this.$axios.get(`request/${name}`).then(
						(response) => {
							this.requests = response.data.requests;
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

			// Select All Data
			selectAll() {
				this.select = [];
				this.requests.data.forEach((request) => {
					this.select.push(request.id);
				});
			},

			//Deselect all data
			deselectall() {
				this.select = [];
			},

			//Approve Request
			approveRequest(id) {
				if (this.click) {
					this.click = false;
					this.$swal
						.fire({
							title: "Are you sure?",
							text: "You want to approve this!",
							icon: "warning",
							showCancelButton: true,
							confirmButtonColor: "#6777ef",
							cancelButtonColor: "#fc544b",
							confirmButtonText: "Yes, approve it!",
						})
						.then((result) => {
							if (result.isConfirmed) {
								this.$axios.post(`approve-request/${id}`).then(
									(response) => {
										$nuxt.$emit("triggerAllRequest");
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

			//Reject Request
			rejectRequest(id) {
				if (this.click) {
					this.click = false;
					this.$swal
						.fire({
							title: "Are you sure?",
							text: "You want to reject this!",
							icon: "warning",
							showCancelButton: true,
							confirmButtonColor: "#6777ef",
							cancelButtonColor: "#fc544b",
							confirmButtonText: "Yes, reject it!",
						})
						.then((result) => {
							if (result.isConfirmed) {
								this.$axios.post(`reject-request/${id}`).then(
									(response) => {
										$nuxt.$emit("triggerAllRequest");
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
			deleteRequest(id) {
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
									.post("delete-request", { idList: list })
									.then(
										(response) => {
											this.select = [];
											$nuxt.$emit("triggerAllRequest");
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
		},

		created() {
			this.getMyRequest();
			this.$nuxt.$on("triggerAllRequest", () => {
				this.getMyRequest();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerAllRequest");
		},
	};
</script>