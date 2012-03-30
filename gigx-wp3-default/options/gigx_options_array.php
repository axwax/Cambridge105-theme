<?php
$options = array (


    array(  "name" => "Bigger Text Box",
	    "desc" => "This is a descriptions",
            "id" => $shortname."_bigger_box",
            "std" => "Bla Default Text",
            "type" => "textarea"
	),
  

  
    array(  "name" => "Default Sidebar Widgets",
	    "desc" => "This sets the default sidebar widgets for the Theme.",
            "id" => $shortname."_checkbox",
            "type" => "checkbox",
            "std" => "categories,archives,meta",
            "options" => array("categories","archives","meta")
	),
    array(  "name" => "Default Widgets above posts",
	    "desc" => "This sets the default widgets above posts.",
            "id" => $shortname."_checkbox",
            "type" => "checkbox",
            "std" => "GIGX Page Title",
            "options" => array("GIGX Page Title")
	),
      array(  "name" => "Default Widgets above entries",
	    "desc" => "This sets the default widgets above post entries.",
            "id" => $shortname."_checkbox",
            "type" => "checkbox",
            "std" => "GIGX Post Title,GIGX Post Date,GIGX Post Author",
            "options" => array("GIGX Post Title","GIGX Post Date","GIGX Post Author")
	)  

);

?>
