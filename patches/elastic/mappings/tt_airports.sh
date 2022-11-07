#!/bin/bash

curl -XPUT "elastic_host:9200/tt_airports" -H 'Content-Type: application/json' -d'
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
    "airports": {
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
        },
        "media": {
          "properties": {
            "map_image": {
              "enabled": false
            }
          }
        },
        "vendor": {
          "properties": {
            "id": {
              "type": "integer"
            },
            "name": {
              "type": "text"
            },
            "location": {
              "properties": {
                "world_area_code": {
                  "type": "keyword",
                  "normalizer": "tolowercase"
                },
                "city": {
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
            "timezone": {
              "properties": {
                "gmt_offset": {
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
                  "type": "text",
                  "fielddata": true
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
                  "type": "text",
                  "fielddata": true
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
        "email": {
          "type": "text"
        },
        "fax": {
          "type": "text"
        },
        "published": {
          "type": "integer"
        },
        "runway_elevation": {
          "type": "text"
        },
        "runway_length": {
          "type": "text"
        },
        "show_on_map": {
          "type": "boolean"
        },
        "stars": {
          "type": "float"
        },
        "telephone": {
          "type": "text"
        },
        "titleLocation": {
          "type": "text"
        },
        "website": {
          "type": "text"
        },
        "popularity":{
          "type": "integer"
        },
        "zoom_order": {
          "type": "float"
        },
        "last_updated": {
          "type": "date",
          "format": "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||MM-dd-yyyy||epoch_millis"
        },
        "useForBooking": {
          "type": "boolean"
        }
      }
    }
  }
}
'
