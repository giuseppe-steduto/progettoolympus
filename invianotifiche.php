<?php
    include "base.php";
    ini_set('display_errors', 1);
    require __DIR__ . '/vendor/autoload.php';
    use Minishlink\WebPush\WebPush;
    use Minishlink\WebPush\Subscription;
    use Minishlink\WebPush\VAPID;


    function inviaNotifiche($cozzi, $notifica) {
        $auth = array(
            'VAPID' => array(
                'subject' => 'https://progettoolympus.ddns.net',
                'publicKey' => "BIJJSDCZFtq3BQGKiO8LB_b-Owvq8vDGl_fwgpb9jtAkLH07_lkAJhKyJbgVXy3fhKKztgjSDyGtxvc879abp0A",
                'privateKey' => "iAypxMCblyZGdcsC_4FcLLIXG3vcq3_G3AlnTtthZQk"
            ),
        );

        while($cozzo = mysqli_fetch_array($cozzi)) {
            $arrayAssociativoIscrizione = json_decode($cozzo["parametrinotifiche"], true);
            $subscription = Subscription::create($arrayAssociativoIscrizione);

            $webPush = new WebPush($auth);

            $webPush->setReuseVAPIDHeaders(true);
            $res = $webPush->sendNotification(
                $subscription,
                json_encode($notifica)
            );

            foreach ($webPush->flush() as $report) {
                $endpoint = $report->getRequest()->getUri()->__toString();

                if ($report->isSuccess()) {
                    echo "[v] Message sent successfully for subscription {$endpoint}.";
                } else {
                    echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
                }
            }
        }
    }

