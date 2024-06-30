<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\SettingSmtp;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use PHPUnit\Framework\Attributes\IgnoreFunctionForCodeCoverage;

class SettingController extends Controller
{
    public function smtp_setting() {
        $smtp = SettingSmtp::find(1);
        return view('admin.backend.settings.smtp_setting', compact('smtp'));
    }


    /**
     * Update smtp setting
     *
     * @param Illuminate\Http\Request $request
     * @param string $id
     * @return Illuminate\Http\RedirectResponse
     * @return array
     */
    public function smtp_update(Request $request, string $id) {

        // Validate input
        $date = $request->validate([
            'mailer' => 'required',
            'host' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password' => 'required',
            'encryption' => 'required',
            'from_address' => 'required',
        ]);

        // Update smtp setting
        SettingSmtp::find($id)->update($date);

        // Notify user
        $notification = array(
            'message' => 'Smtp setting updated successfully.',
            'alert-type' => 'success',
        );

        // Redirect back
        return redirect()->back()->with($notification);

    }

    public function site_setting() {
        $site = SiteSetting::find(1);

        return view('admin.backend.settings.site_setting', compact('site'));
    }

    public function site_setting_update(Request $request, string $id) {

        // Validate input
        $data = $request->validate([
            'logo' => 'nullable|sometimes|image',
            'email_site' => 'required',
            'phone_site' => 'required',
            'address_site' => 'required',
            'facebook' => 'required',
            'twitter' => 'required',
            'instagram' => 'required',
            'linkedin' => 'required',
            'copyright' => 'required',
        ]);

        $site = SiteSetting::find($id);

        if($request->hasFile('logo')) {
            // If the file exists in database and exists in storage folder
            if(!empty($site->logo) && Storage::exists('public/upload/logo/' . $site->logo)) {
                // Delete the old image from storage
                Storage::delete('public/upload/logo/' . $site->logo);
            }
    
            // Upload and resize the new image
            if($request->hasFile('logo')) {
                $manager = new ImageManager(new Driver());
    
                $data['logo'] = hexdec(uniqid()) . '.' . $request->file('logo')->getClientOriginalExtension();
                $img = $manager->read($request->file('logo'))->resize('140', '41'); // For Process Image
                $img->save('storage/upload/logo/' . $data['logo'], 100, 'png');
            }
    
            $site->update($data);
    
            // Return a success message
            $notification = array(
                'message' => 'Site Settings updated successfully.',
                'alert-type' => 'success',
            );
    
            return redirect()->back()->with($notification);

        }

    }
}
