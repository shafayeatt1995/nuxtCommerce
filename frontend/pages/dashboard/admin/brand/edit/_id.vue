<template>
	<div>
		<div class="section-header">
			<h1>Edit Brand</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit" enctype="multipart/form-data">
				<div class="col-lg-12">
					<div class="bg-white p-3 min-h100 card card-primary">
						<h3 class="text-center">General Information</h3>
						<div class="form-group">
							<label for="name">Brand Name</label>
							<input type="text" class="form-control" id="name" v-model="form.name">
							<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
						</div>
						<div class="image-form">
							<div class="image-frame">
								<img :src="preview" class="img-fluid max-h250" alt="logo" v-if="form.logo">
								<img :src="url + preview" class="img-fluid max-h250" alt="logo" v-else>
							</div>
							<label for="logo" class="image-select">
								Chose Logo
							</label>
							<input type="file" class="form-control" id="logo" accept="image/*" @change="image($event)">
							<p class="invalid-feedback" v-if="errors.logo">{{errors.logo[0]}}</p>
						</div>
						<button type="submit" class="btn btn-primary mt-5">
							<transition name="fade" mode="out-in">
								<Spiner v-if="loading" />
								<span v-else>Update Brand</span>
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
		name: "edit-brand",
		head() {
			return {
				title: `Edit Brand - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					name: "",
					logo: "",
				},
				preview: "",
				errors: {},
				click: true,
				loading: false,
			};
		},

		methods: {
			// Get Brand
			editBrand() {
				this.$axios.get(`edit-brand/${this.$route.params.id}`).then(
					(response) => {
						this.form.name = response.data.brand.name;
						this.preview = response.data.brand.logo;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},

			// show Selected image
			image(event) {
				if (event.target.files.length > 0) {
					let file = event.target.files[0];
					let reader = new FileReader();
					reader.onloadend = () => {
						this.preview = reader.result;
					};
					reader.readAsDataURL(file);
					this.form.logo = file;
				}
			},

			//Submit Form
			submit() {
				if (this.click) {
					this.click = false;

					const config = {
						headers: { "content-type": "multipart/form-data" },
					};

					let formData = new FormData();
					formData.append("name", this.form.name);
					formData.append("logo", this.form.logo);

					this.$axios
						.post(
							`update-brand/${this.$route.params.id}`,
							formData,
							config
						)
						.then(
							(response) => {
								$nuxt.$emit("success", response.data);
								this.click = true;
								this.$router.push(
									this.localePath("dashboard-admin-brand")
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
			this.editBrand();
		},
	};
</script>