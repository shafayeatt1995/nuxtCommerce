<template>
	<div class="container mt-40">
		<div v-if="user.role_id === 3">
			<transition name="fade" mode="out-in">
				<div class="text-center" v-if="success">
					<img :src="`${url}images/seller-regestration-success.svg`" alt="regestration success" class="img-fluid">
					<h3 class="mt-3">Seller regestration request submit successfully</h3>
				</div>
				<form @submit.prevent="submit" class="row" v-else>
					<transition name="fade" mode="out-in">
						<div class="col-lg-12 text-center" v-if="success">
							<img :data-src="url + 'images/contact.svg'" class="img-fluid h-350" v-lazy-load />
							<h4 class="strong">Thank You For Your Application. We Will Contact You Soon.</h4>
						</div>
						<div class="offset-lg-2 col-lg-8" v-else>
							<h3 class="text-center">Fill up your store information</h3>
							<form @submit.prevent="submit" enctype="multipart/form-data">
								<div class="image-form mt-3">
									<div class="image-frame">
										<img :src="preview" class="img-fluid max-h250" alt="logo" v-if="form.logo">
										<label for="logo" class="image-frame-content" v-else>
											<i>
												<icon :icon="['fas', 'cloud-upload-alt']"></icon>
											</i>
											<span>Select and upload your logo</span>
										</label>
									</div>
									<label for="logo" class="image-select">
										Chose Logo
									</label>
									<input type="file" class="form-control" id="logo" accept="image/*" @change="image($event)">
									<p class="invalid-feedback" v-if="errors.logo">{{errors.logo[0]}}</p>
								</div>
								<div class="form-group">
									<input class="form-control mt-3" type="text" placeholder="Type your store name" v-model="form.name" />
									<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
									<input class="form-control mt-3" type="text" placeholder="Type your phone number" v-model="form.phone" />
									<p class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</p>
									<input class="form-control mt-3" type="text" placeholder="Type your address" v-model="form.address" />
									<p class="invalid-feedback" v-if="errors.address">{{errors.address[0]}}</p>
									<textarea class="form-control mt-3" id="description" rows="5" placeholder="Type your description" v-model="form.description"></textarea>
									<p class="invalid-feedback" v-if="errors.description">{{errors.description[0]}}</p>
								</div>
								<button type="submit" class="big-button">Apply for a guide</button>
							</form>
						</div>
					</transition>
				</form>
			</transition>
		</div>
		<div class="text-center" v-else>
			<img :data-src="url + 'images/store-slde-show.svg'" class="img-fluid h-350" v-lazy-load />
			<h2>You Already Grant This Permission</h2>
		</div>
	</div>
</template>
<script>
	export default {
		head() {
			return {
				title: `Seller Regestration - ${this.appName}`,
			};
		},

		data() {
			return {
				click: true,
				success: false,
				form: {
					name: "",
					phone: "",
					address: "",
					description: "",
					logo: "",
				},
				preview: "",
				errors: {},
				loading: false,
			};
		},

		methods: {
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
					this.errors = {};

					const config = {
						headers: { "content-type": "multipart/form-data" },
					};

					let formData = new FormData();
					formData.append("name", this.form.name);
					formData.append("phone", this.form.phone);
					formData.append("address", this.form.address);
					formData.append("description", this.form.description);
					formData.append("logo", this.form.logo);

					this.$axios.post("seller-regestration", formData, config).then(
						(response) => {
							$nuxt.$emit("success", response.data);
							this.success = true;
							this.click = true;
						},
						(error) => {
							if (error.response.data.message) {
								this.$nuxt.$emit("error", error);
							} else {
								this.errors = error.response.data.errors;
							}
							this.click = true;
						}
					);
				}
			},
		},
	};
</script>