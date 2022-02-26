<template>
	<div>
		<div class="section-header">
			<h1>Commission List</h1>
		</div>

		<div class="section-body">
			<div class="row bg-white rounded p-3 shadow">
				<div class="d-flex w-100 justify-content-end flex-lg-row flex-column">
					<form class="d-flex mb-3" @submit.prevent="search">
						<input class="form-control" type="text" placeholder="Search..." v-model="searchOption.keyword" @keyup="instantSearch">
						<select class="form-control" v-model="searchOption.collum">
							<option value="name">Search by sub-category</option>
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
							<th scope="col">Category</th>
							<th scope="col">Sub Category</th>
							<th scope="col">Commission type</th>
							<th scope="col">Commission</th>
							<th scope="col">Last Update</th>
						</tr>
					</thead>
					<tbody class="text-center" v-if="loading">
						<td colspan="8">
							<Loader />
						</td>
					</tbody>
					<tbody class="text-center" v-else-if="subCategories.data && subCategories.data.length >= 1">
						<tr v-for="sub in subCategories.data" :key="sub.id">
							<td>
								{{sub.category.name}}
							</td>
							<td>
								{{sub.name}}
							</td>
							<td>
								<span class="badge badge-danger" v-if="sub.commission && sub.commission.type == null">Free</span>
								<span class="badge badge-success color-black" v-else-if="sub.commission && sub.commission.type">Fixed</span>
								<span class="badge badge-success color-black" v-else-if="sub.commission && !sub.commission.type">Percent</span>
								<span class="badge badge-danger" v-else>Not Set</span>
							</td>
							<td>
								<span class="badge badge-danger" v-if="sub.commission && sub.commission.type === null">Free</span>
								<span class="badge badge-success color-black" v-else-if="sub.commission && sub.commission.type">{{sub.commission.commission}}</span>
								<span class="badge badge-success color-black" v-else-if="sub.commission && !sub.commission.type">% {{sub.commission.commission}}</span>
								<span class="badge badge-danger" v-else>Not Set</span>
							</td>
							<td>
								{{sub.commission.updated_at | normalDate}}
							</td>
						</tr>
					</tbody>
					<tbody v-else>
						<td colspan="8" class="pt-3">
							<Not-found message="No commission found" />
						</td>
					</tbody>
				</table>
				<pagination :data="commissions" @pagination-change-page="getResults" class="justify-content-center mt-3 paginate"></pagination>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "all-commissions",
		middleware: "seller",
		head() {
			return {
				title: `Commission - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				form: {
					subCategoryId: "",
					type: false,
					commission: "",
				},
				commissions: {},
				searchOption: {
					keyword: "",
					collum: "name",
				},
				loading: true,
			};
		},
		methods: {
			//Get Commission
			getCommission() {
				this.$axios.get("commission").then(
					(response) => {
						this.subCategories = response.data.subCategories;
						this.loading = false;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},
			getResults(page = 1) {
				this.$axios.get("commission?page=" + page).then((response) => {
					this.subCategories = response.data.subCategories;
				});
			},

			//Search item
			search() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$axios.post("search-commission", this.searchOption).then(
						(response) => {
							this.subCategories = response.data.subCategories;
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
						this.$axios
							.post("search-commission", this.searchOption)
							.then(
								(response) => {
									this.subCategories =
										response.data.subCategories;
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

			commissionForm(sub) {
				if (this.form.subCategoryId === sub.id) {
					this.form.subCategoryId = "";
					this.form.type = false;
					this.form.commission = "";
				} else {
					this.form.subCategoryId = sub.id;
					this.form.type = sub.commission ? sub.commission.type : false;
					this.form.commission = sub.commission
						? sub.commission.commission
						: "";
				}
			},

			updateCommission() {
				if (this.click) {
					this.click = false;
					this.$axios.post("update-commission", this.form).then(
						(response) => {
							$nuxt.$emit("triggerCommission");
							this.form.subCategoryId = "";
							this.form.type = false;
							this.form.commission = "";
							this.loading = true;
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
			this.getCommission();
			this.$nuxt.$on("triggerCommission", () => {
				this.getCommission();
			});
		},

		beforeDestroy() {
			this.$nuxt.$off("triggerCommission");
		},
	};
</script>