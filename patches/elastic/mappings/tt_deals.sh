#!/bin/bash

curl -XPUT "elastic_host:9200/tt_deals" -H 'Content-Type: application/json' -d'
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
    "deals": {
      "properties": {
        "type": {
          "type": "keyword",
          "normalizer": "tolowercase"
        },
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
        "description": {
          "type": "text"
        },
        "details": {
          "properties": {
            "api_id": {
              "type": "integer"
            },
            "type_id": {
              "type": "integer"
            },
            "start": {
              "type": "date",
              "format": "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||MM-dd-yyyy||epoch_millis"
            },
            "end": {
              "type": "date",
              "format": "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||MM-dd-yyyy||epoch_millis"
            },
            "duration": {
              "enabled": "false"
            }
          }
        },
        "media": {
          "properties": {
            "image": {
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
            "description": {
              "type": "text"
            },
            "location": {
              "properties": {
                "city": {
                  "properties": {
                    "id": {
                      "type": "integer"
                    },
                    "code": {
                      "type": "keyword",
                      "normalizer": "tolowercase"
                    },
                    "parent_code": {
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
        "stats": {
          "properties": {
            "nb_views": {
              "type": "integer"
            }
          }
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
        "price": {
          "properties": {
            "currency_code": {
              "type": "keyword",
              "normalizer": "tolowercase"
            },
            "amount_before_discount": {
              "type": "double"
            },
            "amount": {
              "type": "double"
            }
          }
        },
        "stars": {
          "type": "float"
        },
        "popularity": {
          "type": "integer"
        },
        "translation": {
          "dynamic": "true",
          "properties": {}
        },
        "published": {
          "type": "integer"
        },
        "last_updated": {
          "type": "date",
          "format": "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||MM-dd-yyyy||epoch_millis"
        }
      }
    }
  }
}
'
