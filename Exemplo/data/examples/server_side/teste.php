<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico" />
		
		<title>DataTables example</title>
		<style type="text/css" title="currentStyle">
			@import "../../media/css/demo_page.css";
			@import "../../media/css/demo_table.css";
		</style>
		<script type="text/javascript" language="javascript" src="../../media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../../media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable( {
					"bProcessing": true,
					"bServerSide": true,
					"sAjaxSource": "scripts/server_processing.php"
				} );
			} );
		</script>
	</head>
	<body id="dt_example">
		<div id="container">
			<div class="full_width big">
				DataTables server-side processing example
			</div>
			
			<h1>Preamble</h1>
			<p>There are many ways to get your data into DataTables, and if you are working with seriously large databases, you might want to consider using the server-side options that DataTables provides. Basically all of the paging, filtering, sorting etc that DataTables does can be handed off to a server (or any other data source - Google Gears or Adobe Air for example!) and DataTables is just an events and display module.</p>
			<p>The example here shows a very simple display of the CSS data (used in all my other examples), but in this instance coming from the server on each draw. Filtering, multi-column sorting etc all work as you would expect.</p>
			
			<h1>Live example</h1>
			<div id="dynamic">
<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
	<thead>
		<tr>
			<th width="20%">Rendering engine</th>
			<th width="25%">Browser</th>
			<th width="25%">Platform(s)</th>
			<th width="15%">Engine version</th>
			<th width="15%">CSS grade</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="5" class="dataTables_empty">Loading data from server</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th>Rendering engine</th>
			<th>Browser</th>
			<th>Platform(s)</th>
			<th>Engine version</th>
			<th>CSS grade</th>
		</tr>
	</tfoot>
