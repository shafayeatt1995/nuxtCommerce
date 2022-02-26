<template>
	<div>
		<div class="section-header">
			<h1>My Requests</h1>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="action === 'delete' ? deleteRequest() : ''">
						<select class="form-control" v-model="action">
							<option value="">Select a option</option>
							<option value="delete">Delete selected items</option>
						</select>
						<button type="submit" class="btn btn-primary">Apply Action</button>
					</form>
				</div>
				<table class="table table-striped text-center table-responsive-lg">
					<thead>
						<tr>
							<th scope="col">
								<input class="form-check-input" type="checkbox" @click="select.length >= 1 ? deselectall():selectAll()" :checked="select.length >= 1">
							</th>
							<th scope="col">Logo</th>
							<th scope="col">Message</th>
							<th scope="col">Name</th>
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
							<td><img :src="url + request.image" :alt="request.name" class="img-fluid max-w250" v-if="request.image"></td>
							<td>{{request.message}}</td>
							<td>{{request.name}}</td>
							<td>
								<span class="badge badge-warning color-black" v-if="request.status === null">Pending</span>
								<span class="badge badge-success color-black" v-else-if="request.status">Approved</span>
								<span class="badge badge-danger" v-else>Rejected</span>
							</td>
							<td>
								<button class="btn btn-icon btn-danger my-2" @click="deleteRequest(request.id)" v-if="request.status === null">
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
		name: "my-request",
		middleware: "seller",
		head() {
			return {
				title: `My Request - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				requests: {},
				select: [],
				action: "",
				loading: true,
			};
		},
		methods: {
			//Get Plan
			getMyRequest() {
				this.$axios.get("my-request").then(
					(response) => {
						this.requests = response.data.requests;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("my-request?page=" + page).then((response) => {
					this.requests = response.data.requests;
				});
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
									.post("delete-my-request", { idList: list })
									.then(
										(response) => {
											this.select = [];
											$nuxt.$emit("triggerMyRequest");
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
				this.requests.data.forEach((request) => {
					this.select.push(request.id);
				});
			},

			//Deselect all data
			deselectall() {
				this.select = [];
			},
		},

		created() {
			this.getMyRequest();
			this.$nuxt.$on("triggerMyRequest", () => {
				this.getMyRequest();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerMyRequest");
		},
	};
</script>