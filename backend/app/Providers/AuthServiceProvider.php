<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		// 'App\Models\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	 * Register any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();

		Gate::define('admin', function ($user) {
			return $user->role_id === 1;
		});

		Gate::define('seller', function ($user) {
			return $user->role_id === 2;
		});

		Gate::define('customer', function ($user) {
			return $user->role_id === 3;
		});

		Gate::define('adminOrSeller', function ($user) {
			return $user->role_id === 1 || $user->role_id === 2;
		});

		Gate::define('adminOrCustomer', function ($user) {
			return $user->role_id === 1 || $user->role_id === 3;
		});

		Gate::define('authCheck', function ($user) {
			return $user->role_id === 1 || $user->role_id === 2 || $user->role_id === 3;
		});
	}
}
