<?php 
	if(!isset($Translation)) { @header('Location: index.php'); exit; }

	$advanced_search_mode = 0;
	$search_mode_session_key = substr('spm_' . basename(__FILE__), 0, -4);
	if(Request::has('advanced_search_mode')) {
		/* if user explicitly sets search mode by clicking Filter_x from the filters page, 
		 * apply requested mode, and store into session */
		$advanced_search_mode = intval(Request::val('advanced_search_mode')) ? 1 : 0;
		$_SESSION[$search_mode_session_key] = $advanced_search_mode;

	} elseif(isset($_SESSION[$search_mode_session_key])) {
		/* otherwise, check if search mode for given table is specified in user's 
		 * session and apply it */
		$advanced_search_mode = intval($_SESSION[$search_mode_session_key]) ? 1 : 0;
	}
?>

	<input type="hidden" name="advanced_search_mode" value="<?php echo $advanced_search_mode; ?>" id="advanced_search_mode">
	<script>
		$j(function(){
			$j('.btn.search_mode').appendTo('.page-header h1');
			$j('.btn.search_mode').click(function(){
				var mode = parseInt($j('#advanced_search_mode').val());
				$j('#advanced_search_mode').val(1 - mode);
				if(typeof(beforeApplyFilters) == 'function') beforeApplyFilters();
				return true;
			});
		})
	</script>

