INSERT INTO `#__eshop_attributedetails` (`id`, `attribute_id`, `attribute_name`, `language`) VALUES
(1, 1, 'Android', 'en-GB'),
(2, 2, 'iOS', 'en-GB'),
(3, 3, 'Windows Phone', 'en-GB'),
(4, 4, 'BlackBerry', 'en-GB'),
(5, 5, 'Frequency 3G', 'en-GB'),
(6, 6, 'Frequency 4G', 'en-GB'),
(7, 7, 'Induction', 'en-GB'),
(8, 8, 'Multiple SIMs', 'en-GB'),
(9, 9, 'FM', 'en-GB'),
(10, 10, '>= 15"', 'en-GB'),
(11, 11, '>= 14"', 'en-GB'),
(12, 12, '>= 13"', 'en-GB'),
(13, 13, '>= 12"', 'en-GB'),
(14, 14, '>= 11"', 'en-GB'),
(15, 15, '>= 10"', 'en-GB'),
(16, 16, 'Core i7', 'en-GB'),
(17, 17, 'Core i5', 'en-GB'),
(18, 18, 'core i3', 'en-GB'),
(19, 19, 'AMD', 'en-GB'),
(20, 20, 'Pentium', 'en-GB'),
(21, 21, 'Celeron', 'en-GB');

INSERT INTO `#__eshop_attributegroupdetails` (`id`, `attributegroup_id`, `attributegroup_name`, `language`) VALUES
(1, 1, 'Operating System', 'en-GB'),
(2, 2, 'Features', 'en-GB'),
(3, 3, 'Resolutions', 'en-GB'),
(4, 4, 'CPU Type', 'en-GB');

INSERT INTO `#__eshop_attributegroups` (`id`, `published`, `ordering`, `created_date`, `created_by`, `modified_date`, `modified_by`, `checked_out`, `checked_out_time`) VALUES
(1, 1, 1, '2013-05-23 07:39:02', 777, '2013-05-23 07:39:02', 777, 0, '0000-00-00 00:00:00'),
(2, 1, 2, '2013-05-23 07:39:26', 777, '2013-05-23 07:39:26', 777, 0, '0000-00-00 00:00:00'),
(3, 1, 3, '2013-05-23 07:41:36', 777, '2013-05-23 07:41:36', 777, 0, '0000-00-00 00:00:00'),
(4, 1, 4, '2013-05-23 07:43:01', 777, '2013-05-23 07:43:24', 777, 0, '0000-00-00 00:00:00');

INSERT INTO `#__eshop_attributes` (`id`, `attributegroup_id`, `published`, `ordering`, `created_date`, `created_by`, `modified_date`, `modified_by`, `checked_out`, `checked_out_time`) VALUES
(1, 1, 1, 1, '2013-05-23 07:47:47', 777, '2013-05-23 07:47:47', 777, 0, '0000-00-00 00:00:00'),
(2, 1, 1, 2, '2013-05-23 07:48:06', 777, '2013-05-23 07:48:06', 777, 0, '0000-00-00 00:00:00'),
(3, 1, 1, 3, '2013-05-23 07:48:26', 777, '2013-05-23 07:48:26', 777, 0, '0000-00-00 00:00:00'),
(4, 1, 1, 4, '2013-05-23 07:48:38', 777, '2013-05-23 07:48:38', 777, 0, '0000-00-00 00:00:00'),
(5, 2, 1, 5, '2013-05-23 07:49:49', 777, '2013-05-23 07:49:49', 777, 0, '0000-00-00 00:00:00'),
(6, 2, 1, 6, '2013-05-23 07:50:05', 777, '2013-05-23 07:50:05', 777, 0, '0000-00-00 00:00:00'),
(7, 2, 1, 7, '2013-05-23 07:51:02', 777, '2013-05-23 07:51:02', 777, 0, '0000-00-00 00:00:00'),
(8, 2, 1, 8, '2013-05-23 07:51:27', 777, '2013-05-23 07:51:27', 777, 0, '0000-00-00 00:00:00'),
(9, 2, 1, 9, '2013-05-23 07:51:44', 777, '2013-05-23 07:51:44', 777, 0, '0000-00-00 00:00:00'),
(10, 3, 1, 10, '2013-05-23 07:52:55', 777, '2013-05-23 07:52:55', 777, 0, '0000-00-00 00:00:00'),
(11, 3, 1, 11, '2013-05-23 07:53:13', 777, '2013-05-23 07:53:13', 777, 0, '0000-00-00 00:00:00'),
(12, 3, 1, 12, '2013-05-23 07:53:27', 777, '2013-05-23 07:53:27', 777, 0, '0000-00-00 00:00:00'),
(13, 3, 1, 13, '2013-05-23 07:53:42', 777, '2013-05-23 07:53:42', 777, 0, '0000-00-00 00:00:00'),
(14, 3, 1, 14, '2013-05-23 07:53:57', 777, '2013-05-23 07:54:13', 777, 0, '0000-00-00 00:00:00'),
(15, 3, 1, 15, '2013-05-23 07:54:31', 777, '2013-05-23 07:54:31', 777, 0, '0000-00-00 00:00:00'),
(16, 4, 1, 16, '2013-05-23 07:55:02', 777, '2013-05-23 07:55:02', 777, 0, '0000-00-00 00:00:00'),
(17, 4, 1, 17, '2013-05-23 07:55:16', 777, '2013-05-23 07:55:16', 777, 0, '0000-00-00 00:00:00'),
(18, 4, 1, 18, '2013-05-23 07:55:32', 777, '2013-05-23 07:55:32', 777, 0, '0000-00-00 00:00:00'),
(19, 4, 1, 19, '2013-05-23 07:55:52', 777, '2013-05-23 07:55:52', 777, 0, '0000-00-00 00:00:00'),
(20, 4, 1, 20, '2013-05-23 07:56:19', 777, '2013-05-23 07:56:19', 777, 0, '0000-00-00 00:00:00'),
(21, 4, 1, 21, '2013-05-23 07:56:37', 777, '2013-05-23 07:56:37', 777, 0, '0000-00-00 00:00:00');

