#!/bin/bash

curl -XPUT "elastic_host:9200/tt_dischotels" -H 'Content-Type: application/json' -d'
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
    "dischotels": {
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
        "stars": {
          "type": "integer"
        },
        "popularity": {
          "type": "short"
        },
        "published": {
          "type": "integer"
        },
        "email": {
          "type": "text"
        },
        "url": {
          "type": "text"
        },
        "phone": {
          "type": "text"
        },
        "rooms": {
          "type": "text"
        },
        "fax": {
          "type": "text"
        },
        "check_in": {
          "type": "text"
        },
        "check_out": {
          "type": "text"
        },
        "propertyName": {
          "type": "text",
          "fielddata": "true"
        },
        "propertyName_key": {
          "type": "keyword",
          "normalizer": "tolowercase"
        },
        "description": {
          "type": "text"
        },
        "last_modified": {
          "type": "date",
          "format": "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||MM-dd-yyyy||epoch_millis"
        },
        "vendor": {
          "properties": {
            "city": {
              "properties": {
                "id": {
                  "type": "integer"
                },
                "name": {
                  "type": "text"
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
            "state": {
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
        "location": {
          "properties": {
            "address": {
              "type": "text"
            },
            "address_short": {
              "type": "text"
            },
            "location": {
              "type": "text"
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
                "cityPopularity": {
                  "type": "integer"
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
            "state": {
              "properties": {
                "code": {
                  "type": "keyword",
                  "normalizer": "tolowercase"
                },
                "name": {
                  "type": "text"
                }
              }
            }
          }
        },
        "prices": {
          "properties": {
            "price": {
              "type": "text"
            },
            "currencyCode": {
              "type": "text"
            },
            "priceFrom": {
              "type": "text"
            }
          }
        },
        "stats": {
          "properties": {
            "rating": {
              "type": "float"
            },
            "rating_cleanliness": {
              "type": "float"
            },
            "rating_dining": {
              "type": "float"
            },
            "rating_location": {
              "type": "float"
            },
            "rating_rooms": {
              "type": "float"
            },
            "rating_service": {
              "type": "float"
            },
            "reviews_count": {
              "type": "float"
            },
            "avg_rating": {
              "type": "float"
            },
            "nb_votes": {
              "type": "float"
            },
            "rating_points": {
              "type": "text"
            },
            "rating_overall_text": {
              "type": "text"
            },
            "reviews_summary_positive": {
              "type": "text"
            },
            "reviews_summary_negative": {
              "type": "text"
            }
          }
        },
        "features": {
          "dynamic": "true",
          "properties": {
            "title": {
              "type": "text",
              "fielddata": "true"
            },
            "title_key": {
              "type": "keyword",
              "normalizer": "tolowercase"
            }
          }
        },
        "media": {
          "properties": {
            "images_count": {
              "type": "integer"
            },
            "map_image": {
              "type": "text"
            },
            "imageExists": {
              "type": "integer"
            },
            "images": {
              "dynamic": "true",
              "properties": {
                "id": {
                  "type": "integer"
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
