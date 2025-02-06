	var myajax;
	$(document).ready(function(){
		emLoader('show', 'Loading Dashboard...');
		loaddashboard();
		var alert_int;
		var stats_int;
	});	
	function loaddashboard(type)
	{
		$("div[name=menu_bullets]").removeClass("slider_bullet_select");
		$("div[name=menu_bullets]").addClass("slider_bullet");
		$("#div_bullet_"+type).removeClass("slider_bullet");
		$("#div_bullet_"+type).addClass("slider_bullet_select");
		var ajax_board = new sack();
		ajax_board.setVar('type',type);
		srchfilter(ajax_board);
		ajax_board.requestFile = SITE_URL+'user/manage/setdashboard/';
		ajax_board.method = 'POST';
		ajax_board.onCompletion = function()
		{
			if (ajax_board.response != '')
			{
				inner_html('spn_dashboard_data',ajax_board.response);
				$( ".column" ).sortable({
							connectWith: ".column",
							handle: ".portlet-header",
							cancel: ".portlet-toggle",
							placeholder: "portlet-placeholder"
				});
				$( ".portlet-delete" ).click(function() {
					//if (confirm("Are you want to hide this box?"))
					//{
						var mydel = $(this);
						mydel.closest(".portlet").find(".portlet-content").toggle();
						if (mydel.html() == "-")
							mydel.html("+");
						else
							mydel.html("-");
					//}
				});
				$('#column1,#column2').sortable({
				  	update: function(event)
					{
						var type_orderby = '';
						var c1_orderby = '';
						$('#column1').find('.portlet').each(function() {
							c1_orderby += '"'+$(this).attr('myid')+'",';
						});
						c1_orderby = '"c1_order":['+c1_orderby.substring(0,c1_orderby.length-1)+']';
						//console.log("C1: "+c1_orderby);
						
						var c2_orderby = '';
						$('#column2').find('.portlet').each(function() {
							c2_orderby += '"'+$(this).attr('myid')+'",';
						});
						c2_orderby = '"c2_order":['+c2_orderby.substring(0,c2_orderby.length-1)+']';
						//console.log("C2: "+c2_orderby);
						type_orderby = c1_orderby+','+c2_orderby;
						console.log("Order: "+type_orderby);
						updateorder(type_orderby,type);
					}
				});
				if (type == 'main')
					$("#dashboard_legend").html("Home Dashboard");
				else if (type == 'cto')
					$("#dashboard_legend").html("Management Dashboard");
				else
					$("#dashboard_legend").html(type+" Monitoring");
				$(".portlet").hide();
				//setTimeout(function(){			
						/*		 	
						var str = 'ind11,ind1,ind4,ind5';
						orderArray = str.split(",");
						reorder(orderArray, $("#column2"));
						*/
					<?php
						if (is_array($user_dashboard_js) && count($user_dashboard_js) > 0)
						{
							foreach($user_dashboard_js as $dtype => $user_dashboard_js_row)
							{
								if ($dtype == 'topology')
								{
									if ($show_topology == true)
									{
										if ($user_dashboard_js_row['id'] > 0)
										{
						?>
											$( "div[name="+type+"]" ).show();
						<?php
											if ($user_dashboard_js_row['type'] == 'server' || $user_dashboard_js_row['type'] == 'network')
											{
											$topourl = $this->config->item('network_controller_path')."diagram/diagramview/".$user_dashboard_js_row['id']."/large/".trim($user_dashboard_js_row['type'])."/";
											}
											else if ($user_dashboard_js_row['type'] == 'custom')
											{
												$topourl = $this->config->item('network_controller_path')."diagram/index/".$user_dashboard_js_row['id']."/";
											}
											echo "el('iframe_topology').src = '".$topourl."';";
										}
										else
										{
								?>
											inner_html('spn_dashboard_data','<br /><p align=left><a href="javascript: void(0);" onclick="javascript: showfilters(&quot;topology&quot;);">Click Here</a> to select Network Topology</p>');											
								<?php
										}
									}
									else
									{
								?>
										inner_html('spn_dashboard_data','<br /><p align=left>No Data or please check your dashboard settings.<br />To update dashboard settings <a href="<?php echo $this->config->item('user_controller_path');?>manage/userdashboard/" target="_blank">click here</a></p>');
								<?php
									}
								}
								else
								{
						?>
									var jqueryeffects = ["fold","slide","shake","clip","drop","blind","size"];
									var randoneffect = jqueryeffects[Math.floor(Math.random() * jqueryeffects.length)];
									$( "div[name="+type+"]" ).show(randoneffect,2000); //fold,shake,slide,pulsate,clip,drop,blind
						<?php
									echo 'if (type == "'.$dtype.'")';
									echo '{';
									if (is_array($user_dashboard_js_row) && count($user_dashboard_js_row) > 0)
									{
										foreach($user_dashboard_js_row as $row)
										{
											$id = 'dashboard_'.$row['dashboard_id'];
											echo 'inner_html("'.$id.'",textloader("Loading..."));';
											if ($row['jsfunction'] != '')
												echo $row['jsfunction'].'("'.$id.'","'.$dtype.'","'.$row['param'].'");';
										}
									}
									echo '}';
								}
							}
						}
					?>
					//console.log($("div[name=menu_bullets]"));
					lightbox('hide','');
					main_loader('hide','');
				//},1000);
				//setTimeout("main_loader('hide','');",2000);
			}
			else
			{
				inner_html('spn_dashboard_data','No Data or please check your dashboard settings.<br />To update dashboard settings <a href="<?php echo $this->config->item('user_controller_path').'manage/userdashboard/'; ?>" target="_blank">click here</a>.');
				main_loader('hide','');
			}
		}
		ajax_board.runAJAX();
	}
	function logs()
	{
		emLoader('show', 'Loading Logs...');
		var url = SITE_URL+'/boots/logsdata/';
		var postData = $("#frmlog").serialize();
		var exporttype = $("#frmlog input[name=exporttype]").val();
		if (exporttype == 'pdf' || exporttype == 'csv')
		{
			var obj_form = document.frmlog;
			var mywindow = submitForm(url,obj_form);	
			$("#frmlog input[name=exporttype]").val('');
			$("#frmlog input[name=page]").val('');
			emLoader('hide');
		}
		else
		{
			emgridTopDisable('#frmlog');
			myajax = ajaxCall(myajax,url,postData,function(data){$("#logs_data").html(data);emgridTopEnable('#frmlog');emLoader('hide');});
		}
	}