<?php

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateSiteSettingRequest;
use App\Http\Requests\Setting\UpdateSmtpRequest;
use App\Models\SettingSmtp;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class SettingController extends Controller
{
    public function smtp_setting()
    {
        $smtp = SettingSmtp::find(1);

        return view('admin.backend.settings.smtp_setting', compact('smtp'));
    }

    /**
     * Update smtp setting
     *
     * @param  UpdateSmtpRequest  $request  The validated request object
     * @param  string  $id  The SMTP setting ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function smtp_update(UpdateSmtpRequest $request, string $id)
    {
        SettingSmtp::find($id)->update($request->validated());

        return redirect()->back()->with(FlashNotification::success('SMTP setting updated successfully.'));
    }

    public function site_setting()
    {
        $site = SiteSetting::find(1);

        return view('admin.backend.settings.site_setting', compact('site'));
    }

    /**
     * Update site setting
     *
     * @param  UpdateSiteSettingRequest  $request  The validated request object
     * @param  string  $id  The site setting ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function site_setting_update(UpdateSiteSettingRequest $request, string $id)
    {
        $data = $request->validated();
        $site = SiteSetting::find($id);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if (! empty($site->logo) && Storage::exists('public/upload/logo/'.$site->logo)) {
                Storage::delete('public/upload/logo/'.$site->logo);
            }

            // Upload and resize the new image
            $manager = new ImageManager(new Driver);
            $data['logo'] = hexdec(uniqid()).'.'.$request->file('logo')->getClientOriginalExtension();
            $img = $manager->read($request->file('logo'))->resize(140, 41);
            $img->save('storage/upload/logo/'.$data['logo'], 100, 'png');
        }

        $site->update($data);

        return redirect()->back()->with(FlashNotification::success('Site settings updated successfully.'));
    }
}
