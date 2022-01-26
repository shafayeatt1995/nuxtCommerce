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
					<nuxt-link :to="localePath('dashboard')" class="nav-link" :class="active">
						<i>
							<icon :icon="['fas', 'tachometer-alt']"></icon>
						</i>
						<span>Dashboard</span>
					</nuxt-link>
				</li>
				<li class="sidebar-dropdown">
					<a href="#" class="has-dropdown" :class="plan || route.substring(17, 21) === 'plan' ? 'dropdown-active' : ''" @click.prevent="plan = !plan">
						<i>
							<icon :icon="['fas', 'box-open']"></icon>
						</i>
						<span>Plan
							<i :class="plan">
								<icon :icon="['fas', 'chevron-right']"></icon>
							</i>
						</span>
					</a>
					<transition name="slide" mode="out-in">
						<ul class="sidebar-dropdown-menu" v-if="plan || route.substring(17, 21) === 'plan'">
							<li>
								<nuxt-link :to="localePath('dashboard-admin-plan')">All Plan</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-plan-create')">Create Plan</nuxt-link>
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