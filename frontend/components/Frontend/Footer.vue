<template>
	<div class="mt-40">
		<!--Footer area start-->
		<img :src="`${url}images/wave.svg`" alt="wave" class="img-fluid w-100 disable-user-select">
		<footer>
			<div class="container">
				<div class="row">
					<div class="col-lg-4 col-md-6 mb-5">
						<div class="footer-contact">
							<h3 class="color-white">{{$t('contactUs')}}</h3>
							<p class="color-white">
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer
								in feugiat lorem. Pellentque ac placerat tellus.
							</p>

							<ul class="color-white">
								<li>
									<i>
										<icon :icon="['fas', 'globe-europe']"></icon>
									</i>
									Naogaon, Dhaka, Bangladesh
								</li>
								<li>
									<i>
										<icon :icon="['fas', 'envelope']"></icon>
									</i>
									shafayetalanik@gmail.com
								</li>
								<li>
									<i>
										<icon :icon="['fas', 'phone-alt']"></icon>
									</i>
									+88017-28293635
								</li>
							</ul>
						</div>
					</div>
					<div class="col-lg-2 col-md-6 mb-5">
						<div class="footer-link">
							<h3 class="color-white">{{$t('myAccount')}}</h3>
							<ul>
								<li>
									<a href="#"><span></span> {{$t('storeLocation')}}</a>
								</li>
								<li>
									<a href="#"><span></span> {{$t('orderHistory')}}</a>
								</li>
								<li>
									<a href="#"><span></span> {{$t('wishList')}}</a>
								</li>
								<li>
									<a href="#"><span></span> {{$t('newsletter')}}</a>
								</li>
								<li>
									<a href="#"><span></span> Specials</a>
								</li>
								<li>
									<a href="#"><span></span> Gift Certificates</a>
								</li>
								<li>
									<a href="#"><span></span> Affiliate</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-lg-2 col-md-6 mb-5">
						<div class="footer-link">
							<h3 class="color-white">{{$t('information')}}r</h3>
							<ul>
								<li>
									<a href="#"><span></span> {{$t('aboutUs')}}</a>
								</li>
								<li>
									<a href="#"><span></span> {{$t('deliveryInformation')}}</a>
								</li>
								<li>
									<a href="#"><span></span> {{$t('privacyPolicy')}}</a>
								</li>
								<li>
									<a href="#"><span></span> {{$t('brands')}}</a>
								</li>
								<li>
									<a href="#"><span></span> {{$t('contacts')}}</a>
								</li>
								<li>
									<a href="#"><span></span> Returns</a>
								</li>
								<li>
									<a href="#"><span></span> {{$t('siteMap')}}</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 mb-5">
						<div class="footer-newslatter">
							<h3 class="color-white">{{$t('newsletter')}}</h3>
							<p class="color-white">
								Praesent pellentesque volutpat ex, vitae auctor lorem pulvinar
								mollis felis at lacinia. Email Address
							</p>
							<form action="#" class="footer-subscriber-form">
								<input type="email" placeholder="Enter Your Email" />
								<button type="submit">{{$t('subscribe')}}</button>
							</form>
							<div class="footer-social-media">
								<p class="color-white">{{$t('follow')}}</p>
								<ul>
									<li>
										<a href="#"><i class="bg-blue">
												<icon :icon="['fab', 'facebook']"></icon>
											</i></a>
									</li>
									<li>
										<a href="#"><i class="bg-ltblue">
												<icon :icon="['fab', 'twitter']"></icon>
											</i></a>
									</li>
									<li>
										<a href="#"><i class="bg-red">
												<icon :icon="['fab', 'youtube']"></icon>
											</i></a>
									</li>
									<li>
										<a href="#"><i class="bg-purple">
												<icon :icon="['fab', 'instagram']"></icon>
											</i></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-bottom">
				<div class="container">
					<div class="row">
						<div class="col-lg-9">
							<div class="footer-bottom-copyright">
								{{$t('copyright')}}
								<a href="http://amianik.xyz" target="_blank">Shafayet Al-Anik.</a>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="footer-bottom-img">
								<img :data-src="`${url}images/payments.png`" alt="acept-payment" class="img-fluid" v-lazy-load />
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<!--Footer area end-->
	</div>
</template>
<script>
	export default {};
</script>
