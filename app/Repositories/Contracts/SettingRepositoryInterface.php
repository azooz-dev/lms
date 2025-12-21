<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\SettingSmtp;
use App\Models\SiteSetting;

interface SettingRepositoryInterface
{
    public function getSmtpSetting(): ?SettingSmtp;

    public function updateSmtpSetting(SettingSmtp $smtp, array $data): SettingSmtp;

    public function getSiteSetting(): ?SiteSetting;

    public function updateSiteSetting(SiteSetting $site, array $data): SiteSetting;
}