INSERT INTO `#__eshop_categories` (`id`, `category_parent_id`, `category_image`, `products_per_page`, `products_per_row`, `published`, `ordering`, `hits`, `created_date`, `created_by`, `modified_date`, `modified_by`, `checked_out`, `checked_out_time`) VALUES
(1, 0, 'cameras.jpg', 15, 3, 1, 1, 0, '2013-05-23 04:09:57', 777, '2013-05-23 04:09:57', 777, 0, '0000-00-00 00:00:00'),
(2, 1, 'digital-cameras.jpg', 15, 3, 1, 2, 0, '2013-05-23 04:11:06', 777, '2013-05-23 04:17:46', 777, 0, '0000-00-00 00:00:00'),
(3, 1, 'camcorders.jpg', 15, 3, 1, 3, 0, '2013-05-23 04:12:00', 777, '2013-05-23 04:18:02', 777, 0, '0000-00-00 00:00:00'),
(4, 0, 'components.jpg', 15, 3, 1, 4, 0, '2013-05-23 04:14:10', 777, '2013-05-23 04:14:10', 777, 0, '0000-00-00 00:00:00'),
(5, 4, 'monitors.jpg', 15, 3, 1, 5, 0, '2013-05-23 04:15:14', 777, '2013-05-23 04:18:54', 777, 0, '0000-00-00 00:00:00'),
(6, 4, 'memory.jpg', 15, 3, 1, 6, 0, '2013-05-23 04:17:04', 777, '2013-05-23 04:19:07', 777, 0, '0000-00-00 00:00:00'),
(7, 4, 'scanners.jpg', 15, 3, 1, 7, 0, '2013-05-23 04:20:40', 777, '2013-05-23 04:20:40', 777, 0, '0000-00-00 00:00:00'),
(8, 4, 'printers.jpg', 15, 3, 1, 8, 0, '2013-05-23 04:21:52', 777, '2013-05-23 04:21:52', 777, 0, '0000-00-00 00:00:00'),
(9, 4, 'mouse.jpg', 15, 3, 1, 9, 0, '2013-05-23 04:22:50', 777, '2013-05-23 04:22:50', 777, 0, '0000-00-00 00:00:00'),
(10, 0, 'laptops.jpg', 15, 3, 1, 10, 0, '2013-05-23 04:23:37', 777, '2013-05-23 04:23:37', 777, 0, '0000-00-00 00:00:00'),
(11, 10, 'apple-laptops.jpg', 15, 3, 1, 11, 0, '2013-05-23 04:25:13', 777, '2013-05-23 04:25:27', 777, 0, '0000-00-00 00:00:00'),
(12, 10, 'windows-laptops.jpg', 15, 3, 1, 12, 0, '2013-05-23 04:27:17', 777, '2013-05-23 04:27:17', 777, 0, '0000-00-00 00:00:00'),
(13, 0, 'desktops.jpg', 15, 3, 1, 13, 0, '2013-05-23 04:28:23', 777, '2013-05-23 04:28:23', 777, 0, '0000-00-00 00:00:00'),
(14, 13, 'apple-desktops.jpg', 15, 3, 1, 14, 0, '2013-05-23 04:29:46', 777, '2013-05-23 04:29:46', 777, 0, '0000-00-00 00:00:00'),
(15, 13, 'pc-desktops.jpg', 15, 3, 1, 15, 0, '2013-05-23 04:30:47', 777, '2013-05-23 04:30:47', 777, 0, '0000-00-00 00:00:00'),
(16, 0, 'tablet.jpg', 15, 3, 1, 16, 0, '2013-05-23 04:31:44', 777, '2013-05-23 04:31:44', 777, 0, '0000-00-00 00:00:00'),
(17, 0, 'ipads.jpg', 15, 3, 1, 17, 0, '2013-05-23 04:32:47', 777, '2013-05-23 04:32:47', 777, 0, '0000-00-00 00:00:00'),
(18, 0, 'televisions.jpg', 15, 3, 1, 18, 0, '2013-05-23 04:33:41', 777, '2013-05-23 04:33:41', 777, 0, '0000-00-00 00:00:00'),
(19, 0, 'headphones.jpg', 15, 3, 1, 19, 0, '2013-05-23 04:34:28', 777, '2013-05-23 04:34:28', 777, 0, '0000-00-00 00:00:00'),
(20, 0, 'speakers.jpg', 15, 3, 1, 20, 0, '2013-05-23 04:35:32', 777, '2013-05-23 04:35:32', 777, 0, '0000-00-00 00:00:00'),
(21, 0, 'dvd.jpg', 15, 3, 1, 21, 0, '2013-05-23 04:36:08', 777, '2013-05-23 04:36:08', 777, 0, '0000-00-00 00:00:00'),
(22, 0, 'cellphones.jpg', 15, 3, 1, 22, 0, '2013-05-23 04:37:07', 777, '2013-05-23 04:37:07', 777, 0, '0000-00-00 00:00:00');

