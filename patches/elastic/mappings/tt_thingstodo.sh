#!/bin/bash

curl -XPUT "elastic_host:9200/tt_thingstodo" -H 'Content-Type: application/json' -d'
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
    "thingstodo": {
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
        "description": {
          "type": "text"
        },
        "type": {
          "type": "text"
        },
        "aliasId": {
          "type": "integer"
        },
        "alias": {
          "type": "text"
        },
        "countryCode": {
          "type": "text"
        },
        "cityId": {
          "type": "integer"
        }
      }
    }
  }
}
'

