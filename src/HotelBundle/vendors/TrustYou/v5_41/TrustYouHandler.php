<?php

namespace HotelBundle\vendors\TrustYou\v5_41;

use TTBundle\Utils\Utils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use HotelBundle\vendors\TrustYou\Config AS TrustYouConfig;

if (!defined('RESPONSE_SUCCESS')) define('RESPONSE_SUCCESS', false);
if (!defined('RESPONSE_ERROR')) define('RESPONSE_ERROR', true);

class TrustYouHandler
{
    private $config;
    private $CATEGORIES = array();
    private $BADGES     = array(
        "16aa" => "airport-hotel", "244" => "amenities", "63" => "bar-&-beverages", "12" => "beach", "16t" => "beach-hotel", "11b" => "beds", "16g" => "boutique-hotel",
        "131" => "breakfast", "16h" => "business-hotel", "16z" => "casino-hotel", "15h" => "childcare", "16f" => "city-hotel", "333" => "cleanliness",
        "111" => "comfort", "ef97" => "dining-experience", "16y" => "eco-friendly-hotel", "16k" => "economy-hotel", "16c" => "family-hotel",
        "21b" => "fitness-area", "13" => "food", "16ag" => "friends-hotel", "16p" => "golf-hotel", "20" => "grounds", "16ad" => "highway-hotel",
        "16ae" => "hostel", "16" => "hotel", "24" => "hotel-building", "15e" => "housekeeping-staff", "20d" => "kids-facilities", "16ab" => "lake-hotel",
        "14" => "location", "16b" => "luxury-hotel", "24c" => "meeting-&-event-facilities", "20c" => "outdoor-sports-facilities",
        "16i" => "party-hotel", "201" => "pool", "15b" => "reception", "16r" => "resort-hotel", "14d" => "restaurants-&-bars", "16d" => "romantic-hotel",
        "11" => "room", "15m" => "room-service", "21a" => "sauna", "15" => "service", "ddbc" => "service-friendliness", "14e" => "shopping", "14c" => "sightseeing",
        "16u" => "solo-hotel", "24a" => "terrace", "22a" => "value-for-money", "171" => "vibe", "11e" => "view", "16ac" => "water-park-hotel",
        "21" => "wellness-area", "16e" => "wellness-hotel", "15a" => "wellness-staff", "18" => "wifi", "16s" => "winter-sports-hotel"
    );
    private $TRIP_TYPE  = array(
        "business" => "Business", "couple" => "Couples", "solo" => "Solo", "family" => "Families", "friends" => "Friends", "language" => "Language"
    );
    private $LANGUAGE   = 'en';

    public function __construct(Utils $utils, ContainerInterface $container)
    {
        // initialize parameters
        $this->config = new TrustYouConfig($container);

        $this->utils = $utils;
        $this->setCategories();
    }

    private function setLanguage($language)
    {
        $langugeMap     = array('en' => 'en', 'fr' => 'fr', 'in' => 'hi', 'cn' => 'zh');
        $this->LANGUAGE = $langugeMap[$language];
    }

    public function getSeal($ty_id, $language = null)
    {
        if ($language) {
            $this->setLanguage($language);
        }
        $result = $this->sendRequestCurl('seal', $ty_id);
        if (!isset($result['error'])) {
            return $this->prepareSeal($result);
        } else {
            return null;
        }
    }

    public function getMetaReview($ty_id, $language = null, $forPage = 'hotelDetails')
    {
        if (!$ty_id) {
            return null;
        }
        if ($language) {
            $this->setLanguage($language);
        }
        $result = $this->sendRequestCurl('meta_review', $ty_id);
        if (!isset($result['error'])) {
            return $this->prepareMetaReview($result, $forPage);
        } else {
            return null;
        }
    }

    public function getBulk($ty_id, $language = null)
    {
        if ($language) {
            $this->setLanguage($language);
        }
        //$widgets = ["meta_review", "reviews", "seal", "trust_score_distribution", "traveler_distribution"];
        $widgets     = ["meta_review", "reviews"];
        $requestList = array();

        foreach ($widgets as $value) {
            $requestList[] = urlencode($this->getURL($value, $ty_id, false, false));
        }
        $data = 'request_list='.json_encode($requestList, JSON_UNESCAPED_SLASHES);

        $result = $this->sendRequestCurl('bulk', null, $data);
        if (!isset($result['error'])) {
            $response                = array();
            $response['meta_review'] = $this->prepareMetaReview($result['response_list'][0]['response']);
            $response['reviews']     = $result['response_list'][1]['response'];
            return $response;
        } else {
            return null;
        }
    }

