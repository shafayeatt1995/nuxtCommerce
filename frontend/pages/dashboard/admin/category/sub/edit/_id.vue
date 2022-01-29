<template>
	<div>
		<div class="section-header">
			<h1>Edit Sub-Category</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit">
				<div class="col-lg-12">
					<div class="bg-white p-3 min-h100 rounded card-primary">
						<h3 class="text-center">General Information</h3>
						<div class="form-group">
							<label for="status">Category</label>
							<select class="form-control" id="status" v-model="form.categoryId">
								<option value="">Select a category</option>
								<option :value="category.id" v-for="category in categories" :key="category.id">{{category.name}}</option>
							</select>
							<p class="invalid-feedback" v-if="errors.categoryId">{{errors.categoryId[0]}}</p>
						</div>
						<div class="form-group">
							<label for="name">Sub-Category Name</label>
							<input type="text" class="form-control" id="name" v-model="form.name">
							<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
						</div>
						<div class="form-group">
							<label for="status">Status</label>
							<select class="form-control" id="status" v-model="form.status">
								<option :value="true">Enable</option>
								<option :value="false">Disable</option>
							</select>
							<p class="invalid-feedback" v-if="errors.status">{{errors.status[0]}}</p>
						</div>
						<button type="submit" class="btn btn-primary">
							<transition name="fade" mode="out-in">
								<Spiner v-if="loading" />
								<span v-else>Create Sub-Category</span>
							</transition>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</template>
<script>
	export default {
		name: "edit-sub-category",
		head() {
			return {
				title: `Edit Sub-Category - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					categoryId: "",
					name: "",
					status: true,
				},
				categories: [],
				errors: {},
				click: true,
				loading: false,
			};
		},

		methods: {
			// Get category
			editSubCategory() {
				this.$axios.get(`edit-sub-category/${this.$route.params.id}`).then(
					(response) => {
						this.form.categoryId = response.data.category.category_id;
						this.form.name = response.data.category.name;
						this.form.status = response.data.category.status;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},

			getCategoryList() {
				this.$axios.get("category-list").then(
					(response) => {
						this.categories = response.data.categories;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},

			//Submit Form
			submit() {
				if (this.click) {
					this.click = false;
					this.$axios
						.post(
							`update-sub-category/${this.$route.params.id}`,
							this.form
						)
						.then(
							(response) => {
								$nuxt.$emit("success", response.data);
								this.click = true;
								this.$router.push(
									this.localePath("dashboard-admin-category-sub")
								);
							},
							(error) => {
								this.errors = error.response.data.errors;
								this.click = true;
							}
						);
				}
			},
		},

		created() {
			this.getCategoryList();
			this.editSubCategory();
		},
	};
</script>