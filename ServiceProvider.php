<?php

namespace Zoomyboy\Helpers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {
	public function boot() {}

	public function register() {
		$this->publishes([__DIR__.'/assets/js/helpers.js' => resource_path('assets/vendor/helpers/helpers.js')]);
	}
}