INSERT INTO `#__eshop_categorydetails` (`id`, `category_id`, `category_name`, `category_alias`, `category_desc`, `meta_key`, `meta_desc`, `language`) VALUES
(1, 1, 'Cameras', 'cameras', '<p>A camera is an optical instrument that records images that can be stored directly, transmitted to another location, or both. These images may be still photographs or moving images such as videos or movies.</p>', '', '', 'en-GB'),
(2, 2, 'Digital Cameras', 'digital-cameras', '<p>A digital camera (or digicam) is a camera that takes video or still photographs by recording images on an electronic image sensor. Most cameras sold today are digital and digital cameras are incorporated into many devices ranging from PDAs and mobile phones (called camera phones) to vehicles.</p>', '', '', 'en-GB'),
(3, 3, 'Camcorders', 'camcorders', '<p>A camcorder (formally a video camera recorder) is an electronic device that combines a video camera and a video recorder into one unit; typically for out-of-studio consumer video recording.</p>', '', '', 'en-GB'),
(4, 4, 'Components', 'components', '<p>Components is the constituents of electronic circuits.</p>', '', '', 'en-GB'),
(5, 5, 'Monitors', 'monitors', '<p>An approach to synchronize two or more computer tasks that use a shared resource.</p>', '', '', 'en-GB'),
(6, 6, 'Memory', 'memory', '<p>Random-access memory is a form of computer data storage. A random-access device allows stored data to be accessed directly in any random order. In contrast, other data storage media such as hard disks, CDs, DVDs and magnetic tape, as well as early primary memory types such as drum memory.</p>', '', '', 'en-GB'),
(7, 7, 'Scanners', 'scanners', '<p>ConSec, a purveyor of weaponry and security systems, searches out and captures scanners, ostensibly to protect the public from them, but actually to use them for its own nefarious purposes.</p>', '', '', 'en-GB'),
(8, 8, 'Printers', 'printers', '<p>In computing, a printer is a peripheral which produces a representation of an electronic document on physical media such as paper or transparency film. Many printers are local peripherals connected directly to a nearby personal computer. Individual printers are often designed to support both local and network connected users at the same time.</p>', '', '', 'en-GB'),
(9, 9, 'Mouse', 'mouse', '<p>In computing, a mouse is a pointing device that functions by detecting two-dimensional motion relative to its supporting surface. Physically, a mouse consists of an object held under one of the user''s hands, with one or more buttons.</p>', '', '', 'en-GB'),
(10, 10, 'Laptops', 'laptops', '<p>A laptop has most of the same components as a desktop computer, including a display, a keyboard, a pointing device such as a touchpad (also known as a trackpad) and/or a pointing stick, and speakers into a single unit.</p>', '', '', 'en-GB'),
(11, 11, 'Apple Laptops', 'apple-laptops', '<p>Apple Laptops is a line of Macintosh portable computers introduced in January 2006 by Apple Inc., and now in its third generation.</p>', '', '', 'en-GB'),
(12, 12, 'Windows Laptops', 'windows-laptops', '<p>A laptop computer is a personal computer for mobile use. A laptop has most of the same components as a desktop computer, including a display, a keyboard, a pointing device such as a touch-pad.</p>', '', '', 'en-GB'),
(13, 13, 'Desktops', 'desktops', '<p>A personal computer in a form intended for regular use at a single location, as opposed to a mobile laptop or portable computer.</p>', '', '', 'en-GB'),
(14, 14, 'Apple Desktops', 'apple-desktops', '<p>A range of all-in-one Macintosh desktop computers designed and built by Apple Inc.. It has been the primary part of Apple''s consumer desktop offerings since its introduction in 1998.</p>', '', '', 'en-GB'),
(15, 15, 'PC Desktops', 'pc-desktops', '<p>Technically speaking desktop and tower computers are two different styles of computer case that use desk space in varying ways.</p>', '', '', 'en-GB'),
(16, 16, 'Tablet', 'tablet', '<p>A mobile computer that is primarily operated by touching the screen.</p>', '', '', 'en-GB'),
(17, 17, 'iPads', 'ipads', '<p>A line of tablet computers designed and marketed by Apple Inc., which runs Apple''s iOS operating system.</p>', '', '', 'en-GB'),
(18, 18, 'Televisions', 'televisions', '<p>A telecommunication medium for transmitting and receiving moving images that can be monochrome (black-and-white) or colored, with or without accompanying sound.</p>', '', '', 'en-GB'),
(19, 19, 'Headphones', 'headphones', '<p>Headphones are a pair of small loudspeakers that are designed to be held in place close to a user''s ears.</p>', '', '', 'en-GB'),
(20, 20, 'Speakers', 'speakers', '<p>Computer speakers, or multimedia speakers, are speakers external to a computer, that disable the lower fidelity built-in speaker. They often have a low-power internal amplifier.</p>', '', '', 'en-GB'),
(21, 21, 'DVD', 'dvd', '<p>DVD is an optical disc storage format, invented and developed by Philips, Sony, Toshiba, and Panasonic in 1995. DVDs offer higher storage capacity than compact discs while having the same dimensions.</p>', '', '', 'en-GB'),
(22, 22, 'Cell Phones', 'cell-phones', '<p>A device that can make and receive telephone calls over a radio link while moving around a wide geographic area.</p>', '', '', 'en-GB');

INSERT INTO `#__eshop_geozones` (`id`, `geozone_name`, `geozone_desc`, `published`, `created_date`, `created_by`, `modified_date`, `modified_by`, `checked_out`, `checked_out_time`) VALUES
(1, 'UK Shipping', 'UK Shipping Zones', 1, '2013-05-23 08:04:10', 777, '2013-05-23 08:04:10', 777, 0, '0000-00-00 00:00:00'),
(2, 'UK VAT Zone', 'UK VAT', 1, '2013-05-23 08:04:35', 777, '2013-05-23 08:04:35', 777, 0, '0000-00-00 00:00:00');

INSERT INTO `#__eshop_geozonezones` (`id`, `geozone_id`, `zone_id`, `country_id`) VALUES
(1, 1, 0, 222),
(2, 2, 0, 222);

INSERT INTO `#__eshop_manufacturerdetails` (`id`, `manufacturer_id`, `manufacturer_name`, `manufacturer_alias`, `manufacturer_desc`, `language`) VALUES
(1, 1, 'Apple', 'apple', '<p>Apple Inc., formerly Apple Computer, Inc., is an multinational corporation headquartered in Cupertino, California that designs, develops, and sells consumer electronics, computer software and personal computers.</p>', 'en-GB'),
(2, 2, 'Acer', 'acer', '<p>Acer Inc. is a Taiwanese multinational hardware and electronics corporation headquartered in Xizhi, New Taipei City, Taiwan. Acer''s products include inexpensively-targeted desktop and laptop PCs, tablet computers, servers, storage devices, displays, smartphones and peripherals.</p>', 'en-GB'),
(3, 3, 'Asus', 'asus', '<p><span id="ctl00_ContentPlaceHolder1_ctl00_ContentPageContent1_span_product_content_area">ASUS takes its name from Pegasus, the winged horse in Greek mythology that symbolises wisdom and knowledge. ASUS embodies the strength, purity, and adventurous spirit of this fantastic creature, and soars to new heights with each new product it creates.</span></p>', 'en-GB'),
(4, 4, 'BlackBerry', 'blackberry', '<p>BlackBerry is a Canadian telecommunication and wireless equipment company best known as the developer of the BlackBerry brand of smartphones and tablets. The company is headquartered in Waterloo, Ontario, Canada</p>', 'en-GB'),
(5, 5, 'Cannon', 'cannon', '<p>The Cannon Group Inc. was an American group of companies, including Cannon Films, which produced a distinctive line of low-to-medium budget films from 1967 to 1993. The extensive group also owned, amongst others, a large international cinema chain and a video film company that invested heavily in the video market, buying the international video rights to several classic film libraries.</p>\r\n<p> </p>', 'en-GB'),
(6, 6, 'Dell', 'dell', '<p>Dell Inc. (formerly Dell Computer) is an American multinational computer technology corporation based in Round Rock, Texas, United States, that develops, sells, repairs and supports computers and related products and services.</p>', 'en-GB'),
(7, 7, 'HTC', 'htc', '<p>HTC Corporation is a Taiwanese manufacturer of smartphones and tablets headquartered in Taoyuan City, Taiwan. Initially making smartphones based mostly on Microsoft''s Windows Mobile operating system (OS) software, HTC expanded its focus in 2009 to devices based on the Android OS, and in 2010 to Windows Phone OS.</p>', 'en-GB'),
(8, 8, 'IBM', 'ibm', '<p>IBM is an American multinational technology and consulting corporation, with headquarters in Armonk, New York, United States. IBM manufactures and markets computer hardware and software, and offers infrastructure, hosting and consulting services in areas ranging from mainframe computers to nanotechnology.</p>', 'en-GB'),
(9, 9, 'LG', 'lg', '<p>LG Electronics is a South Korean multinational electronics company headquartered in Yeouido-dong, Seoul, and a member of the LG Group chaebol. The company operates its business through five divisions: Mobile Communications, Home Entertainment, Home Appliances, Air Conditioning, and Energy Solutions.</p>\r\n<p> </p>', 'en-GB'),
(10, 10, 'Nokia', 'nokia', '<p>Nokia Corporation is a finnish multinational communications and information technology corporation (originally a paper production plant) that is headquartered in Espoo, Finland.</p>', 'en-GB'),
(11, 11, 'Samsung', 'samsung', '<p>Samsung Group is a South Korean multinational conglomerate company headquartered in Samsung Town, Seoul. It comprises numerous subsidiaries and affiliated businesses, most of them united under the Samsung brand, and is the largest South Korean.</p>', 'en-GB'),
(12, 12, 'Sony', 'sony', '<p>Sony Corporation is a Japanese multinational conglomerate corporation headquartered in Konan Minato, Tokyo, Japan. Its diversified business is primarily focused on the electronics, game, entertainment and financial services sectors.</p>\r\n<p> </p>', 'en-GB');

