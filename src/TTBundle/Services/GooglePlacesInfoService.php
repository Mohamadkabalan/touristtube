<?php

namespace TTBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class GooglePlacesInfoService {

    protected $googleRadarUrl = 'https://maps.googleapis.com/maps/api/place/radarsearch/json';
    protected $googleDetailsUrl = 'https://maps.googleapis.com/maps/api/place/details/json';
    protected $key = 'AIzaSyBcQf8tz2zUaTfNJppUC9Zo44rSBduZmX4';
    protected $location;
    protected $keyword;
    protected $radius;
    protected $type;
    protected $lat;
    protected $lng;
    protected $em;
    protected $utils;
    private $date;

    public function __construct(EntityManager $em, Utils $utils) {
        $this->em = $em;
        $this->utils = $utils;
        $this->date = new \DateTime();
    }

    public function getRadarGooglePlaces() {
        $output = array();
        $output1 = array();
        $this->lat = '39.9075';
        $this->lng = '116.397';
        $this->type = 'lodging';
        $this->keyword = 'Beijing';
        $this->radius = '50000';
        $radiusCounter = $this->radius;
         $coord = array("40.634076,116.657907", "40.241535,116.933774", "40.436570,116.122166", "39.914201,115.874285", "39.606860,116.366048");
  
     for ($i = 0; $i < 5; $i++) {
        if ($i === 0) {
            
            $getRadarInfo = file_get_contents("$this->googleRadarUrl?location=$this->lat,$this->lng&type=$this->type&keyword=$this->keyword&radius=$radiusCounter&key=$this->key");
            $json = json_decode($getRadarInfo, TRUE);
            foreach ($json as $key => $value1) {
                if (!in_array($value1, $output)) {
                  
                    $output[$key] = $value1;
                    
                }
            }
        } else {
           
       
                $getRadarInfo = file_get_contents("$this->googleRadarUrl?location=$coord[$i]&type=$this->type&keyword=$this->keyword&radius=$radiusCounter&key=$this->key");
                $json = json_decode($getRadarInfo, TRUE);
                foreach ($json as $key => $value1) {
                    if (!in_array($value1, $output)) {
                     
                        $output[$key] = $value1;
                         
                    }
                }
            }
        }
        for($a=0; $a <193; $a++){
            $b = 0;
            while($placeIdArray[$a] != $output['results'][$b]['place_id'])
            {
                $b++;
                if($b === 193){
                    break;
                }
            }
        
                 $output1[$a] = $output['results'][$b]['place_id']; 

        }


        return $output1;
    }

