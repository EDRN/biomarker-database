<div id='pagination'>
<?php
    if($pagination->setPaging($paging)):
    //$leftArrow = $html->image("nav/arrowleft.gif", Array('height'=>15));
    //$rightArrow = $html->image("nav/arrowright.gif", Array('height'=>15));
	$leftArrow = "Previous";
	$rightArrow = "Next";
    
    $prev = $pagination->prevPage($leftArrow,false);
    $prev = $prev?$prev:$leftArrow;
    $next = $pagination->nextPage($rightArrow,false);
    $next = $next?$next:$rightArrow;

    $pages = $pagination->pageNumbers("  ");

	echo "<div class=\"summary\">";
    echo $pagination->result("Displaying Results:&nbsp;");
	echo "</div>";
	echo "<div class=\"paginationLinks\">";
    //echo $prev." ".$pages." ".$next;
	echo $pages;
	echo "</div>";
    echo $pagination->resultsPerPage(NULL, ' ');
    endif;
?>
</div> 