INSERT INTO `#__eshop_manufacturers` (`id`, `manufacturer_email`, `manufacturer_url`, `manufacturer_image`, `published`, `ordering`, `hits`, `created_date`, `created_by`, `modified_date`, `modified_by`, `checked_out`, `checked_out_time`) VALUES
(1, 'email@apple.com', 'http://www.apple.com', 'apple.jpg', 1, 1, 0, '2013-05-22 10:28:38', 777, '2013-05-22 10:28:38', 777, 0, '0000-00-00 00:00:00'),
(2, 'email@acer.com', 'http://acer.com', 'acer.jpg', 1, 2, 0, '2013-05-23 02:21:27', 777, '2013-05-23 02:21:27', 777, 0, '0000-00-00 00:00:00'),
(3, 'email@asus.com', 'http://asus.com', 'asus.jpg', 1, 3, 0, '2013-05-23 02:23:42', 777, '2013-05-23 02:23:42', 777, 0, '0000-00-00 00:00:00'),
(4, 'email@blackberry.com', 'http://blackberry.com', 'blackberry.jpg', 1, 4, 0, '2013-05-23 02:25:57', 777, '2013-05-23 02:25:57', 777, 0, '0000-00-00 00:00:00'),
(5, 'email@cannon.com', 'http://cannon.com', 'canon.jpg', 1, 5, 0, '2013-05-23 02:27:20', 777, '2013-05-23 02:27:20', 777, 0, '0000-00-00 00:00:00'),
(6, 'email@dell.com', 'http://dell.com', 'dell.jpg', 1, 6, 0, '2013-05-23 02:28:23', 777, '2013-05-23 02:28:23', 777, 0, '0000-00-00 00:00:00'),
(7, 'email@htc.com', 'http://htc.com', 'htc.jpg', 1, 7, 0, '2013-05-23 02:29:35', 777, '2013-05-23 02:29:35', 777, 0, '0000-00-00 00:00:00'),
(8, 'email@ibm.com', 'http://ibm.com', 'ibm.jpg', 1, 8, 0, '2013-05-23 02:30:31', 777, '2013-05-23 02:30:31', 777, 0, '0000-00-00 00:00:00'),
(9, 'email@lg.com', 'http://lg.com', 'lg.jpg', 1, 9, 0, '2013-05-23 02:31:49', 777, '2013-05-23 02:31:49', 777, 0, '0000-00-00 00:00:00'),
(10, 'email@nokia.com', 'http://nokia.com', 'nokia.jpg', 1, 10, 0, '2013-05-23 02:33:24', 777, '2013-05-23 02:33:24', 777, 0, '0000-00-00 00:00:00'),
(11, 'email@samsung.com', 'http://samsung.com', 'samsung.jpg', 1, 11, 0, '2013-05-23 02:34:30', 777, '2013-05-23 02:34:30', 777, 0, '0000-00-00 00:00:00'),
(12, 'email@sony.com', 'http://sony.com', 'sony.jpg', 1, 12, 0, '2013-05-23 02:35:37', 777, '2013-05-23 02:35:37', 777, 0, '0000-00-00 00:00:00');

INSERT INTO `#__eshop_optiondetails` (`id`, `option_id`, `option_name`, `option_desc`, `language`) VALUES
(1, 1, 'Select a color', NULL, 'en-GB'),
(2, 2, 'Select size', NULL, 'en-GB'),
(3, 3, 'Select technologies', NULL, 'en-GB');

INSERT INTO `#__eshop_options` (`id`, `option_type`, `option_image`, `published`, `ordering`, `created_date`, `created_by`, `modified_date`, `modified_by`, `checked_out`, `checked_out_time`) VALUES
(1, 'Select', NULL, 1, 1, '2013-05-23 04:48:15', 777, '2013-05-23 04:48:15', 777, 0, '0000-00-00 00:00:00'),
(2, 'Radio', NULL, 1, 2, '2013-05-23 04:50:12', 777, '2013-05-23 04:50:42', 777, 0, '0000-00-00 00:00:00'),
(3, 'Checkbox', NULL, 1, 3, '2013-05-23 04:53:09', 777, '2013-05-23 04:53:09', 777, 0, '0000-00-00 00:00:00');

INSERT INTO `#__eshop_optionvaluedetails` (`id`, `optionvalue_id`, `option_id`, `value`, `language`) VALUES
(1, 1, 1, 'Red', 'en-GB'),
(2, 2, 1, 'Blue', 'en-GB'),
(3, 3, 1, 'Green', 'en-GB'),
(4, 4, 1, 'Black', 'en-GB'),
(5, 5, 1, 'Yellow', 'en-GB'),
(6, 6, 2, 'Small', 'en-GB'),
(7, 7, 2, 'Medium', 'en-GB'),
(8, 8, 2, 'Large', 'en-GB'),
(9, 9, 2, 'Extra Large', 'en-GB'),
(10, 10, 2, 'Very Large', 'en-GB'),
(11, 11, 2, 'Huge', 'en-GB'),
(12, 12, 3, 'FM', 'en-GB'),
(13, 13, 3, 'Bluetooth', 'en-GB'),
(14, 14, 3, 'Wifi', 'en-GB'),
(15, 15, 3, 'Camera', 'en-GB'),
(16, 16, 3, 'Voice recognition', 'en-GB');

INSERT INTO `#__eshop_optionvalues` (`id`, `option_id`, `published`, `ordering`) VALUES
(1, 1, 1, 1),
(2, 1, 1, 2),
(3, 1, 1, 3),
(4, 1, 1, 4),
(5, 1, 1, 5),
(6, 2, 1, 1),
(7, 2, 1, 2),
(8, 2, 1, 3),
(9, 2, 1, 4),
(10, 2, 1, 5),
(11, 2, 1, 6),
(12, 3, 1, 1),
(13, 3, 1, 2),
(14, 3, 1, 3),
(15, 3, 1, 4),
(16, 3, 1, 5);

