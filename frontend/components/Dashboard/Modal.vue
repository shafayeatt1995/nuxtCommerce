<template>
	<div class="modal fade show" style="display: block; padding-right: 17px;">
		<div class="modal-dialog" :class="customClass" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{title}}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<slot name="body"></slot>
				</div>
				<div class="modal-footer bg-whitesmoke br">
					<slot name="footer-btn"></slot>
					<button type="button" class="btn btn-danger" @click="closeModal">Close</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			title: "",
			customClass: "",
		},
		methods: {
			closeModal() {
				this.$store.dispatch("dashboardModal", false);
			},
		},
	};
</script>