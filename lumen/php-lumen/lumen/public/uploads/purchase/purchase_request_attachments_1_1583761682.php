<?php
	
//	echo "Title = $pageTitle <br>";
//	echo "tbl_name = $tbl_name <br>";
//	echo "types = $types <br>";
//	echo "default = $default <br>";
//	echo "template_name = $template_name <br>";
//	echo "selected_type = $selected_type <br>";
//	echo "data = $fb_jsondata <br>";
//	echo "paneltitle = $paneltitle <br>";
//	print_r($fbdata);
//	echo " <br>";
?>

<!-------------------------
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
<script src="https://formbuilder.online/assets/js/form-render.min.js"></script>
------------------------->

<script src="<?php echo $this->config->item("base_url"); ?>bstheme/formbuilder/jquery-ui.min.js"></script>
<script src="<?php echo $this->config->item("base_url"); ?>bstheme/formbuilder/form-builder.min.js"></script>
<script src="<?php echo $this->config->item("base_url"); ?>bstheme/formbuilder/form-render.min.js"></script>

<style>
	#fb-render .form-control{font-weight: normal;font-size: 13px;}
</style>

<input type="hidden" name="is_superadmin" id="is_superadmin" value="<?php echo $is_admin; ?>">
<header id="topbar" style="position: relative !important;">
	<div class="topbar-left">
    	<ol class="breadcrumb">
        	<li class="crumb-active">
            	<a href="#"><?=$pageTitle?></a>
        	</li>
	        <li class="crumb-icon">
	            <a href="<?php echo $this->config->item('user_controller_path'); ?>manage/dashboard/">
	                <span class="glyphicon glyphicon-home"></span>
	            </a>
	        </li>
	        <li class="crumb-trail"><?=$pageTitle?></li>
	    </ol>
	</div>
	<?php if($template_name == "ssosettings")
	{ ?>
		<div class="topbar-right hide">
			<div class="btn-group optional_dropdown" data-tooltip="tooltip" data-placement="left" title="Type">
				<button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<span class="glyphicons glyphicons-show_lines fs16"></span>
				</button>
				<ul class="dropdown-menu pull-right" role="menu">
					
				</ul>
			</div>
		</div><?php 
	} ?>
</header>

<div id="content">
	<div class="row">
		<div class="col-md-12">
			<!-- ============================= Outer Panel ============================= -->
			<div class="panel">				
		        <div class="panel-heading">
		            <span class="panel-title type"><?=$paneltitle?></span>
		        </div>
		        <div class="panel-body" style="width:100%;overflow:auto; ">
		        	
		        	<form id="fb-render"></form>
		        </div>
		    </div>
		    <!-- End of "Outer Panel" -->
		</div>
	</div>
</div>


