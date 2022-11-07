#!/bin/bash

curl -XPUT "elastic_host:9200/tt" -H 'Content-Type: application/json' -d'
{
  "settings" : {
    "index" : {
      "analysis" : {
        "analyzer" : {
          "autocomplete_analyzer" : {
            "type" : "custom",
            "tokenizer" : "lowercase",
            "filter"    : ["asciifolding", "name_ngram","stateName_ngram","countryName_ngram","countryThreeCode_ngram","countryFullname_ngram","countryISO3Code_ngram","hotelName_ngram"]
          }
        },
        "filter" : {
          "name_ngram" : {
            "type" : "edgeNGram",
            "min_gram" : 1,
            "max_gram" : 10
          },
          "stateName_ngram" : {
            "type" : "edgeNGram",
            "min_gram" : 1,
            "max_gram" : 10
          },
          "countryName_ngram" : {
            "type" : "edgeNGram",
            "min_gram" : 1,
            "max_gram" : 10
          },
          "countryThreeCode_ngram" : {
            "type" : "edgeNGram",
            "min_gram" : 1,
            "max_gram" : 10
          },
          "countryFullname_ngram" : {
            "type" : "edgeNGram",
            "min_gram" : 1,
            "max_gram" : 10
          },
          "countryISO3Code_ngram" : {
            "type" : "edgeNGram",
            "min_gram" : 1,
            "max_gram" : 10
          },
          "hotelName_ngram" : {
            "type" : "edgeNGram",
            "min_gram" : 1,
            "max_gram" : 10
          },
          "title_ngram" : {
            "type" : "edgeNGram",
            "min_gram" : 1,
            "max_gram" : 10
          }
        }
      }
    }
  }
}
'

echo -e "\n\ndefault mappings:: \n";

curl -XPUT "elastic_host:9200/tt/_default_/_mapping" -H 'Content-Type: application/json' -d'
{
  "properties": {
    "popularity": {
      "type": "integer"
    },
    "country_popularity": {
      "type": "integer"
    },
    "city_popularity": {
      "type": "integer"
    },
    "propertyName":{
      "index": "not_analyzed",
      "type": "string"
    },
    "geolocation": {
      "type": "geo_point"
    },
    "price": {
      "type": "float"
    },
    "stars": {
      "type": "float"
    },
    "nb_views": {
      "type": "integer"
    },
    "category_labels": {
      "index": "not_analyzed",
      "type": "string"
    }
  }
}
'

echo -e "\n\nlocality mappings:: \n";

curl -XPUT "elastic_host:9200/tt/locality/_mapping" -H 'Content-Type: application/json' -d'
{
  "properties": {
    "name": {
      "type": "string",
      "analyzer": "autocomplete_analyzer"
    },
    "state":{
      "type": "object",
      "properties" : {
        "name": {
          "type": "string",
          "analyzer": "autocomplete_analyzer"
        },
        "state_name": {
          "type": "string",
          "analyzer": "autocomplete_analyzer"
        },
        "full_name": {
          "type": "string",
          "analyzer": "autocomplete_analyzer"
        }
      }
    },
    "stateName": {
      "type": "string",
      "analyzer": "autocomplete_analyzer"
    },
    "countryName": {
      "type": "string",
      "analyzer": "autocomplete_analyzer"
    },
    "countryThreeCode": {
      "type": "string",
      "analyzer": "autocomplete_analyzer"
    },
    "countryFullname": {
      "type": "string",
      "analyzer": "autocomplete_analyzer"
    },
    "countryISO3Code": {
      "type": "string",
      "analyzer": "autocomplete_analyzer"
    },
    "popularity": {
      "type": "integer"
    }
  }
}
'

echo -e "\n\nmedia mappings:: \n";

curl -XPUT "elastic_host:9200/tt/media/_mapping" -H 'Content-Type: application/json' -d'
{
  "properties": {
    "title2": {
      "type": "string",
      "analyzer": "autocomplete_analyzer"
    },
    "title3": {
      "index": "not_analyzed",
      "type": "string"
    },
    "cityInfo":{
      "type": "object",
      "properties" : {
        "geolocation": {
          "type": "geo_point"
        }
      }
    }
  }
}
'

echo -e "\n\npoi mappings:: \n";

curl -XPUT "elastic_host:9200/tt/poi/_mapping" -H 'Content-Type: application/json' -d'
{
  "properties": {
    "name2": {
      "type": "string",
      "analyzer": "autocomplete_analyzer"
    },
    "cat": {
      "index": "not_analyzed",
      "type": "string"
    }
  }
}
'


echo -e "\n\nhotel mappings:: \n";

curl -XPUT "elastic_host:9200/tt/hotel/_mapping" -H 'Content-Type: application/json' -d'
{
  "properties": {
    "hotelName": {
      "type": "string",
      "analyzer": "autocomplete_analyzer"
    },
    "features":{
      "type": "object",
      "properties" : {
        "title": {
          "index": "not_analyzed",
          "type": "string"
        }
      }
    },
    "imageExists": {
      "type": "integer"
    }
  }
}
'

echo -e "\n\nrestaurantmappings:: \n";


curl -XPUT "elastic_host:9200/tt/restaurant/_mapping" -H 'Content-Type: application/json' -d'
{
  "properties": {
    "name": {
      "type": "string",
      "analyzer": "autocomplete_analyzer"
    }
  }
}
'


echo -e "\n\nairport mappings:: \n";

curl -XPUT "elastic_host:9200/tt/airport/_mapping" -H 'Content-Type: application/json' -d'
{
  "properties": {
    "used_by_sabre": {
      "type": "boolean"
    }
  }
}
'

echo -e "\n\nHotelsCityNew mappings:: \n";

curl -XPUT "elastic_host:9200/tt/HotelsCityNew/_mapping" -H 'Content-Type: application/json' -d'
{
  "properties": {
    "hotelcount": {
      "type": "integer"
    },
    "city_name": {
      "type": "string"
    },
    "name": {
      "type": "string",
      "analyzer": "autocomplete_analyzer"
    }
  }
}
'

echo -e "\n\n\n";

exit 0;