INSERT INTO `#__eshop_productattributedetails` (`id`, `productattribute_id`, `product_id`, `value`, `language`) VALUES
(1, 1, 1, 'Android 10.1.4', 'en-GB'),
(2, 2, 1, '3G New', 'en-GB'),
(3, 3, 1, 'Core i7 New', 'en-GB'),
(4, 4, 1, 'Intel', 'en-GB'),
(5, 5, 1, 'Window 8', 'en-GB'),
(6, 6, 1, '88.1 Mhz', 'en-GB'),
(7, 7, 5, 'Whole', 'en-GB');

INSERT INTO `#__eshop_productattributes` (`id`, `product_id`, `attribute_id`, `published`) VALUES
(1, 1, 1, 1),
(2, 1, 5, 1),
(3, 1, 16, 1),
(4, 1, 19, 1),
(5, 1, 3, 1),
(6, 1, 9, 1),
(7, 5, 6, 1);

INSERT INTO `#__eshop_productcategories` (`id`, `product_id`, `category_id`) VALUES
(23, 10, 3),
(28, 11, 5),
(29, 11, 15),
(32, 2, 2),
(33, 3, 2),
(37, 7, 2),
(38, 8, 2),
(40, 13, 7),
(44, 15, 7),
(45, 15, 8),
(46, 14, 7),
(47, 14, 8),
(50, 9, 2),
(51, 9, 3),
(52, 9, 20),
(53, 12, 5),
(54, 12, 12),
(55, 4, 2),
(56, 4, 6),
(57, 4, 18),
(58, 6, 2),
(59, 6, 6),
(60, 6, 19),
(63, 1, 2),
(64, 1, 3),
(65, 5, 2);

