import Vue from 'vue';
import { mapGetters } from 'vuex'


const GlobalData = {
	install(Vue, option) {
		Vue.mixin({
			computed: {
				...mapGetters({
					url: 'url',
					appName: 'appName',
					authCheck: 'authentication',
					user: 'user',
					admin: 'isAdmin',
					seller: 'isSeller',
					customer: 'isCustomer',
					activeCurrencyIcon: 'activeCurrencyIcon',
					activeCurrencyRate: 'activeCurrencyRate',
					defaultCurrencyIcon: 'defaultCurrencyIcon',
					defaultCurrencyRate: 'defaultCurrencyRate',
					dashboardModal: 'dashboardModal',
				})
			}
		})
	}
}

Vue.use(GlobalData)