    // Generic Helper Functions
    private function getURL($widget, $ty_id = null, $withEndpoint = true, $withKey = true, $urlParams = null)
    {
        $str = ($withEndpoint) ? $this->config->url : '';

        $params = array();
        if ($withKey) {
            $params[] = "key=".$this->config->api_key;
        }
        if ($widget == 'bulk') {
            $str      .= "/bulk";
            $params[] = $urlParams;
        } else {
            $str .= "/hotels/".$ty_id.$this->getWidgetUrlParam($widget);
            if ($widget == 'meta_review') {
                $params[] = "v=".$this->config->version;
            }
        }

        return sprintf("%s?%s", $str, implode("&", $params));
    }

    private function getWidgetUrlParam($name, $format = 'json')
    {
        return sprintf("/%s.%s", $name, $format);
    }

    private function prepareSeal($result)
    {
        if (isset($result['score']) && !empty($result['score'])) {
            $result['score'] = $this->convertToScale($result['score']);
        }
        return $result;
    }

    private function prepareMetaReview($result, $forPage = 'hotelDetails')
    {
        switch ($forPage) {
            case 'hotelDetails':
            case 'hotelReviews':
            default:
                // remove unused data
                unset($result['response']);
                unset($result['language_meta_review_list']);
                unset($result['hotel_type_list']);
                unset($result['gender_talks_about']);
                break;
        }

        if (isset($result['summary']['score']) && !empty($result['summary']['score'])) {
            $result['summary']['score'] = $this->convertToScale($result['summary']['score']);
        }
        if (!empty($result['badge_list'])) {
            $badgeList = array();
            $count     = 1;
            foreach ($result['badge_list'] as $badge) {
                if ($count <= 5) {
                    unset($badge['highlight_list']);
                    $badge['text'] = strip_tags($badge['text']);
                    if ('ranking' == $badge['badge_type']) {
                        $badge['cat_icon'] = '';
                        array_unshift($badgeList, $badge);
                        $count++;
                    } elseif ($badge['badge_data']['popularity'] < 13) {
                        $badge['cat_icon']                                      = $this->getBadgeIcon($badge['badge_data']['category_id']);
                        $badgeList[(string) $badge['badge_data']['popularity']] = $badge;
                        $count++;
                    }
                }
            }
//                ksort($badgeList);
//                $badgeList = array_slice($badgeList, 0, 5);
            $result['badge_list'] = $badgeList;
        }
        if (isset($result['category_list']) && !empty($result['category_list'])) {
            foreach ($result['category_list'] as $key => $value) {
                $result['category_list'][$key]['score'] = $this->convertToScale($value['score']);
            }
        }
        if (isset($result['trip_type_meta_review_list'])) {
            foreach ($result['trip_type_meta_review_list'] as $tKey => $tripType) {
                $result['trip_type_meta_review_list'][$tKey]['name'] = $this->TRIP_TYPE[$tripType['filter']['trip_type']];
                foreach ($tripType['category_list'] as $key => $value) {
                    $result['trip_type_meta_review_list'][$tKey]['category_list'][$key]['score'] = $this->convertToScale($value['score']);
                }
            }
        }

        $result['scale'] = $this->config->scale;
        return $result;
    }

    private function convertToScale($value)
    {
        return number_format(($this->config->scale * $value) / 100, 1);
    }

    private function getBadgeIcon($catId)
    {

        if (isset($this->BADGES[$catId])) {
            return $this->BADGES[$catId];
        } elseif (isset($this->CATEGORIES[$catId])) {
            $category = $this->CATEGORIES[$catId];
            if (!empty($category['parent_category_list'])) {
                foreach ($category['parent_category_list'] as $parentCatId) {
                    if (isset($this->BADGES[$parentCatId])) {
                        return $this->BADGES[$parentCatId];
                    }
                }
            }
        }
        return null;
    }

