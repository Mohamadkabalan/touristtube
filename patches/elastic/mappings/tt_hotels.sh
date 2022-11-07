#!/bin/bash

curl -XPUT "elastic_host:9200/tt_hotels" -H 'Content-Type: application/json' -d'
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
    "hotels": {
      "properties": {
        "id": {
          "type": "integer"
        },
        "vendor": {
          "dynamic": "true",
          "properties": {
            "id": {
              "type": "integer"
            },
            "name": {
              "type": "text"
            },
            "entity_id": {
              "type": "keyword",
              "normalizer": "tolowercase"
            },
            "coordinates": {
              "type": "geo_point"
            },
            "city": {
              "properties": {
                "id": {
                  "type": "integer"
                },
                "name": {
                  "type": "text"
                },
                "popularity": {
                  "type": "short"
                }
              }
            }
          }
        },
        "media":{
          "dynamic": "true",
          "properties": {
            "filename": {
              "type": "text"
            },
            "location": {
              "type": "text"
            },
            "default_pic": {
              "type": "integer"
            },
            "dupe_pool_id": {
              "type": "integer"
            }
          }
        },
        "amenities":{
          "dynamic": "true",
          "properties": {
            "name": {
              "type": "text",
              "fielddata": true
            },
            "id": {
              "type": "integer"
            }
          }
        },
        "name": {
          "type": "text"
        },
        "name_key": {
          "type": "keyword",
          "normalizer": "tolowercase"
        },
        "slug": {
          "type": "text",
          "index": false
        },
        "description": {
          "type": "text"
        },
        "phone": {
          "type": "text",
          "index": false
        },
        "fax": {
          "type": "text",
          "index": false
        },
        "location": {
          "properties": {
            "id": {
              "type": "integer"
            },
            "title": {
              "type": "text"
            },
            "zip_code": {
              "type": "keyword",
              "normalizer": "tolowercase"
            },
            "downtown": {
              "type": "text"
            },
            "address_line_1": {
              "type": "text"
            },
            "address_line_2": {
              "type": "text"
            },
            "state_code": {
              "type": "keyword",
              "normalizer": "tolowercase"
            },
            "city": {
              "properties": {
                "id": {
                  "type": "integer"
                },
                "code": {
                  "type": "keyword"
                },
                "name": {
                  "type": "text"
                },
	            "coordinates": {
	              "type": "geo_point"
	            }
              }
            },
            "country": {
              "properties": {
                "code": {
                  "type": "keyword",
                  "normalizer": "tolowercase"
                },
                "name": {
                  "type": "text"
                }
              }
            },
            "coordinates": {
              "type": "geo_point"
            }
          }
        },
        "distance": {
          "type": "nested",
          "properties": {
            "id": {
              "type": "integer"
            },
            "from": {
              "type": "keyword",
              "normalizer": "tolowercase"
            },
            "name": {
              "type": "text"
            },
            "value": {
              "type": "float"
            }
          }
        },
        "stars": {
          "type": "integer"
        },
        "popularity": {
          "type": "short"
        },
        "published": {
          "type": "integer"
        },
        "images_downloaded": {
          "type": "boolean"
        },
        "logo": {
          "type": "text",
          "index": false
        },
        "map_image": {
          "type": "text",
          "index": false
        },
        "last_updated": {
          "type": "date",
          "format": "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||MM-dd-yyyy||epoch_millis"
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
