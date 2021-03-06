<?php

namespace App\Jobs;

use App\modules\HRM\Models\PersonalInfo;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Nathanmac\Utilities\Parser\Facades\Parser;

class BlockStatusSms extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $ansar_id;
    protected $reason;

    public function __construct($ansar_id, $reason)
    {
        $this->ansar_id = $ansar_id;
        $this->reason = $reason;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $send_sms_env = env("SEND_SMS", false);
        $test_mobile = env("TEST_SMS_NUMBER", null);
        $phone_number = "";
        $ansar = PersonalInfo::where('ansar_id', $this->ansar_id)->select('mobile_no_self')->first();
        if ($send_sms_env == false && !empty($test_mobile) && $ansar) {
            //Test SMS SEND config=
            $phone_number = $test_mobile;
        } elseif ($send_sms_env == true && $ansar) {
            $phone_number = $ansar->mobile_no_self;
        } else {
            return null;
        }
        $user = env('SSL_USER_ID');
        $pass = env('SSL_PASSWORD');
        $sid = env('SSL_SID');
        $url = "http://sms.sslwireless.com/pushapi/dynamic/server.php";
        Log::info("EMBODIED " . $this->ansar_id);
        $phone = '88' . trim($phone_number);
        $body = "You are Blocked due to " . $this->reason;
        $param = "user=$user&pass=$pass&sms[0][0]=$phone&sms[0][1]=" . urlencode($body) . "&sid=$sid";
        $crl = curl_init();
        curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($crl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($crl, CURLOPT_URL, $url);
        curl_setopt($crl, CURLOPT_HEADER, 0);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($crl, CURLOPT_POST, 1);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $param);
        $response = curl_exec($crl);
        curl_close($crl);
        $r = Parser::xml($response);
        Log::info($r);
    }
}
