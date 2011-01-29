<?php
$options = array (
    array(  "name" => "Radio Selection Set",
	    "desc" => "This is a descriptions",
            "id" => $shortname."_radio",
            "type" => "radio",
            "std" => "3",
            "options" => array("3", "2", "1")
	),

    array(  "name" => "Text Box",
	    "desc" => "This is a descriptions",
            "id" => $shortname."_text_box",
            "std" => "Some Default Text",
            "type" => "text"
	),

    array(  "name" => "Bigger Text Box",
	    "desc" => "This is a descriptions",
            "id" => $shortname."_bigger_box",
            "std" => "Default Text",
            "type" => "textarea"
	),
  
    array(  "name" => "Dropdown Selection Menu",
	    "desc" => "This is a descriptions",
            "id" => $shortname."_dropdown_menu",
            "type" => "select",
            "std" => "Default",
            "options" => array("Default", "Option 1", "Option 2")
	),
  
    array(  "name" => "Checkbox selection set",
	    "desc" => "This is a descriptions",
            "id" => $shortname."_checkbox",
            "type" => "checkbox",
            "std" => "Default",
            "options" => array("Default", "Option 1", "Option 2")
	),
  
    array(  "name" => "Multiple selection box",
	    "desc" => "This is a descriptions",
            "id" => $shortname."_multi_select",
            "type" => "multiselect",
            "std" => "Default",
            "options" => array("Defaults", "Option 1s", "Option 2s")
	)						//The last option should not have a comma after the closing ) bracket	
);

?>
