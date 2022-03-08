<template>
	<div class="main-sidebar" :class="active ? 'active':''" :style="dashboardModal ? 'z-index: 0 !important':''">
		<aside id="sidebar-wrapper">
			<div class="sidebar-brand">
				<nuxt-link :to="localePath('index')" target="_blank">
					<img :src="`${url}images/logo.png`" alt="logo" class="img-fluid px-5">
				</nuxt-link>
			</div>
			<ul class="sidebar-menu" v-if="admin">
				<li>
					<nuxt-link :to="localePath('dashboard')" class="nav-link">
						<i>
							<icon :icon="['fas', 'tachometer-alt']"></icon>
						</i>
						<span>Dashboard</span>
					</nuxt-link>
				</li>
				<li class="sidebar-dropdown">
					<a href="#" class="has-dropdown" :class="store || route.substring(17, 22) === 'store' ? 'dropdown-active' : ''" @click.prevent="store = !store">
						<i>
							<icon :icon="['fas', 'store']"></icon>
						</i>
						<span>Store
							<i :class="store">
								<icon :icon="['fas', 'chevron-right']"></icon>
							</i>
						</span>
					</a>
					<transition name="slide" mode="out-in">
						<ul class="sidebar-dropdown-menu" v-if="store || route.substring(17, 22) === 'store'">
							<li>
								<nuxt-link :to="localePath('dashboard-admin-store')">All Store</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-store-pending')">New Store Request</nuxt-link>
							</li>
						</ul>
					</transition>
				</li>
				<li class="sidebar-dropdown">
					<a href="#" class="has-dropdown" :class="brand || route.substring(17, 22) === 'brand' ? 'dropdown-active' : ''" @click.prevent="brand = !brand">
						<i>
							<icon :icon="['fas', 'tags']"></icon>
						</i>
						<span>Brand
							<i :class="brand">
								<icon :icon="['fas', 'chevron-right']"></icon>
							</i>
						</span>
					</a>
					<transition name="slide" mode="out-in">
						<ul class="sidebar-dropdown-menu" v-if="brand || route.substring(17, 22) === 'brand'">
							<li>
								<nuxt-link :to="localePath('dashboard-admin-brand')">All Brand</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-brand-create')">Create Brand</nuxt-link>
							</li>
						</ul>
					</transition>
				</li>
				<li class="sidebar-dropdown">
					<a href="#" class="has-dropdown" :class="category || route.substring(17, 25) === 'category' ? 'dropdown-active' : ''" @click.prevent="category = !category">
						<i>
							<icon :icon="['far', 'list-alt']"></icon>
						</i>
						<span>Category
							<i :class="category">
								<icon :icon="['fas', 'chevron-right']"></icon>
							</i>
						</span>
					</a>
					<transition name="slide" mode="out-in">
						<ul class="sidebar-dropdown-menu" v-if="category || route.substring(17, 25) === 'category'">
							<li>
								<nuxt-link :to="localePath('dashboard-admin-category')">Category</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-category-sub')">Sub Category</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-category-child')">Child Category</nuxt-link>
							</li>
						</ul>
					</transition>
				</li>
				<li class="sidebar-dropdown">
					<a href="#" class="has-dropdown" :class="productOptions || route.substring(17, 32) === 'product-options' ? 'dropdown-active' : ''" @click.prevent="productOptions = !productOptions">
						<i>
							<icon :icon="['fas', 'tasks']"></icon>
						</i>
						<span>Product Options
							<i :class="category">
								<icon :icon="['fas', 'chevron-right']"></icon>
							</i>
						</span>
					</a>
					<transition name="slide" mode="out-in">
						<ul class="sidebar-dropdown-menu" v-if="productOptions || route.substring(17, 32) === 'product-options'">
							<li>
								<nuxt-link :to="localePath('dashboard-admin-product-options-material')">Material</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-product-options-color')">Color Family</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-product-options-size')">Size</nuxt-link>
							</li>
						</ul>
					</transition>
				</li>
				<li class="sidebar-dropdown">
					<a href="#" class="has-dropdown" :class="currency || route.substring(17, 25) === 'currency' ? 'dropdown-active' : ''" @click.prevent="currency = !currency">
						<i>
							<icon :icon="['far', 'money-bill-alt']"></icon>
						</i>
						<span>Currency
							<i :class="currency">
								<icon :icon="['fas', 'chevron-right']"></icon>
							</i>
						</span>
					</a>
					<transition name="slide" mode="out-in">
						<ul class="sidebar-dropdown-menu" v-if="currency || route.substring(17, 25) === 'currency'">
							<li>
								<nuxt-link :to="localePath('dashboard-admin-currency')">All Currency</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-currency-create')">Create Currency</nuxt-link>
							</li>
						</ul>
					</transition>
				</li>
				<li class="sidebar-dropdown">
					<a href="#" class="has-dropdown" :class="address || route.substring(17, 24) === 'address' ? 'dropdown-active' : ''" @click.prevent="address = !address">
						<i>
							<icon :icon="['fas', 'map-marker-alt']"></icon>
						</i>
						<span>Shipping Address
							<i :class="address">
								<icon :icon="['fas', 'chevron-right']"></icon>
							</i>
						</span>
					</a>
					<transition name="slide" mode="out-in">
						<ul class="sidebar-dropdown-menu" v-if="address || route.substring(17, 24) === 'address'">
							<li>
								<nuxt-link :to="localePath('dashboard-admin-address-country')">Country</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-address-state')">State</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-address-city')">City</nuxt-link>
							</li>
						</ul>
					</transition>
				</li>
				<li class="sidebar-dropdown">
					<a href="#" class="has-dropdown" :class="cost || route.substring(17, 30) === 'shipping-cost' ? 'dropdown-active' : ''" @click.prevent="cost = !cost">
						<i>
							<icon :icon="['fas', 'money-check-alt']"></icon>
						</i>
						<span>Shipping Cost
							<i :class="cost">
								<icon :icon="['fas', 'chevron-right']"></icon>
							</i>
						</span>
					</a>
					<transition name="slide" mode="out-in">
						<ul class="sidebar-dropdown-menu" v-if="cost || route.substring(17, 30) === 'shipping-cost'">
							<li>
								<nuxt-link :to="localePath('dashboard-admin-shipping-cost-create')">Create Shipping Cost</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-shipping-cost')">Shipping Cost</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-shipping-cost-set')">Manage Rules</nuxt-link>
							</li>
						</ul>
					</transition>
				</li>
				<li class="sidebar-dropdown">
					<a href="#" class="has-dropdown" :class="product || route.substring(17, 24) === 'product' ? 'dropdown-active' : ''" @click.prevent="product = !product">
						<i>
							<icon :icon="['fas', 'boxes']"></icon>
						</i>
						<span>Product
							<i :class="product">
								<icon :icon="['fas', 'chevron-right']"></icon>
							</i>
						</span>
					</a>
					<transition name="slide" mode="out-in">
						<ul class="sidebar-dropdown-menu" v-if="product || route.substring(17, 24) === 'product'">
							<li>
								<nuxt-link :to="localePath('dashboard-admin-product')">All Product</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-product-pending')">Pendiing Product</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-product-suspend')">Suspend Product</nuxt-link>
							</li>
						</ul>
					</transition>
				</li>
				<li>
					<nuxt-link :to="localePath('dashboard-admin-request')">
						<i>
							<icon :icon="['fas', 'plus']"></icon>
						</i>
						<span>Request</span>

					</nuxt-link>
				</li>
				<li>
					<nuxt-link :to="localePath('dashboard-admin-commission')">
						<i>
							<icon :icon="['fas', 'percent']"></icon>
						</i>
						<span>Commission</span>

					</nuxt-link>
				</li>
			</ul>
			<ul class="sidebar-menu" v-if="seller">
				<li>
					<nuxt-link :to="localePath('dashboard')" class="nav-link">
						<i>
							<icon :icon="['fas', 'tachometer-alt']"></icon>
						</i>
						<span>Dashboard</span>
					</nuxt-link>
				</li>
				<li class="sidebar-dropdown">
					<a href="#" class="has-dropdown" :class="product || route.substring(18, 25) === 'product' ? 'dropdown-active' : ''" @click.prevent="product = !product">
						<i>
							<icon :icon="['fas', 'boxes']"></icon>
						</i>
						<span>Products
							<i :class="product">
								<icon :icon="['fas', 'chevron-right']"></icon>
							</i>
						</span>
					</a>
					<transition name="slide" mode="out-in">
						<ul class="sidebar-dropdown-menu" v-if="product || route.substring(18, 25) === 'product'">
							<li>
								<nuxt-link :to="localePath('dashboard-seller-product')">All Product</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-seller-product-create')">Create Product</nuxt-link>
							</li>
						</ul>
					</transition>
				</li>
				<li class="sidebar-dropdown">
					<a href="#" class="has-dropdown" :class="request || route.substring(18, 25) === 'request' ? 'dropdown-active' : ''" @click.prevent="request = !request">
						<i>
							<icon :icon="['fas', 'plus']"></icon>
						</i>
						<span>Request
							<i :class="request">
								<icon :icon="['fas', 'chevron-right']"></icon>
							</i>
						</span>
					</a>
					<transition name="slide" mode="out-in">
						<ul class="sidebar-dropdown-menu" v-if="request || route.substring(18, 25) === 'request'">
							<li>
								<nuxt-link :to="localePath('dashboard-seller-request')">My Request</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-seller-request-brand')">Request Brand</nuxt-link>
							</li>
						</ul>
					</transition>
				</li>
				<li>
					<nuxt-link :to="localePath('dashboard-seller-commission')">
						<i>
							<icon :icon="['fas', 'percent']"></icon>
						</i>
						<span>Commission List</span>

					</nuxt-link>
				</li>
			</ul>
		</aside>
	</div>
</template>
<script>
	export default {
		props: {
			active: "",
		},

		data() {
			return {
				route: "",
				store: false,
				brand: false,
				category: false,
				productOptions: false,
				currency: false,
				address: false,
				cost: false,
				product: false,
				request: false,
			};
		},

		methods: {},

		created() {
			this.route = this.$route.path;
		},

		watch: {
			$route(to, from) {
				this.route = to.path;
			},
		},
	};
</script>