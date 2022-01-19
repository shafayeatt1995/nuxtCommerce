<template>
	<!-- Shopping cart area start-->
	<div class="dashboard">
		<div class="container">
			<div class="section-title">
				<div class="row">
					<div class="col-lg-12">
						<h3><i class="far fa-address-card"></i> My Account</h3>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-lg-3">
					<Sidebar />
				</div>
				<div class="col-lg-9">
					<div class="dashboard-body">
						<div class="row">
							<div class="col-lg-6">
								<div class="dashboard-profile">
									<img src="#" data-src="assets/img/avatar/avatar-1.jpg" alt="" class="img-fluid lazy">
									<p>Shafayet Al-Anik</p>
									<h6>shafayealanik@gmail.com</h6>
									<a href="#">Edit Profile</a>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="address">
									<h6 class="bg-blue color-white">Shipping Address</h6>
									<div class="address-name">
										<h3>Shafayet Al-Anik</h3>
										<p>
											<small>Address:</small>
											Naogaon Dhaka Bagladesh
										</p>
									</div>
									<div class="address-phone">
										<p><small>Phone:</small> +8801728-293635</p>
									</div>
									<div class="address-email">
										<p><small>Email:</small> shafayetalanik@gmail.com</p>
									</div>
									<a href="#" class="cta-blue-btn">Edit Address</a>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="dashboard-order">
									<h3>Recent Order</h3>
									<table class="table table-hover table-responsive-sm">
										<thead class="text-center">
											<tr>
												<th>Order Number</th>
												<th>Date</th>
												<th>Status</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody class="text-center">
											<tr>
												<td><a href="order-details.html">#228</a></td>
												<td>30 August, 2020</td>
												<td>Pending</td>
												<td>$150.00 for 2 item(s)</td>
											</tr>
											<tr>
												<td><a href="order-details.html">#280</a></td>
												<td>12 May, 2020</td>
												<td>Waerhouse</td>
												<td>$150.00 for 2 item(s)</td>
											</tr>
											<tr>
												<td><a href="order-details.html">#864</a></td>
												<td>21 February, 2020</td>
												<td>Shiped</td>
												<td>$150.00 for 2 item(s)</td>
											</tr>
											<tr>
												<td><a href="order-details.html">#280</a></td>
												<td>12 May, 2020</td>
												<td>Waerhouse</td>
												<td>$150.00 for 2 item(s)</td>
											</tr>
											<tr>
												<td><a href="order-details.html">#864</a></td>
												<td>21 February, 2020</td>
												<td>Shiped</td>
												<td>$150.00 for 2 item(s)</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Shopping cart area end-->
</template>
<script>
	export default {};
</script>