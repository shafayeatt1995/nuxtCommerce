<template>
	<div>
		<div class="section-header">
			<h1>Create Plan</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit">
				<div class="col-lg-8">
					<div class="bg-white p-3 min-h100 rounded card-primary">
						<h3 class="text-center">General Information</h3>
						<div class="form-group">
							<label for="name">Plan Name</label>
							<input type="text" class="form-control" id="name" v-model="form.name">
							<p class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</p>
						</div>
						<div class="form-group">
							<label for="description">Plan Description</label>
							<input type="text" class="form-control" id="description" v-model="form.description">
							<p class="invalid-feedback" v-if="errors.description">{{errors.description[0]}}</p>
						</div>
						<div class="form-group">
							<label for="durationName">Duration Name</label>
							<input type="text" class="form-control" id="durationName" v-model="form.durationName">
							<p class="invalid-feedback" v-if="errors.durationName">{{errors.durationName[0]}}</p>
						</div>
						<div class="form-group">
							<label for="durationDate">Duration Day</label>
							<input type="number" class="form-control" id="durationDate" v-model="form.durationDay">
							<p class="invalid-feedback" v-if="errors.durationDay">{{errors.durationDay[0]}}</p>
						</div>
						<div class="form-group">
							<label for="price">Price</label>
							<input type="number" class="form-control" id="price" step=".01" v-model="form.price">
							<p class="invalid-feedback" v-if="errors.price">{{errors.price[0]}}</p>
						</div>
						<div class="form-group">
							<label for="discount">Discount</label>
							<select class="form-control" id="discount" v-model="form.discount">
								<option :value="false">No</option>
								<option :value="true">yes</option>
							</select>
						</div>
						<transition name="slide" mode="out-in">
							<div v-if="form.discount">
								<div class="form-group">
									<label for="discountPrice">Discount Price</label>
									<input type="number" class="form-control" id="price" step=".01" v-model="form.discountPrice">
									<p class="invalid-feedback" v-if="errors.discountPrice">{{errors.discountPrice[0]}}</p>
								</div>
								<div class="form-group">
									<label for="discountPrice">Discount Active Date</label>
									<date-picker v-model="form.discountDate" type="date" value-type="YYYY-MM-DD" range></date-picker>
									<p class="invalid-feedback" v-if="errors.discountDate">{{errors.discountDate[0]}}</p>
								</div>
							</div>
						</transition>
						<div class="form-group">
							<label for="productLimit">Product Limit</label>
							<input type="number" class="form-control" id="productLimit" v-model="form.productLimit">
							<p class="invalid-feedback" v-if="errors.productLimit">{{errors.productLimit[0]}}</p>
						</div>
						<div class="form-group">
							<label for="storageLimit">Storage Limit (MB)</label>
							<input type="number" class="form-control" id="storageLimit" v-model="form.storageLimit">
							<p class="invalid-feedback" v-if="errors.storageLimit">{{errors.storageLimit[0]}}</p>
						</div>
						<div class="form-group">
							<label for="variationLimit">Each Product Variation Limit</label>
							<input type="number" class="form-control" id="variationLimit" v-model="form.variationLimit">
							<p class="invalid-feedback" v-if="errors.variationLimit">{{errors.variationLimit[0]}}</p>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="bg-white p-3 min-h100 rounded card-primary">
						<h3 class="text-center">Access Option</h3>
						<div class="form-group">
							<label for="inventory">Inventory</label>
							<select class="form-control" id="inventory" v-model="form.inventory">
								<option :value="true">Enable</option>
								<option :value="false">Disable</option>
							</select>
							<p class="invalid-feedback" v-if="errors.inventory">{{errors.inventory[0]}}</p>
						</div>
						<div class="form-group">
							<label for="pos">POS</label>
							<select class="form-control" id="pos" v-model="form.pos">
								<option :value="true">Enable</option>
								<option :value="false">Disable</option>
							</select>
							<p class="invalid-feedback" v-if="errors.pos">{{errors.pos[0]}}</p>
						</div>
						<div class="form-group">
							<label for="qrCode">QR-Code</label>
							<select class="form-control" id="qrCode" v-model="form.qrCode">
								<option :value="true">Enable</option>
								<option :value="false">Disable</option>
							</select>
							<p class="invalid-feedback" v-if="errors.qrCode">{{errors.qrCode[0]}}</p>
						</div>
						<div class="form-group">
							<label for="support">Support</label>
							<select class="form-control" id="support" v-model="form.support">
								<option :value="true">Enable</option>
								<option :value="false">Disable</option>
							</select>
							<p class="invalid-feedback" v-if="errors.support">{{errors.support[0]}}</p>
						</div>
						<h3 class="mt-5">Publish Option</h3>
						<div class="form-group">
							<label for="status">Status</label>
							<select class="form-control" id="status" v-model="form.status">
								<option :value="true">Active plan</option>
								<option :value="false">Deactive Plan</option>
							</select>
							<p class="invalid-feedback" v-if="errors.status">{{errors.status[0]}}</p>
						</div>
						<button type="submit" class="btn btn-primary">
							<transition name="fade" mode="out-in">
								<Spiner v-if="loading" />
								<span v-else>Create Plan</span>
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
		name: "create-plan",
		components: { DatePicker },
		head() {
			return {
				title: `Create Plan - ${this.appName}`,
			};
		},

		data() {
			return {
				form: {
					name: "",
					description: "",
					durationDay: "",
					durationName: "",
					price: "",
					discount: false,
					discountPrice: "",
					discountDate: [],
					productLimit: "",
					storageLimit: "",
					variationLimit: "",
					inventory: true,
					pos: true,
					qrCode: true,
					support: true,
					status: false,
				},
				errors: {},
				click: true,
				loading: false,
			};
		},

		methods: {
			submit() {
				if (this.click) {
					this.click = false;
					this.$axios.post("create-plan", this.form).then(
						(response) => {
							$nuxt.$emit("success", response.data);
							this.click = true;
							this.$router.push(
								this.localePath("dashboard-admin-plan")
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
	};
</script>