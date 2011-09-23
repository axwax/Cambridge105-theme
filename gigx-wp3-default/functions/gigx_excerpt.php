<?php
/* 
Version: 0.1
Author: Axel Minet
*/

function gigx_excerpt ($content='',$excerpt='',$striphtml=false, $extract_length=200, $link='', $getmore_text='(more...)',$autop=False){
	if ( $excerpt != '' ) {  // uses text from the exceprt field in post screen
	// do something
	$text=$excerpt;
	}
	else {
  	$text = $content;
	}
		$text = explode('<!--more-->', $text, 2);
		if (count($text) > 1) $gotmore = true; // checks if there is a more tag
		
		$text = apply_filters('the_content', $text[0]);
		$text = str_replace(']]>', ']]&gt;', $text);

		if ($striphtml) {
			$text = strip_tags($text);
		} else {
			//
			//	Strip leading and trailing tags from the entry.
			//
			$textnew=gigx_truncate($text, $extract_length, '', false, true,'<br><b><i><u><strong><span><a>');
			if(strlen($textnew)<strlen($text)) {  // extract is shorter than original text
				  $gotmore=true;
				  $text=$textnew;
			}
 		}

		if ($gotmore) { // if extract is shorter than full post then...
			//
			//	Check for adding the onwards link... only needed if some truncation took place.
			//
			if (!empty($link)) {
				$text .= ' <a href="'. $link . "\">$getmore_text</a>";
			} 
		}
	
	if ($autop) $text=wpautop($text);
	return $text;
}


/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ending if the text is longer than length.
 *
 * @param string  $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string  $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 * @return string Trimmed string.
 * Modified by kvs, http://www.securityhacking.tk
 */
    function gigx_truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true, $allowedTags='<br><b><i><u><p><strong><span><div>') {
        if ($considerHtml) {
            if($allowedTags) $text=strip_tags($text,$allowedTags);
            // if the plain text is shorter than the maximum length, return the whole text
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = strlen($ending);
            $open_tags = array();
            $truncate = '';
            $doingtag=0;
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash (f.e. <br/>)
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                    // if tag is a closing tag (f.e. </b>)
                    } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags);
                        $doingtag=0;
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                    // if tag is an opening tag (f.e. <b>)
                    } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                        $doingtag=1;
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length+$content_length> $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1]+1-$entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                // if the maximum length is reached, get off the loop
                if($total_length>= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            if(!$doingtag) { $spacepos = strrpos($truncate, ' '); }
            else { $spacepos=strrpos($truncate,'>'); }
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = substr($truncate, 0, $spacepos);
                if($doingtag){$truncate.=">";}
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }
        return $truncate;
    }
?>
