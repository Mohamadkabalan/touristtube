<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function hotel_search_type_display($type){
    switch($type){
        case SOCIAL_ENTITY_HOTEL:
            return 'Hotel';
        case SOCIAL_ENTITY_LANDMARK:
            return 'POI';
        case SOCIAL_ENTITY_AIRPORT:
            return 'Airport';
        case SOCIAL_ENTITY_CITY:
            return 'City';
        case SOCIAL_ENTITY_COUNTRY:
            return 'Country';
        case SOCIAL_ENTITY_STATE:
            return 'State';
        case SOCIAL_ENTITY_DOWNTOWN:
            return 'Downtown';
    }
}