    private function setCategories()
    {
        $this->CATEGORIES = array(
            "11" => array("category_id" => "11", "parent_category_list" => [], "name" => "Room"),
            "111" => array("category_id" => "111", "parent_category_list" => [], "name" => "Comfort"),
            "111a" => array("category_id" => "111a", "parent_category_list" => ["111"], "name" => "Noise Level"),
            "11a" => array("category_id" => "11a", "parent_category_list" => ["11"], "name" => "Bathroom"),
            "11b" => array("category_id" => "11b", "parent_category_list" => ["11", "111"], "name" => "Beds"),
            "11d" => array("category_id" => "11d", "parent_category_list" => ["11"], "name" => "Balcony"),
            "11e" => array("category_id" => "11e", "parent_category_list" => ["11"], "name" => "View"),
            "11f" => array("category_id" => "11f", "parent_category_list" => ["11", "111"], "name" => "Air Conditioning"),
            "11g" => array("category_id" => "11g", "parent_category_list" => ["11"], "name" => "Minibar"),
            "11h" => array("category_id" => "11h", "parent_category_list" => ["11"], "name" => "TV"),
            "11i" => array("category_id" => "11i", "parent_category_list" => ["11"], "name" => "Furniture"),
            "11j" => array("category_id" => "11j", "parent_category_list" => ["11"], "name" => "Kitchenette"),
            "11l" => array("category_id" => "11l", "parent_category_list" => ["11"], "name" => "Family Room"),
            "11m" => array("category_id" => "11m", "parent_category_list" => ["11"], "name" => "Shower"),
            "12" => array("category_id" => "12", "parent_category_list" => [], "name" => "Beach"),
            "12a" => array("category_id" => "12a", "parent_category_list" => ["12"], "name" => "Beach Sports"),
            "13" => array("category_id" => "13", "parent_category_list" => [], "name" => "Food"),
            "131" => array("category_id" => "131", "parent_category_list" => [], "name" => "Breakfast"),
            "13b" => array("category_id" => "13b", "parent_category_list" => ["13"], "name" => "Menu"),
            "13c" => array("category_id" => "13c", "parent_category_list" => ["13"], "name" => "Salads"),
            "13d" => array("category_id" => "13d", "parent_category_list" => ["13"], "name" => "Meat"),
            "13e" => array("category_id" => "13e", "parent_category_list" => ["13"], "name" => "Beef Dishes/Steak"),
            "13f" => array("category_id" => "13f", "parent_category_list" => ["13"], "name" => "Pork Dishes"),
            "13g" => array("category_id" => "13g", "parent_category_list" => ["13"], "name" => "Poultry Dishes"),
            "13h" => array("category_id" => "13h", "parent_category_list" => ["13"], "name" => "Venison Dishes"),
            "13i" => array("category_id" => "13i", "parent_category_list" => ["13"], "name" => "Lamb Dishes"),
            "13j" => array("category_id" => "13j", "parent_category_list" => ["13"], "name" => "Soups"),
            "13k" => array("category_id" => "13k", "parent_category_list" => ["13"], "name" => "Fish/Seafood"),
            "13l" => array("category_id" => "13l", "parent_category_list" => ["13"], "name" => "Side Dishes"),
            "13m" => array("category_id" => "13m", "parent_category_list" => ["13"], "name" => "Vegetables"),
            "13n" => array("category_id" => "13n", "parent_category_list" => ["13"], "name" => "Desserts & Fruits"),
            "13o" => array("category_id" => "13o", "parent_category_list" => ["13"], "name" => "Vegetarian & Vegan"),
            "13p" => array("category_id" => "13p", "parent_category_list" => ["13"], "name" => "Pasta"),
            "13q" => array("category_id" => "13q", "parent_category_list" => ["13"], "name" => "Pizza"),
            "13r" => array("category_id" => "13r", "parent_category_list" => ["13"], "name" => "Sushi"),
            "13s" => array("category_id" => "13s", "parent_category_list" => ["13"], "name" => "Snacks"),
            "14" => array("category_id" => "14", "parent_category_list" => [], "name" => "Location"),
            "14a" => array("category_id" => "14a", "parent_category_list" => ["14"], "name" => "Accessibility by car"),
            "14c" => array("category_id" => "14c", "parent_category_list" => ["14"], "name" => "Sightseeing"),
            "14d" => array("category_id" => "14d", "parent_category_list" => ["14"], "name" => "Restaurants & Bars"),
            "14e" => array("category_id" => "14e", "parent_category_list" => ["14"], "name" => "Shopping"),
            "14g" => array("category_id" => "14g", "parent_category_list" => ["14"], "name" => "Parking"),
            "14h" => array("category_id" => "14h", "parent_category_list" => ["14"], "name" => "Distance to City Centre"),
            "14i" => array("category_id" => "14i", "parent_category_list" => ["14"], "name" => "Distance to Public Transport"),
            "14j" => array("category_id" => "14j", "parent_category_list" => ["14"], "name" => "Distance to Train Station"),
            "14k" => array("category_id" => "14k", "parent_category_list" => ["14"], "name" => "Distance to Airport"),
            "14l" => array("category_id" => "14l", "parent_category_list" => ["14"], "name" => "Distance to Business Sites"),
            "14m" => array("category_id" => "14m", "parent_category_list" => ["14"], "name" => "Distance to Winter Sports Facilities"),
            "15" => array("category_id" => "15", "parent_category_list" => [], "name" => "Service"),
            "15a" => array("category_id" => "15a", "parent_category_list" => ["15", "21"], "name" => "Wellness Staff"),
            "15b" => array("category_id" => "15b", "parent_category_list" => ["15"], "name" => "Reception"),
            "15c" => array("category_id" => "15c", "parent_category_list" => ["15"], "name" => "Restaurant Service"),
            "15d" => array("category_id" => "15d", "parent_category_list" => ["15"], "name" => "Pool/Beach Service"),
            "15e" => array("category_id" => "15e", "parent_category_list" => ["15"], "name" => "Housekeeping Staff"),
            "15f" => array("category_id" => "15f", "parent_category_list" => ["15"], "name" => "Tour Guide"),
            "15g" => array("category_id" => "15g", "parent_category_list" => ["15"], "name" => "Management"),
            "15h" => array("category_id" => "15h", "parent_category_list" => ["15"], "name" => "Childcare"),
            "15i" => array("category_id" => "15i", "parent_category_list" => ["15", "63"], "name" => "Bar Service"),
            "15j" => array("category_id" => "15j", "parent_category_list" => ["15"], "name" => "Laundry Service"),
            "15k" => array("category_id" => "15k", "parent_category_list" => ["15"], "name" => "Booking Process"),
            "15l" => array("category_id" => "15l", "parent_category_list" => ["15"], "name" => "Hotel Security"),
            "15m" => array("category_id" => "15m", "parent_category_list" => ["15"], "name" => "Room Service"),
            "15n" => array("category_id" => "15n", "parent_category_list" => ["15"], "name" => "Romantic Decoration"),
            "15o" => array("category_id" => "15o", "parent_category_list" => ["15"], "name" => "Recreation Staff"),
            "15p" => array("category_id" => "15p", "parent_category_list" => ["15"], "name" => "Shuttle Service"),
            "15q" => array("category_id" => "15q", "parent_category_list" => ["15"], "name" => "Valet Service"),
            "15r" => array("category_id" => "15r", "parent_category_list" => ["15"], "name" => "Concierge Service"),
            "16" => array("category_id" => "16", "parent_category_list" => [], "name" => "Hotel"),
            "16aa" => array("category_id" => "16aa", "parent_category_list" => ["16"], "name" => "Airport Hotel"),
            "16ab" => array("category_id" => "16ab", "parent_category_list" => ["16"], "name" => "Lake Hotel"),
            "16ac" => array("category_id" => "16ac", "parent_category_list" => ["16"], "name" => "Water Park Hotel"),
            "16ad" => array("category_id" => "16ad", "parent_category_list" => ["16"], "name" => "Highway Hotel"),
            "16ae" => array("category_id" => "16ae", "parent_category_list" => ["16"], "name" => "Hostel"),
            "16af" => array("category_id" => "16af", "parent_category_list" => ["16"], "name" => "Midscale Hotel"),
            "16ag" => array("category_id" => "16ag", "parent_category_list" => ["16"], "name" => "Friends Hotel"),
            "16b" => array("category_id" => "16b", "parent_category_list" => ["16"], "name" => "Luxury Hotel"),
            "16c" => array("category_id" => "16c", "parent_category_list" => ["16"], "name" => "Family Hotel"),
            "16d" => array("category_id" => "16d", "parent_category_list" => ["16"], "name" => "Romantic Hotel"),
            "16e" => array("category_id" => "16e", "parent_category_list" => ["16"], "name" => "Wellness Hotel"),
            "16f" => array("category_id" => "16f", "parent_category_list" => ["16"], "name" => "City Hotel"),
            "16g" => array("category_id" => "16g", "parent_category_list" => ["16"], "name" => "Boutique Hotel"),
            "16h" => array("category_id" => "16h", "parent_category_list" => ["16"], "name" => "Business Hotel"),
            "16i" => array("category_id" => "16i", "parent_category_list" => ["16"], "name" => "Party Hotel"),
            "16k" => array("category_id" => "16k", "parent_category_list" => ["16"], "name" => "Economy Hotel"),
            "16p" => array("category_id" => "16p", "parent_category_list" => ["16"], "name" => "Golf Hotel"),
            "16r" => array("category_id" => "16r", "parent_category_list" => ["16"], "name" => "Resort Hotel"),
            "16s" => array("category_id" => "16s", "parent_category_list" => ["16"], "name" => "Winter Sports Hotel"),
            "16t" => array("category_id" => "16t", "parent_category_list" => ["16"], "name" => "Beach Hotel"),
            "16u" => array("category_id" => "16u", "parent_category_list" => ["16"], "name" => "Solo Hotel"),
            "16y" => array("category_id" => "16y", "parent_category_list" => ["16"], "name" => "Eco-friendly Hotel"),
            "16z" => array("category_id" => "16z", "parent_category_list" => ["16"], "name" => "Casino Hotel"),
            "171" => array("category_id" => "171", "parent_category_list" => [], "name" => "Vibe"),
            "18" => array("category_id" => "18", "parent_category_list" => [], "name" => "WiFi"),
            "20" => array("category_id" => "20", "parent_category_list" => ["244"], "name" => "Grounds"),
            "201" => array("category_id" => "201", "parent_category_list" => [], "name" => "Pool"),
            "20c" => array("category_id" => "20c", "parent_category_list" => ["244"], "name" => "Outdoor Sports Facilities"),
            "20d" => array("category_id" => "20d", "parent_category_list" => ["244"], "name" => "Kids Facilities"),
            "20e" => array("category_id" => "20e", "parent_category_list" => ["244"], "name" => "Golf Court"),
            "20f" => array("category_id" => "20f", "parent_category_list" => ["244"], "name" => "Water Park"),
            "21" => array("category_id" => "21", "parent_category_list" => [], "name" => "Wellness Area"),
            "21a" => array("category_id" => "21a", "parent_category_list" => ["21"], "name" => "Sauna"),
            "21b" => array("category_id" => "21b", "parent_category_list" => ["21"], "name" => "Fitness Area"),
            "22a" => array("category_id" => "22a", "parent_category_list" => [], "name" => "Value for money"),
            "24" => array("category_id" => "24", "parent_category_list" => ["244"], "name" => "Hotel Building"),
            "244" => array("category_id" => "244", "parent_category_list" => [], "name" => "Amenities"),
            "24a" => array("category_id" => "24a", "parent_category_list" => ["244"], "name" => "Terrace"),
            "24b" => array("category_id" => "24b", "parent_category_list" => ["171", "244"], "name" => "Entrance Area"),
            "24c" => array("category_id" => "24c", "parent_category_list" => ["244"], "name" => "Meeting & Event Facilities"),
            "24d" => array("category_id" => "24d", "parent_category_list" => ["244"], "name" => "Smoking Area"),
            "24e" => array("category_id" => "24e", "parent_category_list" => ["244"], "name" => "Ski Storage"),
            "24f" => array("category_id" => "24f", "parent_category_list" => ["244"], "name" => "Handicap Accessible Facilities"),
            "24g" => array("category_id" => "24g", "parent_category_list" => ["244"], "name" => "Shared Facilities"),
            "24h" => array("category_id" => "24h", "parent_category_list" => ["244"], "name" => "Architecture"),
            "24i" => array("category_id" => "24i", "parent_category_list" => ["244"], "name" => "Casino Equipment"),
            "24j" => array("category_id" => "24j", "parent_category_list" => ["244"], "name" => "Elevator"),
            "2b39" => array("category_id" => "2b39", "parent_category_list" => ["13", "333"], "name" => "Dining Area Cleanliness"),
            "2dca" => array("category_id" => "2dca", "parent_category_list" => ["13", "22a"], "name" => "Food Prices"),
            "333" => array("category_id" => "333", "parent_category_list" => [], "name" => "Cleanliness"),
            "343c" => array("category_id" => "343c", "parent_category_list" => ["11", "333"], "name" => "Bathroom Cleanliness"),
            "4b72" => array("category_id" => "4b72", "parent_category_list" => ["11", "111"], "name" => "Room Size"),
            "5b6c" => array("category_id" => "5b6c", "parent_category_list" => ["11", "111"], "name" => "Bathroom Size"),
            "63" => array("category_id" => "63", "parent_category_list" => [], "name" => "Bar and Beverages"),
            "63a" => array("category_id" => "63a", "parent_category_list" => ["63"], "name" => "Alcoholic Drinks"),
            "6a3e" => array("category_id" => "6a3e", "parent_category_list" => ["22a", "63"], "name" => "Beverage Prices"),
            "7118" => array("category_id" => "7118", "parent_category_list" => ["201", "333"], "name" => "Pool Cleanliness"),
            "805b" => array("category_id" => "805b", "parent_category_list" => ["18", "22a"], "name" => "WiFi Cost"),
            "8d11" => array("category_id" => "8d11", "parent_category_list" => ["14"], "name" => "Distance to Beach"),
            "9ba9" => array("category_id" => "9ba9", "parent_category_list" => ["171"], "name" => "Friendly Atmosphere"),
            "9f14" => array("category_id" => "9f14", "parent_category_list" => ["333"], "name" => "Hotel Cleanliness"),
            "a10d" => array("category_id" => "a10d", "parent_category_list" => ["12"], "name" => "Beach Cleanliness"),
            "ae0f" => array("category_id" => "ae0f", "parent_category_list" => ["11"], "name" => "Old/New Room"),
            "b63a" => array("category_id" => "b63a", "parent_category_list" => ["14"], "name" => "Parking Prices"),
            "ba62" => array("category_id" => "ba62", "parent_category_list" => ["131", "22a"], "name" => "Breakfast Prices"),
            "ddbc" => array("category_id" => "ddbc", "parent_category_list" => ["15"], "name" => "Service Friendliness"),
            "deeb" => array("category_id" => "deeb", "parent_category_list" => ["244"], "name" => "Old/New Facilities"),
            "e004" => array("category_id" => "e004", "parent_category_list" => ["21", "333"], "name" => "Wellness Area Cleanliness"),
            "e51a" => array("category_id" => "e51a", "parent_category_list" => ["11"], "name" => "Room Maintenance"),
            "ef97" => array("category_id" => "ef97", "parent_category_list" => ["13"], "name" => "Dining Experience"),
            "f63a" => array("category_id" => "f63a", "parent_category_list" => ["11", "333"], "name" => "Room Cleanliness"),
            "f8d2" => array("category_id" => "f8d2", "parent_category_list" => ["15"], "name" => "Service Professionalism"),
            "vdes" => array("category_id" => "vdes", "parent_category_list" => ["171"], "name" => "Designer Vibe"),
            "vlux" => array("category_id" => "vlux", "parent_category_list" => ["171"], "name" => "Luxurious Vibe"),
            "vmod" => array("category_id" => "vmod", "parent_category_list" => ["171"], "name" => "Modern Vibe"),
            "wifq" => array("category_id" => "wifq", "parent_category_list" => ["18"], "name" => "WiFi Quality")
        );
    }

