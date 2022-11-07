#!/bin/bash

curl -XPUT "elastic_host:9200/tt_media" -H 'Content-Type: application/json' -d'
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
    "media": {
      "properties": {
        "id": {
          "type": "integer"
        },
        "title": {
          "type": "text",
	  "fielddata": "true"
        },
        "title_key": {
          "type": "keyword",
          "normalizer": "tolowercase"
        },
        "name": {
          "type": "text",
          "fielddata": "true"
        },
        "code": {
          "type": "keyword",
          "normalizer": "tolowercase"
        },
        "description": {
          "type": "text"
        },
        "is_public": {
          "type": "boolean"
        },
        "keywords": {
        "type": "text",
          "fields": {
            "keyword": {
              "type": "keyword",
              "normalizer": "tolowercase",
              "ignore_above": 256
            }
          }
        },
        "last_updated": {
          "type": "date",
          "format": "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||MM-dd-yyyy||epoch_millis"
        },
        "posted_date": {
          "type": "date",
          "format": "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||MM-dd-yyyy||epoch_millis"
        },
        "place_taken_at": {
              "type": "text"
        },
        "dimension": {
          "type": "text"
        },
        "duration": {
         "type": "text"
        },
        "channel_id": {
         "type": "integer"
        },
        "allowed_users": {
          "type": "text" 
        },
        "hash_id": {
          "type": "keyword",
          "normalizer": "tolowercase"
        },
        "published": {
              "type": "integer"
        },
        "trip_id": {
              "type": "integer"
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
        "stats": {
          "properties": {
            "can_comment": {
              "type": "boolean"
            },
            "can_share": {
              "type": "boolean"
            },
            "nb_likes": {
              "type": "integer"
            },
            "nb_comments": {
              "type": "integer"
            },
            "nb_ratings": {
              "type": "integer"
            },
            "nb_shares": {
              "type": "integer"
            },
            "nb_views": {
              "type": "integer"
            },
            "rating": {
              "type": "double"
            },
            "up_votes": {
              "type": "integer"
            }
          }
        },
        "media": {
          "properties": {
            "images": {
              "properties": {
                "fullpath": {
                  "type": "text"
                },
                "relativepath": {
                  "type": "text"
                },
                "name": {
                  "type": "text"
                },
                "type": {
                  "type": "keyword",
                  "normalizer": "tolowercase"
                }
              }
            }
          }
        },
        "user": {
          "properties": {
            "images": {
              "properties": {
                "fullName": {
                  "type": "text"
                },
                "email": {
                  "type": "text"
                },
                "id": {
                  "type": "integer"
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