<?php if($advanced_search_mode) { ?>
	<button class="btn btn-lg btn-success pull-right search_mode" id="simple_search_mode" type="submit" name="Filter_x" value="1">Switch to simple search mode</button>
	<script>
		$j(function() {
			$j('#simple_search_mode').click(function() {
				if(!confirm('If you switch to simple search mode, any filters defined here will be lost! Do you still which to proceed?')) return false;
				$j('.clear_filter').click();
			})		
		})
	</script>
	<?php include(__DIR__ . '/../defaultFilters.php'); ?>
	
<?php } else { ?>

	<button class="btn btn-lg btn-default pull-right search_mode" type="submit" name="Filter_x" value="1">Switch to advanced search mode</button>
			<!-- %datetimePicker% -->
			
			<div class="page-header"><h1>
				<a href="Assignments_view.php" style="text-decoration: none; color: inherit;">
					<img src="resources/table_icons/connect.png"> 					Αναθέσεις Filters</a>
			</h1></div>

				

	<div class="row vspacer-lg" style="border-bottom: dotted 2px #DDD;">
		
		<div class="hidden-xs hidden-sm col-md-3 vspacer-lg text-right"><label for="">Τμήμα</label></div>
		<div class="hidden-md hidden-lg col-xs-12 vspacer-lg"><label for="">Τμήμα</label></div>
		
		
		<div class="col-md-8 col-lg-6 vspacer-md">
			<div id="filter_2" class="vspacer-lg"><span></span></div>

			<input type="hidden" class="populatedLookupData" name="1" value="<?php echo htmlspecialchars($FilterValue[1]); ?>" >
			<input type="hidden" name="FilterAnd[1]" value="and">
			<input type="hidden" name="FilterField[1]" value="2">  
			<input type="hidden" id="lookupoperator_2" name="FilterOperator[1]" value="equal-to">
			<input type="hidden" id="filterfield_2" name="FilterValue[1]" value="<?php echo htmlspecialchars($FilterValue[1]); ?>" size="3">
		</div>
		
		
		<div class="col-xs-3 col-xs-offset-9 col-md-offset-0 col-md-1">
			<button type="button" class="btn btn-default vspacer-md btn-block" title='Clear fields' onclick="clearFilters($j(this).parent());" ><span class="glyphicon glyphicon-trash text-danger"></button>
		</div>

			</div>

	<script>

		$j(function() {
			/* display a drop-down of categories that populates its content from ajax_combo.php */
			$j("#filter_2").select2({
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) {
						return {
							s: term,
							p: page,
							t: "Assignments",
							f: "ClassID",
							ut: 1,
							json: 1
						}; 
					},
					results: function (resp, page) { return resp; }
				}
			}).on('change', function(e){
				$j("#filterfield_2").val(e.added.text);
				$j("#lookupoperator_2").val('equal-to');
				if (e.added.id=='{empty_value}'){
					$j("#lookupoperator_2").val('is-empty');
				}
			});


			/* preserve the applied category filter and show it when re-opening the filters page */
			if ($j("#filterfield_2").val().length){
				
				//None case 
				if ($j("#filterfield_2").val() == '<None>'){
					$j("#filter_2").select2( 'data' , {
						id: '{empty-value}',
						text: '<None>'
					});
					$j("#lookupoperator_2").val('is-empty');
					return;
				}
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: {
						s: $j("#filterfield_2").val(),  //search term
						p: 1,                                         //page number
						t: "Assignments",                //table name
						f: "ClassID",               //field name
						json: 1
					}
				}).done(function(response){
					if (response.results.length){
						$j("#filter_2").select2('data' , {
							id: response.results[0].id,
							text: response.results[0].text
						});
					}
				});
			}

		});
	</script>

		
			<!-- ########################################################## -->
					

	<div class="row vspacer-lg" style="border-bottom: dotted 2px #DDD;">
		
		<div class="hidden-xs hidden-sm col-md-3 vspacer-lg text-right"><label for="">Μάθημα:</label></div>
		<div class="hidden-md hidden-lg col-xs-12 vspacer-lg"><label for="">Μάθημα:</label></div>
		
		
		<div class="col-md-8 col-lg-6 vspacer-md">
			<div id="filter_3" class="vspacer-lg"><span></span></div>

			<input type="hidden" class="populatedLookupData" name="2" value="<?php echo htmlspecialchars($FilterValue[2]); ?>" >
			<input type="hidden" name="FilterAnd[2]" value="and">
			<input type="hidden" name="FilterField[2]" value="3">  
			<input type="hidden" id="lookupoperator_3" name="FilterOperator[2]" value="equal-to">
			<input type="hidden" id="filterfield_3" name="FilterValue[2]" value="<?php echo htmlspecialchars($FilterValue[2]); ?>" size="3">
		</div>
		
		
		<div class="col-xs-3 col-xs-offset-9 col-md-offset-0 col-md-1">
			<button type="button" class="btn btn-default vspacer-md btn-block" title='Clear fields' onclick="clearFilters($j(this).parent());" ><span class="glyphicon glyphicon-trash text-danger"></button>
		</div>

			</div>

	<script>

		$j(function() {
			/* display a drop-down of categories that populates its content from ajax_combo.php */
			$j("#filter_3").select2({
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) {
						return {
							s: term,
							p: page,
							t: "Assignments",
							f: "LessonID",
							ut: 1,
							json: 1
						}; 
					},
					results: function (resp, page) { return resp; }
				}
			}).on('change', function(e){
				$j("#filterfield_3").val(e.added.text);
				$j("#lookupoperator_3").val('equal-to');
				if (e.added.id=='{empty_value}'){
					$j("#lookupoperator_3").val('is-empty');
				}
			});


			/* preserve the applied category filter and show it when re-opening the filters page */
			if ($j("#filterfield_3").val().length){
				
				//None case 
				if ($j("#filterfield_3").val() == '<None>'){
					$j("#filter_3").select2( 'data' , {
						id: '{empty-value}',
						text: '<None>'
					});
					$j("#lookupoperator_3").val('is-empty');
					return;
				}
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: {
						s: $j("#filterfield_3").val(),  //search term
						p: 1,                                         //page number
						t: "Assignments",                //table name
						f: "LessonID",               //field name
						json: 1
					}
				}).done(function(response){
					if (response.results.length){
						$j("#filter_3").select2('data' , {
							id: response.results[0].id,
							text: response.results[0].text
						});
					}
				});
			}

		});
	</script>

		
			<!-- ########################################################## -->
					

	<div class="row vspacer-lg" style="border-bottom: dotted 2px #DDD;">
		
		<div class="hidden-xs hidden-sm col-md-3 vspacer-lg text-right"><label for="">Εκπαιδευτικός Α</label></div>
		<div class="hidden-md hidden-lg col-xs-12 vspacer-lg"><label for="">Εκπαιδευτικός Α</label></div>
		
		
		<div class="col-md-8 col-lg-6 vspacer-md">
			<div id="filter_10" class="vspacer-lg"><span></span></div>

			<input type="hidden" class="populatedLookupData" name="3" value="<?php echo htmlspecialchars($FilterValue[3]); ?>" >
			<input type="hidden" name="FilterAnd[3]" value="and">
			<input type="hidden" name="FilterField[3]" value="10">  
			<input type="hidden" id="lookupoperator_10" name="FilterOperator[3]" value="equal-to">
			<input type="hidden" id="filterfield_10" name="FilterValue[3]" value="<?php echo htmlspecialchars($FilterValue[3]); ?>" size="3">
		</div>
		
		
		<div class="col-xs-3 col-xs-offset-9 col-md-offset-0 col-md-1">
			<button type="button" class="btn btn-default vspacer-md btn-block" title='Clear fields' onclick="clearFilters($j(this).parent());" ><span class="glyphicon glyphicon-trash text-danger"></button>
		</div>

			</div>

	<script>

		$j(function() {
			/* display a drop-down of categories that populates its content from ajax_combo.php */
			$j("#filter_10").select2({
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) {
						return {
							s: term,
							p: page,
							t: "Assignments",
							f: "TeacherID",
							ut: 1,
							json: 1
						}; 
					},
					results: function (resp, page) { return resp; }
				}
			}).on('change', function(e){
				$j("#filterfield_10").val(e.added.text);
				$j("#lookupoperator_10").val('equal-to');
				if (e.added.id=='{empty_value}'){
					$j("#lookupoperator_10").val('is-empty');
				}
			});


			/* preserve the applied category filter and show it when re-opening the filters page */
			if ($j("#filterfield_10").val().length){
				
				//None case 
				if ($j("#filterfield_10").val() == '<None>'){
					$j("#filter_10").select2( 'data' , {
						id: '{empty-value}',
						text: '<None>'
					});
					$j("#lookupoperator_10").val('is-empty');
					return;
				}
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: {
						s: $j("#filterfield_10").val(),  //search term
						p: 1,                                         //page number
						t: "Assignments",                //table name
						f: "TeacherID",               //field name
						json: 1
					}
				}).done(function(response){
					if (response.results.length){
						$j("#filter_10").select2('data' , {
							id: response.results[1].id,
							text: response.results[1].text
						});
					}
				});
			}

		});
	</script>

		
			<!-- ########################################################## -->
					

	<div class="row vspacer-lg" style="border-bottom: dotted 2px #DDD;">
		
		<div class="hidden-xs hidden-sm col-md-3 vspacer-lg text-right"><label for="">Εκπαιδευτικός Β</label></div>
		<div class="hidden-md hidden-lg col-xs-12 vspacer-lg"><label for="">Εκπαιδευτικός Β</label></div>
		
		
		<div class="col-md-8 col-lg-6 vspacer-md">
			<div id="filter_11" class="vspacer-lg"><span></span></div>

			<input type="hidden" class="populatedLookupData" name="4" value="<?php echo htmlspecialchars($FilterValue[4]); ?>" >
			<input type="hidden" name="FilterAnd[4]" value="and">
			<input type="hidden" name="FilterField[4]" value="11">  
			<input type="hidden" id="lookupoperator_11" name="FilterOperator[4]" value="equal-to">
			<input type="hidden" id="filterfield_11" name="FilterValue[4]" value="<?php echo htmlspecialchars($FilterValue[4]); ?>" size="3">
		</div>
		
		
		<div class="col-xs-3 col-xs-offset-9 col-md-offset-0 col-md-1">
			<button type="button" class="btn btn-default vspacer-md btn-block" title='Clear fields' onclick="clearFilters($j(this).parent());" ><span class="glyphicon glyphicon-trash text-danger"></button>
		</div>

			</div>

	<script>

		$j(function() {
			/* display a drop-down of categories that populates its content from ajax_combo.php */
			$j("#filter_11").select2({
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) {
						return {
							s: term,
							p: page,
							t: "Assignments",
							f: "TeacherID2",
							ut: 1,
							json: 1
						}; 
					},
					results: function (resp, page) { return resp; }
				}
			}).on('change', function(e){
				$j("#filterfield_11").val(e.added.text);
				$j("#lookupoperator_11").val('equal-to');
				if (e.added.id=='{empty_value}'){
					$j("#lookupoperator_11").val('is-empty');
				}
			});


			/* preserve the applied category filter and show it when re-opening the filters page */
			if ($j("#filterfield_11").val().length){
				
				//None case 
				if ($j("#filterfield_11").val() == '<None>'){
					$j("#filter_11").select2( 'data' , {
						id: '{empty-value}',
						text: '<None>'
					});
					$j("#lookupoperator_11").val('is-empty');
					return;
				}
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: {
						s: $j("#filterfield_11").val(),  //search term
						p: 1,                                         //page number
						t: "Assignments",                //table name
						f: "TeacherID2",               //field name
						json: 1
					}
				}).done(function(response){
					if (response.results.length){
						$j("#filter_11").select2('data' , {
							id: response.results[1].id,
							text: response.results[1].text
						});
					}
				});
			}

		});
	</script>

		
			<!-- ########################################################## -->
			
		<?php $si = 5; ?>
		<?php for($afi = $si; $afi <= 12; $afi++) { ?>
			<!-- advanced filter <?php echo $afi; ?> -->
			<input type="hidden" name="FilterAnd[<?php echo $afi; ?>]" value="<?php echo html_attr(Request::val('FilterAnd')[$afi]); ?>">
			<input type="hidden" name="FilterField[<?php echo $afi; ?>]" value="<?php echo html_attr(Request::val('FilterField')[$afi]); ?>">
			<input type="hidden" name="FilterOperator[<?php echo $afi; ?>]" value="<?php echo html_attr(Request::val('FilterOperator')[$afi]); ?>">
			<input type="hidden" name="FilterValue[<?php echo $afi; ?>]" value="<?php echo html_attr(Request::val('FilterValue')[$afi]); ?>">
		<?php } ?>

					<!-- filter actions -->
			<div class="row">
				<div class="col-md-3 col-md-offset-3 col-lg-offset-4 col-lg-2 vspacer-lg">
					<input type="hidden" name="apply_sorting" value="1">
					<button type="submit" id="applyFilters" onclick="beforeApplyFilters(event);return true;" class="btn btn-success btn-block btn-lg"><i class="glyphicon glyphicon-ok"></i> Apply filters</button>
				</div>
								<div class="col-md-3 col-lg-2 vspacer-lg">
					<button onclick="beforeCancelFilters();" type="submit" id="cancelFilters" class="btn btn-warning btn-block btn-lg"><i class="glyphicon glyphicon-remove"></i> Cancel</button>
				</div>
			</div>

			<script>
				$j(function(){
					//stop event if it is already bound
					$j(".numeric").off("keydown").on("keydown", function (e) {
						// Allow: backspace, delete, tab, escape, enter and .
						if ($j.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
							// Allow: Ctrl+A, Command+A
							(e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
							// Allow: home, end, left, right, down, up
							(e.keyCode >= 35 && e.keyCode <= 40)) {
								// let it happen, don't do anything
								return;
						}
						// Ensure that it is a number and stop the keypress
						if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
							e.preventDefault();
						}
					});                
				});
				
				/* function to handle the action of the clear field button */
				function clearFilters(elm){
					var parentDiv = $j(elm).parent(".row ");
					//get all input nodes
					inputValueChildren = parentDiv.find("input[type!=radio][name^=FilterValue]");
					inputRadioChildren = parentDiv.find("input[type=radio][name^=FilterValue]");
					
					//default input nodes ( text, hidden )
					inputValueChildren.each(function( index ) {
						$j( this ).val('');
					});
					
					//radio buttons
					inputRadioChildren.each(function(index) {
						$j(this).prop('checked', false);

						//checkbox case
						if($j(this).val() == '') $j(this).prop("checked", true).click();
					});
					
					//lookup and select dropdown
					parentDiv.find("div[id$=DropDown],div[id^=filter_]").select2("val", "");

					//for lookup
					parentDiv.find("input[id^=lookupoperator_]").val('equal-to');

				}
				
				function checkboxFilter(elm) {
					if (elm.value == "null") {
						$j("#" + elm.className).val("is-empty");
					} else {
						$j("#" + elm.className).val("equal-to");
					}
				}
				
				/* funtion to remove unsupplied fields */
				function beforeApplyFilters(event){
				
					// get all field submitted values
					$j(":input[type=text][name^=FilterValue],:input[type=hidden][name^=FilterValue],:input[type=radio][name^=FilterValue]:checked").each(function(index) {
						  
						// if type=hidden  and options radio fields with the same name are checked, supply its value
						if($j(this).attr('type') == 'hidden' &&  $j(":input[type=radio][name='" + $j(this).attr('name') + "']:checked").length > 0) {
							return;
						}
						  
						// do not submit fields with empty values
						if(!$j(this).val()) {
							var fieldNum = $j(this).attr('name').match(/(\d+)/)[0];
							$j(":input[name='FilterField[" + fieldNum + "]']").val('');
						};
					});

				}
				
				function beforeCancelFilters(){
					

					//other fields
					$j('form')[0].reset();

					//lookup case ( populate with initial data)
					$j(":input[class='populatedLookupData']").each(function(){
					  

						$j(":input[name='FilterValue["+$j(this).attr('name')+"]']").val($j(this).val());
						if ($j(this).val()== '<None>'){
							$j(this).parent(".row ").find('input[id^="lookupoperator"]').val('is-empty');
						}else{
							$j(this).parent(".row ").find('input[id^="lookupoperator"]').val('equal-to');
						}
							
					})

					//options case ( populate with initial data)
					$j(":input[class='populatedOptionsData']").each(function(){
					   
						$j(":input[name='FilterValue["+$j(this).attr('name')+"]']").val($j(this).val());
					})


					//checkbox, radio options case
					$j(":input[class='checkboxData'],:input[class='optionsData'] ").each(function(){
						var filterNum = $j(this).val();
						var populatedValue = eval("filterValue_"+filterNum);                  
						var parentDiv = $j(this).parent(".row ");

						//check old value
						parentDiv.find("input[type=radio][value='"+populatedValue+"']").prop('checked', true).click();
					
					})

					//remove unsuplied fields
					beforeApplyFilters();

					return true;
				}
			</script>
			
			<style>
				.form-control{ width: 100% !important; }
				.select2-container, .select2-container.vspacer-lg{ max-width: unset !important; width: 100%; margin-top: 0 !important; }
			</style>


		

<?php } ?>