INSERT INTO `#__eshop_productdetails` (`id`, `product_id`, `product_name`, `product_alias`, `product_desc`, `product_short_desc`, `product_tag`, `meta_key`, `meta_desc`, `language`) VALUES
(1, 1, 'Canon 20D Digital', 'canon-20d-digital', '<p>Created for people who want to experiment with photography, the Canon EOS 8.2 MP camera is a flexible portable body only. You will be able to select the quantity of storage in the camera as this Canon EOS 20D features a flash memory card slot. High-quality pictures and superb performance are yours with this Canon EOS digital SLR camera.</p>\r\n<p>With its black body, the Canon EOS 8.2 MP camera is a refined device for taking pictures. As this Canon EOS 20D comes with rechargeable Lithium-ion batteries, you will be able to always be prepared to preserve your life''s great moments. A higher number of megapixels means you can crop and enlarge your photos without causing pixelation. Show off the captured moments of your life and send them to family and friends with the 1.8-inch LCD monitor included with the Canon EOS 8.2 MP camera.</p>\r\n<p>This Canon EOS 20D includes only the body and no lens. Selecting the most appropriate interchangeable lens or lenses based on your photography needs is one upside to buying the camera body alone.A battery charger and lithium ion battery are also included with this camera body.This camera had a BRAND NEW SHUTTER installed in December, and a full cleaning was performed! This will extend the working life of the camera by several years. The body has a few cosmetic cuffs/scratches, but is completely perfectly functioning, including all buttons, metering, focus and exposure settings.</p>', 'Seller assumes all responsibility for this listing.', '', '', '', 'en-GB'),
(2, 2, 'Canon EOS Digital Rebel', 'canon-eos-digital-rebel', '<p><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;"> The flash memory card slot featured on this Canon EOS Digital Rebel XT / 350D digital camera makes it easy for you to select the size of storage in the camera. This Canon EOS digital SLR camera comes with rechargeable Lithium-ion batteries allowing you to be prepared to take photographs. With its 8 megapixel sensor, the Canon EOS 8 MP camera is perfect for making brilliant prints, and it allows you to deliver 8x11 inch prints of the moments of your life in realistic clarity.</span></p>\r\n<p><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;">A greater number of megapixels means you can enlarge and crop your photos without having a detrimental affect on your picture''s quality. High-quality pictures and wonderful performance are yours with this Canon EOS Digital Rebel XT / 350D digital camera. In addition, the 1.8-inch LCD monitor included with this Canon EOS digital SLR camera makes it easy for you to compose memories with ease. Be the envy of your friends with the Canon EOS 8 MP camera and its stylish silver body. This Canon EOS Digital Rebel XT / 350D digital camera comes with only the body and no lens.</span></p>\r\n<p><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;">Picking the best interchangeable lens or lenses based on your expanding photography needs and budget is one advantage to purchasing the camera body separately.</span></p>\r\n<p> </p>', 'Professional portable body only and is made for photography.', '', '', '', 'en-GB'),
(3, 3, 'Canon EOS Rebel T4i', 'canon-eos-rebel-t4i', '<p><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;">The sensor in this black Canon EOS Rebel T4i/650D enhances autofocus interruption time by utilizing contrast and phase detection combined. In addition, the Canon 18.0 MP camera offers three separate methods for focusing in on subjects.</span></p>\r\n<p><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;">They are face detection, multi-point AF, and single point AF. For reducing or eliminating image noise, this digital SLR camera comes with a multi-shot noise-reduction feature that essentially takes multiple pictures then combines them to create the highest quality image. Similarly, the Canon EOS Rebel T4i/650D also has Handheld Night and HDR Backlight, both of which take numerous images to construct the best image in certain situations.</span></p>\r\n<p><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;">For quick and easy access to images, the Canon 18.0 MP camera is designed with a 3-inch Clear View II Vari-Angle touchscreen LCD monitor so users can easily reach settings or pinch zoom and slide through images. With a resolution of 5184x3456 pixels and an EF-S 18 mm-135 mm f/3.5-5.6 image stabilized zoom lens, this digital SLR camera ensures consumers can capture crisp, clear, high-quality photos.</span></p>', 'This Canon 18.0 MP camera comes with a nine-point.', '', '', '', 'en-GB'),
(4, 4, ' Canon Powershot SX40', 'canon-powershot-sx40', '<table width="100%" border="0" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr>\r\n<td colspan="2"><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;"> A vari-angle 2.7-inch LCD monitor in this Canon digital camera lets you compose and frame your shots perfectly. The 35x optical zoom in this 12.1 MP camera lets you shoot close-up shots of distant subjects. An optical image stabilizer in the Canon PowerShot SX40 HS automatically detects and corrects camera shake, helping you to capture clean and blur-free images. This Canon digital camera employs motion detection technology that automatically selects right shutter speed and ISO settings, letting you focus and capture fast moving subjects. The Canon PowerShot SX40 HS supports 1080p HD video with stereo sound recording, letting you record glorious moments of your life in HD quality.</span><br class="br" /><br class="br" /></td>\r\n</tr>\r\n<tr>\r\n<td colspan="2"><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;"><strong>Product Features</strong></span></td>\r\n</tr>\r\n<tr>\r\n<td colspan="2">\r\n<ul>\r\n<li><strong>35x Wide-Angle Optical Zoom and 24mm lens</strong><br />The PowerShot SX40 HS is equipped with a Genuine Canon 35x Zoom lens that provides the telephoto power to bring your distant subjects incredibly close. It also features a useful zoom range that begins at 24mm wide-angle and extends to 840mm telephoto (35mm equivalent). The 24mm ultra Wide-Angle allows you to create shots with dramatic perspective and makes it easy to shoot sweeping landscapes, large groups of people and tall buildings. This superb lens delivers outstanding optical performance throughout its zoom range. It''s advanced design employs UD glass, double-sided aspherical glass-molded and ultra-high-refraction-index glass lens elements to effectively suppress chromatic aberration while maintaining a remarkably compact size. The camera uses a VCM (Voice Coil Motor) for high-speed, quiet, energy-efficient lens movement with precise control.</li>\r\n<li><strong>Optical Image Stabilizer</strong><br />Handheld shooting can often lead to camera shake, making photos and videos blurry. Canon''s Optical Image Stabilizer is a sophisticated system that uses lens-shift technology to correct for unwanted camera movement. It makes handheld photography more practical, providing excellent image quality in many difficult shooting situations: outdoors at dusk, inside without a flash, and even at the telephoto end of the zoom range without a tripod. For photos, it enables shooting at slower shutter speeds, accommodating more low-light shooting situations than ever before without having to boost ISO sensitivity. With camera shake and vibration reduced, you get a sharper, steadier image. And because it is an optical system, there is none of the image degradation typical with electronic image stabilizers.</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>', 'The Canon PowerShot SX40 HS is a 12.1 MP camera.', '', '', '', 'en-GB'),
(5, 5, 'Canon Powershot SX160', 'canon-powershot-sx160', '<p><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;">Shoot rich-quality pictures with this Canon PowerShot 16 MP camera, thanks to its CCD image sensor. Moreover, with an optical zoom of 4x, this black compact digital camera lets you clearly capture distant subjects. </span></p>\r\n<p><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;">This Canon PowerShot 16 MP camera ensures steady shots even when it is accidentally jerked, owing to its image-stabilization feature. Featuring autofocus mode, the Canon PowerShot SX160 digital camera quickly and accurately narrows down on the subject, so that your subject appears sharp and clear in your recordings.<br /></span></p>', 'Capture lifelike images with the compact digital camera.', '', '', '', 'en-GB'),
(6, 6, 'USA Canon EOS 6D', 'usa-canon-eos-6d', '<p><span>The <strong>EOS 6D DSLR camera</strong> will give you incredibly high quality images even in the hardest settings, making it the perfect companion for travel and nature photography. </span></p>', 'In the Canon EOS 6D, the lens was removed.', '', '', '', 'en-GB'),
(7, 7, 'Canon 5d Mark 2 II Body', 'canon-5d-mark-2-ii-body', '<p><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;">This Canon EOS digital SLR camera comes with only the body and no lens. Selecting the interchangeable lens or lenses based on your photography needs is the main advantage to getting the camera body on its own.</span></p>', 'A flexible portable body only and is designed for photography.', '', '', '', 'en-GB'),
(8, 8, 'Canon PowerShot SX150', 'canon-powershot-sx150', '<p><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;">Featuring the Smart AUTO feature, this Canon digital camera makes optimal settings to capture the subject in the best possible way. Shoot amazing quality videos with the 720p HD resolution at 30 fps of this Canon digicam, and share them with your friends and family.</span></p>', 'Shooting tall buildings, a large bunch of people.', '', '', '', 'en-GB'),
(9, 9, 'Sony HDR Camcorder', 'sony-hdr-camcorder', '<p><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;">With an 80 GB hard drive for storage, this Sony Handycam camcorder lets you record for long hours at a stretch. And this HD camcorder has a compact design, making it easy to tote along.</span></p>', 'Lets you capture videos in superior quality.', '', '', '', 'en-GB'),
(10, 10, 'Samsung HMX Camcorder', 'samsung-hmx-camcorder', '<p><span style="font-family: Arial,Helvetica,sans-serif; font-size: small;">The IntelliStudio editing software in this HD camcorder lets you play, edit, and share files by simply plugging it into your PC via its USB port. With an illumination of 0 lux, the Samsung HMX-F80BN lets you record clear footage even in low-light conditions.</span></p>', 'This Samsung camcorder brings distant objects 52 times closer.', '', '', '', 'en-GB'),
(11, 11, 'Samsung HDTV Monitor', 'samsung-hdtv-monitor', '<p>Just as you''ve seen in Samsung LED TV''s, our LED monitors make colors more rich and the action more real than you''ve dreamt possible. Let the story come to life with vivid colors and crystal-clear detail.</p>', 'This device deliver a visually stunning picture quality.', '', '', '', 'en-GB'),
(12, 12, 'Samsung LCD Monitor', 'samsung-lcd-monitor', '<p>Samsung''s ultra-slim LED backlit monitor is just 16.5 mm thin which makes it easy to fit into most spaces</p>', 'Samsung LED Monitor backlit for ultra-thin profile.', '', '', '', 'en-GB'),
(13, 13, 'PC Sheetfed Scanner', 'pc-sheetfed-scanner', '<ul>\r\n<li>The Neat Company NeatDesk for PC Sheetfed Scanner</li>\r\n<li>Power supply</li>\r\n<li>USB cable</li>\r\n</ul>', 'Scan documents and photos with this The Neat Company.', '', '', '', 'en-GB'),
(14, 14, 'Flatbed Photo Scanner', 'flatbed-photo-scanner', '<p>Up to 6400 dpi optical resolution (12800 x 12800 dpi with interpolation); up to 6400 x 9600 dpi hardware resolution (54400 x 74880 (6400 dpi) effective pixels).</p>', 'Can restore faded color photos with a single touch.', '', '', '', 'en-GB'),
(15, 15, 'Dell 720 Color Printer', 'dell-720-color-printer', '<p>This is accomplished by:</p>\r\n<p>1. Feeding in the paper.</p>\r\n<p>2. Moving the ink jets laterally.</p>\r\n<p>3. Spraying the ink onto the paper.</p>\r\n<p>4. Feeding the paper out of the system.</p>', 'This is a cheaper version of a printer and is often given out.', '', '', '', 'en-GB');

