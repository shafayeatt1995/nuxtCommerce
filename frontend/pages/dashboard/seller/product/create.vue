<template>
	<div>
		<div class="section-header">
			<h1>Create Product</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit">
				<div class="col-lg-12">
					<div class="bg-white p-3 min-h100 rounded card-primary">
						<h3 class="text-center">General Information</h3>
						<div class="form-group">
							<label for="name">Product Name</label>
							<input type="text" class="form-control" id="name" v-model="form.name">
							<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
						</div>
						<div class="form-group">
							<label for="category">Select category</label>
							<div class="row">
								<div class="col-lg-4">
									<select class="form-control my-2" id="category" v-model="form.category_id" @change="form.category_id ? changeCategory() : ''">
										<option value="">Select a category</option>
										<option :value="category.id" v-for="category in categories" :key="category.id">{{category.name}}</option>
									</select>
									<p class="invalid-feedback" v-if="errors.category_id">{{errors.category_id[0]}}</p>
								</div>
								<div class="col-lg-4">
									<select class="form-control my-2" v-model="form.sub_category_id" :disabled="form.category_id && subCategories.length < 1" @change="form.sub_category_id ? changeSubCategory() : ''">
										<option value="">Select a sub category</option>
										<option :value="sub.id" v-for="sub in subCategories" :key="sub.id">{{sub.name}}</option>
									</select>
									<p class="invalid-feedback" v-if="errors.sub_category_id">{{errors.sub_category_id[0]}}</p>
								</div>
								<div class="col-lg-4">
									<select class="form-control my-2" v-model="form.child_category_id" :disabled="form.category_id && form.sub_category_id && childCategories.length < 1">
										<option value="">Select a child category</option>
										<option :value="child.id" v-for="child in childCategories" :key="child.id">{{child.name}}</option>
									</select>
									<p class="invalid-feedback" v-if="errors.child_category_id">{{errors.child_category_id[0]}}</p>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>
								Select Brand
								<i class="pointer" @click="request.brand = !request.brand">
									<icon :icon="['fas', 'question']"></icon>
								</i>
								<transition name="fade" mode="out-in">
									<nuxt-link :to="localePath('dashboard-seller-request-brand')" class="float-info" v-if="request.brand">Request for new Brand</nuxt-link>
								</transition>
							</label>
							<select class="form-control my-2" v-model="form.brand_id">
								<option value="">Select a brand</option>
								<option :value="brand.id" v-for="brand in brands" :key="brand.id">{{brand.name}}</option>
							</select>
							<p class="invalid-feedback" v-if="errors.brand_id">{{errors.brand_id[0]}}</p>
						</div>
						<ul>
							<li v-for="(feature, key) in form.features" :key="`feature-${key}`">
								{{feature}}
								<span class="pointer ml-2" @click="removeFeature(key)"><i>
										<icon :icon="['fas', 'times']"></icon>
									</i></span>
							</li>
						</ul>
						<div class="form-group">
							<label for="feature">
								Product Feature
							</label>
							<div class="d-flex">
								<input type="text" class="form-control" id="feature" v-model="feature" required>
								<button type="button" class="btn btn-primary" @click="addFeature">Add Feature</button>
							</div>
							<p class="invalid-feedback" v-if="errors.features">{{errors.features[0]}}</p>
						</div>
						<div class="form-group">
							<label for="box">
								Whats in the box
							</label>
							<input type="text" class="form-control" id="box" v-model="form.box" required>
							<p class="invalid-feedback" v-if="errors.features">{{errors.features[0]}}</p>
						</div>
						<button type="button" class="btn btn-primary">
							<transition name="fade" mode="out-in">
								<Spiner v-if="loading" />
								<span v-else>Create product</span>
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
		name: "create-product",
		middleware: "seller",
		head() {
			return {
				title: `Create Product - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					name: "",
					brand_id: "",
					category_id: "",
					sub_category_id: "",
					child_category_id: "",
					features: [],
					box: "",
				},
				request: {
					brand: false,
				},
				brands: [],
				categories: [],
				subCategories: [],
				childCategories: [],
				feature: "",
				errors: {},
				click: true,
				loading: false,
			};
		},

		methods: {
			//Get Category List
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

			//Get Brand List
			getBrandList() {
				this.$axios.get("brand-list").then(
					(response) => {
						this.brands = response.data.brands;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},

			//Change Category
			changeCategory() {
				this.form.sub_category_id = "";
				this.$axios.get(`sub-category-list/${this.form.category_id}`).then(
					(response) => {
						this.subCategories = response.data.subCategories;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},

			//Change Sub Category
			changeSubCategory() {
				this.form.child_category_id = "";
				this.$axios
					.get(`child-category-list/${this.form.sub_category_id}`)
					.then(
						(response) => {
							this.childCategories = response.data.childCategories;
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
					this.$axios.post("create-category", this.form).then(
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

			//Add Feature
			addFeature() {
				if (this.form.features.length < 5) {
					this.form.features.push(this.feature);
					this.feature = "";
				} else {
					this.$nuxt.$emit(
						"customError",
						"You can add maximum 5 features"
					);
				}
			},
			// Remove Feature
			removeFeature(key) {
				this.form.features.splice(key, 1);
			},
		},

		created() {
			this.getCategoryList();
			this.getBrandList();
		},
	};
</script>