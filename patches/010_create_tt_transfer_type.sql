CREATE TABLE IF NOT EXISTS `tt_transfer_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tt_transfer_type` (`id`, `name`, `description`, `image`) VALUES
(1, 'Shared', 'An affordable, convenient and secure solution', 'images/stepseven/stepsiximg1.png'),
(2, 'Private', 'A comfortable, deluxe option with a dedicated driver ready for your safe transfer.', 'images/stepseven/stepsiximg2.png');