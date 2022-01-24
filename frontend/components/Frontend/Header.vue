<template>
	<div>
		<!--Off canvas menu start-->
		<div class="off-canvas-menu" :class="active === 'cart' ? 'off-canvas-triggered':''">
			<button class="cart-close-btn" @click="activeSection('cart')">
				<i>
					<icon :icon="['fas', 'times']"></icon>
				</i>
			</button>
			<h3>Shopping Cart</h3>
			<div class="off-canvas-cart-product">
				<a href="#" class="off-canvas-cart-product-image">
					<!-- <img src="#" data-src="assets/img/product/1.jpg" alt="" class="img-fluid lazy" /> -->
				</a>
				<div class="off-canvas-cart-product-info">
					<a href="#">Herschel Leather Duffle Sun Glass In Brown Color</a>
					<h5>
						2
						<i>
							<icon :icon="['fas', 'times']"></icon>
						</i>
						$40 = $80
					</h5>
					<a href="#"><i>
							<icon :icon="['fas', 'times']"></icon>
						</i></a>
				</div>
				<hr />
			</div>
			<div class="off-canvas-cart-product">
				<a href="#" class="off-canvas-cart-product-image">
					<!-- <img src="#" data-src="assets/img/product/2.jpg" alt="" class="img-fluid lazy" /> -->
				</a>
				<div class="off-canvas-cart-product-info">
					<a href="#"> Herschel Leather Duffle Bag In Brown Color </a>
					<h5>
						2
						<i>
							<icon :icon="['fas', 'times']"></icon>
						</i>
						$40 = $80
					</h5>
					<a href="#"><i>
							<icon :icon="['fas', 'times']"></icon>
						</i></a>
				</div>
				<hr />
			</div>
			<hr />
			<div class="off-canvas-cart-product-amount">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Heading</th>
							<th>amount</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Subtotal:</td>
							<td>$80</td>
						</tr>
						<tr>
							<td>Discount:</td>
							<td>$5</td>
						</tr>
						<tr>
							<td><b>Total:</b></td>
							<td><b>$100</b></td>
						</tr>
					</tbody>
				</table>
				<a href="#" class="cta-button-grey">View Cart</a>
				<a href="#" class="cta-button">Checkout</a>
			</div>
		</div>
		<div class="off-canvas-overlay" :class="offCanvasOverlay ? 'off-canvas-triggered':''" @click="activeSection('')"></div>
		<!--Off canvas menu end-->

		<!--Back to top area start-->
		<transition name="fade" mode="out-in">
			<button class="back-to-top" @click="scrollTop" v-if="scroll > 200"><i>
					<icon :icon="['fas', 'arrow-up']"></icon>
				</i>
			</button>
		</transition>
		<!--Back to top area end-->

		<!--My account area start-->
		<div class="my-accoount mobile-only" :class="active === 'myAccount' ? 'my-account-show':''">
			<FrontendMyAccount />
		</div>
		<!--My account area end-->

		<!--Header area start-->
		<header>
			<div class="container">
				<!--Header top area start-->
				<div id="header-top" class="header-top">
					<div class="row">
						<div class="col-lg-6">
							<div class="header-top-left">
								<ul>
									<li><a href="about.html">{{$t('aboutUs')}}</a></li>
									<li><a href="contact-us.html">{{$t('contacts')}} </a></li>
									<li>
										<nuxt-link :to="localePath('dashboard', 'en')" target="_blank" v-if="authCheck">{{$t('becomeASeller')}}</nuxt-link>
										<nuxt-link :to="localePath('dashboard-login', 'en')" target="_blank" v-else>{{$t('becomeASeller')}}</nuxt-link>
									</li>
								</ul>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="header-top-right">
								<ul>
									<li class="pc-only">
										<p>{{$t('myAccount')}}
											<span>
												<i>
													<icon :icon="['fas', 'chevron-down']"></icon>
												</i>
											</span>
										</p>
										<FrontendMyAccount />
									</li>
									<li>
										<p>{{$t('currency')}}
											<span>
												<i>
													<icon :icon="['fas', 'chevron-down']"></icon>
												</i>
											</span>
										</p>
										<ul>
											<li><a href="#">€ Euro</a></li>
											<li><a href="#">£ Pound</a></li>
											<li><a href="#">$ USD</a></li>
											<li><a href="#">৳ BDT</a></li>
										</ul>
									</li>
									<li>
										<p>{{$t('language')}}
											<span>
												<i>
													<icon :icon="['fas', 'chevron-down']"></icon>
												</i>
											</span>
										</p>
										<ul>
											<li>
												<nuxt-link :to="switchLocalePath('en')">
													<img src="@/assets/images/flags/usa.png" alt=""> English
												</nuxt-link>
											</li>
											<li>
												<nuxt-link :to="switchLocalePath('fr')">
													<img src="@/assets/images/flags/france.png" alt=""> French
												</nuxt-link>
											</li>
										</ul>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<!--Header top area end-->

				<!--Header body area start-->
				<div class="header-body">
					<div class="row">
						<div class="col-lg-2">
							<div class="logo">
								<nuxt-link :to="localePath('index')">
									<img :src="`${url}images/logo.png`" alt="logo" class="img-fluid" />
									<div class="mobile-menu"></div>
								</nuxt-link>
								<button class="mobile-only">
									<i>
										<icon :icon="['fas', 'bars']"></icon>
									</i>
								</button>
							</div>
						</div>

						<div class="col-lg-8">
							<div class="header-search">
								<form class="search">
									<select class="search-categories form-select" :style="`background-image:url(${url}images/dropdown.svg);`">
										<option value="" selected>All Categories</option>
										<option value="instruments">Instruments</option>
										<option value="power-tools">Power Tools</option>
										<option value="hand-tools">Hand Tools</option>
										<option value="machine-tools">Machine Tools</option>
										<option value="power-machinery">Power Machinery</option>
										<option value="measurement">Measurement</option>
										<option value="clothes-and-ppe">Clothes and PPE</option>
										<option value="electronics">Electronics</option>
										<option value="computers">Computers</option>
										<option value="automotive">Automotive</option>
										<option value="furniture-appliances">Furniture</option>
										<option value="music">Music</option>
										<option value="books">Books</option>
										<option value="health-beauty">Health &amp; Beauty</option>
									</select>
									<input type="text" placeholder="Search.." name="search" />
									<button type="submit">{{$t('search')}}</button>
								</form>
							</div>
						</div>
						<div class="col-lg-2">
							<ul class="nav-cart">
								<li class="mobile-only">
									<nuxt-link :to="localePath('index')">
										<i>
											<icon :icon="['fas', 'home']"></icon>
										</i>
									</nuxt-link>
								</li>
								<li class="pc-only">
									<button>
										<i>
											<icon :icon="['far', 'chart-bar']"></icon>
										</i>
										<small class="color-white">3</small>
									</button>
								</li>
								<li>
									<button>
										<i>
											<icon :icon="['far', 'heart']"></icon>
										</i>
										<small class="color-white">2</small>
									</button>
								</li>
								<li>
									<button type="button" @click="activeSection('cart')">
										<i>
											<icon :icon="['fab', 'opencart']"></icon>
										</i>
										<small class="color-white">5</small>
									</button>
								</li>
								<li class="mobile-only">
									<button type="button" @click="activeSection('myAccount')">
										<i>
											<icon :icon="['far', 'user']"></icon>
										</i>
									</button>
								</li>
							</ul>

						</div>
					</div>
				</div>
				<!--Header body area end-->
			</div>
			<div class="btb"></div>
			<div class="container">
				<!--Header navbar area start-->
				<div id="navigation" class="navigation">
					<ul>
						<li>
							<nuxt-link :to="localePath('index')">Home</nuxt-link>
						</li>
						<li>
							<a href="single-store.html">Shop
								<span><i>
										<icon :icon="['fas', 'chevron-down']"></icon>
									</i></span></a>
							<ul>
								<li>
									<a href="feature-brands.html"><span></span> Feature Brands</a>
								</li>
								<li>
									<a href="cart.html"><span></span> Cart</a>
								</li>
								<li>
									<a href="checkout.html"><span></span> Checkout</a>
								</li>
								<li>
									<a href="checkout-success.html"><span></span> Checkout Success</a>
								</li>
								<li>
									<a href="wishlist.html"><span></span> Wish list</a>
								</li>
								<li>
									<a href="order-details.html"><span></span> Track Order</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="products.html">Product
								<span><i>
										<icon :icon="['fas', 'chevron-down']"></icon>
									</i></span></a>
							<ul>
								<li>
									<a href="top-store.html"><span></span> Store</a>
								</li>
								<li>
									<a href="products.html"><span></span> Products</a>
								</li>
								<li>
									<a href="single-product.html"><span></span> Single Products</a>
								</li>
								<li>
									<a href="single-store.html"><span></span> Single store</a>
								</li>
								<li>
									<a href="single-feature-brand.html"><span></span> Single Brands</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="dashboard.html">Account
								<span><i>
										<icon :icon="['fas', 'chevron-down']"></icon>
									</i></span></a>
							<ul>
								<li>
									<a href="login.html"><span></span> Login</a>
								</li>
								<li>
									<a href="dashboard.html"><span></span> Dashboard</a>
								</li>
								<li>
									<a href="edit-profile.html"><span></span> Edit Profile</a>
								</li>
								<li>
									<a href="order-history.html"><span></span> Order History</a>
								</li>
								<li>
									<a href="order-details.html"><span></span> Order Details</a>
								</li>
								<li>
									<a href="edit-address.html"><span></span> Edit Address</a>
								</li>
								<li>
									<a href="edit-password.html"><span></span> Change Password</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="contact-us.html">Pages
								<span><i>
										<icon :icon="['fas', 'chevron-down']"></icon>
									</i></span></a>
							<ul>
								<li>
									<a href="about.html"><span></span> About Us</a>
								</li>
								<li>
									<a href="contact-us.html"><span></span> Contact Us</a>
								</li>
								<li>
									<a href="404.html"><span></span> 404</a>
								</li>
								<li>
									<a href="terms.html"><span></span> Terms &amp; Condition</a>
								</li>
								<li>
									<a href="faq.html"><span></span> FAQ</a>
								</li>
							</ul>
						</li>
						<li><a href="#">Buy Theme</a></li>
					</ul>
					<!--Header navbar area end-->
				</div>
			</div>
		</header>
		<!--Header area end-->
		<div class="mt-40"></div>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				offCanvasOverlay: false,
				active: "",
				scroll: 0,
			};
		},

		methods: {
			// Active mobile menu or other section
			activeSection(section) {
				if (this.active !== section) {
					this.active = section;
				} else {
					this.active = "";
				}
				this.offCanvasOverlay = !this.offCanvasOverlay;
			},

			// For Tracking Scroll
			handleScroll() {
				this.scroll = window.scrollY;
			},

			// Scroll to top
			scrollTop() {
				window.scrollTo(0, 0);
			},
		},

		// For Tracking Scroll
		beforeMount() {
			window.addEventListener("scroll", this.handleScroll);
		},
		beforeDestroy() {
			window.removeEventListener("scroll", this.handleScroll);
		},

		watch: {
			$route(to, from) {
				this.active = "";
				this.offCanvasOverlay = false;
			},
		},
	};
</script>
