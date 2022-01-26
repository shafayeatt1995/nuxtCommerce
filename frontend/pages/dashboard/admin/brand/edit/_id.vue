<template>
	<div>
		<div class="section-header">
			<h1>Create Plan</h1>
		</div>

		<div class="section-body">
			<form class="row" @submit.prevent="submit">
				<div class="col-lg-8">
					<div class="bg-white p-3 min-h100 card card-primary">
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
					<div class="bg-white p-3 min-h100 card card-primary">
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
								<span v-else>Update Plan</span>
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
		name: "edit-plan",
		components: { DatePicker },
		head() {
			return {
				title: `Edit Plan - ${this.appName}`,
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
					inventory: "",
					pos: "",
					qrCode: "",
					support: "",
					status: false,
				},
				errors: {},
				click: true,
				loading: false,
			};
		},

		methods: {
			editPlan() {
				this.$axios.get(`edit-plan/${this.$route.params.id}`).then(
					(response) => {
						this.form.name = response.data.plan.name;
						this.form.description = response.data.plan.description;
						this.form.durationDay = response.data.plan.duration_day;
						this.form.durationName = response.data.plan.duration_name;
						this.form.price = response.data.plan.price;
						this.form.discount = response.data.plan.discount_price
							? true
							: false;
						this.form.discountPrice = response.data.plan.discount_price;
						this.form.discountDate = [
							response.data.plan.discount_start,
							response.data.plan.discount_end,
						];
						this.form.productLimit = response.data.plan.product_limit;
						this.form.storageLimit = response.data.plan.storage_limit;
						this.form.variationLimit =
							response.data.plan.variation_limit;
						this.form.inventory = response.data.plan.inventory;
						this.form.pos = response.data.plan.pos;
						this.form.qrCode = response.data.plan.qr_code;
						this.form.support = response.data.plan.support;
						this.form.status = response.data.plan.status;
					},
					(error) => {
						$nuxt.$emit("error", error);
					}
				);
			},

			submit() {
				if (this.click) {
					this.click = false;
					this.$axios
						.post(`update-plan/${this.$route.params.id}`, this.form)
						.then(
							(response) => {
								$nuxt.$emit("success", response.data);
								this.click = true;
								this.$router.push({
									name: "dashboard-admin-plan___en",
								});
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
			this.editPlan();
		},
	};
</script>