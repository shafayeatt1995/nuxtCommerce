<template>
	<div>
		<div class="section-header">
			<h1>Edit Store</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit" enctype="multipart/form-data">
				<div class="col-lg-12">
					<div class="bg-white p-3 min-h100 rounded card-primary">
						<h3 class="text-center">General Information</h3>
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
						<div class="form-group">
							<label for="name">Store Name</label>
							<input type="text" class="form-control" id="name" v-model="form.name">
							<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
						</div>
						<div class="form-group">
							<label for="phone">Phone Number</label>
							<input type="text" class="form-control" id="phone" v-model="form.phone">
							<p class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</p>
						</div>
						<div class="form-group">
							<label for="phone">Address</label>
							<input type="text" class="form-control" id="address" v-model="form.address">
							<p class="invalid-feedback" v-if="errors.address">{{errors.address[0]}}</p>
						</div>
						<div class="form-group">
							<label for="phone">Description</label>
							<textarea class="form-control" id="description" rows="5" v-model="form.description"></textarea>
							<p class="invalid-feedback" v-if="errors.description">{{errors.description[0]}}</p>
						</div>
						<button type="submit" class="btn btn-primary">
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
		name: "edit-store",
		middleware: "admin",
		head() {
			return {
				title: `Edit Store - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					name: "",
					phone: "",
					address: "",
					description: "",
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
			editStore() {
				this.$axios.get(`edit-store/${this.$route.params.id}`).then(
					(response) => {
						this.form.name = response.data.store.name;
						this.form.phone = response.data.store.phone;
						this.form.address = response.data.store.address;
						this.form.description = response.data.store.description;
						this.preview = response.data.store.logo;
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
					formData.append("phone", this.form.phone);
					formData.append("address", this.form.address);
					formData.append("description", this.form.description);
					formData.append("logo", this.form.logo);

					this.$axios
						.post(
							`update-store/${this.$route.params.id}`,
							formData,
							config
						)
						.then(
							(response) => {
								$nuxt.$emit("success", response.data);
								this.click = true;
								this.$router.push(
									this.localePath("dashboard-admin-store")
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
			this.editStore();
		},
	};
</script>