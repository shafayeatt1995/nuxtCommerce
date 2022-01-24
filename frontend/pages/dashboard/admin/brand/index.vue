<template>
	<div>
		<div class="section-header">
			<h1>All Plans</h1>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100  justify-content-between">
					<form class="d-flex mb-3" @submit.prevent="selectAction">
						<select class="form-control" v-model="action">
							<option value="">Select a option</option>
							<option value="delete">Delete selected item</option>
						</select>
						<button type="submit" class="btn btn-primary">Apply Action</button>
					</form>
					<nuxt-link :to="localePath('dashboard-admin-plan-create')" class="btn btn-primary mb-4">Create Plan</nuxt-link>
				</div>
				<table class="table table-striped text-center">
					<thead>
						<tr>
							<th scope="col">
								<input class="form-check-input" type="checkbox" @click="select.length >= 1 ? deselectall():selectAll()" :checked="select.length >= 1">
							</th>
							<th scope="col">Name</th>
							<th scope="col">Price</th>
							<th scope="col">Duration</th>
							<th scope="col">User</th>
							<th scope="col">Status</th>
							<th scope="col">Create At</th>
							<th scope="col">Action</th>
						</tr>
					</thead>
					<tbody v-if="load && plans.data.length > 0">
						<tr v-for="plan in plans.data" :key="plan.id">
							<th scope="row">
								<input class="form-check-input" type="checkbox" v-model="select" :value="plan.id">
							</th>
							<td>{{plan.name}}</td>
							<td>{{plan.price}}</td>
							<td>{{plan.duration_name}} ({{plan.duration_day}} Days)</td>
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
					<tbody class="text-center" v-else-if="!load">
						<td colspan="8">
							<Loader />
						</td>
					</tbody>
					<tbody class="text-center" v-else>
						<td colspan="8">
							<Not-found message="There is no plan found" />
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
				load: false,
			};
		},
		methods: {
			//Get Plan
			getPlan() {
				this.$axios.get("plan").then(
					(response) => {
						this.plans = response.data.plans;
						this.load = true;
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

			deletePlan(id) {
				if (this.click) {
					this.click = false;
					this.$axios.post("delete-plan", { idList: [id] }).then(
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
				}
			},

			selectAction() {
				if (this.click && this.action === "delete") {
					this.click = false;
					this.$axios.post("delete-plan", { idList: this.select }).then(
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
		},

		created() {
			this.getPlan();
			this.$nuxt.$on("triggerPlan", () => {
				this.getPlan();
			});
		},
	};
</script>