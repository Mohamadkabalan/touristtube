UPDATE `airport` SET `published`='-2' WHERE airport_code = 'BUH';
UPDATE `airport` SET `published`='-2' WHERE airport_code = 'ZYA';
UPDATE `airport` SET `published`='-2' WHERE airport_code = 'XVQ';
UPDATE `airport` SET `name`='Oral, Kazakhstan' WHERE airport_code = 'URA';
UPDATE `airport` SET `city_id`='2315257' WHERE airport_code = 'URA';


curl -XGET http://ELASTIC:9200/tt_v2.1/airport/_search -H 'Content-Type: application/json' -d '{
  "query":
  {
    "match": {
      "code": "URA"
    }
  }
}'

