<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\SettingSmtp;
use App\Models\SiteSetting;
use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class SettingService
{
    public function __construct(
        private readonly SettingRepositoryInterface $settingRepository
    ) {}

    public function getSmtpSetting(): ?SettingSmtp
    {
        return $this->settingRepository->getSmtpSetting();
    }

    public function updateSmtpSetting(array $data): SettingSmtp
    {
        $smtp = $this->settingRepository->getSmtpSetting();

        return $this->settingRepository->updateSmtpSetting($smtp, $data);
    }

    public function getSiteSetting(): ?SiteSetting
    {
        return $this->settingRepository->getSiteSetting();
    }

    public function updateSiteSetting(array $data, ?UploadedFile $logo = null): SiteSetting
    {
        $site = $this->settingRepository->getSiteSetting();

        if ($logo) {
            $this->deleteOldLogo($site);
            $data['logo'] = $this->processLogo($logo);
        }

        return $this->settingRepository->updateSiteSetting($site, $data);
    }

    private function processLogo(UploadedFile $logo): string
    {
        $manager = new ImageManager(new Driver);
        $filename = hexdec(uniqid()).'.'.$logo->getClientOriginalExtension();

        $img = $manager->read($logo)->resize(140, 41);
        $img->save('storage/upload/logo/'.$filename, 100, 'png');

        return $filename;
    }

    private function deleteOldLogo(SiteSetting $site): void
    {
        if (! empty($site->logo) && Storage::exists('public/upload/logo/'.$site->logo)) {
            Storage::delete('public/upload/logo/'.$site->logo);
        }
    }
}
