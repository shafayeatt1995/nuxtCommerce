import axios from 'axios';
export const state = () => ({
	url: process.env.URL,
	appName: ''
})

export const getters = {
	url: (state) => state.url,
	appName: (state) => state.appName,
	authentication: (state) => state.auth.loggedIn,
	user: (state) => state.auth.user,
	isAdmin: (state) => state.auth.loggedIn ? state.auth.user.role_id === 1 ? true : false : false,
	isSeller: (state) => state.auth.loggedIn ? state.auth.user.role_id === 2 ? true : false : false,
	isCustomer: (state) => state.auth.loggedIn ? state.auth.user.role_id === 3 ? true : false : false,
}

export const mutations = {
	// Get Initial Data
	setup(state, response) {
		state.appName = response.data.appName;
	},
}

export const actions = {
	// Get Initial Data
	async nuxtServerInit(context) {
		const response = await axios.get(context.state.url + 'api/app');
		context.commit('setup', response);
	},
	nuxtClientInit(context) {
		axios.get(context.state.url + 'api/app').then(
			(response) => {
				context.commit('setup', response);
			}
		)
	},
}
