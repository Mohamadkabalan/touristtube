<?php

$current_channel = channelFromURL (db_sanitize(UriGetArg(0)));

$css_link = ReturnLink("css/profile_TTpage_left_notifications.css?v=".PROFILE_TTPAGE_LEFT_NOTIFICATIONS_CSS_V);