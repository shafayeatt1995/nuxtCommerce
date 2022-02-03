<template>
	<div>
		<div class="section-header">
			<h1>Create Sub-Category</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit">
				<div class="col-lg-12">
					<div class="bg-white p-3 min-h100 rounded card-primary">
						<h3 class="text-center">General Information</h3>
						<div class="form-group">
							<label for="status">Category</label>
							<select class="form-control" id="status" v-model="form.categoryId" @change="form.categoryId ? changeCategory() : ''">
								<option value="">Select a category</option>
								<option :value="category.id" v-for="category in categories" :key="category.id">{{category.name}}</option>
							</select>
							<p class="invalid-feedback" v-if="errors.categoryId">{{errors.categoryId[0]}}</p>
						</div>
						<div class="form-group">
							<label for="status">Sub Category</label>
							<select class="form-control" id="status" v-model="form.subCategoryId" :disabled="form.categoryId && subCategories.length < 1">
								<option value="">Select a sub category</option>
								<option :value="sub.id" v-for="sub in subCategories" :key="sub.id">{{sub.name}}</option>
							</select>
							<p class="invalid-feedback" v-if="errors.subCategoryId">{{errors.subCategoryId[0]}}</p>
						</div>
						<div class="form-group">
							<label for="name">Child-Category Name</label>
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
								<span v-else>Create Child-Category</span>
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
		name: "create-sub-category",
		head() {
			return {
				title: `Create Sub Category - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					categoryId: "",
					subCategoryId: "",
					name: "",
					status: true,
				},
				categories: [],
				subCategories: [],
				errors: {},
				click: true,
				loading: false,
			};
		},

		methods: {
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

			//Change Category
			changeCategory() {
				this.form.subCategoryId = "";
				this.$axios.get(`sub-category-list/${this.form.categoryId}`).then(
					(response) => {
						this.subCategories = response.data.subCategories;
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
					this.errors = {};
					this.$axios.post("create-child-category", this.form).then(
						(response) => {
							$nuxt.$emit("success", response.data);
							this.click = true;
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
		},
	};
</script>