<script type="text/javascript">
	var module_name   = "<?=$tbl_name;?>";
	var typewithtitle = "<?=$typewithtitle;?>";
	var types 		  = "<?=$types;?>";
	var default_type  = "<?=$default;?>";
	var selected_type = "<?=$selected_type;?>";
	var sel_type_exist= true;
	var tmp_types 	  = [];
	var tmp_title 	  = [];
	
	if(selected_type == "") selected_type = default_type;
	
	$(document).ready(function(){
		//check if selected_type does not exist then load default page
//		if(selected_type != "")
//		{
			tmp_types = types.split(",");
			if(typewithtitle == true)
			{
				var tmptyp     = [];
				var tmptitl    = [];
				var tmp1;
				for(var i=0; i<tmp_types.length;i++)
				{
					tmp1   	   = tmp_types[i];
					tmp1   	   = tmp1.split(":");
					tmptyp[i]  = tmp1[0];
					tmptitl[i] = tmp1[1];
				}
				tmp_types  	   = tmptyp;
				tmp_title  	   = tmptitl;
			}
			
			if(tmp_types.length > 0 && selected_type != "" && tmp_types.indexOf(selected_type) == -1)
			{
				sel_type_exist = false;
			}
//		}
		
		<?php if($template_name == "ssosettings") { ?>//todo
			if(sel_type_exist == false && selected_type == default_type && tmp_types.length > 0){
				
				selected_type = tmp_types[0];
				window.location.href = '<?php echo $this->config->item("admin_controller_path"); ?>manage/ssosettings/'+selected_type;
				return false;
			}else if(tmp_types.length == 0){
				window.location.href = '<?php echo $this->config->item("admin_controller_path"); ?>user/manage/dashboard';
				return false;
			}
				
			if(sel_type_exist == false){
				window.location.href = '<?php echo $this->config->item("admin_controller_path"); ?>manage/ssosettings';
				return false;
			}
			
			//set 'sso_setting.type' options in dropdown
			if(types.trim() != ""){
//				var tmp_types = types.split(",");
				
				if(tmp_types.length > 0){
					var class_name = "";
					for (var i=0;i<tmp_types.length;i++){
						var tmptyp = tmp_types[i];
						
						
						if(tmptyp == selected_type) class_name = "active";
						else class_name = "";
						
						var onclick_action = "load_formbuilder_template('"+module_name+"','"+tmptyp+"');";
						var href_action = '<?php echo $this->config->item("admin_controller_path"); ?>manage/ssosettings/'+tmptyp;
						
						if(tmp_title.length == tmp_types.length){
							$(".topbar-right ul.dropdown-menu").append("<li class='"+class_name+"'><a href='"+href_action+"'>"+tmp_title[i]+"</a></li>");
						}
						else{
							$(".topbar-right ul.dropdown-menu").append("<li class='"+class_name+"'><a href='"+href_action+"'>"+tmptyp+"</a></li>");
						}
					}
					$(".topbar-right").removeClass("hide").show();
				}
			}
		<?php  }?>
		
		/*
		//set panel title and check if selected_type does not exist then load default page
		if(selected_type != ""){
			var tmp_types = types.split(",");
			if(tmp_types.length > 0) {
				if(tmp_types.indexOf(selected_type) > -1) $(".panel-title.type").html(selected_type);
				else $(".panel-title.type").html(default_type);
			}
			else $(".panel-title.type").html("<?=$paneltitle?>");
		}else{
			$(".panel-title.type").html("<?=$paneltitle?>");
		}
		*/
		
		//render template
		var fbRender = document.getElementById('fb-render');
		var formRenderOpts = {
			formData: JSON.stringify(<?=$fb_jsondata?>),
			dataType: 'json'
		};
		$(fbRender).formRender(formRenderOpts);
		getformdata();
		
		$(".empty_para_min_height").css("min-height","65px");
		$(".fb-radio input.col-md-6").removeClass("col-md-6");
	});
	
	//onclick of options in dropdown, load related form
	function load_formbuilder_template(module_name,sel_type){
		window.location.href = '<?php echo $this->config->item("admin_controller_path"); ?>manage/'+module_name+'/'+sel_type;
	}
	
	//fetch data
	function getformdata(){
		if(module_name != ""){
			var getdataurl = "";
			
			switch(module_name){
				case "sso_setting":
					if(sel_type_exist) getdataurl = '<?php echo $this->config->item("admin_controller_path"); ?>manage/getdata_ssosetting/'+selected_type;
					break;
			}
			
			//------------------------
			if(getdataurl != ""){
				$.ajax({
					type: 'POST',
					url: getdataurl,
					cache: false,
					sync:false,
					success: function (response) {
						try {
							if(response){
								response = JSON.parse(response);
								setformdata(response[0]);
								
								//....................
								$('.fb-radio-group input[type=radio]').each(function(){
									if($(this).attr('value') == undefined && $(this).siblings('label:eq(0)').text() == "No") $(this).attr('value','false')
								});
								//....................
							}
						} catch (e) {
							console.log(response);
							return false;
						}
					}
				});
			}
			//------------------------
		}
	}
	
	//set data into form
	function setformdata(json_data){	//json key = html element id, value = element value
		
		if(module_name == "sso_setting"){
//			console.log(json_data);
			$.each(json_data, function(i, value) {
				if(i != undefined && ($("#fb-render #"+i.trim()).length > 0 || $("#fb-render [name='"+i+"']").length > 0)){
					switch(i){
						case "status":	//for radio buttons
						case "smtp_auth":
						case "smtp_status":
						case "use_ssl":
						case "use_tls":
						case "billing":
						case "billing_module":
						case "import_frm_billing":
						case "sso_adldap":
						case "sso_db":
						case "otp_enabled":
						case "captcha_enabled":
						case "maintainance":
						case "logs_enable":
							if((value == false || value == "false") && $("#fb-render [name='"+i+"'][value='true']").length == 1){
								$("#fb-render [name='"+i+"']").each(function(){
									if($(this).attr("value") == undefined) $(this).attr("value","false");
								});
							}
							i = $("#fb-render [name='"+i+"'][value='"+value+"']").attr('id');
						break;
						case "group_container":
						case "group_container_operator":
							var jdata = value;//JSON.parse(value);
							value	  = "";
							$.each(jdata,function(k,v){
								if(value == "") value = v;
								else value = value + "," + v;
							});
							
						break;
						case "configuration":	//for configuration
							if(value != ""){
								var jdata = JSON.parse(value);
								setformdata(jdata);
							}
						break;
					}
					putdata(i,value);
				}
			});
			
			$("#save_setting").unbind("click").click(function(){
				save_ssosetting();return false;
			});
		}else{
			$.each(json_data, function(i, value) {
				if($("#fb-render #"+i.trim()).length > 0){
					putdata(i,value);
				}
			});
		}
	}
	
	//put data in form fields
	function putdata(id,value){
		if(id != undefined && $("#"+id.trim()).length == 1){
			var ele 	 = $("#"+id.trim());
//			if(ele.length == 0) ele = $('[name="'+id+'"]');
			var ele_type = ele.attr("type");
			
			switch(ele_type){
				case "text":
				case "hidden":
				case "password":
					ele.val(value);
				break;
				
				case "radio":
					//$.each(ele,function(key,obj){
					//	if(obj.value == value) ele.prop("checked",true);
					//});
					ele.prop("checked",true);
				break;
				
			}
		}
	}
	
	function save_ssosetting(){
		var frm_data = {};
		var config   = get_ssosetting_configdata();
		$("#configuration").val(config);
		
		
		$(".db_field").each(function(){
			switch($(this).attr("type")){
				case "text":
				case "hidden":
				case "password":
					if($(this).attr("name") != undefined && $(this).attr("name") != ""){
						frm_data[$(this).attr("name")] = $(this).val();
					}
				break;
				case "radio":
					if($(this).prop('checked')){
						if($(this).attr("name") != undefined && $(this).attr("name") != ""){
							
							var radio_val = $(this).val();
							if($(this).siblings('label:eq(0)').text() == "No" && $(this).attr("value") == undefined) radio_val = false;
							
							frm_data[$(this).attr("name")] = radio_val;
						}
					}
				break;
			}
		});
		
		if(Object.keys(frm_data).length > 0){
			frm_data = JSON.stringify(frm_data);
			url		 = '<?php echo $this->config->item("admin_controller_path"); ?>manage/savedata_ssosetting';
			
			$.ajax({
				type: 'POST',
				url: url,
				data:{frm_data:frm_data},
				cache: false,
				sync:false,
				success: function (response) {
					try {
						if(response){
							//response = JSON.parse(response);
							console.log(response);
							
							if(response > 0) alert("Record saved successfully.");
							else alert("Could not save record.");
						}
					} catch (e) {
						console.log(response);
						return false;
					}
				}
			});
		}
		
	}
	
	function get_ssosetting_configdata(){
		var config_data = "";
		$(".config_data").each(function(){
			switch($(this).attr("type")){
				case "text":
				case "hidden":
				case "password":
					var val = '"' + $(this).val() + '"';
					
					if($(this).attr("name") == "group_container" || $(this).attr("name") == "group_container_operator"){
						val = $(this).val();
						val = val.split(",");
						val1= '';
						$.each(val, function(k,v){
							if(val1 == "") val1 = '"'+k+'"' +':'+ '"'+v+'"';
							else val1 = val1 + ',"'+k+'"' +':'+ '"'+v+'"';
						})
						val = '{'+val1+'}';
					}
					
					if(config_data == "") config_data = '"'+$(this).attr("name")+'":'+val;
					else config_data = config_data + ',"'+$(this).attr("name")+'":'+val;
				break;
				case "radio":
					if($(this).prop('checked')){
						var radio_val = '"' + $(this).val() + '"';
						
						if($(this).val() == "true" || $(this).val() == "false") radio_val = $(this).val();
						
						if($(this).siblings('label:eq(0)').text() == "No" && $(this).attr("value") == undefined) radio_val = "false";
						
						if(config_data == "") config_data = '"'+$(this).attr("name")+'":'+radio_val;
						else config_data = config_data + ',"'+$(this).attr("name")+'":'+radio_val;
					}
				break;
			}
		});
		if(config_data != "") config_data = "{" + config_data + "}";
		return config_data;
	}
</script>
