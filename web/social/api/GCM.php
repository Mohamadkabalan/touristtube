<?php


$url = 'https://android.googleapis.com/gcm/send';
$serverApiKey = "AIzaSyA-VL9wlwilL_ogBBNq1CGOVHXdx_jvDRg";
$reg = "APA91bHTq_kB5pbUF9ZSbtD3jgGHSWgK7s6JbKCcZ6KpehmmggUzCZV94U_AoydQHxnHlE9P4gI783ImQG2oxa3aNulfnPcHsDwJvjUnbQnq4bRYye2gJVtjyQHn4XnK7rMRrirFEpSGZyGshfVkrSxQhF47nvKdSQ";
//$reg = "APA91bF6qBYd1uvP1WKUVDnPZ-cAJjcdHb0lFk56nxHz1Lf8mKftKaXb8S5j52awCPswUYxCC3SOQ0FleMEKIec03qJK0mJY_jbNxfQTA-UAYiHXzANNhTM8hbvGNRVQ8x0Bn03MayOK7P3MrRQXc53kU59cG0ZkvQ";
 //$reg = "APA91bFpX4dtNdbAxlnRiU-NALjZIKjKxOKu5ptNcD6HwADsD_763P_7T_UyUHlilSqHejJyY690CDtbFHWNB0vJqhXlk4p9KI1_PnmR-2pidFK9732wqBYsREppVW9J2cfEIVJifYYbLV2VoO6j58V2rATB6D0dBw";
 //$reg = "APA91bHeM52PImhSgv9XghCFYjR1g6dDGylibWVh42snNU17XJCiwsbAAPG7FEiMo8V3bb-OslSchO_xNtOx15-PCobp1IjBDKtOlN5A777-oynG7od3_bAyCudta1cnhttp0uhgY4Iy";
 //$reg = "APA91bGUfZD5w2uFRtdJQ2F7vRgFofFA_4VbEhdA_0DFN_sJRCmH9WqUsdz5tfnzDIHORecErybAk9M7kCyA9tZ7OQtJ2eXRQ2Sypp6EZgkdLcpvWJG8abS0smVCiQMkpLFhCvpvmeIM";
         //APA91bE3X4IFo4dD7j3UJDnD8MDmDGzgl24sSYUiJq2DCwnPmew9Cw81AqV_Bj6mAa_MR7cq47kpfGgAkB-d8vkPnWal0GriQHgNNwL5H0I60K_Asn3VxCNGoX1aqcoVuvZhb9T0uo1p
		 //APA91bGUfZD5w2uFRtdJQ2F7vRgFofFA_4VbEhdA_0DFN_sJRCmH9WqUsdz5tfnzDIHORecErybAk9M7kCyA9tZ7OQtJ2eXRQ2Sypp6EZgkdLcpvWJG8abS0smVCiQMkpLFhCvpvmeIM
$headers = array(
 'Content-Type:application/json',
 'Authorization:key=' . $serverApiKey
 );

 $data = array(
 'registration_ids' => array($reg)
 , 'data' => array(
 'type' => 'New'
 , 'title' => 'GCM'
 , 'msg' => 'Here we completed GCM demo.'
 , 'url' => 'http://androidmyway.wordpress.com'
 )
 );

 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 if ($headers)
 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 curl_setopt($ch, CURLOPT_POST, true);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

 $response = curl_exec($ch);

curl_close($ch);

echo $response."<BR>".time();