#!/bin/bash

curl -XPUT "elastic_host:9200/tt_location" -H 'Content-Type: application/json' -d'
{
  "settings": {
    "analysis": {
      "normalizer": {
        "tolowercase": {
          "type": "custom",
          "char_filter": [],
          "filter": [
            "lowercase",
            "asciifolding"
          ]
        }
      }
    }
  },
  "mappings": {
    "location": {
      "properties": {
        "id": {
          "type": "integer"
        },
        "type": {
          "type": "keyword",
          "normalizer": "tolowercase"
        },
        "name": {
          "type": "text",
          "fielddata": "true"
        },
        "name_key": {
          "type": "keyword",
          "normalizer": "tolowercase"
        },
        "fullName": {
          "type": "text"
        },
        "popularity": {
          "type": "integer"
        },
        "coordinates": {
          "type": "geo_point"
        },
        "stateCode": {
          "type": "text"
        },
        "countryCode":{
          "type": "text",
          "fielddata": "true"
        },
        "countryThreeCode":{
          "type": "text",
          "fielddata": "true"
        },
        "countryISO3Code":{
          "type": "text",
          "fielddata": "true"
        },
        "timezoneid":{
          "type": "text"
        },
        "usedBySafeCharge":{
          "type": "boolean"
        },
        "flagIcon":{
          "type": "text"
        },
        "boundingBox": {
          "type": "geo_shape"
        },
        "translation": {
          "dynamic": "true",
          "properties": {}
        }
      }
    }
  }
}
'