</table>
			</div>
			<div class="spacer"></div>
			
			
			<h1>Initialisation code</h1>
			<pre class="brush: js;">$(document).ready(function() {
	$('#example').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "scripts/server_processing.php"
	} );
} );</pre>
		<style type="text/css">
			@import "../examples_support/syntax/css/shCore.css";
		</style>
			<script type="text/javascript" language="javascript" src="../examples_support/syntax/js/shCore.js"></script>
			
			<h1>Server response</h1>
			<p>The code below shows the latest JSON data that has been returned from the server in response to the Ajax request made by DataTables. This will update as further requests are made.</p>
			<pre id="latest_xhr" class="brush: js;"></pre>
			
			<h1>Server side (PHP) code</h1>
			<pre>&lt;?php
	/*
	 * Script:    DataTables server-side script for PHP and MySQL
	 * Copyright: 2010 - Allan Jardine
	 * License:   GPL v2 or BSD (3-point)
	 */
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array( 'engine', 'browser', 'platform', 'version', 'grade' );
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "id";
	
	/* DB table to use */
	$sTable = "ajax";
	
	/* Database connection information */
	$gaSql['user']       = "";
	$gaSql['password']   = "";
	$gaSql['db']         = "";
	$gaSql['server']     = "localhost";
	
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * MySQL connection
	 */
	$gaSql['link'] =  mysqli_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
		die( 'Could not open connection to server' );
	
	mysqli_select_db( $gaSql['db'], $gaSql['link'] ) or 
		die( 'Could not select database '. $gaSql['db'] );
	
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) &amp;&amp; $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysqli_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysqli_real_escape_string( $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i&lt;intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysqli_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i&lt;count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i&lt;count($aColumns) ; $i++ )
	{
		if ( $_GET['bSearchable_'.$i] == "true" &amp;&amp; $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
	";
	$rResult = mysqli_query($db, $sQuery, $gaSql['link'] ) or die(mysqli_error());
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysqli_query($db, $sQuery, $gaSql['link'] ) or die(mysqli_error());
	$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
	";
	$rResultTotal = mysqli_query($db, $sQuery, $gaSql['link'] ) or die(mysqli_error());
	$aResultTotal = mysqli_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" =&gt; intval($_GET['sEcho']),
		"iTotalRecords" =&gt; $iTotal,
		"iTotalDisplayRecords" =&gt; $iFilteredTotal,
		"aaData" =&gt; array()
	);
	
	while ( $aRow = mysqli_fetch_array( $rResult ) )
	{
		$row = array();
		for ( $i=0 ; $i&lt;count($aColumns) ; $i++ )
		{
			if ( $aColumns[$i] == "version" )
			{
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
			}
			else if ( $aColumns[$i] != ' ' )
			{
				/* General output */
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?&gt;</pre>
			
			
			<h1>Other examples</h1>
			<div class="demo_links">
				<h2>Basic initialisation</h2>
				<ul>
					<li><a href="../basic_init/zero_config.html">Zero configuration</a></li>
					<li><a href="../basic_init/filter_only.html">Feature enablement</a></li>
					<li><a href="../basic_init/table_sorting.html">Sorting data</a></li>
					<li><a href="../basic_init/multi_col_sort.html">Multi-column sorting</a></li>
					<li><a href="../basic_init/multiple_tables.html">Multiple tables</a></li>
					<li><a href="../basic_init/hidden_columns.html">Hidden columns</a></li>
					<li><a href="../basic_init/complex_header.html">Complex headers - grouping with colspan</a></li>
					<li><a href="../basic_init/dom.html">DOM positioning</a></li>
					<li><a href="../basic_init/state_save.html">State saving</a></li>
					<li><a href="../basic_init/alt_pagination.html">Alternative pagination styles</a></li>
					<li>Scrolling: <br>
						<a href="../basic_init/scroll_x.html">Horizontal</a> / 
						<a href="../basic_init/scroll_y.html">Vertical</a> / 
						<a href="../basic_init/scroll_xy.html">Both</a> / 
						<a href="../basic_init/scroll_y_theme.html">Themed</a> / 
						<a href="../basic_init/scroll_y_infinite.html">Infinite</a>
					</li>
					<li><a href="../basic_init/language.html">Change language information (internationalisation)</a></li>
					<li><a href="../basic_init/themes.html">ThemeRoller themes (Smoothness)</a></li>
				</ul>
				
				<h2>Advanced initialisation</h2>
				<ul>
					<li>Events: <br>
						<a href="../advanced_init/events_live.html">Live events</a> / 
						<a href="../advanced_init/events_pre_init.html">Pre-init</a> / 
						<a href="../advanced_init/events_post_init.html">Post-init with fnGetNodes</a>
					</li>
					<li><a href="../advanced_init/column_render.html">Column rendering</a></li>
					<li><a href="../advanced_init/html_sort.html">Sorting without HTML tags</a></li>
					<li><a href="../advanced_init/dom_multiple_elements.html">Multiple table controls (sDom)</a></li>
					<li><a href="../advanced_init/length_menu.html">Defining length menu options</a></li>
					<li><a href="../advanced_init/complex_header.html">Complex headers and hidden columns</a></li>
					<li><a href="../advanced_init/dom_toolbar.html">Custom toolbar (element) around table</a></li>
					<li><a href="../advanced_init/highlight.html">Row highlighting with CSS</a></li>
					<li><a href="../advanced_init/row_grouping.html">Row grouping</a></li>
					<li><a href="../advanced_init/row_callback.html">Row callback</a></li>
					<li><a href="../advanced_init/footer_callback.html">Footer callback</a></li>
					<li><a href="../advanced_init/sorting_control.html">Control sorting direction of columns</a></li>
					<li><a href="../advanced_init/language_file.html">Change language information from a file (internationalisation)</a></li>
				</ul>
				
				<h2>API</h2>
				<ul>
					<li><a href="../api/add_row.html">Dynamically add a new row</a></li>
					<li><a href="../api/multi_filter.html">Individual column filtering (using "input" elements)</a></li>
					<li><a href="../api/multi_filter_select.html">Individual column filtering (using "select" elements)</a></li>
					<li><a href="../api/highlight.html">Highlight rows and columns</a></li>
					<li><a href="../api/row_details.html">Show and hide details about a particular record</a></li>
					<li><a href="../api/select_row.html">User selectable rows (multiple rows)</a></li>
					<li><a href="../api/select_single_row.html">User selectable rows (single row) and delete rows</a></li>
					<li><a href="../api/editable.html">Editable rows (with jEditable)</a></li>
					<li><a href="../api/form.html">Submit form with elements in table</a></li>
					<li><a href="../api/counter_column.html">Index column (static number column)</a></li>
					<li><a href="../api/show_hide.html">Show and hide columns dynamically</a></li>
					<li><a href="../api/api_in_init.html">API function use in initialisation object (callback)</a></li>
					<li><a href="../api/tabs_and_scrolling.html">DataTables scrolling and tabs</a></li>
					<li><a href="../api/regex.html">Regular expression filtering</a></li>
				</ul>
			</div>
			
			<div class="demo_links">
				<h2>Data sources</h2>
				<ul>
					<li><a href="../data_sources/dom.html">DOM</a></li>
					<li><a href="../data_sources/js_array.html">Javascript array</a></li>
					<li><a href="../data_sources/ajax.html">Ajax source</a></li>
					<li><a href="../data_sources/server_side.html">Server side processing</a></li>
				</ul>
				
				<h2>Server-side processing</h2>
				<ul>
					<li><a href="../server_side/server_side.html">Obtain server-side data</a></li>
					<li><a href="../server_side/custom_vars.html">Add extra HTTP variables</a></li>
					<li><a href="../server_side/post.html">Use HTTP POST</a></li>
					<li><a href="../server_side/ids.html">Automatic addition of IDs and classes to rows</a></li>
					<li><a href="../server_side/object_data.html">Reading table data from objects</a></li>
					<li><a href="../server_side/row_details.html">Show and hide details about a particular record</a></li>
					<li><a href="../server_side/select_rows.html">User selectable rows (multiple rows)</a></li>
					<li><a href="../server_side/jsonp.html">JSONP for a cross domain data source</a></li>
					<li><a href="../server_side/editable.html">jEditable integration with DataTables</a></li>
					<li><a href="../server_side/defer_loading.html">Deferred loading of Ajax data</a></li>
					<li><a href="../server_side/column_ordering.html">Custom column ordering (in callback data)</a></li>
					<li><a href="../server_side/pipeline.html">Pipelining data (reduce Ajax calls for paging)</a></li>
				</ul>
				
				<h2>Ajax data source</h2>
				<ul>
					<li><a href="../ajax/ajax.html">Ajax sourced data (array of arrays)</a></li>
					<li><a href="../ajax/objects.html">Ajax sourced data (array of objects)</a></li>
					<li><a href="../ajax/defer_render.html">Deferred DOM creation for extra speed</a></li>
					<li><a href="../ajax/null_data_source.html">Empty data source columns</a></li>
					<li><a href="../ajax/custom_data_property.html">Use a data source other than aaData (the default)</a></li>
					<li><a href="../ajax/objects_subarrays.html">Read column data from sub-arrays</a></li>
					<li><a href="../ajax/deep.html">Read column data from deeply nested properties</a></li>
				</ul>
				
				<h2>Plug-ins</h2>
				<ul>
					<li><a href="../plug-ins/plugin_api.html">Add custom API functions</a></li>
					<li><a href="../plug-ins/sorting_plugin.html">Sorting and automatic type detection</a></li>
					<li><a href="../plug-ins/sorting_sType.html">Sorting without automatic type detection</a></li>
					<li><a href="../plug-ins/paging_plugin.html">Custom pagination controls</a></li>
					<li><a href="../plug-ins/range_filtering.html">Range filtering / custom filtering</a></li>
					<li><a href="../plug-ins/dom_sort.html">Live DOM sorting</a></li>
					<li><a href="../plug-ins/html_sort.html">Automatic HTML type detection</a></li>
				</ul>
			</div>
			
			
			<div id="footer" class="clear" style="text-align:center;">
				<p>
					Please refer to the <a href="http://www.datatables.net/usage">DataTables documentation</a> for full information about its API properties and methods.<br>
					Additionally, there are a wide range of <a href="http://www.datatables.net/extras">extras</a> and <a href="http://www.datatables.net/plug-ins">plug-ins</a> which extend the capabilities of DataTables.
				</p>
				
				<span style="font-size:10px;">
					DataTables designed and created by <a href="http://www.sprymedia.co.uk">Allan Jardine</a> &copy; 2007-2011<br>
					DataTables is dual licensed under the <a href="http://www.datatables.net/license_gpl2">GPL v2 license</a> or a <a href="http://www.datatables.net/license_bsd">BSD (3-point) license</a>.
				</span>
			</div>
		</div>
	</body>
</html>