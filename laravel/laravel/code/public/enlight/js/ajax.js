	// JavaScript Document
	var ajaxtimeout = 0; // do not use same varible for any other functionality
	function emLoaderText(id,txt)
	{
		if (txt == '' || txt == undefined || txt == false)
			txt = 'Loading...';
		$('#'+id).html(txt);
		return true;
	}
	function emLoaderTextClear(id)
	{
		$('#'+id).html('');
		return true;
	}
	function emLoader(flg,msg,id)
	{
		if (id == false || id == undefined)
			id = 'main';
		//if (msg == undefined || msg == '')
           msg = trans('label.lbl_loading');
		if (flg == 'show')
        {
        	if (id == 'main')
        	{
				$('#'+id).block({
					css: {
							border: 'none',
							backgroundColor: 'yellow',
							width: 'auto',
							left: '80%',
							color: '#000',
							padding: '3px 8px 3px 8px',
							'box-shadow': '5px 6px 20px rgb(51, 51, 51)',
							'-webkit-border-radius': '5px',
							'-moz-border-radius': '5px',
							'border-radius': '5px',
							top: '100px',
							position: 'fixed'
						 },
					overlayCSS:  {
						backgroundColor:	'#000',
						opacity:			0.7,
						cursor:				'wait',
						position: 			'fixed',
						height:				'105%'
					},
					message: msg
				});

				$('.blockUI.blockOverlay:visible').css("position","fixed");
			}
			else
			{
				$('#'+id).block({
					css: {
							border: 'none',
							backgroundColor: 'yellow',
							width: 'auto',
							color: '#000',
							padding: '3px 8px 3px 8px',
							'box-shadow': '5px 6px 20px rgb(51, 51, 51)',
							'-webkit-border-radius': '5px',
							'-moz-border-radius': '5px',
							'border-radius': '5px',
							top: '100px',
							position: 'absolute'
						 },
					overlayCSS:  {
						backgroundColor:	'#000',
						opacity:			0.7,
						cursor:				'wait',
					},
					message: msg
				});
			}
		}
		else if (flg == 'hide')
        {
			if($('#'+id).length)
			{
				$('#'+id).unblock();
			}

		}
	}
	//async:false,
	function ajaxCall(ajaxreturn, ajaxurl, postdata, callback_s, callback_e='ajaxError', tmout=300000)//tmout=300000(5 min)
	{
		ajaxreturn = $.ajax({
			type: 'POST',
			url: ajaxurl,
            timeout: tmout,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
			data: postdata,
			success: function (data) {
				var fn = new Function(callback_s(data));
				if (typeof fn === "function") fn();
				setTimeout(function() {
				   $("#emgridadvsearch").removeClass('hide');
			   	}, 800);
			},
			error: callback_e,
			beforeSend : function() {
				if(ajaxreturn != null) {
					ajaxreturn.abort();
				}
			},
		});
		return ajaxreturn;
	}
	function ajaxCall_po(ajaxreturn, ajaxurl, postdata, callback_s, callback_e='ajaxError', tmout=300000)//tmout=300000(5 min)
	{
		ajaxreturn = $.ajax({
			type: 'POST',
			url: ajaxurl,
			async: false,
            timeout: tmout,
            async:false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
			data: postdata,
			success: function (data) {
				var fn = new Function(callback_s(data));
				if (typeof fn === "function") fn();
				setTimeout(function() {
				   $("#emgridadvsearch").removeClass('hide');
			   	}, 800);
			},
			error: callback_e,
			beforeSend : function() {
				if(ajaxreturn != null) {
					ajaxreturn.abort();
				}
			},
		});
		return ajaxreturn;
	}
	//async:false,
	function ajaxCall_test(ajaxreturn, ajaxurl, postdata, callback_s, callback_e='ajaxError', tmout=300000)//tmout=300000(5 min)
	{
		ajaxreturn = $.ajax({
			 type: "POST",
                    url: ajaxurl,
                    data: postdata,
                    async:false,
                    processData: false,
                    contentType: false,
            timeout: tmout,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
			//data: postdata,
			success: function (data) {
				var fn = new Function(callback_s(data));
				if (typeof fn === "function") fn();
				setTimeout(function() {
				   $("#emgridadvsearch").removeClass('hide');
			   	}, 800);
			},
			error: callback_e,
			beforeSend : function() {
				if(ajaxreturn != null) {
					ajaxreturn.abort();
				}
			},
		});
		return ajaxreturn;
	}
	function ajaxError(jqXHR, exception)
	{
		emLoader('hide');
		var msg = '';
		if (jqXHR.status === 0) {
			//msg = 'Ajax: Not connect.\n Verify Network.';
		} else if (jqXHR.status == 404) {
			msg = 'Ajax: Requested page not found. [404]';
		} else if (jqXHR.status == 500) {
			msg = 'Ajax: Internal Server Error [500].';
		} else if (exception === 'parsererror') {
			msg = 'Ajax: Requested JSON parse failed.';
		} else if (exception === 'timeout') {
			msg = 'Ajax: Time out error.';
		} else if (exception === 'abort') {
			msg = 'Ajax: Request aborted.';
		} else {
			msg = 'Ajax: Uncaught Error.\n' + jqXHR.responseText;
		}
		if(msg != '')
		{
			alert(msg);
		}
	}
	function setLimit(fr,fun_call)
	{
		var fn = new Function(fun_call);
		if (typeof fn === "function") fn();
	}
	function setPage(fr,fun_call)
	{
		var fn = new Function(fun_call);
		var pagedata = $(fr).attr("pagedata");
		var form_obj = $(fr).closest("form").attr('id');
		$("#"+form_obj+" input[name=page]").val(pagedata);
		if (typeof fn === "function") fn();
	}
	function setCurrentPage(form_obj,fun_call)
	{
		$("#"+form_obj+" input[name=page]").val($("#"+form_obj+" .pagination > li.active > a").html() - 1);
		var fn = new Function(fun_call);
		if (typeof fn === "function") fn();
	}
	function searchRecords(fun_call)
	{
		var fn 		 = new Function(fun_call);
		var srchtext = $('#searchkeyword').val();
		
		if(srchtext != "" && srchtext != undefined) $('#searchkeyword').val(srchtext.replace(/^\s+/,"")); //left spaces trim
		
		clearTimeout(ajaxtimeout);
		if ((srchtext.trim() != "" && srchtext.length > 2) || srchtext.length == 0)
		{
			ajaxtimeout = setTimeout(function(){
				fn();
			},1000);
			// ajaxtimeout = setTimeout(fn(),3000);
        }
        return false;
	}
	function exportFile(fr, fun_call, type)
	{
		var fn = new Function(fun_call);
		var form_obj = $(fr).closest("form").attr('id');
		$("#"+form_obj+" input[name=exporttype]").val(type);
		$("#"+form_obj+" input[name=page]").val($("#"+form_obj+" .pagination > li.active > a").html() - 1);
		if (typeof fn === "function") fn();
	}
	function submitForm(url,obj,width,height)
	{
		if (width == '' || width == false || width == undefined)
			width = 1150;
		if (height == '' || height == false || height == undefined)
			height = 700;
		var d = new Date();
		var newwnd = d.getTime()
		var mywindow = window.open('',newwnd,"location=0,status=0,scrollbars=1,width="+width+",height="+height+",menubar=0");
		obj.target = newwnd;
		obj.action = url;
		obj.submit();
		return mywindow;
	}
	function closeWindow(mywindow, interval=5000) // interval 3 seconds
	{
		setTimeout(function(){
			if (mywindow != null)
			{
				mywindow.close();
			}
		}, interval);
	}
	function emgridTopDisable(fr)
	{
		var form_obj = $(fr).closest("form").attr('id');
		//$("#"+form_obj+" input[id=srchtext]").prop('disabled', true);
		$("#"+form_obj+" button[id=icon_gridpdf]").prop('disabled', true);
		$("#"+form_obj+" button[id=icon_gridcsv]").prop('disabled', true);
		$("#"+form_obj+" button[id=icon_gridprint]").prop('disabled', true);
	}
	function emgridTopEnable(fr)
	{
		var form_obj = $(fr).closest("form").attr('id');
		//$("#"+form_obj+" input[id=srchtext]").prop('disabled', false);
		$("#"+form_obj+" button[id=icon_gridpdf]").prop('disabled', false);
		$("#"+form_obj+" button[id=icon_gridcsv]").prop('disabled', false);
		$("#"+form_obj+" button[id=icon_gridprint]").prop('disabled', false);
	}
	function emExport(type, obj_form)
 	{
 		if (type == 'pdf' || type == 'csv')
 		{
 			var mywindow = submitForm(url,obj_form);
 			$("#frmlog input[name=type]").val('');
 			$("#frmlog input[name=page]").val('');
 		}
 		return true;
 	}
 	$("#div_emadvsearch").hide();
	$("#spn_emadvsearch").click(function(){
			$("#div_emadvsearch").slideToggle('slow');
	});
	$("#datepickerdiv").hide();
	$(".pickdate").click(function(){
			$("#datepickerdiv").slideToggle('slow');
	});
