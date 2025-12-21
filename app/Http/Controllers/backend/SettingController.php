<?php

declare(strict_types=1);

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateSiteSettingRequest;
use App\Http\Requests\Setting\UpdateSmtpRequest;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        private readonly SettingService $settingService
    ) {}

    public function smtp_setting(): View
    {
        $smtp = $this->settingService->getSmtpSetting();

        return view('admin.backend.settings.smtp_setting', compact('smtp'));
    }

    public function smtp_update(UpdateSmtpRequest $request, string $id): RedirectResponse
    {
        $this->settingService->updateSmtpSetting($request->validated());

        return redirect()->back()->with(FlashNotification::success('SMTP setting updated successfully.'));
    }

    public function site_setting(): View
    {
        $site = $this->settingService->getSiteSetting();

        return view('admin.backend.settings.site_setting', compact('site'));
    }

    public function site_setting_update(UpdateSiteSettingRequest $request, string $id): RedirectResponse
    {
        $this->settingService->updateSiteSetting(
            $request->validated(),
            $request->file('logo')
        );

        return redirect()->back()->with(FlashNotification::success('Site settings updated successfully.'));
    }
}
