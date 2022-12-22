<?php
	echo "
	<nav aria-label='Page navigation example'>
	<ul class='pagination'>";
 
	// button for first page
	if($page>1){
	    echo "<li class='page-item'><a class='page-link' href='{$page_url}' title='Go to the first page.'>
	        <<
	    </a></li>";
	}
	 
	// calculate total pages
	$total_pages = ceil($total_rows / $records_per_page);
	 
	// range of links to show
	$range =1;
	 
	// display links to 'range of pages' around 'current page'
	$initial_num = $page - $range;
	$condition_limit_num = ($page + $range)  + 1;
	 
	for ($x=$initial_num; $x<$condition_limit_num; $x++) {
	 
	    // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
	    if (($x > 0) && ($x <= $total_pages)) {
	 
	        // current page
	        if ($x == $page) {
	            echo "<li class='page-item active'><a class='page-link' href='#''>$x <span class='sr-only'>(current)</span></a></li>";
	        } 
	 
	        // not current page
	        else {
	            echo "<li class='page-item'><a class='page-link' href='{$page_url}page=$x'>$x</a></li>";
	        }
	    }
	}
	 
	// button for last page
	if($page<$total_pages){
	    echo "<li class='page-item'><a class='page-link' href='{$page_url}page={$total_pages}' title='Last page is {$total_pages}.'>
	        >>
	    </a></li>";
	}
	 
	echo "</ul>
	</nav>";
?>