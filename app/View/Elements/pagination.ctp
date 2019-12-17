<div id='pagination'>
<?php
    if($this->Pagination->setPaging($paging)):
    //$leftArrow = $this->Html->image("nav/arrowleft.gif", Array('height'=>15));
    //$rightArrow = $this->Html->image("nav/arrowright.gif", Array('height'=>15));
	$leftArrow = "Previous";
	$rightArrow = "Next";
    
    $prev = $this->Pagination->prevPage($leftArrow,false);
    $prev = $prev?$prev:$leftArrow;
    $next = $this->Pagination->nextPage($rightArrow,false);
    $next = $next?$next:$rightArrow;

    $pages = $this->Pagination->pageNumbers("  ");

	echo "<div class=\"summary\">";
    echo $this->Pagination->result("Displaying Results:&nbsp;");
	echo "</div>";
	echo "<div class=\"paginationLinks\">";
    //echo $prev." ".$pages." ".$next;
	echo $pages;
	echo "</div>";
    echo $this->Pagination->resultsPerPage(NULL, ' ');
    endif;
?>
</div> 