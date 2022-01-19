<template>
	<section class="section">
		<div class="container mt-5">
			<div class="row">
				<div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
					<div class="login-brand">
						<img :src="`${url}images/logo.png`" alt="logo" width="150">
					</div>

					<div class="card card-warning">
						<div class="card-header">
							<h4>Login</h4>
						</div>

						<div class="card-body">
							<form class="needs-validation" @submit.prevent="login">
								<div class="form-group">
									<label for="email">Email</label>
									<input id="email" type="email" class="form-control" v-model="credential.email" tabindex="1" required autofocus />
									<p class="invalid-feedback" v-if="error">Email not found</p>

								</div>

								<div class="form-group">
									<div class="d-block">
										<label for="password" class="control-label">Password</label>
										<div class="float-right">
											<a href="auth-forgot-password.html" class="text-small">
												Forgot Password?
											</a>
										</div>
									</div>
									<input id="password" type="password" class="form-control" v-model="credential.password" tabindex="2" required />
									<p class="invalid-feedback" v-if="error">Password not matched</p>
								</div>

								<div class="form-group">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
										<label class="custom-control-label" for="remember-me">Remember Me</label>
									</div>
								</div>

								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-lg btn-block" :class="loading ? 'disable-hover':''" tabindex="4">
										<transition name="fade" mode="out-in">
											<span v-if="loading" key="1">Please Wait</span>
											<span v-else key="2">Login</span>
										</transition>
									</button>
								</div>
							</form>
							<div class="text-center my-5">
								<p>Copyright &copy; v-commerce 2022</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>
<script>
	export default {
		auth: "guest",
		head() {
			return {
				title: `Dashboard Login - ${this.appName}`,
			};
		},
		data() {
			return {
				click: true,
				loading: false,
				credential: {
					email: "",
					password: "",
				},
				error: false,
			};
		},

		methods: {
			login() {
				if (this.click) {
					this.click = false;
					this.loading = true;
					this.error = false;
					this.$auth
						.loginWith("laravelJWT", { data: this.credential })
						.then(
							(response) => {
								this.$router.push("../dashboard");
								this.click = true;
								this.loading = false;
							},
							(e) => {
								this.error = true;
								this.click = true;
								this.loading = false;
							}
						);
				}
			},
		},

		mounted() {
			document.title = "Login";
		},
	};
</script>