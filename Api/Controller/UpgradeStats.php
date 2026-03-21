<?php

namespace UpgradeStats\Api\Controller;

use XF\Api\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class UpgradeStats extends AbstractController
{
    public function actionGet(ParameterBag $params)
    {
        // Restrict to API keys with the 'upgrades:read' scope (defined below)
        $this->assertApiScope('upgrades:read');

        // Optional: restrict to super users only
        // $this->assertIsSuperUser();

        /** @var \YourName\UpgradeStats\Repository\UpgradeStats $repo */
        $repo = $this->repository('UpgradeStats:UpgradeStats');

        $active   = $repo->getActiveUpgrades();
        $expired  = $repo->getExpiredUpgrades();
        $upgrades = $repo->getUpgradeDefinitions();
        $monthly  = $repo->getCurrentMonthResults();
        $payments = $repo->getPaymentLog();

        return $this->apiResult([
            'userUpgradeActive'  => $active,
            'userUpgradeExpired' => $expired,
            'userUpgrades'       => $upgrades,
            'combinedResults'    => $monthly,
            'paymentLog'         => $payments,
        ]);
    }
}