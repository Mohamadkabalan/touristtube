#!/bin/bash

curl -XPUT "elastic_host:9200/tt_dictionary" -H 'Content-Type: application/json' -d'
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
    "dictionary": {
      "properties": {
        "id": {
          "type": "integer"
        },
        "keyword": {
          "type": "keyword",
          "normalizer": "tolowercase"
        },
        "name": {
          "type": "text"
        },
        "published": {
          "type": "integer"
        },
        "results": {
          "type": "nested",
          "properties": {
            "id": {
              "type": "integer"
            },
            "type": {
              "type": "keyword",
              "normalizer": "tolowercase"
            },
            "entity_id": {
              "type": "integer"
            },
            "entity_type": {
              "type": "integer"
            },
            "name": {
              "type": "text"
            },
            "popularity": {
              "type": "integer"
            },
            "entity_popularity": {
              "type": "integer"
            },
            "published": {
              "type": "integer"
            },
            "coordinates": {
              "type": "geo_point"
            },
            "city": {
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
                "coordinates": {
                  "type": "geo_point"
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
                }
              }
            }
          }
        }
      }
    }
  }
}
'
