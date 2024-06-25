var FiltersEnabled = 0; // if your not going to use transitions or filters in any of the tips set this to 0
var spacer="&nbsp; &nbsp; &nbsp; ";

// email notifications to admin
notifyAdminNewMembers0Tip=["", spacer+"No email notifications to admin."];
notifyAdminNewMembers1Tip=["", spacer+"Notify admin only when a new member is waiting for approval."];
notifyAdminNewMembers2Tip=["", spacer+"Notify admin for all new sign-ups."];

// visitorSignup
visitorSignup0Tip=["", spacer+"If this option is selected, visitors will not be able to join this group unless the admin manually moves them to this group from the admin area."];
visitorSignup1Tip=["", spacer+"If this option is selected, visitors can join this group but will not be able to sign in unless the admin approves them from the admin area."];
visitorSignup2Tip=["", spacer+"If this option is selected, visitors can join this group and will be able to sign in instantly with no need for admin approval."];

// Assignments table
Assignments_addTip=["",spacer+"This option allows all members of the group to add records to the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table. A member who adds a record to the table becomes the 'owner' of that record."];

Assignments_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table."];
Assignments_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table."];
Assignments_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table."];
Assignments_view3Tip=["",spacer+"This option allows each member of the group to view all records in the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table."];

Assignments_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table."];
Assignments_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table."];
Assignments_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table."];
Assignments_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table, regardless of their owner."];

Assignments_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table."];
Assignments_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table."];
Assignments_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table."];
Assignments_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the '&#913;&#957;&#945;&#952;&#941;&#963;&#949;&#953;&#962;' table."];

// Teachers table
Teachers_addTip=["",spacer+"This option allows all members of the group to add records to the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table. A member who adds a record to the table becomes the 'owner' of that record."];

Teachers_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table."];
Teachers_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table."];
Teachers_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table."];
Teachers_view3Tip=["",spacer+"This option allows each member of the group to view all records in the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table."];

Teachers_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table."];
Teachers_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table."];
Teachers_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table."];
Teachers_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table, regardless of their owner."];

Teachers_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table."];
Teachers_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table."];
Teachers_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table."];
Teachers_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the '&#917;&#954;&#960;&#945;&#953;&#948;&#949;&#965;&#964;&#953;&#954;&#959;&#943;' table."];

// Lessons table
Lessons_addTip=["",spacer+"This option allows all members of the group to add records to the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table. A member who adds a record to the table becomes the 'owner' of that record."];

Lessons_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table."];
Lessons_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table."];
Lessons_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table."];
Lessons_view3Tip=["",spacer+"This option allows each member of the group to view all records in the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table."];

Lessons_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table."];
Lessons_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table."];
Lessons_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table."];
Lessons_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table, regardless of their owner."];

Lessons_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table."];
Lessons_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table."];
Lessons_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table."];
Lessons_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the '&#924;&#945;&#952;&#942;&#956;&#945;&#964;&#945;' table."];

// Classes table
Classes_addTip=["",spacer+"This option allows all members of the group to add records to the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table. A member who adds a record to the table becomes the 'owner' of that record."];

Classes_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table."];
Classes_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table."];
Classes_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table."];
Classes_view3Tip=["",spacer+"This option allows each member of the group to view all records in the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table."];

Classes_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table."];
Classes_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table."];
Classes_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table."];
Classes_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table, regardless of their owner."];

Classes_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table."];
Classes_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table."];
Classes_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table."];
Classes_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the '&#932;&#956;&#942;&#956;&#945;&#964;&#945;' table."];

// Sectors table
Sectors_addTip=["",spacer+"This option allows all members of the group to add records to the '&#932;&#959;&#956;&#949;&#943;&#962;' table. A member who adds a record to the table becomes the 'owner' of that record."];

Sectors_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the '&#932;&#959;&#956;&#949;&#943;&#962;' table."];
Sectors_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the '&#932;&#959;&#956;&#949;&#943;&#962;' table."];
Sectors_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the '&#932;&#959;&#956;&#949;&#943;&#962;' table."];
Sectors_view3Tip=["",spacer+"This option allows each member of the group to view all records in the '&#932;&#959;&#956;&#949;&#943;&#962;' table."];

Sectors_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the '&#932;&#959;&#956;&#949;&#943;&#962;' table."];
Sectors_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the '&#932;&#959;&#956;&#949;&#943;&#962;' table."];
Sectors_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the '&#932;&#959;&#956;&#949;&#943;&#962;' table."];
Sectors_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the '&#932;&#959;&#956;&#949;&#943;&#962;' table, regardless of their owner."];

Sectors_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the '&#932;&#959;&#956;&#949;&#943;&#962;' table."];
Sectors_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the '&#932;&#959;&#956;&#949;&#943;&#962;' table."];
Sectors_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the '&#932;&#959;&#956;&#949;&#943;&#962;' table."];
Sectors_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the '&#932;&#959;&#956;&#949;&#943;&#962;' table."];