INSERT INTO `#__eshop_productimages` (`id`, `product_id`, `image`, `published`, `ordering`, `created_date`, `created_by`, `modified_date`, `modified_by`, `checked_out`, `checked_out_time`) VALUES
(1, 1, 'canon-20d-digital-1.jpg', 1, 1, '2013-05-23 10:30:15', 777, '2013-05-24 10:22:49', 777, 0, '0000-00-00 00:00:00'),
(2, 1, 'canon-20d-digital-2.jpg', 1, 2, '2013-05-23 10:30:15', 777, '2013-05-24 10:22:49', 777, 0, '0000-00-00 00:00:00'),
(3, 1, 'canon-20d-digital-3.jpg', 1, 3, '2013-05-23 10:30:15', 777, '2013-05-24 10:22:49', 777, 0, '0000-00-00 00:00:00'),
(4, 1, 'canon-20d-digital-4.jpg', 1, 4, '2013-05-23 10:30:15', 777, '2013-05-24 10:22:49', 777, 0, '0000-00-00 00:00:00'),
(5, 1, 'canon-20d-digital-5.jpg', 1, 5, '2013-05-23 10:30:15', 777, '2013-05-24 10:22:49', 777, 0, '0000-00-00 00:00:00'),
(6, 1, 'canon-20d-digital-6.jpg', 1, 6, '2013-05-23 10:30:54', 777, '2013-05-24 10:22:49', 777, 0, '0000-00-00 00:00:00');

INSERT INTO `#__eshop_productoptions` (`id`, `product_id`, `option_id`, `required`) VALUES
(26, 5, 1, 1),
(27, 1, 1, 1),
(28, 1, 2, 1),
(29, 1, 3, 1),
(30, 9, 1, 1),
(31, 10, 2, 1);

INSERT INTO `#__eshop_productoptionvalues` (`id`, `product_option_id`, `product_id`, `option_id`, `option_value_id`, `quantity`, `price`, `price_sign`, `weight`, `weight_sign`) VALUES
(129, 26, 5, 1, 2, 60, 4.50000000, '+', 5.00000000, '+'),
(130, 26, 5, 1, 4, 40, 6.00000000, '+', 8.00000000, '+'),
(131, 27, 1, 1, 1, 40, 5.00000000, '+', 4.50000000, '+'),
(132, 27, 1, 1, 2, 40, 6.00000000, '+', 5.50000000, '+'),
(133, 27, 1, 1, 3, 40, 7.00000000, '+', 6.50000000, '+'),
(134, 27, 1, 1, 4, 40, 8.00000000, '+', 7.50000000, '+'),
(135, 28, 1, 2, 6, 50, 1.50000000, '+', 2.50000000, '+'),
(136, 28, 1, 2, 7, 30, 2.50000000, '+', 3.50000000, '+'),
(137, 28, 1, 2, 8, 30, 3.50000000, '+', 4.50000000, '+'),
(138, 28, 1, 2, 10, 30, 5.50000000, '+', 6.50000000, '+'),
(139, 29, 1, 3, 12, 100, 4.00000000, '+', 8.00000000, '+'),
(140, 29, 1, 3, 13, 25, 6.00000000, '+', 10.00000000, '+'),
(141, 29, 1, 3, 14, 25, 8.00000000, '+', 12.00000000, '+'),
(142, 29, 1, 3, 15, 25, 10.00000000, '+', 14.00000000, '+'),
(143, 29, 1, 3, 16, 25, 12.00000000, '+', 16.00000000, '+'),
(144, 30, 9, 1, 1, 40, 5.00000000, '+', 7.50000000, '+'),
(145, 30, 9, 1, 2, 40, 6.00000000, '+', 8.00000000, '+'),
(146, 30, 9, 1, 4, 40, 7.00000000, '+', 9.50000000, '+'),
(147, 31, 10, 2, 6, 25, 2.50000000, '+', 4.50000000, '+'),
(148, 31, 10, 2, 7, 25, 3.00000000, '+', 4.00000000, '+'),
(149, 31, 10, 2, 8, 25, 4.00000000, '+', 5.00000000, '+'),
(150, 31, 10, 2, 10, 25, 5.00000000, '+', 6.00000000, '+');

INSERT INTO `#__eshop_productrelations` (`id`, `product_id`, `related_product_id`) VALUES
(28, 8, 2),
(29, 8, 6),
(35, 15, 13),
(36, 15, 14),
(37, 14, 13),
(50, 6, 2),
(51, 6, 4),
(52, 6, 8),
(69, 5, 2),
(70, 5, 3),
(71, 5, 6),
(72, 1, 2),
(73, 1, 3),
(74, 1, 4),
(75, 1, 5),
(76, 1, 6),
(77, 1, 7),
(78, 1, 8),
(79, 1, 9),
(80, 2, 1),
(81, 2, 4),
(82, 2, 5),
(83, 3, 2),
(84, 3, 4),
(85, 3, 5),
(86, 4, 3),
(87, 4, 7),
(88, 4, 8),
(89, 7, 3),
(90, 7, 6),
(91, 7, 8),
(92, 10, 2),
(93, 10, 5),
(94, 12, 11);

