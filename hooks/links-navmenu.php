<?php
	/*
	* You can add custom links to the navigation menu by appending them here ...
	* The format for each link is:
		$navLinks[] = [
			'url' => 'path/to/link', 
			'title' => 'Link title', 
			'groups' => ['group1', 'group2'], // groups allowed to see this link, use '*' if you want to show the link to all groups
			'icon' => 'path/to/icon',
			'table_group' => 0, // optional index of table group, default is 0
		];
	*/


	/* calendar links */
		$navLinks[] = [
			'url' => 'hooks/calendar-all-calendar.php',
			'icon' => 'resources/table_icons/calendar.png',
			'title' => 'Πρόγραμμα Βιντεοπροβολέων',
			'groups' => ['Admins'],
			'table_group' => '2',
		];

	/* end of calendar links */