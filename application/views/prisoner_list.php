<html>

	<head>
		<?php $this->load->view('meta'); ?>
		
		</head>
	<body style="padding-top: 70px;" dir="<?=$this->session->userdata('direction') ?>">
		<?php $this->load->view('menu_bar'); ?>
		<div class="container">
			<h3>
				&nbsp;<?= $this->lang->line('prisoners_list'); ?>&nbsp;
				<button class="btn btn-success pull-right" onclick="new_record()"><i class="glyphicon glyphicon-plus"></i> Add New Prisoner</button>
			</h3>
			
			<hr />
			<!-- <table id="table" class="table table-striped table-hover" cellspacing="0" width="100%"> Edit 11/28 -->
			<table id="table" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
	                    <th><?= $this->lang->line('id'); ?></th>
	                    <th><?= $this->lang->line('license_number'); ?></th>
	                    <th><?= $this->lang->line('name'); ?></th>
	                    <th><?= $this->lang->line('middle_name'); ?></th>
	                    <th><?= $this->lang->line('last_name'); ?></th>
						<th><?= $this->lang->line('street_num'); ?></th>
						<th><?= $this->lang->line('street_name'); ?></th>
						<th><?= $this->lang->line('apartment_num'); ?></th>
						<th><?= $this->lang->line('city'); ?></th>
						<th><?= $this->lang->line('zipcode'); ?></th>
						<th><?= $this->lang->line('phone'); ?></th>
						<th><?= $this->lang->line('birth_city'); ?></th>
						<th><?= $this->lang->line('birth_country'); ?></th>
						<th><?= $this->lang->line('ssn'); ?></th>
						<th><?= $this->lang->line('sex'); ?></th>
						<th><?= $this->lang->line('height_feet'); ?></th>
						<th><?= $this->lang->line('height_inches'); ?></th>
						<th><?= $this->lang->line('weight'); ?></th>
	                    <th><?= $this->lang->line('age'); ?></th>
	                    <th><?= $this->lang->line('eye_color'); ?></th>
						<th><?= $this->lang->line('hair_color'); ?></th>
	                    <th><?= $this->lang->line('num_of_children'); ?></th>
						<th><?= $this->lang->line('property_management'); ?></th>
	                    <th><?= $this->lang->line('criminal_history'); ?></th>
	                    <th><?= $this->lang->line('permanent_province'); ?></th>
	                    <th><?= $this->lang->line('permanent_district'); ?></th>
	                    <th><?= $this->lang->line('present_province'); ?></th>
	                    <th><?= $this->lang->line('present_district'); ?></th>
	                    <th><?= $this->lang->line('profile_pic'); ?></th>
	                    <th>Actions</th>
	                </tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		
		<link rel="stylesheet" href="<?php echo base_url("assets/datatables/media/css/dataTables.bootstrap.min.css"); ?>" />
		<script src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js')?>"></script>
		<script src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js')?>"></script>
		<script src="<?php echo base_url('assets/underscore-min.js')?>"></script>

		<link rel="stylesheet" href="<?php echo base_url('assets/datatables/extensions/Buttons/css/buttons.dataTables.min.css')?>" />
		<script src="<?php echo base_url('assets/datatables/extensions/Buttons/js/dataTables.buttons.min.js')?>"></script>
		<script src="<?php echo base_url('assets/datatables/extensions/Buttons/js/buttons.html5.min.js')?>"></script>
		<script src="<?php echo base_url('assets/Stuk-jszip/dist/jszip.min.js')?>"></script>
		<script src="<?php echo base_url('assets/pdfmake/build/pdfmake.min.js')?>"></script>
		<script src="<?php echo base_url('assets/pdfmake/build/vfs_fonts.js')?>"></script>
		  
		<script type= 'text/javascript'>
			var save_method; //for save method string
		    var oTable;
		    var provincesList = <?= json_encode($provincesList) ?>;
		    var districtsList = <?= json_encode($districtsList) ?>;
		    var photos_directory = "<?= base_url('photos/') ?>";

            $(document).ready(function () {
            	$("li#prisoners", ".navbar-nav").addClass("active");
            	$("input[type='date']").datepicker({
            		dateFormat: "yy-mm-dd"
            	});
            	
                oTable = $('#table').DataTable({
                	"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                	"scrollX": true,
                    "processing": true,
                    "serverSide": true,
                    // "bJQueryUI": true,
                    "ajax": "<?php echo site_url('prisoner/prisoner_list')?>",
                    // "sDom": 'T<"clear">lfrtip'
					language: {
						search: "<?= $this->lang->line('search'); ?>"
					},
					columnDefs: [{
						"targets": 14,
						"searchable": false,
						"orderable": false,
						"width": "125px"
					}],
					dom: 'Bfltip',
					buttons: [
						'copyHtml5',
						'excelHtml5',
						'csvHtml5',
						'pdfHtml5'
					]
                });

                $('[name="permanentProvince"]', '#modal_form_edit').change(function(event) {
                	render_district_list(get_district_list(event.currentTarget.value), $('[name="permanentDistrict"]', '#modal_form_edit'));
                });

                $('[name="presentProvince"]', '#modal_form_edit').change(function(event) {
                	render_district_list(get_district_list(event.currentTarget.value), $('[name="presentDistrict"]', '#modal_form_edit'));
                });
            });

            function get_district_list(province_id)
            {
            	return _.where(districtsList, {"province_id": province_id});
            }

            function render_district_list(district_list, selectEl)
            {
            	$(selectEl).empty();
            	$('<option>').appendTo(selectEl);
            	$.each(district_list, function(index, value) {
					$('<option>').attr('value', value.id).html(value.name).appendTo(selectEl);
				});
            }

			function new_record()
			{
				save_method = 'new';
				$('#form', '#modal_form_edit')[0].reset(); // reset form on modals
				$('[name="permanentDistrict"]', '#modal_form_edit').empty();
				$('[name="presentDistrict"]', '#modal_form_edit').empty();

				// $.ajax({
				// 	url : "<?php echo site_url('prisoner/new_prisoner/')?>",
				// 	type: "GET",
				// 	dataType: "JSON",
				// 	success: function(data)
				// 	{
				// 		var groupsSelectEl = $('[name="group"]', '#modal_form_edit');
				// 		$.each(data, function(index, value) {
				// 			$('<option>').attr('value', value.id).html(value.group_name).appendTo(groupsSelectEl);
				// 		});

						$('#modal_form_edit').modal('show'); // show bootstrap modal when complete loaded
						$('.modal-title', '#modal_form_edit').text('Add New User'); // Set Title to Bootstrap modal title
				// 	},
				// 	error: function (jqXHR, textStatus, errorThrown)
				// 	{
				// 		alert('Error get data from ajax');
				// 	}
				// });
			}

            function view_record(id)
			{
				save_method = 'update';
				$('#form', '#modal_form_view')[0].reset(); // reset form on modals

				//Ajax Load data from ajax. Edit 11/28.
				$.ajax({
					url : "<?php echo site_url('prisoner/view/')?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data)
					{
						if(data.success === true) {
							$('p#id', '#modal_form_view').html(data.result.id);
							$('p#licenseNumber', '#modal_form_view').html(data.result.license_number);
							$('p#name', '#modal_form_view').html(data.result.name);
							$('p#middleName', '#modal_form_view').html(data.result.middle_name);
							$('p#lastName', '#modal_form_view').html(data.result.last_name);
							$('p#streetNum', '#modal_form_view').html(data.result.street_num);
							$('p#streetName', '#modal_form_view').html(data.result.street_name);
							$('p#apartmentNum', '#modal_form_view').html(data.result.apartment_num);
							$('p#city', '#modal_form_view').html(data.result.city);
							$('p#zipcode', '#modal_form_view').html(data.result.zipcode);
							$('p#phone', '#modal_form_view').html(data.result.phone);
							$('p#birthCity', '#modal_form_view').html(data.result.birth_city);
							$('p#birthCountry', '#modal_form_view').html(data.result.birth_country);
							$('p#ssn', '#modal_form_view').html(data.result.ssn);
							$('p#sex', '#modal_form_view').html(data.result.sex);
							$('p#heightFeet', '#modal_form_view').html(data.result.height_feet);
							$('p#heightInches', '#modal_form_view').html(data.result.height_inches);
							$('p#weight', '#modal_form_view').html(data.result.weight);
							$('p#age', '#modal_form_view').html(data.result.age);
							$('p#eyeColor', '#modal_form_view').html(data.result.eye_color);
							$('p#hairColor', '#modal_form_view').html(data.result.hair_color);
							$('p#numOfChildren', '#modal_form_view').html(data.result.num_of_children);
							$('p#propertyManagement', '#modal_form_view').html(data.result.property_management);
							$('p#criminalHistory', '#modal_form_view').html(data.result.criminal_history===1? '<?= $this->lang->line("yes"); ?>': '<?= $this->lang->line("no"); ?>');
							$('p#permanentProvince', '#modal_form_view').html(data.result.permanent_province);
							$('p#permanentDistrict', '#modal_form_view').html(data.result.permanent_district);
							$('p#presentProvince', '#modal_form_view').html(data.result.present_province);
							$('p#presentDistrict', '#modal_form_view').html(data.result.present_district);
							
							if(data.result.profile_pic !== '' && data.result.profile_pic !== null)
							{
								$('img#profilePic', '#modal_form_view').attr("src", photos_directory + '/' + data.result.profile_pic);
								$('img#profilePic', '#modal_form_view').attr("alt", 'Failed to display the photo.');
							}
							else
							{
								$('img#profilePic', '#modal_form_view').attr("alt", 'Profile photo is not uploaded.');
							}

							$('#modal_form_view').modal('show'); // show bootstrap modal when complete loaded
						} else {
							alert(data.message);
						}
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});
			}

			function edit_record(id)
			{
				save_method = 'update';
				$('#form', '#modal_form_edit')[0].reset(); // reset form on modals
				$('p#id', '#modal_form_edit').empty();
				$('[name="permanentDistrict"]', '#modal_form_edit').empty();
				$('[name="presentDistrict"]', '#modal_form_edit').empty();

				//Ajax Load data from ajax. Edit 11/28
				$.ajax({
					url : "<?php echo site_url('prisoner/edit/')?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data)
					{
						if(data.success === true) {
							$('p#id', '#modal_form_edit').html(data.result.prisoner.id);
							$('[name="id"]', '#modal_form_edit').val(data.result.prisoner.id);
							$('[name="licenseNumber"]', '#modal_form_edit').val(data.result.prisoner.license_number);
							$('[name="name"]', '#modal_form_edit').val(data.result.prisoner.name);
							$('[name="middleName"]', '#modal_form_edit').val(data.result.prisoner.middle_name);
							$('[name="lastName"]', '#modal_form_edit').val(data.result.prisoner.last_name);
							$('[name="streetNum"]', '#modal_form_edit').val(data.result.prisoner.street_num);
							$('[name="streetName"]', '#modal_form_edit').val(data.result.prisoner.street_name);
							$('[name="apartmentNum"]', '#modal_form_edit').val(data.result.prisoner.apartment_num);
							$('[name="city"]', '#modal_form_edit').val(data.result.prisoner.city);
							$('[name="zipcode"]', '#modal_form_edit').val(data.result.prisoner.zipcode);
							$('[name="phone"]', '#modal_form_edit').val(data.result.prisoner.phone);
							$('[name="birthCity"]', '#modal_form_edit').val(data.result.prisoner.birth_city);
							$('[name="birthCountry"]', '#modal_form_edit').val(data.result.prisoner.birth_country);
							$('[name="ssn"]', '#modal_form_edit').val(data.result.prisoner.ssn);
							$('[name="sex"]', '#modal_form_edit').val(data.result.prisoner.sex);
							$('[name="heightFeet"]', '#modal_form_edit').val(data.result.prisoner.height_feet);
							$('[name="heightInches"]', '#modal_form_edit').val(data.result.prisoner.height_inches);
							$('[name="weight"]', '#modal_form_edit').val(data.result.prisoner.weight);
							$('[name="age"]', '#modal_form_edit').val(data.result.prisoner.age);
							$('[name="eyeColor"]', '#modal_form_edit').val(data.result.prisoner.eye_color_id);
							$('[name="hairColor"]', '#modal_form_edit').val(data.result.prisoner.hair_color_id);
							$('[name="numOfChildren"]', '#modal_form_edit').val(data.result.prisoner.num_of_children);
							$('[name="propertyManagement"]', '#modal_form_edit').val(data.result.prisoner.property_management);
							$('[name="criminalHistory"]', '#modal_form_edit').prop('checked', (data.result.prisoner.criminal_history===1||data.result.prisoner.criminal_history==='1'? true: false));
							$('[name="permanentProvince"]', '#modal_form_edit').val(data.result.prisoner.permanent_province_id);

							var permanentDistrictsSelectEl = $('[name="permanentDistrict"]', '#modal_form_edit');
							render_district_list(data.result.permanentDistricts, permanentDistrictsSelectEl);

							$('[name="permanentDistrict"]', '#modal_form_edit').val(data.result.prisoner.permanent_district_id);
							$('[name="presentProvince"]', '#modal_form_edit').val(data.result.prisoner.present_province_id);

							var presentDistrictsSelectEl = $('[name="presentDistrict"]', '#modal_form_edit');
							render_district_list(data.result.presentDistricts, presentDistrictsSelectEl);

							$('[name="presentDistrict"]', '#modal_form_edit').val(data.result.prisoner.present_district_id);

							if(data.result.prisoner.profile_pic !== '' && data.result.prisoner.profile_pic !== null)
							{
								$('img#profilePicDisplay', '#modal_form_edit').attr("src", photos_directory + '/' + data.result.prisoner.profile_pic);
								$('img#profilePicDisplay', '#modal_form_edit').attr("alt", 'Failed to display the photo.');
							}
							else
							{
								$('img#profilePicDisplay', '#modal_form_edit').attr("alt", 'Profile photo is not uploaded.');
							}

							$('#modal_form_edit').modal('show'); // show bootstrap modal when complete loaded
							$('.modal-title', '#modal_form_edit').text('Edit User'); // Set Title to Bootstrap modal title
						} else {
							alert(data.message);
						}
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error get data from ajax');
					}
				});
			}

			function delete_record(id)
			{
				if(confirm('Are you sure delete this data?'))
				{
					// ajax delete data to database
					$.ajax({
						url : "<?php echo site_url('prisoner/delete')?>/"+id,
						type: "POST",
						dataType: "JSON",
						success: function(data)
						{
							if(data.success === true) {
								//if success reload ajax table
								$('#modal_form_edit').modal('hide');
								reload_table();
							} else {
								alert(data.message);
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error adding / update data');
						}
					});

				}
			}

			function lock_record(id)
			{
				if(confirm('Are you sure to lock this data?'))
				{
					// ajax delete data to database
					$.ajax({
						url : "<?php echo site_url('prisoner/lock')?>/"+id,
						type: "GET",
						dataType: "JSON",
						success: function(data)
						{
							if(data.success === true) {
								reload_table();
							} else {
								alert(data.message);
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error adding / update data');
						}
					});

				}
			}

			function unlock_record(id)
			{
				if(confirm('Are you sure to unlock this data?'))
				{
					// ajax delete data to database
					$.ajax({
						url : "<?php echo site_url('prisoner/unlock')?>/"+id,
						type: "GET",
						dataType: "JSON",
						success: function(data)
						{
							if(data.success === true) {
								reload_table();
							} else {
								alert(data.message);
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert('Error adding / update data');
						}
					});

				}
			}

			function reload_table()
			{
				oTable.ajax.reload(null,false); //reload datatable ajax
			}

			function save_record()
			{
				var url;
				if(save_method == 'new')
				{
					url = "<?php echo site_url('prisoner/add')?>";
				}
				else
				{
					url = "<?php echo site_url('prisoner/update')?>";
				}

				var formData = new FormData($('#form', '#modal_form_edit')[0]);

				// ajax adding data to database
				$.ajax({
					url : url,
					type: "POST",
					data: formData,
					mimeType: "multipart/form-data",
					contentType: false,
					cache: false,
					processData: false,
					success: function(data)
					{
						data = JSON.parse(data);
						if(data.success === true)
						{
							$('#modal_form_edit').modal('hide');
							reload_table();
						}
						else
						{
							alert(data.message);
						}
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Error adding / update data');
					}
				});

				// ajax adding data to database
				// $.ajax({
				// 	url : url,
				// 	type: "POST",
				// 	data: $('#form', '#modal_form_edit').serialize(),
				// 	dataType: "JSON",
				// 	success: function(data)
				// 	{
				// 		//if success close modal and reload ajax table
				// 		$('#modal_form_edit').modal('hide');
				// 		reload_table();
				// 	},
				// 	error: function (jqXHR, textStatus, errorThrown)
				// 	{
				// 		alert('Error adding / update data');
				// 	}
				// });
			}
        </script>

        <!-- Bootstrap modal View Edit 11/28 -->
		<div class="modal fade" id="modal_form_view" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">View User</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form" class="form-horizontal">
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('id'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="id"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('license_number'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="licenseNumber"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('name'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="name"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('middle_name'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="middleName"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('last_name'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="lastName"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('street_num'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="streetNum"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('street_name'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="streetName"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('apartment_num'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="apartmentNum"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('city'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="city"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('zipcode'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="zipcode"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('phone'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="phone"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('birth_city'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="birthCity"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('birth_country'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="birthCountry"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('ssn'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="ssn"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('sex'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="sex"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('height_feet'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="heightFeet"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('height_inches'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="heightInches"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('weight'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="weight"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('age'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="age"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('eye_color'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="eyeColor"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('hair_color'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="hairColor"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('num_of_children'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="numOfChildren"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('property_management'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="propertyManagement"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('criminal_history'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="criminalHistory"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('permanent_province'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="permanentProvince"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('permanent_district'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="permanentDistrict"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('present_province'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="presentProvince"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('present_district'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="presentDistrict"></p>
								</div>
							</div>
							<div class="form-group">
								<!-- <label class="control-label col-sm-4">Profile Photo</label> -->
								<div class="col-sm-12">
									<div class="thumbnail">
										<img id="profilePic" alt="Profile Photo not exist" class="img-rounded">
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<!-- End Bootstrap modal -->

		<!-- Bootstrap modal Edit 11/28-->
		<div class="modal fade" id="modal_form_edit" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="modal-title">Edit User</h3>
					</div>
					<div class="modal-body form">
						<form action="#" id="form" class="form-horizontal">
							<input type="hidden" value="" name="id"/>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('id'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static" id="id"></p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('license_number'); ?></label>
								<div class="col-sm-8">
									<input name="licenseNumber" placeholder="License Number" class="form-control" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4"><?= $this->lang->line('name'); ?></label>
								<div class="col-md-8">
									<input name="name" placeholder="Name" class="form-control" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-4"><?= $this->lang->line('middle_name'); ?></label>
								<div class="col-md-8">
									<input name="middleName" placeholder="Middle Name" class="form-control" type="text">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('last_name'); ?></label>
								<div class="col-sm-8">
									<input name="lastName" placeholder="Last Name" class="form-control" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('street_num'); ?></label>
								<div class="col-sm-8">
									<input name="streetNum" placeholder="Street Number" class="form-control" type="number">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('street_name'); ?></label>
								<div class="col-sm-8">
									<input name="streetName" placeholder="Street Name" class="form-control" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('apartment_num'); ?></label>
								<div class="col-sm-8">
									<input name="apartmentNum" placeholder="Apartment Number" class="form-control" type="number">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('city'); ?></label>
								<div class="col-sm-8">
									<input name="city" placeholder="City" class="form-control" type="text">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('zipcode'); ?></label>
								<div class="col-sm-8">
									<input name="zipcode" placeholder="Zipcode" class="form-control" type="number">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('phone'); ?></label>
								<div class="col-sm-8">
									<input name="phone" placeholder="Phone Number" class="form-control" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('birth_city'); ?></label>
								<div class="col-sm-8">
									<input name="birthCity" placeholder="Birth City" class="form-control" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('birth_country'); ?></label>
								<div class="col-sm-8">
									<input name="birthCountry" placeholder="Birth Country" class="form-control" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('ssn'); ?></label>
								<div class="col-sm-8">
									<input name="ssn" placeholder="SSN" class="form-control" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('sex'); ?></label>
								<div class="col-sm-8">
									<input name="sex" placeholder="Sex" class="form-control" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('height_feet'); ?></label>
								<div class="col-sm-8">
									<input name="heightFeet" placeholder="Height Feet" class="form-control" type="number">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('height_inches'); ?></label>
								<div class="col-sm-8">
									<input name="heightInches" placeholder="Height Inches" class="form-control" type="number">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('weight'); ?></label>
								<div class="col-sm-8">
									<input name="weight" placeholder="Weight" class="form-control" type="number">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('age'); ?></label>
								<div class="col-sm-8">
									<input name="age" placeholder="Age" class="form-control" type="number">
								</div>
							</div>
							<div class="form-group has-error">
								<label class="control-label col-sm-4"><?= $this->lang->line('eye_color'); ?></label>
								<div class="col-sm-8">
									<select name="eyeColor" class="form-control" class="form-control">
										<option></option>
										<?php foreach ($eyeColorList as $key => $value) {
											echo "<option value='" . $value->id . "'>" . $value->status . "</option>";
										} ?>
									</select>
								</div>
							</div>
							<div class="form-group has-error">
								<label class="control-label col-sm-4"><?= $this->lang->line('hair_color'); ?></label>
								<div class="col-sm-8">
									<select name="hairColor" class="form-control" class="form-control">
										<option></option>
										<?php foreach ($hairColorList as $key => $value) {
											echo "<option value='" . $value->id . "'>" . $value->status . "</option>";
										} ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('num_of_children'); ?></label>
								<div class="col-sm-8">
									<input name="numOfChildren" placeholder="Number of Children" class="form-control" type="number">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"><?= $this->lang->line('property_management'); ?></label>
								<div class="col-sm-8">
									<input name="propertyManagement" placeholder="Property Management" class="form-control" type="text">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"></label>
								<div class="col-sm-8">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="criminalHistory"> <?= $this->lang->line('criminal_history'); ?>
										</label>
									</div>
								</div>
							</div>
							<div class="form-group has-error">
								<label class="control-label col-sm-4"><?= $this->lang->line('permanent_province'); ?></label>
								<div class="col-sm-8">
									<select name="permanentProvince" class="form-control" class="form-control">
										<option></option>
										<?php foreach ($provincesList as $key => $value) {
											echo "<option value='" . $value->id . "'>" . $value->name . "</option>";
										} ?>
									</select>
								</div>
							</div>
							<div class="form-group has-error">
								<label class="control-label col-sm-4"><?= $this->lang->line('permanent_district'); ?></label>
								<div class="col-sm-8">
									<select name="permanentDistrict" class="form-control" class="form-control">
									</select>
								</div>
							</div>
							<div class="form-group has-error">
								<label class="control-label col-sm-4"><?= $this->lang->line('present_province'); ?></label>
								<div class="col-sm-8">
									<select name="presentProvince" class="form-control" class="form-control">
										<option></option>
										<?php foreach ($provincesList as $key => $value) {
											echo "<option value='" . $value->id . "'>" . $value->name . "</option>";
										} ?>
									</select>
								</div>
							</div>
							<div class="form-group has-error">
								<label class="control-label col-sm-4"><?= $this->lang->line('present_district'); ?></label>
								<div class="col-sm-8">
									<select name="presentDistrict" class="form-control" class="form-control">
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-4"><?= $this->lang->line('profile_pic'); ?></label>
								<div class="col-sm-8">
									<input name="profilePic" placeholder="Number of Children" class="form-control" type="file" size="20">
								</div>
							</div>
							<div class="form-group">
								<!-- <label class="control-label col-sm-4">Profile Photo</label> -->
								<div class="col-sm-12">
									<div class="thumbnail">
										<img id="profilePicDisplay" alt="Profile Photo" class="img-rounded">
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSave" onclick="save_record()" class="btn btn-primary">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<!-- End Bootstrap modal -->
		<?php $this->load->view('footer'); ?>
	</body>
</html>