    public function getDetailsGooglePlaces() {

//        $fs = new Filesystem();
        $getPrevContent = array();
        $output1 = array();
        $output2 = array();
        $placeIdArray = array("ChIJAcFDW5gD8TURqEAeeWEz_QE",
            "ChIJ9Rhg_RX98DURLhYNRbxskRY",
            "ChIJ1VB7OKtG8TURO88d35Tqe8w",
            "ChIJCRX_FrAc8TURpofNXPcAeNQ",
            "ChIJxbitGc_68DURDO_zR68o2WQ",
            "ChIJeS1IUOTk8DUR2WyIcF1Kc9A",
            "ChIJN0rwVL0N8TUROIjgL1dVtTw",
            "ChIJiZ_joSkD8TURW1jQUyqUE9E",
            "ChIJBeY57-MN8TURJilhFF0ab4I",
            "ChIJGf4YazWT8DURB2Mcv6O1f0E",
            "ChIJeWwk6URW8DURWvadDdkfZnI",
            "ChIJ_c_sg-5G8TURYuiBQrK79H8",
            "ChIJ1yZ8NCXl8DURnshqtMAI9wE",
            "ChIJz5dt-Xj98DUR2iGKPS_ikeM",
            "ChIJ2yNPookw8DURwL88e2coIuk",
            "ChIJXWoVFpUd8TUR8IeQTYb6bqY",
            "ChIJTwZR5OPk8DURx1-QXBUbyC4",
            "ChIJDbWZr3-x8TURkalkMnHaQJA",
            "ChIJH_VqC4EH8TURgX9nSKDp_ic",
            "ChIJryVISD5d8DUR2pT_B72JeCk",
            "ChIJowVeIFNd8DURzK0s5wK85kg",
            "ChIJ9Rhg_RX98DURvtlhX9r3ii0",
            "ChIJt9mTwK4W8TURGcbssB1jK0A",
            "ChIJfx_meO5e8DUR_AIJ3k0vB04",
            "ChIJJ5Wz7osH8TURBfw6-Row5dk",
            "ChIJuz_OmbU28DURnb4YdbHL7cA",
            "ChIJ-_KduH9Y8DURMNtzHwcZSU4",
            "ChIJOUqGWZgD8TURZ4_Z1SjPeRc",
            "ChIJAWhtccOk8TUR_uH2m9m9rNY",
            "ChIJ8YYr_cPo8DUR9Lg3gQjn9qc",
            "ChIJ1RsyE2-g8TURMQawUJSIQgE",
            "ChIJCfnAjp0H8TURiyF-8guPOO0",
            "ChIJL7edrRf98DURhpZQ21KNbE4",
            "ChIJD_kD1SsD8TURcWR1fnNXtDc",
            "ChIJnR_NP19H8DURyJ4QQ-vtS6o",
            "ChIJ4RLyfFc98TURKJBXHrgDL3A",
            "ChIJ0fs0boaj8TURlxu68mW59aM",
            "ChIJ9Rhg_RX98DURPUYCXvDyzgA",
            "ChIJByfou3P98DUR5a1Q6VTu-A8",
            "ChIJH_XA6K9U8DUR4tFtv2QSz8k",
            "ChIJCWIJlyXl8DURoKWsRBpqFEc",
            "ChIJLRlsDgyg8TUR5KtL-mpVeHo",
            "ChIJ61Djua4N8TURgLN3aG_CNvQ",
            "ChIJmejLqYMC8TURTw00m--pG0Y",
            "ChIJl0QvIAPl8DURzpid1EUBSes",
            "ChIJc9dajV1d8DURFTfizBLfQj8",
            "ChIJzfNF2v_M8TUR4yuScGAm_Qg",
            "ChIJNQXgoAgg8TURxuMfYUZr-OQ",
            "ChIJCfEDqFxH8DURzOX7xKfjwr0",
            "ChIJVVWFuQur8TURuRMADbPfYWU",
            "ChIJb56YJkMS8TUR-W5wM4iCLH8",
            "ChIJi_OrkS9V8DUReb1pPGQPhKI",
            "ChIJk38W55ZS8DURWKiHD_GKlTA",
            "ChIJXf4jFKVS8DURjxEu5uOwz58",
            "ChIJDQ4kFeBN8DURQHoMdxrBQp4",
            "ChIJ0fjJ3sxc8DURRcpqqtQOtPo",
            "ChIJ6VG7XH6r8TURP938ZkKLQDQ",
            "ChIJ__9Pv-ir8TURmq0U1l5iQfU",
            "ChIJxz_lWM9S8DUR3fZnRhUqvWg",
            "ChIJg0FeidJS8DUR3Mq2B8dOao0",
            "ChIJR9spET1U8DURG8Ju8u-90ts",
            "ChIJk38W55ZS8DUR7OkVcytYrs8",
            "ChIJvYDkHcAsAjQRdAN7wjedoiU",
            "ChIJS950pmtS8DURx8DZrWDuIQw",
            "ChIJq-YMgIGs8TURdmJe3C_YWSM",
            "ChIJf_ht3KII8TURVhm8SIe7M8s",
            "ChIJOVN2iuVU8DURzLXIIVRIqsQ",
            "ChIJwziNYhCt8TUR1vfdKh4qmb4",
            "ChIJdwSZcxCt8TUR9gCtGpwFLW0",
            "ChIJg4-8DdFS8DUR1aLD0mJrl8M",
            "ChIJJ2xqzdJS8DURVVU1VoI0o4M",
            "ChIJ3W5jRnBW8DURe4hwTjjDRjo",
            "ChIJUcwAUXFT8DURTS8wqyWVF60",
            "ChIJaXRnSE6r8TURl3Lbx2DKXA4",
            "ChIJQ9HMb75N8DURDyF8BlpIzW8",
            "ChIJcVilxBxiSzQRoeWecyMbqFg",
            "ChIJVz31ffms8TURDSuZCxDT8KY",
            "ChIJMRK-Fbj68DURuG2K9xAFVgI",
            "ChIJUe5zup4-8DUR2ko6AVlnOPU",
            "ChIJyQUiqWj98DUR-ImVWbUR_uM",
            "ChIJHc-3ZKxW8DURx2idt6C-1mM",
            "ChIJSY5uoByt8TUR2TwOU_u9prs",
            "ChIJ9XE8rWVU8DUR7QPjROcMrAE",
            "ChIJ20VcauJmAjQRaCzmJidxPTk",
            "ChIJ7YShhfUi8TURsWSB7VXa1c0",
            "ChIJq6r6MP9U8DUR2RhsbqVvMdg",
            "ChIJmREorF0Q8TURrwFUxGUkCf8",
            "ChIJxz-DO4dY8DURS6u8n_ZchiQ",
            "ChIJGb-b6uPi8DUR8ZNIkKomsVU",
            "ChIJc2vC7N6s8TUR_j6JV9gru3I",
            "ChIJb3YSNhmk8TURq8n1867GNgg",
            "ChIJHWpTidlR8DURAdCa7rn6-cE",
            "ChIJrZ0JjuCs8TURBz4OT7iX6tU",
            "ChIJU6VElypX8DURXYmME9Am5e0",
            "ChIJy9Vpax2t8TUR91ZAM6jUelw",
            "ChIJdcTsg-5G8TURWa0Uqm3ndUQ",
            "ChIJX155hjSt8TUR4OSEw-nfFuY",
            "ChIJRbgC_qEI8TUR_N8kJANxH4g",
            "ChIJvyBA6IdR8DURrtzd7nTclHc",
            "ChIJfeyaqZlR8DURoHjcQATPDv8",
            "ChIJgW5Vd0xK8DUR2QlfJ8jkVCE",
            "ChIJn4tO2Jzw8DURiQ32IILnYXQ",
            "ChIJC7kXHF6r8TURsMklqmvP-Jo",
            "ChIJe_q4ZShV8DURfepmbYGfZc0",
            "ChIJjcHIs1NR8DURRB9UAN_YjaM",
            "ChIJ-f58nIOy8TURTKUxtXsOXyQ",
            "ChIJ72JEwiyt8TURMof_qON4J9Q",
            "ChIJR1-KFmVF8DURDQ0VpvnI3qM",
            "ChIJwbDDXOqs8TURsjRGGA8id6U",
            "ChIJEzhVR8L97TURdZgqt_zZyCQ",
            "ChIJKzbX-8NU8DURG4g3CbmhXsc",
            "ChIJDUQCVlZU8DURXwk3wNN3GrQ",
            "ChIJY54hZYtU8DURsqQiM1Jm2do",
            "ChIJA_Arl11R8DURhoLXiT48wUQ",
            "ChIJzRGJrfP48DURuNnxwwz-zrQ",
            "ChIJm2ceHmRR8DURPjmOfdZAVNQ",
            "ChIJwYNulGFT8DURKsnBRFqR8Xc",
            "ChIJWZ7x7VdS8DURXz-OMtfvaQQ",
            "ChIJ88kA5hZU8DURJmWGzixG1UI",
            "ChIJe1BHsY1U8DURaK_LiwjBdtk",
            "ChIJL3mUmWBR8DURsInEDNlv7HQ",
            "ChIJm2ceHmRR8DURQNLJI0N5B9M",
            "ChIJ_YE1Aco_8DURcYV1QVUZEuo",
            "ChIJizX1TMhU8DURymxnVnvXjGc",
            "ChIJiUxEdwUj8TURC-fEJr4GbmU",
            "ChIJs-ldlkxV8DURdKNqz1-IlLc",
            "ChIJ7ZpoJrr98DURzWaQoX2P_JY",
            "ChIJXaTsZErl8DUR7nnWBDNU6t4",
            "ChIJW_SqT1j98DURLA3PKbAxCvw",
            "ChIJOdgN8hr48DURSEJy8q-6O3k",
            "ChIJr9DgAXBf8DURW-DBWMdGv3M",
            "ChIJMe9yN5M4vTYRE10GA23LsIk",
            "ChIJ05x5wKKr8TUR2eGm7j4xU3w",
            "ChIJX2MK6rFN8DUR_jJLRhJb9Es",
            "ChIJw0BUS0Y58DURPN-f4cQNldM",
            "ChIJjYOCJI0U8DURGl6-qwZEq6E",
            "ChIJsaioU0QC8TURbJAKQI8JNcc",
            "ChIJRTTj1R348DUR5tdltMGmHGw",
            "ChIJ-RHps-Pk8DURkUkF1ler7G8",
            "ChIJd6J-0VKq8TURiHjzf1W8cPA",
            "ChIJpVnkOq1U8DURp6hKbg9AUAI",
            "ChIJldbZ0RND8DURqNdXuSEz5Z0",
            "ChIJ7f5KMnj98DURE9HjhFb3pi8",
            "ChIJ6fajK85p8TURpqflHMiK7n4",
            "ChIJUZwmAiYO8TUR4VFK63k7Zw8",
            "ChIJ0zdYDKj88DUREN8-Ty-oeCg",
            "ChIJ11dzbEqq8TURCeZdbaVFusc",
            "ChIJLaSJ7--z8TURc4NIOm8pabk",
            "ChIJn39cs7ex8TUR2E1nlsUlLY4",
            "ChIJWdaOF-um8TURdT9wU2ehLGs",
            "ChIJMd7LAHqo8TUR_PKUqcoWpg4",
            "ChIJiWNTtN378TURnZOpkjqaY8A",
            "ChIJn5GESwvm8DUROqj3HdH0eRo",
            "ChIJNyCIxahU8DURsexYXrywac8",
            "ChIJBaxgHdNa8DURQ_30ETi8H1U",
            "ChIJw0GhDiKp8TURYYbiMHcSNYY",
            "ChIJl03Sc8Ad8TURcAknC30HFOg",
            "ChIJQ9y5GCTl8DUR9s2AIHzM6pw",
            "ChIJharkua4N8TURZMOBjYGQx1Q",
            "ChIJjUyTHsVS8DURWZUXbc1Mxi4",
            "ChIJE1lkp-r48DURQ1yBDaT4FaI",
            "ChIJ8f3lOkda8DURpcC-MVZQyKs",
            "ChIJcXDM-7Xs8DURDv3uuSEkA0A",
            "ChIJ8XbXIPb-8DURh7Z2FRFmAkg",
            "ChIJhRZNuaki8TURExS1PT901HU",
            "ChIJiZUfbwqr8TURrO7N-voluKw",
            "ChIJkzfNSPT-8DURufJCnqGn78g",
            "ChIJjVKED9BT8DURbQv0BIFTeQs",
            "ChIJqZIH2jU58DUR-hWCQH7_AGM",
            "ChIJO4p2JzdU8DURMK1o1L4dHY4",
            "ChIJj2Aji2vt8DURfYdgRMv8cKY",
            "ChIJ0cs6sDNN8DURudUxaqdrP-8",
            "ChIJt1c7XFZU8DURsgJIJu5ugZw",
            "ChIJC-TFMj798DURNUYHP5V0tOQ",
            "ChIJ-7gW7stS8DURblr9-SmV9kA",
            "ChIJ2TRyqTha8DURvxgJjDFFidY",
            "ChIJPwiCN3Og8TURKvmnol2EfQk",
            "ChIJsT0DY-lX8DURndB8XJl-7kQ",
            "ChIJN-6dT0D98DURUZ5Iqvv6AQU",
            "ChIJY6Ae9hY58DURfBWKEmRupmQ",
            "ChIJlUI8Sg5U8DURJSMiJ4-mx7E",
            "ChIJSe-reBcC8TURsBjUYLtvPLk",
            "ChIJWdYHbyMj8TUR1qO_ib_Yhwk",
            "ChIJkWxlG8xU8DURrJgvbm5BMAM",
            "ChIJ9Rhg_RX98DURWlggiolFLLY",
            "ChIJZytRIiTl8DUR6SpjLkyEXM8",
            "ChIJqZ5T9_z_8DUR3NlRnrwmaCk",
            "ChIJif4Zd97-8DURNQ9Ys4VhnME",
            "ChIJN87Qvq768DURUyF--DaImcQ",
            "ChIJpZ5WvGgY8TUR1GpmGlAnwZM",
            "ChIJUwXR45Tw8DUR6dS3DmMyXBo",
            "ChIJ4fY5LVwd8TUR_5gCJhYTO-A",
            "ChIJ9R9vx76h8TUR7v9LwxofNrw",);
        $arrlength = count($placeIdArray);


        for ($i = 10; $i < 11; $i++) {
            $place_id = $placeIdArray[$i];
            $getPlaceDetails = file_get_contents("$this->googleDetailsUrl?placeid=$place_id&language=en&key=$this->key");

            $json = json_decode($getPlaceDetails, TRUE);
            foreach ($json as $key => $value1) {
                if (!in_array($value1, $output2)) {
                    $output2[$i][$key] = $value1;
                }
            }
        }


//        $getPrevContent = json_decode(file_get_contents('GooglePlaceApi.json'), true);
//        foreach ($getPrevContent as $key => $value) {
//            if (!in_array($value, $output2)) {
//                $output2[$i][$key] = $value;
//            }
//        }
//        $result = array_merge($output2, $getPrevContent);
        try {
            file_put_contents('GooglePlacesApi.json', json_encode($output2, JSON_UNESCAPED_UNICODE));
        } catch (IOException $e) {
            
        }
        return $output2;
    }