// Heads table
Heads_addTip=["",spacer+"This option allows all members of the group to add records to the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table. A member who adds a record to the table becomes the 'owner' of that record."];

Heads_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table."];
Heads_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table."];
Heads_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table."];
Heads_view3Tip=["",spacer+"This option allows each member of the group to view all records in the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table."];

Heads_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table."];
Heads_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table."];
Heads_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table."];
Heads_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table, regardless of their owner."];

Heads_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table."];
Heads_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table."];
Heads_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table."];
Heads_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the '&#933;&#960;&#949;&#973;&#952;&#965;&#957;&#959;&#953; &#932;&#956;&#951;&#956;&#940;&#964;&#969;&#957;' table."];

// Projectors_timetable table
Projectors_timetable_addTip=["",spacer+"This option allows all members of the group to add records to the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table. A member who adds a record to the table becomes the 'owner' of that record."];

Projectors_timetable_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table."];
Projectors_timetable_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table."];
Projectors_timetable_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table."];
Projectors_timetable_view3Tip=["",spacer+"This option allows each member of the group to view all records in the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table."];

Projectors_timetable_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table."];
Projectors_timetable_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table."];
Projectors_timetable_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table."];
Projectors_timetable_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table, regardless of their owner."];

Projectors_timetable_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table."];
Projectors_timetable_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table."];
Projectors_timetable_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table."];
Projectors_timetable_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the '&#935;&#961;&#959;&#957;&#959;&#955;&#972;&#947;&#953;&#959; &#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#941;&#969;&#957; ' table."];

// Hours table
Hours_addTip=["",spacer+"This option allows all members of the group to add records to the '&#911;&#961;&#949;&#962;' table. A member who adds a record to the table becomes the 'owner' of that record."];

Hours_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the '&#911;&#961;&#949;&#962;' table."];
Hours_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the '&#911;&#961;&#949;&#962;' table."];
Hours_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the '&#911;&#961;&#949;&#962;' table."];
Hours_view3Tip=["",spacer+"This option allows each member of the group to view all records in the '&#911;&#961;&#949;&#962;' table."];

Hours_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the '&#911;&#961;&#949;&#962;' table."];
Hours_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the '&#911;&#961;&#949;&#962;' table."];
Hours_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the '&#911;&#961;&#949;&#962;' table."];
Hours_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the '&#911;&#961;&#949;&#962;' table, regardless of their owner."];

Hours_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the '&#911;&#961;&#949;&#962;' table."];
Hours_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the '&#911;&#961;&#949;&#962;' table."];
Hours_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the '&#911;&#961;&#949;&#962;' table."];
Hours_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the '&#911;&#961;&#949;&#962;' table."];

// Projectors table
Projectors_addTip=["",spacer+"This option allows all members of the group to add records to the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table. A member who adds a record to the table becomes the 'owner' of that record."];

Projectors_view0Tip=["",spacer+"This option prohibits all members of the group from viewing any record in the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table."];
Projectors_view1Tip=["",spacer+"This option allows each member of the group to view only his own records in the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table."];
Projectors_view2Tip=["",spacer+"This option allows each member of the group to view any record owned by any member of the group in the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table."];
Projectors_view3Tip=["",spacer+"This option allows each member of the group to view all records in the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table."];

Projectors_edit0Tip=["",spacer+"This option prohibits all members of the group from modifying any record in the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table."];
Projectors_edit1Tip=["",spacer+"This option allows each member of the group to edit only his own records in the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table."];
Projectors_edit2Tip=["",spacer+"This option allows each member of the group to edit any record owned by any member of the group in the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table."];
Projectors_edit3Tip=["",spacer+"This option allows each member of the group to edit any records in the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table, regardless of their owner."];

Projectors_delete0Tip=["",spacer+"This option prohibits all members of the group from deleting any record in the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table."];
Projectors_delete1Tip=["",spacer+"This option allows each member of the group to delete only his own records in the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table."];
Projectors_delete2Tip=["",spacer+"This option allows each member of the group to delete any record owned by any member of the group in the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table."];
Projectors_delete3Tip=["",spacer+"This option allows each member of the group to delete any records in the '&#914;&#953;&#957;&#964;&#949;&#959;&#960;&#961;&#959;&#946;&#959;&#955;&#949;&#943;&#962;' table."];

/*
	Style syntax:
	-------------
	[TitleColor,TextColor,TitleBgColor,TextBgColor,TitleBgImag,TextBgImag,TitleTextAlign,
	TextTextAlign,TitleFontFace,TextFontFace, TipPosition, StickyStyle, TitleFontSize,
	TextFontSize, Width, Height, BorderSize, PadTextArea, CoordinateX , CoordinateY,
	TransitionNumber, TransitionDuration, TransparencyLevel ,ShadowType, ShadowColor]

*/

toolTipStyle=["white","#00008B","#000099","#E6E6FA","","images/helpBg.gif","","","","\"Trebuchet MS\", sans-serif","","","","3",400,"",1,2,10,10,51,1,0,"",""];

applyCssFilter();
