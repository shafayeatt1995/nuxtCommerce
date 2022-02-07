export default function ({ store, redirect }) {
	if (!store.getters.isSeller) {
		return redirect('/dashboard')
	}
}