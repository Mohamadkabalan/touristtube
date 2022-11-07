CREATE TABLE `corpo_user_profiles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `section_title` varchar(100) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `corpo_user_profiles`
--
ALTER TABLE `corpo_user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD INDEX `slug` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `corpo_user_profiles`
--
ALTER TABLE `corpo_user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;