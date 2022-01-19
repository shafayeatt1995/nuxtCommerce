<template lang="">
<ul v-if="authCheck">
	<li v-if="admin || seller">
		<nuxt-link :to="localePath('dashboard', 'en')" target="_blank" v-if="authCheck">
			<i>
				<icon :icon="['fas', 'tachometer-alt']"></icon>
			</i>
			Dashboard
		</nuxt-link>
		<nuxt-link :to="localePath('dashboard-login', 'en')" target="_blank" v-else>
			<i>
				<icon :icon="['fas', 'tachometer-alt']"></icon>
			</i>
			Dashboard
		</nuxt-link>
	</li>
	<li>
		<nuxt-link :to="localePath('profile')">
			<i>
				<icon :icon="['far', 'user-circle']"></icon>
			</i>
			Profile
		</nuxt-link>
	</li>
	<li>
		<a href="edit-profile.html"><i>
				<icon :icon="['fas', 'edit']"></icon>
			</i>
			Edit Profile</a>
	</li>
	<li>
		<a href="order-history.html"><i>
				<icon :icon="['fas', 'history']"></icon>
			</i>
			Order History</a>
	</li>
	<li>
		<a href="#" @click.prevent="logout"><i>
				<icon :icon="['fas', 'sign-out-alt']"></icon>
			</i>
			Log Out</a>
	</li>
</ul>
<ul v-else>
	<li>
		<nuxt-link :to="localePath('login')">
			<i>
				<icon :icon="['far', 'user-circle']"></icon>
			</i>
			Login
		</nuxt-link>
	</li>
	<li v-if="!authCheck">
		<nuxt-link :to="localePath('register')">
			<i>
				<icon :icon="['far', 'user-circle']"></icon>
			</i>
			Register
		</nuxt-link>
	</li>
</ul>
</template>
<script>
	export default {
		methods: {
			// Logout
			logout() {
				this.$auth.logout("laravelJWT").then(
					() => {
						this.$router.push("/");
					},
					() => {}
				);
			},
		},
	};
</script>