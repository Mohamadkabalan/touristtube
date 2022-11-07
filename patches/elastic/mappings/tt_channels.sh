#!/bin/bash

curl -XPUT "elastic_host:9200/tt_channels" -H 'Content-Type: application/json' -d'
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
    "channels": {
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
        "url": {
          "type": "text"
        },
        "summary": {
          "type": "text"
        },
        "last_updated": {
          "type": "date",
          "format": "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||MM-dd-yyyy||epoch_millis"
        },
        "popularity": {
          "type": "integer"
        },
        "stats": {
          "properties": {
            "nb_comments": {
              "type": "integer"
            },
            "up_votes": {
              "type": "integer"
            },
            "social_weight": {
              "type": "integer"
            }
          }
        },
        "owner": {
          "properties": {
            "id": {
              "type": "integer"
            },
            "name": {
              "type": "text"
            }
          }
        },
        "category": {
          "properties": {
            "id": {
              "type": "integer"
            },
            "name": {
              "type": "text"
            }
          }
        },
        "media": {
          "properties": {
            "images": {
              "properties": {
                "header": {
                  "type": "text"
                },
                "logo": {
                  "type": "text"
                }
              }
            }
          }
        },
        "location": {
          "properties": {
            "city": {
              "properties": {
                "id": {
                  "type": "integer"
                },
                "name": {
                  "type": "text"
                },
                "name_key": {
                  "type": "keyword",
                  "normalizer": "tolowercase"
                },
                "popularity": {
                  "type": "integer"
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
                "name": {
                  "type": "text"
                },
                "name_key": {
                  "type": "keyword",
                  "normalizer": "tolowercase"
                }
              }
            },
            "coordinates": {
              "type": "geo_point"
            }
          }
        }
      }
    }
  }
}
'
