# 405163 and 409828 are the same and we are keeping the first one as per hrs.com. Note that both exist as seperate hotels in the latest property file.
UPDATE cms_hotel SET published = -2 WHERE id = 409828;

# Make sure elastic index is updated after running the above update statement so that the autocomplete does not show it anymore.