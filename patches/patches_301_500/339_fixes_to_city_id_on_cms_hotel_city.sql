UPDATE cms_hotel_city SET city_name = 'Beijing' WHERE location_id = 48979 AND city_id = 1384846;
UPDATE cms_hotel_city SET city_name = 'Seoul' WHERE location_id = 37473 AND city_id = 2283909;
UPDATE cms_hotel_city SET city_name = 'Copenhagen' WHERE location_id = 49467 AND city_id = 1676414;
UPDATE cms_hotel_city SET city_name = 'Rome (Lazio)' WHERE location_id = 54084 AND city_id = 2211494;

# Start fixing major cities that have a city_id linked to more than 1 location_id
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 1548;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 1845 WHERE location_id = 19981;
UPDATE cms_hotel_city SET city_id = 2447469 WHERE location_id = 26727;
UPDATE cms_hotel_city SET city_id = 7869 WHERE location_id = 195410;
UPDATE cms_hotel_city SET city_id = 8956 WHERE location_id = 227608;
UPDATE cms_hotel_city SET city_id = 2482019 WHERE location_id = 260852;
UPDATE cms_hotel_city SET city_id = 3293 WHERE location_id = 605941;
UPDATE cms_hotel_city SET city_id = 15568 WHERE location_id = 607517;
UPDATE cms_hotel_city SET city_id = 8692 WHERE location_id = 1250312;
UPDATE cms_hotel_city SET city_id = 7869 WHERE location_id = 3324483;
# NOTE: cms_hotel_city.location_ids 195410 and 3324483 have the same name which is Cancún (Quintana Roo)
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 1566;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 28717 WHERE location_id = 260926;
UPDATE cms_hotel_city SET city_id = 28717 WHERE location_id = 270974;
UPDATE cms_hotel_city SET city_id = 40567 WHERE location_id = 605249;
UPDATE cms_hotel_city SET city_id = 29552 WHERE location_id = 607860;
UPDATE cms_hotel_city SET city_id = 4876 WHERE location_id = 608005;
# NOTE: cms_hotel_city.location_ids 260926 and 270974 have equivalent name
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 2834;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 30558 WHERE location_id = 260947;
UPDATE cms_hotel_city SET city_id = 30558 WHERE location_id = 260949;
# NOTE: cms_hotel_city.location_ids  260947 and  260949 have equivalent name
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 5514;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 46612 WHERE location_id = 3233484;
UPDATE cms_hotel_city SET city_id = 46619 WHERE location_id = 3233631;
UPDATE cms_hotel_city SET city_id = 3329 WHERE location_id = 3242599;
UPDATE cms_hotel_city SET city_id = 4951 WHERE location_id = 3242794;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 6652;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 37594 WHERE location_id = 6984;
UPDATE cms_hotel_city SET city_id = 34664 WHERE location_id = 275862;
UPDATE cms_hotel_city SET city_id = 38484 WHERE location_id = 3240429;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 7115;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 38392 WHERE location_id = 260847;
UPDATE cms_hotel_city SET city_id = 38392 WHERE location_id = 610209;
UPDATE cms_hotel_city SET city_id = 38392 WHERE location_id = 611233;
# NOTE: cms_hotel_city.location_ids 260847, 610209 and 611233 have the same name which is Colima (Colima)
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 24353;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 24304 WHERE location_id = 260972;
UPDATE cms_hotel_city SET city_id = 24353 WHERE location_id = 3233467;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 28801;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 28801 WHERE location_id = 260925;
UPDATE cms_hotel_city SET city_id = 40613 WHERE location_id = 3255839;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 34315;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 35480 WHERE location_id = 262899;
UPDATE cms_hotel_city SET city_id = 24309 WHERE location_id = 604786;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 37409;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 26529 WHERE location_id = 22584;
UPDATE cms_hotel_city SET city_id = 23607 WHERE location_id = 205910;
UPDATE cms_hotel_city SET city_id = 34077 WHERE location_id = 271195;
UPDATE cms_hotel_city SET city_id = 24066 WHERE location_id = 273477;
UPDATE cms_hotel_city SET city_id = 30533 WHERE location_id = 281482;
UPDATE cms_hotel_city SET city_id = 38792 WHERE location_id = 284400;
UPDATE cms_hotel_city SET city_id = 32928 WHERE location_id = 606268;
UPDATE cms_hotel_city SET city_id = 37409 WHERE location_id = 609071;
UPDATE cms_hotel_city SET city_id = 40354 WHERE location_id = 269243;
UPDATE cms_hotel_city SET city_id = 23607 WHERE location_id = 271121;
UPDATE cms_hotel_city SET city_id = 25002 WHERE location_id = 3232991;
UPDATE cms_hotel_city SET city_id = 41613 WHERE location_id = 3234431;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 37956;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 45191 WHERE location_id = 274696;
UPDATE cms_hotel_city SET city_id = 37956 WHERE location_id = 279266;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 38467;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 26734 WHERE location_id = 22517;
UPDATE cms_hotel_city SET city_id = 29660 WHERE location_id = 605437;
UPDATE cms_hotel_city SET city_id = 23788 WHERE location_id = 3253178;
UPDATE cms_hotel_city SET city_id = 35091 WHERE location_id = 609900;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 40136;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 31777 WHERE location_id = 14998;
UPDATE cms_hotel_city SET city_id = 26166 WHERE location_id = 262996;
UPDATE cms_hotel_city SET city_id = 2449296 WHERE location_id = 604496;
UPDATE cms_hotel_city SET city_id = 25965 WHERE location_id = 607492;
UPDATE cms_hotel_city SET city_id = 41839 WHERE location_id = 611511;
UPDATE cms_hotel_city SET city_id = 24331 WHERE location_id = 3067878;
UPDATE cms_hotel_city SET city_id = 35538 WHERE location_id = 3265519;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 40758;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 31473 WHERE location_id = 135810;
UPDATE cms_hotel_city SET city_id = 40758 WHERE location_id = 607494;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 46644;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 46644 WHERE location_id = 3242391;
UPDATE cms_hotel_city SET city_id = 2447730 WHERE location_id = 3255666;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 47799;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 48462 WHERE location_id = 3151123;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 47801;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 60151 WHERE location_id = 3148746;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 48182;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 60000 WHERE location_id = 3146269;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 48191;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 48320 WHERE location_id = 11792;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 48353;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 48354 WHERE location_id = 13334;
UPDATE cms_hotel_city SET city_id = 47753 WHERE location_id = 290070;
UPDATE cms_hotel_city SET city_id = 48162 WHERE location_id = 2961224;
UPDATE cms_hotel_city SET city_id = 48166 WHERE location_id = 3148264;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 48561;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 2209934 WHERE location_id = 39137;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 48587;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 48587 WHERE location_id = 3324834;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 48636;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 48623 WHERE location_id = 3148045;
UPDATE cms_hotel_city SET city_id = 47597 WHERE location_id = 3148296;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 49664;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 2102463 WHERE location_id = 136634;
UPDATE cms_hotel_city SET city_id = 1323415 WHERE location_id = 1245785;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 49829;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 60233 WHERE location_id = 1245789;
UPDATE cms_hotel_city SET city_id = 49829 WHERE location_id = 3157055;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 50821;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 59815 WHERE location_id = 3149477;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 51484;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 48654 WHERE location_id = 3148232;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 53300;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 50001 WHERE location_id = 3148081;
UPDATE cms_hotel_city SET city_id = 48748 WHERE location_id = 3148222;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 60752;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 49149 WHERE location_id = 3148145;
UPDATE cms_hotel_city SET city_id = 60358 WHERE location_id = 3156840;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 64813;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 58177 WHERE location_id = 3151181;
UPDATE cms_hotel_city SET city_id = 1621246 WHERE location_id = 39155;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 65052;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 47729 WHERE location_id = 39301;
UPDATE cms_hotel_city SET city_id = 1778387 WHERE location_id = 3148155;
UPDATE cms_hotel_city SET city_id = 47728 WHERE location_id = 3146356;
UPDATE cms_hotel_city SET city_id = 48411 WHERE location_id = 3149079;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 67340;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 2208537 WHERE location_id = 558578;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 73071;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 69779 WHERE location_id = 882852;
UPDATE cms_hotel_city SET city_id = 83621 WHERE location_id = 884641;
UPDATE cms_hotel_city SET city_id = 73072 WHERE location_id = 886847;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 83567;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 68562 WHERE location_id = 42675;
UPDATE cms_hotel_city SET city_id = 68922 WHERE location_id = 869028;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 85239;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 85049 WHERE location_id = 15712;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 85524;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 84681 WHERE location_id = 18848;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 85532;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 85330 WHERE location_id = 1211789;
UPDATE cms_hotel_city SET city_id = 84322 WHERE location_id = 19092;
UPDATE cms_hotel_city SET city_id = 84863 WHERE location_id = 1215197;
UPDATE cms_hotel_city SET city_id = 83649 WHERE location_id = 1215255;
UPDATE cms_hotel_city SET city_id = 84328 WHERE location_id = 3164988;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 123468;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 123672 WHERE location_id = 1246249;
UPDATE cms_hotel_city SET city_id = 123329 WHERE location_id = 1246343;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 123725;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 124924 WHERE location_id = 1246317;
UPDATE cms_hotel_city SET city_id = 123726 WHERE location_id = 1246354;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 123731;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 123734 WHERE location_id = 1246251;
UPDATE cms_hotel_city SET city_id = 122957 WHERE location_id = 1246298;
UPDATE cms_hotel_city SET city_id = 124958 WHERE location_id = 1246329;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 124384;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 124855 WHERE location_id = 1246267;
UPDATE cms_hotel_city SET city_id = 124851 WHERE location_id = 1246321;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 124937;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 124430 WHERE location_id = 1246306;
UPDATE cms_hotel_city SET city_id = 124940 WHERE location_id = 1246348;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 124978;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 123261 WHERE location_id = 1246342;
UPDATE cms_hotel_city SET city_id = 124849 WHERE location_id = 1246360;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 125198;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 2482024 WHERE location_id = 1246271;
UPDATE cms_hotel_city SET city_id = 125049 WHERE location_id = 1246357;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 125473;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 2449881 WHERE location_id = 6426;
UPDATE cms_hotel_city SET city_id = 128536 WHERE location_id = 229832;
UPDATE cms_hotel_city SET city_id = 126015 WHERE location_id = 229910;
UPDATE cms_hotel_city SET city_id = 131688 WHERE location_id = 235637;
UPDATE cms_hotel_city SET city_id = 127072 WHERE location_id = 1236054;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 127565;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 131107 WHERE location_id = 201524;
UPDATE cms_hotel_city SET city_id = 127956 WHERE location_id = 241284;
UPDATE cms_hotel_city SET city_id = 130025 WHERE location_id = 1237163;
UPDATE cms_hotel_city SET city_id = 125786 WHERE location_id = 229916;
UPDATE cms_hotel_city SET city_id = 128496 WHERE location_id = 236144;
UPDATE cms_hotel_city SET city_id = 127749 WHERE location_id = 236317;
UPDATE cms_hotel_city SET city_id = 131945 WHERE location_id = 237727;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 127973;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 127973 WHERE location_id = 67464;
UPDATE cms_hotel_city SET city_id = 127972 WHERE location_id = 229843;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 127995;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 132108 WHERE location_id = 236118;
UPDATE cms_hotel_city SET city_id = 128683 WHERE location_id = 241133;
UPDATE cms_hotel_city SET city_id = 130047 WHERE location_id = 2900390;
UPDATE cms_hotel_city SET city_id = 129833 WHERE location_id = 229794;
UPDATE cms_hotel_city SET city_id = 130634 WHERE location_id = 235779;
UPDATE cms_hotel_city SET city_id = 128468 WHERE location_id = 236153;
UPDATE cms_hotel_city SET city_id = 127751 WHERE location_id = 236316;
UPDATE cms_hotel_city SET city_id = 125431 WHERE location_id = 236734;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 128019;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 125850 WHERE location_id = 236648;
UPDATE cms_hotel_city SET city_id = 132153 WHERE location_id = 2901015;
UPDATE cms_hotel_city SET city_id = 131639 WHERE location_id = 229639;
UPDATE cms_hotel_city SET city_id = 130201 WHERE location_id = 229776;
UPDATE cms_hotel_city SET city_id = 126318 WHERE location_id = 229892;
UPDATE cms_hotel_city SET city_id = 126029 WHERE location_id = 230000;
UPDATE cms_hotel_city SET city_id = 131581 WHERE location_id = 235657;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 130116;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 130116 WHERE location_id = 229780;
UPDATE cms_hotel_city SET city_id = 125471 WHERE location_id = 229926;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 132116;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 130464 WHERE location_id = 240415;
UPDATE cms_hotel_city SET city_id = 132116 WHERE location_id = 2900808;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 132140;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 127975 WHERE location_id = 178443;
UPDATE cms_hotel_city SET city_id = 132140 WHERE location_id = 2900884;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 132640;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 132640 WHERE location_id = 290011;
UPDATE cms_hotel_city SET city_id = 132548 WHERE location_id = 733527;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 132809;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 2486398 WHERE location_id = 730144;
UPDATE cms_hotel_city SET city_id = 132482 WHERE location_id = 730059;
UPDATE cms_hotel_city SET city_id = 132509 WHERE location_id = 3075997;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 133561;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 134884 WHERE location_id = 22755;
UPDATE cms_hotel_city SET city_id = 139865 WHERE location_id = 729971;
UPDATE cms_hotel_city SET city_id = 138992 WHERE location_id = 730721;
UPDATE cms_hotel_city SET city_id = 134333 WHERE location_id = 733276;
UPDATE cms_hotel_city SET city_id = 134265 WHERE location_id = 730052;
UPDATE cms_hotel_city SET city_id = 137208 WHERE location_id = 3075868;
UPDATE cms_hotel_city SET city_id = 136595 WHERE location_id = 3076106;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 133755;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 140743 WHERE location_id = 726401;
UPDATE cms_hotel_city SET city_id = 133719 WHERE location_id = 728218;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 134389;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 138791 WHERE location_id = 8231;
UPDATE cms_hotel_city SET city_id = 138737 WHERE location_id = 76907;
UPDATE cms_hotel_city SET city_id = 136312 WHERE location_id = 150629;
UPDATE cms_hotel_city SET city_id = 140788 WHERE location_id = 289899;
UPDATE cms_hotel_city SET city_id = 140807 WHERE location_id = 729528;
UPDATE cms_hotel_city SET city_id = 135832 WHERE location_id = 731091;
UPDATE cms_hotel_city SET city_id = 140013 WHERE location_id = 731993;
UPDATE cms_hotel_city SET city_id = 136761 WHERE location_id = 733243;
UPDATE cms_hotel_city SET city_id = 140885 WHERE location_id = 186269;
UPDATE cms_hotel_city SET city_id = 139945 WHERE location_id = 730062;
UPDATE cms_hotel_city SET city_id = 136363 WHERE location_id = 730671;
UPDATE cms_hotel_city SET city_id = 138491 WHERE location_id = 731983;
UPDATE cms_hotel_city SET city_id = 140173 WHERE location_id = 3075991;
UPDATE cms_hotel_city SET city_id = 137297 WHERE location_id = 3076018;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 135191;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 139442 WHERE location_id = 149244;
UPDATE cms_hotel_city SET city_id = 139228 WHERE location_id = 149493;
UPDATE cms_hotel_city SET city_id = 133783 WHERE location_id = 172648;
UPDATE cms_hotel_city SET city_id = 140286 WHERE location_id = 170731;
UPDATE cms_hotel_city SET city_id = 139330 WHERE location_id = 727813;
UPDATE cms_hotel_city SET city_id = 133996 WHERE location_id = 728409;
UPDATE cms_hotel_city SET city_id = 133726 WHERE location_id = 733150;
UPDATE cms_hotel_city SET city_id = 140747 WHERE location_id = 733752;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 135815;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 133823 WHERE location_id = 25584;
UPDATE cms_hotel_city SET city_id = 137807 WHERE location_id = 731330;
UPDATE cms_hotel_city SET city_id = 139303 WHERE location_id = 729595;
UPDATE cms_hotel_city SET city_id = 133345 WHERE location_id = 3074419;
UPDATE cms_hotel_city SET city_id = 140719 WHERE location_id = 3076104;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 135828;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 133040 WHERE location_id = 163580;
UPDATE cms_hotel_city SET city_id = 135828 WHERE location_id = 3074454;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 136084;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 135839 WHERE location_id = 9052;
UPDATE cms_hotel_city SET city_id = 133885 WHERE location_id = 25507;
UPDATE cms_hotel_city SET city_id = 140014 WHERE location_id = 729308;
UPDATE cms_hotel_city SET city_id = 133882 WHERE location_id = 730319;
UPDATE cms_hotel_city SET city_id = 138043 WHERE location_id = 730399;
UPDATE cms_hotel_city SET city_id = 138658 WHERE location_id = 732529;
UPDATE cms_hotel_city SET city_id = 134065 WHERE location_id = 730313;
UPDATE cms_hotel_city SET city_id = 140728 WHERE location_id = 3074473;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 137566;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 136538 WHERE location_id = 712;
UPDATE cms_hotel_city SET city_id = 134135 WHERE location_id = 25190;
UPDATE cms_hotel_city SET city_id = 135457 WHERE location_id = 234592;
UPDATE cms_hotel_city SET city_id = 135054 WHERE location_id = 728326;
UPDATE cms_hotel_city SET city_id = 140789 WHERE location_id = 99446;
UPDATE cms_hotel_city SET city_id = 133902 WHERE location_id = 728756;
UPDATE cms_hotel_city SET city_id = 139989 WHERE location_id = 730716;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 139415;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 140864 WHERE location_id = 725934;
UPDATE cms_hotel_city SET city_id = 140744 WHERE location_id = 725937;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 140000;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 138984 WHERE location_id = 733714;
UPDATE cms_hotel_city SET city_id = 140242 WHERE location_id = 173575;
UPDATE cms_hotel_city SET city_id = 139398 WHERE location_id = 726992;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 140310;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 140751 WHERE location_id = 6155;
UPDATE cms_hotel_city SET city_id = 138594 WHERE location_id = 149453;
UPDATE cms_hotel_city SET city_id = 137303 WHERE location_id = 160365;
UPDATE cms_hotel_city SET city_id = 136542 WHERE location_id = 169299;
UPDATE cms_hotel_city SET city_id = 133158 WHERE location_id = 77176;
UPDATE cms_hotel_city SET city_id = 137452 WHERE location_id = 732575;
UPDATE cms_hotel_city SET city_id = 134630 WHERE location_id = 3075555;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 140739;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 137582 WHERE location_id = 153763;
UPDATE cms_hotel_city SET city_id = 140739 WHERE location_id = 725936;
UPDATE cms_hotel_city SET city_id = 140763 WHERE location_id = 733141;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 155046;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 1833646 WHERE location_id = 188064;
UPDATE cms_hotel_city SET city_id = 1831389 WHERE location_id = 197979;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 196951;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 400468 WHERE location_id = 41402;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 197588;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 915599 WHERE location_id = 169997;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 198304;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 1289132 WHERE location_id = 41457;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 198566;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 198566 WHERE location_id = 129895;
UPDATE cms_hotel_city SET city_id = 1857412 WHERE location_id = 287621;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 202149;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 205752 WHERE location_id = 289531;
UPDATE cms_hotel_city SET city_id = 206425 WHERE location_id = 612202;
UPDATE cms_hotel_city SET city_id = 205597 WHERE location_id = 612232;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 202616;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 203231 WHERE location_id = 612235;
UPDATE cms_hotel_city SET city_id = 202619 WHERE location_id = 612301;
UPDATE cms_hotel_city SET city_id = 201236 WHERE location_id = 612318;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 216941;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 216942 WHERE location_id = 611879;
UPDATE cms_hotel_city SET city_id = 218544 WHERE location_id = 611891;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 244379;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 1859180 WHERE location_id = 1186022;
UPDATE cms_hotel_city SET city_id = 1859180 WHERE location_id = 1186301;
UPDATE cms_hotel_city SET city_id = 1859180 WHERE location_id = 1188780;
UPDATE cms_hotel_city SET city_id = 1859180 WHERE location_id = 1190782;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 257472;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 252457 WHERE location_id = 944261;
UPDATE cms_hotel_city SET city_id = 270776 WHERE location_id = 946489;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 262266;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 262269 WHERE location_id = 135536;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 267788;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 261721 WHERE location_id = 943239;
UPDATE cms_hotel_city SET city_id = 253319 WHERE location_id = 943368;
UPDATE cms_hotel_city SET city_id = 253301 WHERE location_id = 943544;
UPDATE cms_hotel_city SET city_id = 250757 WHERE location_id = 943854;
UPDATE cms_hotel_city SET city_id = 260486 WHERE location_id = 944817;
UPDATE cms_hotel_city SET city_id = 251394 WHERE location_id = 946437;
UPDATE cms_hotel_city SET city_id = 266087 WHERE location_id = 946619;
UPDATE cms_hotel_city SET city_id = 272489 WHERE location_id = 946651;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 272339;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 857051 WHERE location_id = 792638;
UPDATE cms_hotel_city SET city_id = 856986 WHERE location_id = 854168;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 272413;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 255627 WHERE location_id = 944246;
UPDATE cms_hotel_city SET city_id = 281825 WHERE location_id = 944376;
UPDATE cms_hotel_city SET city_id = 269263 WHERE location_id = 944912;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 274694;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 253132 WHERE location_id = 946305;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 361348;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 378746 WHERE location_id = 303614;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 362330;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 360916 WHERE location_id = 316157;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 366298;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 378539 WHERE location_id = 290403;
UPDATE cms_hotel_city SET city_id = 368369 WHERE location_id = 295702;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 376162;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 376162 WHERE location_id = 328412;
UPDATE cms_hotel_city SET city_id = 377145 WHERE location_id = 328853;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 377620;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 397541 WHERE location_id = 111227;
UPDATE cms_hotel_city SET city_id = 397308  WHERE location_id = 296725;
UPDATE cms_hotel_city SET city_id = 397138  WHERE location_id = 316805;
UPDATE cms_hotel_city SET city_id = 365657  WHERE location_id = 316976;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 377623;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 396884 WHERE location_id = 111110;
UPDATE cms_hotel_city SET city_id = 364945 WHERE location_id = 112497;
UPDATE cms_hotel_city SET city_id = 372878 WHERE location_id = 133714;
UPDATE cms_hotel_city SET city_id = 374149 WHERE location_id = 291502;
UPDATE cms_hotel_city SET city_id = 376835 WHERE location_id = 293509;
UPDATE cms_hotel_city SET city_id = 365573 WHERE location_id = 293765;
UPDATE cms_hotel_city SET city_id = 373826 WHERE location_id = 317276;
UPDATE cms_hotel_city SET city_id = 366463 WHERE location_id = 324607;
UPDATE cms_hotel_city SET city_id = 377234 WHERE location_id = 324615;
UPDATE cms_hotel_city SET city_id = 375856 WHERE location_id = 330532;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 377624;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 396699 WHERE location_id = 25570;
UPDATE cms_hotel_city SET city_id = 377113 WHERE location_id = 109767;
UPDATE cms_hotel_city SET city_id = 396913 WHERE location_id = 111236;
UPDATE cms_hotel_city SET city_id = 375498 WHERE location_id = 153269;
UPDATE cms_hotel_city SET city_id = 377095 WHERE location_id = 228997;
UPDATE cms_hotel_city SET city_id = 397181 WHERE location_id = 313124;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 377626;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 374553 WHERE location_id = 7390;
UPDATE cms_hotel_city SET city_id = 368771 WHERE location_id = 18125;
UPDATE cms_hotel_city SET city_id = 373159 WHERE location_id = 25076;
UPDATE cms_hotel_city SET city_id = 377162 WHERE location_id = 109733;
UPDATE cms_hotel_city SET city_id = 397404 WHERE location_id = 155510;
UPDATE cms_hotel_city SET city_id = 387334 WHERE location_id = 289615;
UPDATE cms_hotel_city SET city_id = 397400 WHERE location_id = 289797;
UPDATE cms_hotel_city SET city_id = 368754 WHERE location_id = 298931;
UPDATE cms_hotel_city SET city_id = 368733 WHERE location_id = 298932;
UPDATE cms_hotel_city SET city_id = 374247 WHERE location_id = 299338;
UPDATE cms_hotel_city SET city_id = 374043 WHERE location_id = 299341;
UPDATE cms_hotel_city SET city_id = 391605 WHERE location_id = 299346;
UPDATE cms_hotel_city SET city_id = 373955 WHERE location_id = 299597;
UPDATE cms_hotel_city SET city_id = 369468 WHERE location_id = 299937;
UPDATE cms_hotel_city SET city_id = 368771 WHERE location_id = 300572;
UPDATE cms_hotel_city SET city_id = 374653 WHERE location_id = 311791;
UPDATE cms_hotel_city SET city_id = 375202 WHERE location_id = 335611;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 380368;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 383918 WHERE location_id = 20656;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 381141;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 386309 WHERE location_id = 155158;
UPDATE cms_hotel_city SET city_id = 381141 WHERE location_id = 294872;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 381222;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 396953 WHERE location_id = 111428;
UPDATE cms_hotel_city SET city_id = 381222 WHERE location_id = 112790;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 383748;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 384928 WHERE location_id = 299438;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 384173;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 391325 WHERE location_id = 306261;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 387592;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 381979 WHERE location_id = 156263;
UPDATE cms_hotel_city SET city_id = 397462 WHERE location_id = 302261;
UPDATE cms_hotel_city SET city_id = 382110 WHERE location_id = 302547;
UPDATE cms_hotel_city SET city_id = 381146 WHERE location_id = 302556;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 389516;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 397558 WHERE location_id = 152575;
UPDATE cms_hotel_city SET city_id = 389516 WHERE location_id = 154361;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 396289;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 391585 WHERE location_id = 295722;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 396344;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 397381 WHERE location_id = 17436;
UPDATE cms_hotel_city SET city_id = 379770 WHERE location_id = 27897;
UPDATE cms_hotel_city SET city_id = 377802 WHERE location_id = 29433;
UPDATE cms_hotel_city SET city_id = 396868 WHERE location_id = 109462;
UPDATE cms_hotel_city SET city_id = 392612 WHERE location_id = 132327;
UPDATE cms_hotel_city SET city_id = 390200 WHERE location_id = 134731;
UPDATE cms_hotel_city SET city_id = 385615 WHERE location_id = 138043;
UPDATE cms_hotel_city SET city_id = 395811 WHERE location_id = 289062;
UPDATE cms_hotel_city SET city_id = 394698 WHERE location_id = 289065;
UPDATE cms_hotel_city SET city_id = 395449 WHERE location_id = 289841;
UPDATE cms_hotel_city SET city_id = 387693 WHERE location_id = 304085;
UPDATE cms_hotel_city SET city_id = 390598 WHERE location_id = 305707;
UPDATE cms_hotel_city SET city_id = 394698 WHERE location_id = 305742;
UPDATE cms_hotel_city SET city_id = 398281 WHERE location_id = 307212;
UPDATE cms_hotel_city SET city_id = 396923 WHERE location_id = 310157;
UPDATE cms_hotel_city SET city_id = 378974 WHERE location_id = 312932;
UPDATE cms_hotel_city SET city_id = 395449 WHERE location_id = 317687;
UPDATE cms_hotel_city SET city_id = 393892 WHERE location_id = 321353;
UPDATE cms_hotel_city SET city_id = 391129 WHERE location_id = 332871;
UPDATE cms_hotel_city SET city_id = 390653 WHERE location_id = 332965;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 396350;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 397527 WHERE location_id = 111461;
UPDATE cms_hotel_city SET city_id = 384787 WHERE location_id = 111995;
UPDATE cms_hotel_city SET city_id = 381968 WHERE location_id = 112641;
UPDATE cms_hotel_city SET city_id = 377799 WHERE location_id = 113525;
UPDATE cms_hotel_city SET city_id = 378724 WHERE location_id = 301381;
UPDATE cms_hotel_city SET city_id = 380708 WHERE location_id = 302990;
UPDATE cms_hotel_city SET city_id = 379030 WHERE location_id = 303775;
UPDATE cms_hotel_city SET city_id = 380907 WHERE location_id = 309294;
UPDATE cms_hotel_city SET city_id = 377819 WHERE location_id = 319091;
UPDATE cms_hotel_city SET city_id = 397337 WHERE location_id = 337183;
UPDATE cms_hotel_city SET city_id = 380991 WHERE location_id = 3037445;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 396351;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 393829 WHERE location_id = 6123;
UPDATE cms_hotel_city SET city_id = 384575 WHERE location_id = 20057;
UPDATE cms_hotel_city SET city_id = 384409 WHERE location_id = 20092;
UPDATE cms_hotel_city SET city_id = 395878 WHERE location_id = 109751;
UPDATE cms_hotel_city SET city_id = 396978 WHERE location_id = 110905;
UPDATE cms_hotel_city SET city_id = 386749 WHERE location_id = 111470;
UPDATE cms_hotel_city SET city_id = 384356 WHERE location_id = 112122;
UPDATE cms_hotel_city SET city_id = 381478 WHERE location_id = 141011;
UPDATE cms_hotel_city SET city_id = 393686 WHERE location_id = 229971;
UPDATE cms_hotel_city SET city_id = 393686 WHERE location_id = 300762;
UPDATE cms_hotel_city SET city_id = 381478 WHERE location_id = 306333;
UPDATE cms_hotel_city SET city_id = 386458 WHERE location_id = 307100;
UPDATE cms_hotel_city SET city_id = 393032 WHERE location_id = 308371;
UPDATE cms_hotel_city SET city_id = 397507 WHERE location_id = 312586;
UPDATE cms_hotel_city SET city_id = 383999 WHERE location_id = 320646;
UPDATE cms_hotel_city SET city_id = 390017 WHERE location_id = 322419;
UPDATE cms_hotel_city SET city_id = 386949 WHERE location_id = 327137;
UPDATE cms_hotel_city SET city_id = 382603 WHERE location_id = 334410;
UPDATE cms_hotel_city SET city_id = 386231 WHERE location_id = 1796393;
UPDATE cms_hotel_city SET city_id = 393030 WHERE location_id = 3040697;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 396352;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 396700 WHERE location_id = 24108;
UPDATE cms_hotel_city SET city_id = 397166 WHERE location_id = 109966;
UPDATE cms_hotel_city SET city_id = 394565 WHERE location_id = 109972;
UPDATE cms_hotel_city SET city_id = 397260 WHERE location_id = 133370;
UPDATE cms_hotel_city SET city_id = 379071 WHERE location_id = 157141;
UPDATE cms_hotel_city SET city_id = 387447 WHERE location_id = 299938;
UPDATE cms_hotel_city SET city_id = 397090 WHERE location_id = 302091;
UPDATE cms_hotel_city SET city_id = 379063 WHERE location_id = 302570;
UPDATE cms_hotel_city SET city_id = 386776 WHERE location_id = 303486;
UPDATE cms_hotel_city SET city_id = 379515 WHERE location_id = 305829;
UPDATE cms_hotel_city SET city_id = 379063 WHERE location_id = 313044;
UPDATE cms_hotel_city SET city_id = 379977 WHERE location_id = 325194;
UPDATE cms_hotel_city SET city_id = 397095 WHERE location_id = 325308;
UPDATE cms_hotel_city SET city_id = 389446 WHERE location_id = 332825;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 396922;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 377129 WHERE location_id = 310765;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 397009;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 388240 WHERE location_id = 337768;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 397463;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 377351 WHERE location_id = 288003;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 397517;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 370042 WHERE location_id = 318775;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 400808;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 400453 WHERE location_id = 139815;
UPDATE cms_hotel_city SET city_id = 399084 WHERE location_id = 149400;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 403805;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 415925 WHERE location_id = 289813;
UPDATE cms_hotel_city SET city_id = 400547 WHERE location_id = 923310;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 404067;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 416520 WHERE location_id = 922504;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 404778;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 404778 WHERE location_id = 203855;
UPDATE cms_hotel_city SET city_id = 408459 WHERE location_id = 289859;
UPDATE cms_hotel_city SET city_id = 403974 WHERE location_id = 891020;
UPDATE cms_hotel_city SET city_id = 417540 WHERE location_id = 916772;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 405125;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 416505 WHERE location_id = 891030;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 405721;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 405721 WHERE location_id = 203043;
UPDATE cms_hotel_city SET city_id = 417521 WHERE location_id = 923181;
UPDATE cms_hotel_city SET city_id = 415925 WHERE location_id = 923202;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 405821;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 415881 WHERE location_id = 73923;
UPDATE cms_hotel_city SET city_id = 415876 WHERE location_id = 73807;
UPDATE cms_hotel_city SET city_id = 415879 WHERE location_id = 172719;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 408391;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 415869 WHERE location_id = 914504;
UPDATE cms_hotel_city SET city_id = 403568 WHERE location_id = 921707;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 2486614;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 30558 WHERE location_id = 3244460;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 408868;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 411767 WHERE location_id = 888380;
UPDATE cms_hotel_city SET city_id = 412965 WHERE location_id = 917746;
UPDATE cms_hotel_city SET city_id = 412503 WHERE location_id = 920408;
UPDATE cms_hotel_city SET city_id = 410115 WHERE location_id = 921937;
UPDATE cms_hotel_city SET city_id = 417205 WHERE location_id = 916280;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 411156;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 410163 WHERE location_id = 922203;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 412963;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 411164 WHERE location_id = 892113;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 415017;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 414200 WHERE location_id = 915740;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 415086;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 417308 WHERE location_id = 920929;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 415834;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 415960 WHERE location_id = 920794;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 415847;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 412497 WHERE location_id = 889693;
UPDATE cms_hotel_city SET city_id = 418028 WHERE location_id = 891931;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 415848;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 412005 WHERE location_id = 891532;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 415852;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 416401 WHERE location_id = 891840;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 415855;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 405127 WHERE location_id = 913534;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 415864;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 416523 WHERE location_id = 919369;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 415881;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 404923 WHERE location_id = 73923;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 415866;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 403758 WHERE location_id = 889199;
UPDATE cms_hotel_city SET city_id = 408235 WHERE location_id = 921830;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 415884;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 416755 WHERE location_id = 4387;
UPDATE cms_hotel_city SET city_id = 416756 WHERE location_id = 99478;
UPDATE cms_hotel_city SET city_id = 416754 WHERE location_id = 214929;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 415980;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 409252 WHERE location_id = 287864;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 416539;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 751306 WHERE location_id = 912429;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 417247;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 415631 WHERE location_id = 923038;
UPDATE cms_hotel_city SET city_id = 404445 WHERE location_id = 923381;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 417546;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 406961 WHERE location_id = 891000;
UPDATE cms_hotel_city SET city_id = 408542 WHERE location_id = 891006;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 417581;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 403283 WHERE location_id = 290401;
UPDATE cms_hotel_city SET city_id = 405729 WHERE location_id = 914341;
UPDATE cms_hotel_city SET city_id = 417581 WHERE location_id = 915782;
UPDATE cms_hotel_city SET city_id = 403525 WHERE location_id = 922387;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 404445;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 417303 WHERE location_id = 289811;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 417698;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 417597 WHERE location_id = 915498;
UPDATE cms_hotel_city SET city_id = 414211 WHERE location_id = 915501;
UPDATE cms_hotel_city SET city_id = 415914 WHERE location_id = 915835;
UPDATE cms_hotel_city SET city_id = 414163 WHERE location_id = 916483;
UPDATE cms_hotel_city SET city_id = 411998 WHERE location_id = 918386;
UPDATE cms_hotel_city SET city_id = 412133 WHERE location_id = 921945;
UPDATE cms_hotel_city SET city_id = 409882 WHERE location_id = 913289;
UPDATE cms_hotel_city SET city_id = 415913 WHERE location_id = 917545;
UPDATE cms_hotel_city SET city_id = 413011 WHERE location_id = 918056;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 417784;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 411102 WHERE location_id = 916125;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 417819;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 405142 WHERE location_id = 922357;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 418028;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 416207 WHERE location_id = 912500;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 418660;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 419697 WHERE location_id = 612399;
UPDATE cms_hotel_city SET city_id = 418625 WHERE location_id = 612730;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 419706;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 418379 WHERE location_id = 613756;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 419836;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 418643 WHERE location_id = 612740;
UPDATE cms_hotel_city SET city_id = 418271 WHERE location_id = 613735;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 421802;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 421802 WHERE location_id = 26714;
UPDATE cms_hotel_city SET city_id = 434510 WHERE location_id = 947354;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 422565;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 422565 WHERE location_id = 25411;
UPDATE cms_hotel_city SET city_id = 423658 WHERE location_id = 946689;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 422729;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 1865441 WHERE location_id = 6404;
UPDATE cms_hotel_city SET city_id = 1865604 WHERE location_id = 88783;
UPDATE cms_hotel_city SET city_id = 1865394 WHERE location_id = 159316;
UPDATE cms_hotel_city SET city_id = 1860180 WHERE location_id = 202023;
UPDATE cms_hotel_city SET city_id = 1859962 WHERE location_id = 234490;
UPDATE cms_hotel_city SET city_id = 1857927 WHERE location_id = 234517;
UPDATE cms_hotel_city SET city_id = 1865436 WHERE location_id = 255064;
UPDATE cms_hotel_city SET city_id = 1857444 WHERE location_id = 257473;
UPDATE cms_hotel_city SET city_id = 1857890 WHERE location_id = 268911;
UPDATE cms_hotel_city SET city_id = 1859507 WHERE location_id = 285615;
UPDATE cms_hotel_city SET city_id = 1865442 WHERE location_id = 1183165;
UPDATE cms_hotel_city SET city_id = 1858906 WHERE location_id = 1184073;
UPDATE cms_hotel_city SET city_id = 1865599 WHERE location_id = 1184972;
UPDATE cms_hotel_city SET city_id = 1855296 WHERE location_id = 1185280;
UPDATE cms_hotel_city SET city_id = 1865441 WHERE location_id = 1185763;
UPDATE cms_hotel_city SET city_id = 1859507 WHERE location_id = 1186245;
UPDATE cms_hotel_city SET city_id = 1859507 WHERE location_id = 1186958;
UPDATE cms_hotel_city SET city_id = 1858244 WHERE location_id = 1187395;
UPDATE cms_hotel_city SET city_id = 1859507 WHERE location_id = 1187452;
UPDATE cms_hotel_city SET city_id = 1855393 WHERE location_id = 1188233;
UPDATE cms_hotel_city SET city_id = 1864688 WHERE location_id = 1188649;
UPDATE cms_hotel_city SET city_id = 1856263 WHERE location_id = 1189221;
UPDATE cms_hotel_city SET city_id = 1859964 WHERE location_id = 1189363;
UPDATE cms_hotel_city SET city_id = 1857926 WHERE location_id = 1190183;
UPDATE cms_hotel_city SET city_id = 1859530 WHERE location_id = 1190242;
UPDATE cms_hotel_city SET city_id = 1855609 WHERE location_id = 1190994;
UPDATE cms_hotel_city SET city_id = 1861386 WHERE location_id = 1191549;
UPDATE cms_hotel_city SET city_id = 1865442 WHERE location_id = 3126687;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 425404;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 2355294 WHERE location_id = 947450;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 427801;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 433497 WHERE location_id = 948937;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 442824;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 442827 WHERE location_id = 1015813;
UPDATE cms_hotel_city SET city_id = 442828 WHERE location_id = 1048271;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 443147;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 438047 WHERE location_id = 2981905;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 443417;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 453335 WHERE location_id = 1041299;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 459978;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 527327 WHERE location_id = 286837;
UPDATE cms_hotel_city SET city_id = 527327 WHERE location_id = 961913;
UPDATE cms_hotel_city SET city_id = 463902 WHERE location_id = 1026277;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 469692;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 469558 WHERE location_id = 976746;
UPDATE cms_hotel_city SET city_id = 446940 WHERE location_id = 1047186;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 475764;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 489671 WHERE location_id = 154296;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 451576;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 531553 WHERE location_id = 1047023;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 459710;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 564049 WHERE location_id = 1015485;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 2283909 WHERE location_id = 3324177;
UPDATE cms_hotel_city SET city_id = 1246396 WHERE location_id = 3204981;
UPDATE cms_hotel_city SET city_id = 1254823 WHERE location_id = 3204980;
UPDATE cms_hotel_city SET city_id = 1258545 WHERE location_id = 599585;
UPDATE cms_hotel_city SET city_id = 1047083 WHERE location_id = 474369;
UPDATE cms_hotel_city SET city_id = 2486520 WHERE location_id = 3157023;
UPDATE cms_hotel_city SET city_id = 2486526 WHERE location_id = 3157042;
UPDATE cms_hotel_city SET city_id = 2486523 WHERE location_id = 3157040;
UPDATE cms_hotel_city SET city_id = 2486516 WHERE location_id = 3156619;
UPDATE cms_hotel_city SET city_id = 2486514 WHERE location_id = 3151210;
UPDATE cms_hotel_city SET city_id = 2486522 WHERE location_id = 3157025;
UPDATE cms_hotel_city SET city_id = 2486440 WHERE location_id = 3146645;
UPDATE cms_hotel_city SET city_id = 2460402 WHERE location_id = 203369;
UPDATE cms_hotel_city SET city_id = 1837005 WHERE location_id = 125074;
UPDATE cms_hotel_city SET city_id = 1833927 WHERE location_id = 101393;
UPDATE cms_hotel_city SET city_id = 2086779 WHERE location_id = 439654;
UPDATE cms_hotel_city SET city_id = 2081732 WHERE location_id = 6739;
UPDATE cms_hotel_city SET city_id = 847147 WHERE location_id = 851866;
UPDATE cms_hotel_city SET city_id = 785743 WHERE location_id = 1796369;
UPDATE cms_hotel_city SET city_id = 778959 WHERE location_id = 1383066;
UPDATE cms_hotel_city SET city_id = 770629 WHERE location_id = 1382932;
UPDATE cms_hotel_city SET city_id = 778672 WHERE location_id = 1103325;
UPDATE cms_hotel_city SET city_id = 2235342 WHERE location_id = 157554;
UPDATE cms_hotel_city SET city_id = 2230877 WHERE location_id = 140313;
UPDATE cms_hotel_city SET city_id = 2229862 WHERE location_id = 98403;
UPDATE cms_hotel_city SET city_id = 879726 WHERE location_id = 196602;
UPDATE cms_hotel_city SET city_id = 2482861 WHERE location_id = 192200;
UPDATE cms_hotel_city SET city_id = 2482772 WHERE location_id = 191200;
UPDATE cms_hotel_city SET city_id = 2482153 WHERE location_id = 195974;
UPDATE cms_hotel_city SET city_id = 918533 WHERE location_id = 853718;
UPDATE cms_hotel_city SET city_id = 919247 WHERE location_id = 852195;
UPDATE cms_hotel_city SET city_id = 921296 WHERE location_id = 780792;
UPDATE cms_hotel_city SET city_id = 921434 WHERE location_id = 28771;
UPDATE cms_hotel_city SET city_id = 920575 WHERE location_id = 21293;
UPDATE cms_hotel_city SET city_id = 1909113 WHERE location_id = 702406;
UPDATE cms_hotel_city SET city_id = 1593489 WHERE location_id = 119256;
UPDATE cms_hotel_city SET city_id = 2485287 WHERE location_id = 614371;
UPDATE cms_hotel_city SET city_id = 2485136 WHERE location_id = 602862;
UPDATE cms_hotel_city SET city_id = 2484959 WHERE location_id = 591485;
UPDATE cms_hotel_city SET city_id = 2082143 WHERE location_id = 462207;
UPDATE cms_hotel_city SET city_id = 2080455 WHERE location_id = 460449;
UPDATE cms_hotel_city SET city_id = 2080567 WHERE location_id = 21989;
UPDATE cms_hotel_city SET city_id = 2086397 WHERE location_id = 19142;
UPDATE cms_hotel_city SET city_id = 2081444 WHERE location_id = 9529;
UPDATE cms_hotel_city SET city_id = 2082096 WHERE location_id = 4517;
UPDATE cms_hotel_city SET city_id = 1842310 WHERE location_id = 128881;
UPDATE cms_hotel_city SET city_id = 752852 WHERE location_id = 1134315;
UPDATE cms_hotel_city SET city_id = 1717582 WHERE location_id = 17023;
UPDATE cms_hotel_city SET city_id = 1736504 WHERE location_id = 143986;
UPDATE cms_hotel_city SET city_id = 854594 WHERE location_id = 795057;
UPDATE cms_hotel_city SET city_id = 1829197 WHERE location_id = 109358;
UPDATE cms_hotel_city SET city_id = 1193923 WHERE location_id = 74195;
UPDATE cms_hotel_city SET city_id = 2106751 WHERE location_id = 590213;
UPDATE cms_hotel_city SET city_id = 2091822 WHERE location_id = 21765;
UPDATE cms_hotel_city SET city_id = 2105791 WHERE location_id = 590406;
UPDATE cms_hotel_city SET city_id = 2106547 WHERE location_id = 327;
# SELECT * FROM `cms_hotel_city` WHERE `city_id` = 476159;
# ===================================================================
UPDATE cms_hotel_city SET city_id = 512929 WHERE location_id = 128942;
UPDATE cms_hotel_city SET city_id = 520753 WHERE location_id = 1090399;
UPDATE cms_hotel_city SET city_id = 501333 WHERE location_id = 1090673;
UPDATE cms_hotel_city SET city_id = 451601 WHERE location_id = 1091128;
UPDATE cms_hotel_city SET city_id = 442038 WHERE location_id = 1093387;
UPDATE cms_hotel_city SET city_id = 493193 WHERE location_id = 1383267;
UPDATE cms_hotel_city SET city_id = 558756 WHERE location_id = 1383446;
UPDATE cms_hotel_city SET city_id = 575429 WHERE location_id = 2968226;
UPDATE cms_hotel_city SET city_id = 483678 WHERE location_id = 2975003;
UPDATE cms_hotel_city SET city_id = 463658 WHERE location_id = 2975322;
UPDATE cms_hotel_city SET city_id = 501748 WHERE location_id = 2976883;
