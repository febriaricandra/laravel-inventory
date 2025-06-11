<?php

namespace App\Http\View\Composers;

use App\Models\Setting;
use Illuminate\View\View;

class AppComposer
{
    /**
     * Bind data to the view.
     *
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
                [, $action] = explode('.', $name);

                return $action !== 'view';
            })
            ->map(function ($name) {
                [$resource] = explode('.', $name);

                return $resource;
            })
            ->values()
            ->toArray();
    }
}
