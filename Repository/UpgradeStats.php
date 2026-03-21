<?php

namespace UpgradeStats\Repository;

use XF\Mvc\Entity\Repository;

class UpgradeStats extends Repository
{
    public function getActiveUpgrades(): array
    {
        return $this->db()->fetchAll("SELECT * FROM xf_user_upgrade_active");
    }

    public function getExpiredUpgrades(): array
    {
        return $this->db()->fetchAll("SELECT * FROM xf_user_upgrade_expired");
    }

    public function getUpgradeDefinitions(): array
    {
        return $this->db()->fetchAll("SELECT * FROM xf_user_upgrade");
    }

    public function getCurrentMonthResults(): array
    {
        $startOfMonth = strtotime(date('Y-m-01 00:00:00'));
        $endOfMonth   = strtotime(date('Y-m-t 23:59:59'));

        $active = $this->db()->fetchAll("
            SELECT 'active' AS status, user_upgrade_record_id, user_id,
                   user_upgrade_id, start_date, end_date
            FROM xf_user_upgrade_active
            WHERE start_date BETWEEN ? AND ?
        ", [$startOfMonth, $endOfMonth]);

        $expired = $this->db()->fetchAll("
            SELECT 'expired' AS status, user_upgrade_record_id, user_id,
                   user_upgrade_id, start_date, end_date
            FROM xf_user_upgrade_expired
            WHERE start_date BETWEEN ? AND ?
        ", [$startOfMonth, $endOfMonth]);

        return array_merge($active, $expired);
    }

    public function getPaymentLog(): array
    {
        return $this->db()->fetchAll("
            SELECT * FROM xf_payment_provider_log
            WHERE log_message = 'Payment received, upgraded/extended.'
            ORDER BY provider_log_id DESC
        ");
    }
}