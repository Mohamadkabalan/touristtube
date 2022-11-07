UPDATE cms_hotel h
SET h.popularity = 50
WHERE h.id IN(
   SELECT DISTINCT hi.hotel_id
   FROM cms_hotel_image hi
   WHERE hi.tt_media_type_id = 2
)