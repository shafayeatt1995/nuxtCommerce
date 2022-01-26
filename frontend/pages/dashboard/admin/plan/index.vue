<template>
	<div>
		<div class="section-header">
			<h1>All Plans</h1>
			<nuxt-link :to="localePath('dashboard-admin-plan-create')" class="btn btn-primary">Create Plan</nuxt-link>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100  justify-content-between flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="action === 'delete' ? deletePlan() : ''">
						<select class="form-control" v-model="action">
							<option value="">Select a option</option>
							<option value="delete">Delete selected item</option>
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
							<th scope="col">Duration</th>
							<th scope="col">Price</th>
							<th scope="col">Discount Status</th>
							<th scope="col">User</th>
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
					<tbody class="text-center" v-else-if="plans.data && plans.data.length >= 1">
						<tr v-for="plan in plans.data" :key="plan.id">
							<th scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="plan.id">
							</th>
							<td>{{plan.name}}</td>
							<td>{{plan.duration_name}} ({{plan.duration_day}} Days)</td>
							<td>
								<span v-if="plan.discount_price">${{plan.discount_price | currency}} <del class="ml-1">${{plan.price | currency}}</del></span>
								<span v-else>${{plan.price | currency}}</span>
							</td>
							<td>
								<span class="badge" :class="new Date() < new Date(plan.discount_start) ? 'badge-warning color-black' : new Date() > new Date(plan.discount_end) ? 'badge-danger' : 'badge-success color-black'" v-if="plan.discount_price">{{new Date() < new Date(plan.discount_start) ? `Discount will start after ${Math.abs(Math.floor((new Date() - new Date(plan.discount_start)) / (1000 * 60 * 60 * 24)))} days` : new Date() > new Date(plan.discount_end) ? 'Discount Expire' : 'Discounts Running'}}</span>
								<span class="badge badge-danger" v-else>No Discount</span>
							</td>
							<td>0</td>
							<td>
								<span class="badge badge-success color-black" v-if="plan.status">Active</span>
								<span class="badge badge-danger" v-else>Deactive</span>
							</td>
							<td>{{plan.created_at | date}}</td>
							<td>
								<nuxt-link :to="localePath({name: 'dashboard-admin-plan-edit-id', params:{id: plan.id}})" class="btn btn-icon btn-primary mx-2">
									<i>
										<icon :icon="['fas', 'edit']"></icon>
									</i>
								</nuxt-link>
								<button class="btn btn-icon btn-danger" @click="deletePlan(plan.id)">
									<i>
										<icon :icon="['fas', 'trash-alt']"></icon>
									</i>
								</button>
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No plan found" />
						</td>
					</tbody>
				</table>
				<pagination :data="plans" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-plan",
		head() {
			return {
				title: `All Plan - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				plans: {},
				select: [],
				action: "",
				deleteType: "",
				searchOption: {
					keyword: "",
					collum: "name",
				},
				loading: true,
			};
		},
		methods: {
			//Get Plan
			getPlan() {
				this.$axios.get("plan").then(
					(response) => {
						this.plans = response.data.plans;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("plan?page=" + page).then((response) => {
					this.plans = response.data.plans;
				});
			},

			//Confirm Delete
			deletePlan(id) {
				if (this.click) {
					this.click = false;
					this.$swal
						.fire({
							title: "Are you sure?",
							text: "You won't be able to revert this!",
							icon: "warning",
							showCancelButton: true,
							confirmButtonColor: "#3085d6",
							cancelButtonColor: "#d33",
							confirmButtonText: "Yes, delete it!",
						})
						.then((result) => {
							if (result.isConfirmed) {
								let list = id ? [id] : this.select;
								this.$axios
									.post("delete-plan", { idList: list })
									.then(
										(response) => {
											this.select = [];
											$nuxt.$emit("triggerPlan");
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
				this.plans.data.forEach((plan) => {
					this.select.push(plan.id);
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
					this.$axios.post("search-plan", this.searchOption).then(
						(response) => {
							this.plans = response.data.plans;
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
			this.getPlan();
			this.$nuxt.$on("triggerPlan", () => {
				this.getPlan();
			});
		},
	};
</script>