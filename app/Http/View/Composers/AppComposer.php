<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Category;
use App\Models\TransactionProduct;
use App\Models\Setting;

class AppComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with($this->bindToView());
    }


    protected function bindToView()
    {
        $user = $resources = $setting = null;
        if (auth()->check()) {
            $user = auth()->user();
            $resources = $this->getResources($user);
            $setting = Setting::getSetting('company_name');
        } else {
            $user = null;
            $resources = [];
            $setting = Setting::getSetting('company_name');
        }

        return compact('user', 'resources', 'setting');
    }


    protected function getResources($user)
    {
        return $user->getAllPermissions()
            ->pluck('name')
            ->reject(function ($name) {
                list(, $action) = explode('.', $name);
                return $action !== 'view';
            })
            ->map(function ($name) {
                list($resource) = explode('.', $name);
                return $resource;
            })
            ->values()
            ->toArray();
    }
}