<template>
	<!-- Your Section area start-->
	<div class="login-register">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 offset-lg-3">
					<div class="login-tab">
						<ul class="nav login-tab-title" id="myTab" role="tablist">
							<li>
								<nuxt-link :to="localePath('login')" class="active login-title-divider">
									<i>
										<icon :icon="['fas', 'sign-in-alt']"></icon>
									</i>
									Login Your Account
								</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('register')">
									<i>
										<icon :icon="['fas', 'user']"></icon>
									</i>
									Register Account
								</nuxt-link>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="login">
								<div class="auth">
									<h4>Login to Your Account</h4>
									<p>Login with your social network</p>
									<ul>
										<li>
											<button type="button" class="bg-red" @click="socialLogin('github')">
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
									<form class="auth-form login-form" @submit.prevent="login">
										<div class="form-group">
											<label for="email">Email address</label>
											<i>
												<icon :icon="['fas', 'user']"></icon>
											</i>
											<input type="email" id="email" class="form-control" v-model="credential.email" required>
										</div>
										<div class="form-group">
											<label for="password">Password</label>
											<i>
												<icon :icon="['fas', 'lock']"></icon>
											</i>
											<input type="password" id="password" class="form-control" v-model="credential.password" required>
										</div>
										<div class="form-group form-check">
											<input type="checkbox" class="form-check-input" id="remeber" name="remember">
											<label class="form-check-label Label-fix" for="remeber">Remember Me</label>
										</div>
										<a href="http://localhost/laracommerce/password/reset" class="forget-password">Forgot
											Password ?</a>
										<button type="submit" class="big-button" :class="loading ? 'disable-hover':''">
											<transition name="fade" mode="out-in">
												<Spiner v-if="loading" />
												<span v-else>Login</span>
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
				loading: false,
				credential: {
					email: "",
					password: "",
					social: false,
				},
			};
		},

		methods: {
			login() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.$auth
						.loginWith("laravelJWT", { data: this.credential })
						.then(
							() => {
								this.$router.push("/");
								this.click = true;
								this.loading = false;
							},
							() => {
								this.click = true;
								this.loading = false;
							}
						);
				}
			},
		},
	};
</script>