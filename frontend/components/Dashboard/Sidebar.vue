<template>
	<div class="main-sidebar sidebar-style-2" :class="active ? 'active':''">
		<aside id="sidebar-wrapper">
			<div class="sidebar-brand">
				<a href="index.html" class="d-block">
					<img :src="`${url}images/logo.png`" alt="logo" class="img-fluid px-5">
				</a>
			</div>
			<ul class="sidebar-menu">
				<li :class="route === '/dashboard' ?'active' : ''">
					<nuxt-link :to="localePath('dashboard')" class="nav-link" :class="active">
						<i>
							<icon :icon="['fas', 'tachometer-alt']"></icon>
						</i>
						<span>Dashboard</span>
					</nuxt-link>
				</li>
				<li class="sidebar-dropdown">
					<a href="#" class="has-dropdown" :class="route.substring(0, 21) === '/dashboard/admin/plan' ? 'dropdown-active' : ''" @click.prevent="changeMenu('/dashboard/admin/plan')">
						<i>
							<icon :icon="['fas', 'columns']"></icon>
						</i>
						<span>Plan
							<i :class="activeMenu.substring(0, 21) === '/dashboard/admin/plan' ? 'rotate-90' : ''">
								<icon :icon="['fas', 'chevron-right']"></icon>
							</i>
						</span>
					</a>
					<Slide :active="activeMenu.substring(0, 21) === '/dashboard/admin/plan'" :duration="300">
						<ul class="sidebar-dropdown-menu">
							<li>
								<nuxt-link :to="localePath('dashboard-admin-plan')">All Plan</nuxt-link>
							</li>
							<li>
								<nuxt-link :to="localePath('dashboard-admin-plan-create')">Create Plan</nuxt-link>
							</li>
						</ul>
					</Slide>
				</li>
				<li :class="route === '/dashboard' ?'active' : ''">
					<nuxt-link :to="localePath('dashboard')" class="nav-link" :class="active">
						<i>
							<icon :icon="['fas', 'tachometer-alt']"></icon>
						</i>
						<span>Dashboard</span>
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
				activeMenu: "",
				route: "",
			};
		},

		methods: {
			changeMenu(menu) {
				this.activeMenu = this.activeMenu !== menu ? menu : "";
			},
		},

		created() {
			this.activeMenu = this.$route.path;
			this.route = this.$route.path;
		},

		watch: {
			$route(to, from) {
				this.route = to.path;
			},
		},
	};
</script>