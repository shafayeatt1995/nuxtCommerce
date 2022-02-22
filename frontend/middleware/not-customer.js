export default function ({ store, redirect }) {
	if (store.getters.isCustomer) {
		return redirect('/')
	}
}