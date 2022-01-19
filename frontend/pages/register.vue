<template>
	<!-- Your Section area start-->
	<div class="login-register">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 offset-lg-3">
					<div class="login-tab">
						<ul class="nav login-tab-title" id="myTab" role="tablist">
							<li>
								<nuxt-link :to="localePath('login')" class="login-title-divider">
									<i>
										<icon :icon="['fas', 'sign-in-alt']"></icon>
									</i>
									Login Your Account
								</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('register')" class="active">
									<i>
										<icon :icon="['fas', 'user']"></icon>
									</i>
									Register Account
								</nuxt-link>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="register">
								<div class="auth">
									<h4>Create Your Account</h4>
									<p>Create an account</p>
									<ul>
										<li>
											<button type="button" class="bg-red">
												<i>
													<icon :icon="['fab', 'google']"></icon>
												</i>
												Google
											</button>
										</li>
										<li>
											<button type="button" class="bg-blue">
												<i class="fab fa-facebook-f">
													<icon :icon="['fab', 'facebook-f']"></icon>
												</i>
												Facebook
											</button>
										</li>
										<li>
											<button type="button" class="bg-ltblue">
												<i>
													<icon :icon="['fab', 'twitter']"></icon>
												</i>
												Twitter
											</button>
										</li>
									</ul>
									<div class="form-divider">
										<p>Or</p>
									</div>
									<form class="auth-form register-form" @submit.prevent="register">
										<div class="form-group">
											<label for="name">Name</label>
											<i>
												<icon :icon="['fas', 'user']"></icon>
											</i>
											<input type="text" id="name" class="form-control" v-model="credential.name" required>
										</div>
										<div class="form-group">
											<label for="email">Email address</label>
											<i>
												<icon :icon="['fas', 'envelope']"></icon>
											</i>
											<input type="email" class="form-control" id="email" v-model="credential.email" required>
										</div>
										<div class="form-group">
											<label for="password">Password</label>
											<i>
												<icon :icon="['fas', 'lock']"></icon>
											</i>
											<input type="password" id="password" class="form-control" v-model="credential.password" required>
											<small id="emailHelp" class="form-text text-muted">Must use 8-15 characters and
												one number or symbol.
											</small>
										</div>
										<div class="form-group">
											<label for="password-confirm">Confirm Password</label>
											<i>
												<icon :icon="['fas', 'lock']"></icon>
											</i>
											<input type="password" class="form-control" id="password-confirm" v-model="credential.password_confirmation" required>
										</div>
										<div class="form-group form-check">
											<input type="checkbox" class="form-check-input" id="reg" v-model="accept">
											<label class="form-check-label Label-fix" for="reg">I Agree to V-Commerce <a href="terms.html">Terms of Services</a></label>
										</div>
										<button class="big-button" type="submit" :class="loading || !accept ? 'disable-hover':''">
											<transition name="fade" mode="out-in">
												<Spiner v-if="loading" />
												<span v-else>Register</span>
											</transition>
										</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Your Section area end-->
</template>
<script>
	export default {
		auth: "guest",

		head() {
			return {
				title: `Login - ${this.appName}`,
			};
		},

		data() {
			return {
				click: true,
				accept: false,
				loading: false,
				credential: {
					name: "",
					email: "",
					password: "",
					password_confirmation: "",
				},
			};
		},

		methods: {
			register() {
				if (this.click) {
					this.click = false;
					this.$axios.post("register", this.credential).then(
						() => {
							this.$auth
								.loginWith("laravelJWT", { data: this.credential })
								.then(
									() => {
										this.loading = false;
									},
									() => {
										this.loading = false;
										// $nuxt.$emit(
										// 	"customError",
										// 	"Email or Password Not Matched"
										// );
									}
								);
							this.click = true;
						},
						(error) => {
							// $nuxt.$emit('error', error);
							this.click = true;
						}
					);
				}
				this.$auth.loginWith("laravelJWT", { data: this.credential });
			},
		},
	};
</script>