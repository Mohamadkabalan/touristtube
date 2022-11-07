#!/bin/bash

curl -XPUT "elastic_host:9200/tt_poi" -H 'Content-Type: application/json' -d'
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
    "poi": {
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
        "email": {
          "type": "text"
        },
        "fax": {
          "type": "text"
        },
        "phone": {
          "type": "text"
        },
        "last_updated": {
          "type": "date",
          "format": "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||MM-dd-yyyy||epoch_millis"
        },
        "popularity": {
          "type": "integer"
        },
        "price": {
          "properties": {
            "currency_code": {
              "type": "keyword",
              "normalizer": "tolowercase"
            },
            "amount": {
              "type": "double"
            }
          }
        },
        "published": {
          "type": "integer"
        },
        "show_on_map": {
          "type": "boolean"
        },
        "stars": {
          "type": "float"
        },
        "status": {
          "type": "text"
        },
        "website": {
          "type": "text"
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
        "media": {
          "properties": {
            "images": {
              "properties": {
                "default_pic": {
                  "type": "boolean"
                },
                "filename": {
                  "type": "text"
                },
                "id": {
                  "type": "integer"
                },
                "poi_id": {
                  "type": "integer"
                },
                "user_id": {
                  "type": "integer"
                }
              }
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
            "zipcode": {
              "type": "keyword",
              "normalizer": "tolowercase"
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
            },
            "titleLocation": {
              "type": "text"
            },
            "address": {
              "type": "text"
            }
          }
        }
      }
    }
  }
}
'
