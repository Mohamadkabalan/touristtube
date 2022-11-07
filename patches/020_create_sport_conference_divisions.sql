#ADD NEW SPA SUB DIVISIONS
#
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (273, 'Treatment Room 1', '4', '4');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (274, 'Treatment Room 2', '4', '4');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (275, 'Treatment Room 3', '4', '4');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (276, 'Treatment Room 4', '4', '4');

#Add new Sport category and divisions
#
INSERT INTO `hotel_divisions_categories` (`id`, `name`) VALUES (19, 'Sport Courts');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (277, 'Sport Court', '19');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (278, 'Sport Court 1.1', '19');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (279, 'Sport Court 1.2', '19', '277', '999');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (280, 'Sport Court 1.3', '19', '277', '999');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (281, 'Sport Court 1.4', '19', '277', '999');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (282, 'Sport Court 1.5', '19', '277', '999');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (283, 'Sport Court 1.6', '19', '277', '999');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`, `sort_order`) VALUES (284, 'Sport Court 1.7', '19', '277', '999');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (285, 'Sport Court 2', '19');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (286, 'Sport Court 2.1', '19', '285');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (287, 'Sport Court 2.2', '19', '285');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (288, 'Sport Court 2.3', '19', '285');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (289, 'Sport Court 2.4', '19', '285');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (290, 'Sport Court 2.5', '19', '285');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (291, 'Sport Court 2.6', '19', '285');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (292, 'Sport Court 2.7', '19', '285');


#Add new Conference Divisions
#
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (293, 'Conference 2', '14');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (294, 'Conference 3', '14');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (295, 'Conference 4', '14');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (296, 'Conference 5', '14');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (297, 'Conference 2.1', '14', '293');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (298, 'Conference 2.2', '14', '293');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (299, 'Conference 2.3', '14', '293');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (300, 'Conference 2.4', '14', '293');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (301, 'Conference 2.5', '14', '293');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (302, 'Conference 2.6', '14', '293');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (303, 'Conference 3.1', '14', '294');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (304, 'Conference 3.2', '14', '294');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (305, 'Conference 3.3', '14', '294');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (306, 'Conference 3.4', '14', '294');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (307, 'Conference 3.5', '14', '294');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (308, 'Conference 3.6', '14', '294');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (309, 'Conference 4.1', '14', '295');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (310, 'Conference 4.2', '14', '295');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (311, 'Conference 4.3', '14', '295');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (312, 'Conference 4.4', '14', '295');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (313, 'Conference 4.5', '14', '295');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (314, 'Conference 4.6', '14', '295');

INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (315, 'Conference 5.1', '14', '296');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (316, 'Conference 5.2', '14', '296');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (317, 'Conference 5.3', '14', '296');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (318, 'Conference 5.4', '14', '296');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (319, 'Conference 5.5', '14', '296');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (320, 'Conference 5.6', '14', '296');
