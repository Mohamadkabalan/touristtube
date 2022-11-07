
# ERROR 1406 (22001): Data too long for column 'placetakenat' at row 1
# SELECT MAX(LENGTH(placetakenat)) FROM ml_videos;
# 174

ALTER TABLE cms_videos MODIFY COLUMN placetakenat VARCHAR(200) DEFAULT NULL;


UPDATE cms_videos v 
INNER JOIN ml_videos ml ON (ml.video_id = v.id AND ml.lang_code = 'en') 
SET v.title = ml.title, v.description = ml.description, v.placetakenat = ml.placetakenat, v.keywords = ml.keywords;


DELETE FROM ml_videos WHERE lang_code = 'en';

# curl --request DELETE http://esdev01.gcp:9200/tt_media
# /var/www/patches/elastic/mappings/tt_media.sh
# php5.6 /var/www/web/social/be/maintenance/elastic/import_media_New.php