    public function currency_convert($amount, $currencyfrom, $currencyto) {

        $currencyRateLastUpdatedTime = $this->em->getRepository('TTBundle:CurrencyRate')->findBycurrencyCode($currencyfrom);

        if (!$currencyRateLastUpdatedTime) {

            return null;
        }
        foreach ($currencyRateLastUpdatedTime as $valueRateLastUpdatedTime) {
            $time = $valueRateLastUpdatedTime->getLastUpdate();
            if (!$time) {

                return null;
            }
            if ($this->date >= $time->modify("+10 minutes")) {

                $testing = $this->getCurrencyRate();
                if ($testing == null) {
                    return null;
                }
            }

//            if ($this->date->modify("-10 minutes") <= $time) {
//                return $time;
//            }
        }
        $currencyRateFrom = $this->em->getRepository('TTBundle:CurrencyRate')->findBycurrencyCode($currencyfrom);
        if (!$currencyRateFrom) {

            return null;
        }
        foreach ($currencyRateFrom as $valueRateFrom) {
            $from = $valueRateFrom->getCurrencyRate();
            if (!$from) {

                return null;
            }
        }
        $currencyRateTo = $this->em->getRepository('TTBundle:CurrencyRate')->findBycurrencyCode($currencyto);
        if (!$currencyRateTo) {

            return null;
        }
        foreach ($currencyRateTo as $valueRateTo) {
            $to = $valueRateTo->getCurrencyRate();
            if (!$to) {

                return null;
            }
        }
        $conversion_rate = $from / $to;
        $converted_amount = round($amount / $conversion_rate, 2);

        return $converted_amount;
    }

}