INSERT INTO `#__eshop_products` (`id`, `manufacturer_id`, `product_sku`, `product_weight`, `product_weight_id`, `product_length`, `product_width`, `product_height`, `product_length_id`, `product_price`, `product_taxclass_id`, `product_quantity`, `product_shipping`, `product_shipping_cost`, `product_image`, `product_available_date`, `product_featured`, `published`, `ordering`, `hits`, `created_date`, `created_by`, `modified_date`, `modified_by`, `checked_out`, `checked_out_time`) VALUES
(1, 5, '0013803044430-20D', 0.60000000, 1, 50.00000000, 30.00000000, 20.00000000, 1, 122.50000000, 1, 200, 1, 10.00000000, 'canon-20d-digital.jpg', '2013-05-01 00:00:00', 1, 1, 1, 0, '2013-05-23 08:19:00', 777, '2013-05-29 07:53:17', 777, 0, '0000-00-00 00:00:00'),
(2, 5, '013803049541', 0.65000000, 1, 65.00000000, 35.00000000, 25.00000000, 1, 120.00000000, 2, 300, 1, 10.00000000, 'canon-eos-digital-rebel-xt-350.jpg', '2013-05-01 00:00:00', 0, 1, 2, 0, '2013-05-23 08:37:13', 777, '2013-05-29 07:53:31', 777, 0, '0000-00-00 00:00:00'),
(3, 5, '6558B005', 1.20000000, 1, 110.00000000, 65.00000000, 45.00000000, 1, 750.00000000, 1, 200, 1, 10.00000000, 'canon-eos-rebel-t4i.jpg', '0000-00-00 00:00:00', 1, 1, 3, 0, '2013-05-23 08:43:03', 777, '2013-05-29 07:53:49', 777, 0, '0000-00-00 00:00:00'),
(4, 5, '5251B001', 0.80000000, 1, 55.00000000, 45.00000000, 35.00000000, 1, 250.00000000, 1, 80, 1, 10.00000000, 'canon-powershot-sx40.jpg', '0000-00-00 00:00:00', 1, 1, 4, 0, '2013-05-23 08:45:58', 777, '2013-05-29 07:54:05', 777, 0, '0000-00-00 00:00:00'),
(5, 5, '6354B001', 0.85000000, 1, 60.00000000, 50.00000000, 40.00000000, 1, 290.00000000, 1, 100, 1, 10.00000000, 'canon-powershot-sx160.jpg', '0000-00-00 00:00:00', 1, 1, 5, 0, '2013-05-23 08:48:40', 777, '2013-05-24 08:25:10', 777, 0, '0000-00-00 00:00:00'),
(6, 5, '8035B002', 0.75000000, 1, 50.00000000, 60.00000000, 45.00000000, 1, 145.00000000, 2, 120, 1, 10.00000000, 'usa-canon-eos-6d.jpg', '0000-00-00 00:00:00', 0, 1, 6, 0, '2013-05-23 08:51:25', 777, '2013-05-24 08:20:14', 777, 0, '0000-00-00 00:00:00'),
(7, 5, '2764B003', 0.70000000, 1, 69.00000000, 59.00000000, 49.00000000, 1, 199.00000000, 2, 150, 1, 10.00000000, 'canon-5d-mark-2-ii-body.jpg', '0000-00-00 00:00:00', 0, 1, 7, 0, '2013-05-23 08:58:39', 777, '2013-05-29 07:54:22', 777, 0, '0000-00-00 00:00:00'),
(8, 5, '5664B001', 0.55000000, 1, 30.00000000, 25.00000000, 20.00000000, 1, 280.00000000, 0, 200, 1, 10.00000000, 'canon-powershot-sx150.jpg', '0000-00-00 00:00:00', 1, 1, 8, 0, '2013-05-23 09:02:37', 777, '2013-05-24 07:58:49', 777, 0, '0000-00-00 00:00:00'),
(9, 12, 'HDR-XR100', 1.40000000, 1, 80.00000000, 30.00000000, 20.00000000, 1, 350.00000000, 1, 120, 1, 10.00000000, 'sony-hdr-xr100-camcorder.jpg', '0000-00-00 00:00:00', 0, 1, 9, 0, '2013-05-23 09:08:53', 777, '2013-05-29 07:55:29', 777, 0, '0000-00-00 00:00:00'),
(10, 11, 'HMX-F80BN', 0.80000000, 1, 75.00000000, 35.00000000, 25.00000000, 1, 420.00000000, 1, 100, 1, 10.00000000, 'samsung-hmx-f80-camcorder.jpg', '0000-00-00 00:00:00', 1, 1, 10, 0, '2013-05-23 09:12:13', 777, '2013-05-29 07:57:09', 777, 0, '0000-00-00 00:00:00'),
(11, 11, 'LT22B350ND', 3.20000000, 1, 80.00000000, 70.00000000, 55.00000000, 1, 229.99000000, 0, 100, 1, 10.00000000, 'samsung-hdtv-monitor.jpg', '0000-00-00 00:00:00', 1, 1, 11, 0, '2013-05-24 04:28:02', 777, '2013-05-24 04:30:32', 777, 0, '0000-00-00 00:00:00'),
(12, 11, 'BX2440X', 6.50000000, 1, 120.00000000, 100.00000000, 90.00000000, 1, 280.00000000, 1, 150, 1, 10.00000000, 'samsung-lcd-monitor.jpg', '0000-00-00 00:00:00', 0, 1, 12, 0, '2013-05-24 07:55:29', 777, '2013-05-29 07:57:21', 777, 0, '0000-00-00 00:00:00'),
(13, 3, '9097185', 12.00000000, 1, 150.00000000, 140.00000000, 50.00000000, 1, 399.99000000, 0, 90, 1, 10.00000000, 'pc-sheetfed-scanner.jpg', '0000-00-00 00:00:00', 0, 1, 13, 0, '2013-05-24 08:02:35', 777, '2013-05-24 08:04:05', 777, 0, '0000-00-00 00:00:00'),
(14, 2, '8460747', 14.00000000, 1, 160.00000000, 140.00000000, 80.00000000, 1, 164.99000000, 0, 50, 1, 10.00000000, 'flatbed-photo-scanner.jpg', '0000-00-00 00:00:00', 1, 1, 14, 0, '2013-05-24 08:08:18', 777, '2013-05-24 08:17:32', 777, 0, '0000-00-00 00:00:00'),
(15, 6, 'DELL720P', 12.50000000, 1, 145.00000000, 125.00000000, 85.00000000, 1, 350.00000000, 0, 45, 1, 10.00000000, 'dell-720-color-printer.jpg', '0000-00-00 00:00:00', 1, 1, 15, 0, '2013-05-24 08:16:06', 777, '2013-05-24 08:16:33', 777, 0, '0000-00-00 00:00:00');

INSERT INTO `#__eshop_taxclasses` (`id`, `taxclass_name`, `taxclass_desc`, `published`, `created_date`, `created_by`, `modified_date`, `modified_by`, `checked_out`, `checked_out_time`) VALUES
(1, 'Downloadable Products', 'Downloadable', 1, '2013-05-23 08:09:53', 777, '2013-05-23 08:09:53', 777, 0, '0000-00-00 00:00:00'),
(2, 'Taxable Goods', 'Taxed Stuff', 1, '2013-05-23 08:10:26', 777, '2013-05-23 08:10:26', 777, 0, '0000-00-00 00:00:00');

INSERT INTO `#__eshop_taxcustomergroups` (`id`, `tax_id`, `customergroup_id`) VALUES
(1, 1, 1),
(2, 2, 1);

INSERT INTO `#__eshop_taxes` (`id`, `geozone_id`, `tax_name`, `tax_rate`, `tax_type`, `published`, `created_date`, `created_by`, `modified_date`, `modified_by`, `checked_out`, `checked_out_time`) VALUES
(1, 2, 'Eco Tax (-2.00)', 2.00000000, 'F', 1, '2013-05-23 08:07:55', 777, '2013-05-23 08:07:55', 777, 0, '0000-00-00 00:00:00'),
(2, 2, 'VAT (17.5%)', 17.50000000, 'P', 1, '2013-05-23 08:08:33', 777, '2013-05-23 08:08:33', 777, 0, '0000-00-00 00:00:00');

INSERT INTO `#__eshop_taxrules` (`id`, `taxclass_id`, `tax_id`, `based_on`, `priority`) VALUES
(1, 1, 2, 'payment', 1),
(2, 1, 1, 'store', 2),
(3, 2, 2, 'shipping', 1),
(4, 2, 1, 'shipping', 2);