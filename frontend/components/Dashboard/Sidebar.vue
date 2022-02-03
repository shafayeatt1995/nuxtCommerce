<template>
	<div class="main-sidebar sidebar-style-2" :class="active ? 'active':''">
		<aside id="sidebar-wrapper">
			<div class="sidebar-brand">
				<nuxt-link :to="localePath('index')" target="_blank">
					<img :src="`${url}images/logo.png`" alt="logo" class="img-fluid px-5">
				</nuxt-link>
			</div>
			<ul class="sidebar-menu">
				<li>
					<nuxt-link :to="localePath('dashboard')" class="nav-link">
						<i>
							<icon :icon="['fas', 'tachometer-alt']"></icon>
						</i>
						<span>Dashboard</span>
					</nuxt-link>
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
								<nuxt-link :to="localePath('dashboard-admin-currency-create')">Create Currency</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-currency')">All Currency</nuxt-link>
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
				<li>
					<nuxt-link :to="localePath('dashboard-admin-commission')">
						<i>
							<icon :icon="['fas', 'percent']"></icon>
						</i>
						<span>Commission</span>

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
				plan: false,
				brand: false,
				category: false,
				productOptions: false,
				currency: false,
				address: false,
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