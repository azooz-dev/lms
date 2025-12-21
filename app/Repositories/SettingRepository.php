<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\SettingSmtp;
use App\Models\SiteSetting;
use App\Repositories\Contracts\SettingRepositoryInterface;

class SettingRepository implements SettingRepositoryInterface
{
    public function getSmtpSetting(): ?SettingSmtp
    {
        return SettingSmtp::find(1);
    }

    public function updateSmtpSetting(SettingSmtp $smtp, array $data): SettingSmtp
    {
        $smtp->update($data);

        return $smtp;
    }

    public function getSiteSetting(): ?SiteSetting
    {
        return SiteSetting::find(1);
    }

    public function updateSiteSetting(SiteSetting $site, array $data): SiteSetting
    {
        $site->update($data);

        return $site;
    }
}
