<template>
	<div>
		<div class="section-header">
			<h1>Create Product</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit" enctype="multipart/form-data">
				<div class="col-lg-12">
					<div class="bg-white p-3 min-h100 rounded card-primary">
						<h3 class="text-center">General Information</h3>
						<div class="image-form mt-3">
							<div class="image-frame grid" v-if="newImages.length !== 0">
								<div class="select-image" v-for="(image, key) in newImages" :key="`image-${key}`">
									<img :src="image" class="img-fluid max-h250" alt="logo">
									<span class="pointer" v-tooltip.top-center="'Click to remove'" @click="removeImage(key)">
										<i>
											<icon :icon="['fas', 'times']"></icon>
										</i>
									</span>
								</div>
							</div>
							<div class="image-frame" v-else>
								<label for="logo" class="image-frame-content">
									<i>
										<icon :icon="['fas', 'cloud-upload-alt']"></icon>
									</i>
									<span>Select and upload product images. Max 10 images.</span>
								</label>
							</div>
							<label :for="images.length < 10 ? 'logo' : ''" class="image-select">
								<i>
									<icon :icon="['fas', 'cloud-upload-alt']"></icon>
								</i>
								Select Images
							</label>
							<input type="file" class="form-control" id="logo" accept="image/*" @change="image($event)" multiple>
							<p class="invalid-feedback" v-if="errors.image">{{errors.image[0]}}</p>
						</div>
						<div class="form-group">
							<label for="name">Product Name</label>
							<input type="text" class="form-control" id="name" v-model="form.name" required>
							<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
						</div>
						<div class="form-group">
							<label for="category">Select category</label>
							<div class="row">
								<div class="col-lg-4">
									<select class="form-control my-2" id="category" v-model="form.category_id" @change="form.category_id ? changeCategory() : ''" required>
										<option value="">Select a category</option>
										<option :value="category.id" v-for="category in categories" :key="category.id">{{category.name}}</option>
									</select>
									<p class="invalid-feedback" v-if="errors.category_id">{{errors.category_id[0]}}</p>
								</div>
								<div class="col-lg-4">
									<select class="form-control my-2" v-model="form.sub_category_id" :disabled="form.category_id && subCategories.length < 1" @change="form.sub_category_id ? changeSubCategory() : ''" required>
										<option value="">Select a sub category</option>
										<option :value="sub.id" v-for="sub in subCategories" :key="sub.id">{{sub.name}}</option>
									</select>
									<p class="invalid-feedback" v-if="errors.sub_category_id">{{errors.sub_category_id[0]}}</p>
								</div>
								<div class="col-lg-4">
									<select class="form-control my-2" v-model="form.child_category_id" :disabled="form.category_id && form.sub_category_id && childCategories.length < 1" required>
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
							<select class="form-control my-2" v-model="form.brand_id" required>
								<option value="">Select a brand</option>
								<option :value="brand.id" v-for="brand in brands" :key="brand.id">{{brand.name}}</option>
							</select>
							<p class="invalid-feedback" v-if="errors.brand_id">{{errors.brand_id[0]}}</p>
						</div>
						<hr>
						<h3 class="text-center">Product Info</h3>
						<div class="form-group">
							<label for="feature">
								Product Feature
							</label>
							<ul v-if="form.features.length !== 0">
								<li v-for="(feature, key) in form.features" :key="`feature-${key}`">
									{{feature}}
									<span class="pointer ml-2" @click="removeFeature(key)"><i>
											<icon :icon="['fas', 'times']"></icon>
										</i></span>
								</li>
							</ul>
							<form class="d-flex grid-gap-5" @submit.prevent="addFeature">
								<input type="text" class="form-control" id="feature" v-model="feature" required>
								<button type="submit" class="btn btn-primary">Add Feature</button>
							</form>
							<p class="invalid-feedback" v-if="errors.features">{{errors.features[0]}}</p>
						</div>
						<div class="form-group">
							<label>
								Product Specifications
							</label>
							<ul v-if="form.specifications.length !== 0">
								<li v-for="(specification, key) in form.specifications" :key="`specification-${key}`">
									{{specification.title}}
									<i>
										<icon :icon="['fas', 'arrow-right']"></icon>
									</i>
									{{specification.name}}
									<span class="pointer ml-2" @click="removeSpecification(key)"><i>
											<icon :icon="['fas', 'times']"></icon>
										</i></span>
								</li>
							</ul>
							<form class="d-flex grid-gap-5" @submit.prevent="addSpecification">
								<input type="text" class="form-control" v-model="specificationTitle" placeholder="Specification Title" required>
								<input type="text" class="form-control" v-model="specificationName" placeholder="Specification Name" required>
								<button type="submit" class="btn btn-primary">Add Feature</button>
							</form>
							<p class="invalid-feedback" v-if="errors.features">{{errors.features[0]}}</p>
						</div>
						<div class="form-group">
							<label for="item">
								Which item will the customer get
							</label>
							<ul v-if="form.items.length !== 0">
								<li v-for="(item, key) in form.items" :key="`item-${key}`">
									{{item}}
									<span class="pointer ml-2" @click="removeItem(key)"><i>
											<icon :icon="['fas', 'times']"></icon>
										</i></span>
								</li>
							</ul>
							<form class="d-flex grid-gap-5" @submit.prevent="addItem">
								<input type="text" class="form-control" id="item" v-model="item" required>
								<button type="submit" class="btn btn-primary">Add Item</button>
							</form>
							<p class="invalid-feedback" v-if="errors.items">{{errors.items[0]}}</p>
						</div>
						<div class="form-group">
							<label>
								Product Materials
							</label>
							<div class="form-check checkbox-grid">
								<div v-for="(material, key) in materials" :key="`material-${key}`">
									<input class="form-check-input" type="checkbox" :id="`material${key}`" :value="material.id" v-model="form.materials">
									<label class="form-check-label ml-1" :for="`material${key}`">{{material.name}}</label>
								</div>
							</div>
							<p class="invalid-feedback" v-if="errors.materials">{{errors.materials[0]}}</p>
						</div>
						<div class="form-group">
							<label>
								Product Weight (KG)
							</label>
							<input type="number" step="0.01" class="form-control" v-model="form.weight" required>
							<p class="invalid-feedback" v-if="errors.weight">{{errors.weight[0]}}</p>
						</div>
						<div class="form-group">
							<label>
								Product Description
							</label>
							<ckeditor v-model="form.description"></ckeditor>
							<p class="invalid-feedback" v-if="errors.description">{{errors.description[0]}}</p>
						</div>
						<hr>
						<!-- Product Variation start -->
						<h3 class="text-center">Product Variation</h3>
						<table class="table table-striped text-center table-responsive-md" v-if="form.variations.length > 0">
							<thead>
								<tr>
									<th scope="col">Color</th>
									<th scope="col">Size</th>
									<th scope="col">Price</th>
									<th scope="col">Discount</th>
									<th scope="col">invertory</th>
									<th scope="col">Action</th>
								</tr>
							</thead>
							<tbody class="text-center">
								<tr v-for="(variation, key) in form.variations" :key="key">
									<td>
										{{colors.find(color => color.id === variation.color_id).name}}
										<span class="color-bar" :style="`background: ${colors.find(color => color.id === variation.color_id).code}`"></span>
									</td>
									<td>{{sizes.find(size => size.id === variation.size_id).name}}</td>
									<td>{{defaultCurrencyIcon}} {{variation.price}}</td>
									<td>
										<div v-if="variation.special_price !== null">
											<p><b>Price:</b> {{variation.special_price}}</p>
											<p><b>Date:</b> {{variation.start_date | normalDate}}
												<i>
													<icon :icon="['fas', 'arrow-right']"></icon>
												</i>
												{{variation.end_date | normalDate}}
											</p>
										</div>
										<p v-else>
											No Discount
										</p>
									</td>
									<td>
										<div v-if="variation.quantity !== null">
											<p>SKU: {{variation.sku}}</p>
											<p>Qty: {{variation.quantity}}</p>
										</div>
										<p v-else>
											No Inventory
										</p>
									</td>
									<td>
										<button type="button" class="btn btn-icon btn-primary my-2" @click="editVariation(variation, key)">
											<i>
												<icon :icon="['fas', 'edit']"></icon>
											</i>
										</button>
										<button type="button" class="btn btn-icon btn-danger my-2" @click="removeVariation(key)">
											<i>
												<icon :icon="['fas', 'trash-alt']"></icon>
											</i>
										</button>
									</td>
								</tr>
							</tbody>
						</table>
						<form class="row" @submit.prevent="addVariation" enctype="multipart/form-data">
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Select Size
										<i class="pointer" @click="request.size = !request.size">
											<icon :icon="['fas', 'question']"></icon>
										</i>
										<transition name="fade" mode="out-in">
											<nuxt-link :to="localePath('dashboard-seller-request-brand')" class="float-info" v-if="request.size">Request for new size</nuxt-link>
										</transition>
									</label>
									<select class="form-control my-2" v-model="variation.size_id" required>
										<option value="">Select a Size</option>
										<option :value="size.id" v-for="size in sizes" :key="size.id">
											{{size.name}}
										</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>
										Select Color
										<i class="pointer" @click="request.color = !request.color">
											<icon :icon="['fas', 'question']"></icon>
										</i>
										<transition name="fade" mode="out-in">
											<nuxt-link :to="localePath('dashboard-seller-request-brand')" class="float-info" v-if="request.color">Request for new color</nuxt-link>
										</transition>
									</label>
									<select class="form-control my-2" v-model="variation.color_id" required>
										<option value="">Select a color</option>
										<option :value="color.id" v-for="color in colors" :key="color.id" :style="`background: ${color.code}`">
											{{color.name}}
										</option>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>
										Product Price
									</label>
									<input type="number" step="0.01" class="form-control" v-model="variation.price" required>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="special-price">Discount</label>
									<select class="form-control" id="special-price" v-model="variation.discount">
										<option :value="true">Yes</option>
										<option :value="false">No</option>
									</select>
								</div>
							</div>
							<transition name="slide" mode="out-in">
								<div class="col-md-6" v-if="variation.discount">
									<div class="form-group">
										<label for="special-price">Special Price</label>
										<input type="number" step="0.01" class="form-control" v-model="variation.special_price" :required="variation.discount">
									</div>
								</div>
							</transition>
							<transition name="slide" mode="out-in">
								<div class="col-md-6" v-if="variation.discount">
									<div class="form-group">
										<label for="special-price">Special Price Date</label>
										<date-picker v-model="variation.date" type="date" range value-type="YYYY-MM-DD" placeholder="Select date range" :require="variation.discount"></date-picker>
									</div>
								</div>
							</transition>
							<div class="col-md-12">
								<div class="form-group">
									<label for="special-price">Inventory</label>
									<select class="form-control" id="special-price" v-model="variation.inventory">
										<option :value="true">Yes</option>
										<option :value="false">No</option>
									</select>
								</div>
							</div>
							<transition name="slide" mode="out-in">
								<div class="col-md-6" v-if="variation.inventory">
									<div class="form-group">
										<label>
											Product Quantity
										</label>
										<input type="number" class="form-control" v-model="variation.quantity" :required="variation.inventory">
									</div>
								</div>
							</transition>
							<transition name="slide" mode="out-in">
								<div class="col-md-6" v-if="variation.inventory">
									<div class="form-group">
										<label>
											Product SKU
										</label>
										<input type="text" class="form-control" v-model="variation.sku" :required="variation.inventory">
									</div>
								</div>
							</transition>
							<div class="col-md-12 d-flex flex-column w-100 justify-content-end">
								<button type="submit" class="btn btn-primary">Add Veriation</button>
								<p class="invalid-feedback" v-if="errors.variations">{{errors.variations[0]}}</p>
							</div>
						</form>
						<hr>
						<!-- Product Variation end -->

						<h3 class="text-center">SEO</h3>
						<div class="form-group">
							<label for="tag">
								Search Tag
							</label>
							<ul class="d-flex grid-gap-5 p-0" v-if="form.tags.length !== 0">
								<li class="badge badge-primary" v-for="(tag, key) in form.tags" :key="`tag-${key}`">
									{{tag}}
									<span class="pointer ml-2" @click="removeTag(key)"><i>
											<icon :icon="['fas', 'times']"></icon>
										</i></span>
								</li>
							</ul>
							<form class="d-flex grid-gap-5" @submit.prevent="addTag">
								<div class="position-relative w-100">
									<input type="text" class="form-control" id="tag" v-model="tag" :maxlength="maxCharacter" required>
									<span class="tagCharecter">{{tag.length}}/{{maxCharacter - tag.length}}</span>
								</div>
								<button type="submit" class="btn btn-primary">Add Tag</button>
							</form>
							<p class="invalid-feedback" v-if="errors.tags">{{errors.tags[0]}}</p>
						</div>
						<div class="form-group">
							<label for="tag">
								Meta Description
							</label>
							<textarea class="form-control" id="meta" v-model="form.meta" required></textarea>
							<p class="invalid-feedback" v-if="errors.meta">{{errors.meta[0]}}</p>
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
	import DatePicker from "vue2-datepicker";
	import "vue2-datepicker/index.css";

	export default {
		name: "create-product",
		middleware: "seller",
		components: { DatePicker },
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
					specifications: [],
					items: [],
					materials: [],
					description: "",
					variations: [],
					image: [],
					weight: "",
					tags: [],
					meta: "",
					status: true,
				},
				request: {
					brand: false,
					color: false,
				},
				variation: {
					color_id: "",
					size_id: "",
					price: "",
					discount: false,
					special_price: "",
					date: [],
					inventory: false,
					quantity: "",
					sku: "",
				},

				brands: [],
				categories: [],
				subCategories: [],
				childCategories: [],
				materials: [],
				colors: [],
				sizes: [],
				feature: "",
				specificationTitle: "",
				specificationName: "",
				item: "",
				tag: "",
				maxCharacter: 25,
				images: [],
				newImages: [],
				errors: {},
				click: true,
				loading: false,
			};
		},

		methods: {
			//Get new product info
			newProductInfo() {
				this.$axios.get("new-product-info").then(
					(response) => {
						this.categories = response.data.categories;
						this.brands = response.data.brands;
						this.materials = response.data.materials;
						this.colors = response.data.colors;
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
					.get(`product-child-category-list/${this.form.sub_category_id}`)
					.then(
						(response) => {
							this.childCategories = response.data.childCategories;
							this.sizes = response.data.sizes;
						},
						(error) => {
							$nuxt.$emit("error", error);
						}
					);
			},

			//Add Feature
			addFeature() {
				if (this.form.features.length < 5) {
					if (this.feature) {
						this.form.features.push(this.feature);
						this.feature = "";
					}
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

			//Add Specification
			addSpecification() {
				this.form.specifications.push({
					title: this.specificationTitle,
					name: this.specificationName,
				});
				this.specificationTitle = "";
				this.specificationName = "";
			},

			// Remove Specification
			removeSpecification(key) {
				this.form.specifications.splice(key, 1);
			},

			//Add Item
			addItem() {
				if (this.item) {
					this.form.items.push(this.item);
					this.item = "";
				}
			},

			// Remove Item
			removeItem(key) {
				this.form.items.splice(key, 1);
			},

			//Add Tag
			addTag() {
				if (this.form.tags.length < 10) {
					if (this.tag) {
						this.form.tags.push(this.tag);
						this.tag = "";
					}
				} else {
					this.$nuxt.$emit("customError", "You can add maximum 10 tags");
				}
			},

			// Remove Tag
			removeTag(key) {
				this.form.tags.splice(key, 1);
			},

			// Add Product Image
			image(event) {
				if (event.target.files.length > 0) {
					for (let file of Object.entries(event.target.files)) {
						let reader = new FileReader();
						if (file[1].size < 2097153) {
							if (this.images.length < 10) {
								reader.onloadend = () => {
									this.newImages.push(reader.result);
								};
								reader.readAsDataURL(file[1]);
								this.images.push(file[1]);
								this.form.image.push(this.form.image.length + 1);
							} else {
								$nuxt.$emit(
									"customError",
									"You Can Upload Maximum 10 Images"
								);
							}
						} else {
							$nuxt.$emit("customError", "Maximum Image Size 2 MB");
						}
					}
				}
			},

			//Remove Image
			removeImage(key) {
				this.newImages.splice(key, 1);
				this.images.splice(key, 1);
				this.form.image.splice(key, 1);
			},

			// Add veriation
			addVariation() {
				let data = {
					color_id: this.variation.color_id,
					size_id: this.variation.size_id,
					price: this.variation.price,
					discount: this.variation.discount,
					special_price: this.variation.discount
						? this.variation.special_price
						: null,
					start_date: this.variation.discount
						? this.variation.date.length === 2
							? this.variation.date[0]
							: new Date()
						: null,
					end_date: this.variation.discount
						? this.variation.date.length === 2
							? this.variation.date[1]
							: new Date()
						: null,
					inventory: this.variation.inventory,
					quantity: this.variation.inventory
						? this.variation.quantity
						: null,
					sku: this.variation.inventory ? this.variation.sku : null,
				};
				this.form.variations.push(data);

				this.variation.color_id = "";
				this.variation.size_id = "";
				this.variation.price = "";
				this.variation.discount = false;
				this.variation.special_price = "";
				this.variation.date = [];
				this.variation.inventory = false;
				this.variation.quantity = "";
				this.variation.sku = "";
			},

			// Edit Veriation
			editVariation(variation, key) {
				this.variation.color_id = variation.color_id;
				this.variation.size_id = variation.size_id;
				this.variation.price = variation.price;
				this.variation.discount =
					variation.special_price !== null ? true : false;
				this.variation.special_price = variation.special_price || "";
				this.variation.date =
					variation.special_price !== null
						? [variation.start_date, variation.end_date]
						: [];
				this.variation.inventory =
					variation.quantity !== null ? true : false;
				this.variation.quantity = variation.quantity || "";
				this.variation.sku = variation.sku || "";

				this.form.variations.splice(key, 1);
			},

			// Remove Variation
			removeVariation(key) {
				this.form.variations.splice(key, 1);
			},

			//Submit Form
			submit() {
				if (this.click) {
					this.click = false;
					this.errors = {};

					this.$axios.post("create-product", this.form).then(
						(response) => {
							const config = {
								headers: { "content-type": "multipart/form-data" },
							};
							let formData = new FormData();
							this.images.forEach((image) => {
								formData.append("images[]", image);
							});
							this.$axios
								.post(
									`upload-product-image/${response.data.slug}`,
									formData,
									config
								)
								.then(
									(res) => {
										$nuxt.$emit("success", res.data);
										this.click = true;
										this.$router.push(
											this.localePath(
												"dashboard-seller-product"
											)
										);
									},
									() => {
										this.click = true;
									}
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
			this.newProductInfo();
		},
	};
</script>