    // Curl Helper Functions
    private function sendRequestCurl($widget, $ty_id = null, $data = '')
    {

        $headers = array(
            'Content-Type' => 'text/xml; charset="utf-8"',
            'Accept' => 'text/xml',
            'Accept-Language' => $this->LANGUAGE,
            'Content-Length' => 0,
        );

        $response = $this->utils->send_data($this->getURL($widget, $ty_id, true, true, $data), '', \HTTP_Request2::METHOD_GET, array(), $headers);

        if ($response['response_error']) {
            $responseText = json_decode($response['response_text'], true);
            if ($responseText) {
                $error = $responseText['meta']['error'];
            } else {
                try {
                    $responseText = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response['response_text']);
                    $responseXML  = '';
                    if (!empty($responseText)) {
                        libxml_use_internal_errors(true);
                        $doc = new \SimpleXMLElement($responseText);
                        if ($doc !== FALSE) {
                            $responseXML = $doc;
                        }
                        libxml_clear_errors();
                    }
                    $error = $this->errorHandler($response, $responseXML);
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            }
            return array('error' => $error);
        } else {
            $responseData = json_decode($response['response_text'], true);
            return $responseData['response'];
        }
    }

    private function errorHandler($response, $xml)
    {

        $error = false;

        if ($response['response_error'] == RESPONSE_ERROR) {
            $error = true;
        }

        if ($error && empty($xml)) {
            $error = $response['reason_phrase'];
        } else {
            try {
                $exception      = $xml->xpath('body');
                $exceptionArray = json_decode(json_encode($exception), true);
                if (!empty($exceptionArray)) {
                    $error = implode("|", $exceptionArray[0]);
                } elseif ($error) {
                    $error = $response['response_text'];
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }
        return $error;
    }
}
