#!/bin/bash

curl -XPUT "elastic_host:9200/tt_hotelscities" -H 'Content-Type: application/json' -d'
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
    "hotelsCities": {
      "properties": {
        "id": {
          "type": "integer"
        },
        "name": {
          "type": "text",
          "fielddata": "true"
        },
        "name_key": {
          "type": "keyword",
          "normalizer": "tolowercase"
        },
        "popularity": {
          "type": "integer"
        },
        "coordinates": {
          "type": "geo_point"
        },
        "tags": {
          "type": "text",
          "fields": {
            "keyword": {
              "type": "keyword",
              "normalizer": "tolowercase",
              "ignore_above": 256
            }
          }
        },
        "state": {
          "properties": {
            "id": {
              "type": "integer"
            },
            "code": {
              "type": "keyword",
              "normalizer": "tolowercase"
            },
            "name": {
              "type": "text"
            },
            "name_key": {
              "type": "keyword",
              "normalizer": "tolowercase"
            }
          }
        },
        "country": {
          "properties": {
            "id": {
              "type": "integer"
            },
            "code": {
              "type": "keyword",
              "normalizer": "tolowercase"
            },
            "iso3": {
              "type": "keyword",
              "normalizer": "tolowercase"
            },
            "name": {
              "type": "text"
            },
            "name_key": {
              "type": "keyword",
              "normalizer": "tolowercase"
            }
          }
        }
      }
    }
  }
}
'
