var postData = null;
var glballocatedcnt;
var maxcnt;

$(document).ready(function() {
    softwaredetails(sid);
    $(document).on("click", ".swasset_attach", function() {
        swaddAsset(sid)
    });
    $(document).on("click", "#hideoption", function() {
        optionhide()
    });
    $(document).on("click", "#showoption", function() {
        optionshow()
    });
    $(document).on("click", "#attchasset", function() {
        swattachassetsave();
    });
    $(document).on("click", ".install_asset_delete", function() {
        var software_id = $(this).attr('id');
        swassetdelete(software_id);
    });
    $(document).on("click", ".swlicense_attach", function() {
        swaddLicense(sid)
    });
    $(document).on("click", "#swaddLicensesubmit", function() {
        swaddLicensesubmit();
    });

    $(document).on("click", ".softwarelicense_edit", function() {
        var software_license_id = $(this).attr('id');
        softwarelicenseedit(software_license_id);
    });
    $(document).on("click", "#swaddLicenseeditsubmit", function() {
        softwarelicenseeditsubmit();
    });


   
       /* $(document).on("change", "#maxinstallation", function() {
        alert();
        if($('#maxinstallation').val() > '10' )
            {
                alert('Max Installation should not be greater than 10.');
            }
        });*/

    $(document).on("change", "#license_type_id", function() {
        var type = $(this).find('option:selected').attr("name");
        var installation_allow = $(this).find('option:selected').data('installation_allow');
        console.log(installation_allow) ;
        if(installation_allow == "Single" || installation_allow == 'OEM')
        {
            $('#maxinstallation').val("1");            
            $("#maxinstallation").prop('readonly', true);
        }
        else if(installation_allow == "Unlimited")
        {
            $('#maxinstallation').val("Unlimited");
            $("#maxinstallation").prop('readonly', true);
        }
        else if(installation_allow == "Volume")
        {
            $("#maxinstallation").prop('readonly', false);
            $('#maxinstallation').val("");
            
        }
        /*if (type == 'OEM' || type == 'Enterprise Perpetual' || type == 'Enterprise Subscription' || type == 'Individual') 
        {
            $('#maxinstallation').val("1");
            
            $("#maxinstallation").prop('readonly', true);
            //$('#max_installation').prop('checked',true);
        } else if (type == 'Free License') {
            
            $('#maxinstallation').val("Unlimited");
            $("#maxinstallation").prop('readonly', true);
        }*/
        

        
    });

    $(document).on("click", "#swallocate_license", function() {
        softwarelicensellocate(sid)
    });
    $(document).on("click", ".allocate_asset_delete", function() {
        var software_id = $(this).attr('id');
        swassetalloctedelete(software_id);
    });
    $(document).on("click", ".allocate_deallocate", function() {
        var software_id = $(this).attr('id');
        swdeallocateuninstall(software_id);
    });

    $(document).on("click", "#callhistory", function() {
        getswhistory(sid);

    });
    $(document).on("click", "#callinstall", function() {
        getswinstallation(sid);
    });
    $(document).on("click", "#calllicense", function() {
        getsoftwarelicense(sid);
    });

    $(document).on("click", ".allocation_btn", function() {
    var software_license_id = $(this).attr('id');
    var swassetsallocate_count = $("#swassetsallocate_count").val();
    var sum = $("#sum").val();
    maxcnt = $(this).attr('data-max');
    swlicensemaxacount(software_license_id);
   
});
    $(document).on("click", ".check", function() {
        //var max = $("#max_installation").val();  
       
        var selectassetids = [];
        $('.selectassetidsChk:checkbox:checked').each(function() {
            //credentailNames.push($(this).data("temp_name"));
            selectassetids.push($(this).data("temp_name"));

        });
        $("#credentialnames").val(selectassetids);
        //console.log(selectassetids);
        var asset = selectassetids.length;
        if(maxcnt == "Unlimited")
        {

        }
        else
        {
            var maxval = parseInt(maxcnt);
            //alert(typeof(asset));
            alert(maxval);
            if(maxval == asset){
                $("#swallocate_license").prop('disabled', false);
            }
            else if (maxval >= asset) {
               $("#swallocate_license").prop('disabled', false);  
            }
           
            else {
                 alert(trans('messages.msg_license_as_installation'));
                $("#swallocate_license").prop('disabled', true);
                
            } 
        }

    });

$(document).on("click", "#callswdetails", function() {
        softwaredetails(sid);
    });

$(document).on("change", "#expiry_date", function() {
    var adate = $("#acquisition_date").val();
    var edate = $("#expiry_date").val();
    if(adate <= edate)
    {
        console.log('correct');
    }
    else {
        alert(trans('messages.msg_aqui_expiry'));

    }
  
});


});


function softwareList(sid) {

    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_software_loading'));
    var url = SITE_URL + '/software/list';
    //var postData = $("#frmdevices").serialize();
    var postData = {
        'datatype': 'json',
        'software_id': sid
    };


    var userajax = ajaxCall(userajax, url, postData, function(data) {
        var result = JSON.parse(data);
        showResponse(data, 'software_list', 'msg_div');
        emLoader('hide');
        initsingleselect();
        //initmultiselect();
    });

}


function softwaredetails(first_software_id) {

    emLoader('show', trans('messages.msg_software_loading'));
    var url = SITE_URL + '/software/details';

    // postData = {'software_id' : first_software_id};
    var postData = {
        'datatype': 'json',
        'software_id': first_software_id
    };

    emLoader('show', trans('messages.msg_software_loading'));
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        emLoader('hide');
        showResponse(data, 'software_detail', 'msg_div');
        //showDropZoneFile("dropZone");


    });
}

function getswinstallation(first_software_id) {
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/getswinstallation';
    var postData = {
        'datatype': 'json',
        'software_id': first_software_id
    };

    emLoader('show', trans('messages.msg_software_loading'));
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {

        $('#div_install_details').html(data);
        emLoader('hide');

    });
}

function getsoftwarelicense(first_software_id) {
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/getsoftwarelicense';
    var postData = {
        'datatype': 'json',
        'software_id': first_software_id
    };

    emLoader('show', trans('messages.msg_software_loading'));
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {

        $('#div_license_details').html(data);
        emLoader('hide');

    });
}


function getswhistory11(first_software_id) {
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/getswhistory';
    var postData = {
        'datatype': 'json',
        'software_id': first_software_id
    };

    emLoader('show', trans('messages.msg_software_loading'));
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {

        $('#div_history_details').html(data);
        emLoader('hide');

    });
}

function getswhistory(first_software_id) {
    //alert(sid);
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/getswhistory';
    //var postData = { 'datatype': 'json', 'software_id': sid};

    var postData = $("#frmdevices").serialize();
    //console.log(postData);
    var exporttype = $("#frmdevices input[name=exporttype]").val();
    if (exporttype == 'pdf' || exporttype == 'csv' || exporttype == 'print') {
        var obj_form = document.frmdevices;
        var mywindow = submitForm(url, obj_form, 1, 1);
        $("#frmdevices input[name=exporttype]").val('');
        $("#frmdevices input[name=page]").val('');
        emLoader('hide');
    } else {
        var mongraphsajax = ajaxCall(mongraphsajax, url, postData, function(data) {
            //$('#div_history_details').html(data);
            showResponse(data, 'div_history_details', 'msg_div');
            emLoader('hide');
        });
    }
}

function swaddAsset(sid) {
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var bv_id = $('#bv_id').val();
    var location_id = $('#location_id').val();
    var asset_id = $('#asset_id').val();
    var tag = $('#tag').val();

    var postData = {
        'asset_id': asset_id,
        'location_id': location_id,
        'bv_id': bv_id,
        "tag": tag
    };
    var url = SITE_URL + '/swaddasset';
    var passchangeajax = ajaxCall(passchangeajax, url, postData, function(data) {
        lightbox('show', data, trans('label.lbl_add_sw_install'), 'maxlarge');
        initsingleselect();
        emLoader('hide');
        $('#attach #software_id').val(sid);
    });
}

function assetSelect(asset_status) {
    $('#multiassetids').html("<option>Select</option>");
    var variable_name = $("#variable_name").val();
    if (variable_name != "") {

        clearMsg('msg_popup');
        emLoader('show', trans('label.lbl_sw_asset_saving'));
        var bv_id = $("#bv_id").val();
        var cuasset_id = $("#asset_id").val();
        var location_id = $("#location_id").val();


        var url = SITE_URL + '/getcitempidsw';
        var postData = {
            "variable_name": variable_name,
            "asset_status": asset_status,
            "bv_id": bv_id,
            "location_id": location_id
        }
        var asserselect = ajaxCall(asserselect, url, postData, function(data) {
            var str = "";
            if (data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                    var dt = data[i];
                    if (cuasset_id != dt.asset_id) {
                        if (dt.display_name == '')
                            str = str + '<option value="' + dt.asset_id + '">' + dt.asset_tag + '</option>';
                        else
                            str = str + '<option value="' + dt.asset_id + '">' + dt.asset_tag + '(' + dt.display_name + ')</option>';
                    }

                }
            } else {
                //str = '<option value="">trans(label.no_records)</option>';
                str = '<option value="">No Records</option>';

            }

            if (str != '') {
                $('#multiassetids').append(str);
                $('#selectassetids').html(str);
                $("#selectassetids").children('option').hide();
            }


        });
    } else {
        var str = "";
        //$('#multiassetids').html(str);
        //  $('#selectassetids').html(str);
        $('#multiassetids').html(str);
        $('#selectassetids').html(str);
        $("#selectassetids").children('option').hide();
    }

    emLoader('hide');
}

function optionhide() {
    $('#multiassetids :selected').each(function() {
        $("#multiassetids option[value=" + $(this).val() + "]").remove();
        $("#selectassetids").append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
    });
}

function optionshow() {
    $('#selectassetids :selected').each(function() {
        $("#selectassetids option[value=" + $(this).val() + "]").remove();
        $("#multiassetids").append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
    });
}

function swattachassetsave() {
    clearMsg('msg_popup');
    clearMsg('msg_div');
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_sw_asset_saving'));

    $('#selectassetids option').each(function() {
        if ($(this).css('display') == 'block') {
            //$(this).prop("selected", true);
            $(this).attr('selected', true);
        }

    });

    var url = SITE_URL + '/swattachassetsave';
    var postData = $("#attach").serialize();
    //var postData = { 'datatype': 'json', 'software_id': sid};
    //console.log(postData);
    var attachsaveasset = ajaxCall(attachsaveasset, url, postData, function(data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        } else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data, '', 'msg_div');
            var asset_id = $('#asset_id').val();
            var sid = $('#software_id').val();
            getswinstallation(sid);
        }
    });
}


function swassetdelete(software_id) {
    if (confirm(trans('messages.msg_sw_asset_delete'))) {
        clearMsg('msg_div');
        closeMsgAuto('msg_div');
        emLoader('show', trans('messages.msg_deleting_swasset'));
        var id = software_id.split('_')[1];
        var assetid = software_id.split('_')[2];
        var postData = {
            'datatype': 'json',
            'software_id': id,
            'asset_id': assetid
        };
        var url = SITE_URL + '/swassetremove';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_div');
                emLoader('hide');
            } else {
                emLoader('hide');
                showResponse(data, '', 'msg_div');
                getswinstallation(id);
            }
        });
    }
}

function swaddLicense(sid) {
    clearMsg('msg_div');
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_add_license'));

    var bv_id = $('#bv_id').val();
    var location_id = $('#location_id').val();
    var asset_id = $('#asset_id').val();
    var tag = $('#tag').val();

    var postData = {
        'software_id' : sid, //Adedd By Namrata to get 'Software Manufacturer' selected on "Add License" page
        'asset_id': asset_id,
        'location_id': location_id,
        'bv_id': bv_id,
        "tag": tag
    };
    var url = SITE_URL + '/swaddlisense';
    var passchangeajax = ajaxCall(passchangeajax, url, postData, function(data) {
        lightbox('show', data, trans('label.lbl_add_license'), 'large');
        initsingleselect();
        emLoader('hide');

        datecalendar('acquisition_date');
        datecalendar('expiry_date');
        $('#addformsoftwarelicense #software_id').val(sid);
    });
}


function swaddLicensesubmit() {
    clearMsg('msg_popup');
    clearMsg('msg_div');
    closeMsgAuto('msg_div');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', trans('label.lbl_software_license'));
    
    var url = SITE_URL + '/swaddLicensesubmit';
    var postData = $("#addformsoftwarelicense").serialize();
    //console.log(postData);
    
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        var result = JSON.parse(data);
        //console.log(result);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
             window.scrollTo(0, 0);
            emLoader('hide');
        } else {
            emLoader('hide');
            lightbox('hide');

            window.scrollTo(0, 0);
            showResponse(data, 'grid_data', 'msg_div');
            getsoftwarelicense(sid);
        }

    });
}



function softwarelicenseedit(software_license_id) {
    var id = software_license_id.split('_')[1];
    var sid = $("#software_id").val();
    var postData = {
        'datatype': 'json',
        'id': id,
        'software_id': sid        
    };
    //console.log(postData);
    var url = SITE_URL + '/softwarelicenseedit';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        lightbox('show', data, trans('messages.msg_software_license_edit'), 'large');
        emLoader('hide');
        //console.log($('#software_id').val(sid));
    });
}

function softwarelicenseeditsubmit() {
    clearMsg('msg_div');
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_updating_software'));
    var url = SITE_URL + '/softwarelicenseeditsubmit';
    var postData = $("#addformsoftwarelicense").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        //console.log(data);
        var result = JSON.parse(data);
        // console.log(result);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            window.scrollTo(0, 0);
            emLoader('hide');
        } else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data, 'grid_data', 'msg_div');
            getsoftwarelicense(sid);

        }

    });

}
$(document).on("click", "#assetidsCheckAll", function() {
    if (this.checked) {
        $(".selectassetidsChk").each(function() {
            this.checked = true;
        });
    } else {
        $(".selectassetidsChk").each(function() {
            this.checked = false;
        });
    }
});



function softwarelicensellocate(sid) {
    
    clearMsg('msg_div');
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_sw_asset_saving'));
    var selectassetids = [];
    $('.selectassetidsChk:checkbox:checked').each(function() {
        //credentailNames.push($(this).data("temp_name"));
        selectassetids.push($(this).data("temp_name"));

    });
    $("#credentialnames").val(selectassetids);
    $("#swassetsallocate_count").hide();
    //console.log(selectassetids);
    var asset = selectassetids.length;
    var max = $("#max_installation").val();        
   // if (max >= asset) {


        var url = SITE_URL + '/softwarelicensellocate';
        //var postData = $("#allocateform").serialize();
        //var selectassetids = $(this).val();
        var software_license_id = $('#software_license_id').val();
        
        var selectassetids = selectassetids;
        var software_id = sid;
        var postData = {
            'datatype': 'json',
            'software_id': sid,
            'software_license_id': software_license_id,
            'selectassetids': selectassetids
        };
        //console.log(postData);
        var attachsaveasset = ajaxCall(attachsaveasset, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_popup');
                emLoader('hide');
            } else {
                emLoader('hide');
                lightbox('hide');
                showResponse(data, '', 'msg_div');
                var asset_id = $('#asset_id').val();
                //console.log($('#software_id').val(sid));
                getsoftwarelicense(sid);
            }
        });
    // } else {
    //     emLoader('hide');
    //     lightbox('hide');
    //     var m = trans('messages.msg_license_reach'); //'Max allowed for this license has been already reached.';
    //     data = {
    //         is_error: "danger",
    //         msg: m,
    //         html: ""
    //     };
    //     showResponse(JSON.stringify(data), '', 'msg_div');

    // }
}

function swassetalloctedelete(software_id) {
    if (confirm(trans('messages.msg_sw_uninstall'))) {
        clearMsg('msg_div');
        closeMsgAuto('msg_div');
        emLoader('show', trans('messages.msg_deleting_swasset'));
        var id = software_id.split('_')[1];
        var assetid = software_id.split('_')[2];
        var postData = {
            'datatype': 'json',
            'software_id': id,
            'asset_id': assetid
        };
        var url = SITE_URL + '/swallocateassetremove';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_div');
                emLoader('hide');
            } else {
                emLoader('hide');
                showResponse(data, '', 'msg_div');
                getsoftwarelicense(id);

            }
        });
    }
}

function swdeallocateuninstall(software_id) {
    if (confirm(trans('messages.msg_sw_uninstall'))) {
        clearMsg('msg_div');
        closeMsgAuto('msg_div');
        emLoader('show', trans('messages.msg_deleting_swasset'));
        var id = software_id.split('_')[1];
        var assetid = software_id.split('_')[2];
        var postData = {
            'datatype': 'json',
            'software_id': id,
            'asset_id': assetid
        };
        var url = SITE_URL + '/swdeallocateuninstall';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_div');
                emLoader('hide');
            } else {
                emLoader('hide');
                showResponse(data, '', 'msg_div');
                getsoftwarelicense(id);

            }
        });
    }
}

function swlicensemaxacount(software_license_id) {
    var id = software_license_id;
    var postData = {
        'datatype': 'json',
        'software_license_id': id
    };
    //console.log(postData);
    var url = SITE_URL + '/swlicensemaxacount';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        emLoader('hide');
        console.log(data);

        if (data == 'null') {
            aglballocatedcnt = '0';

        } else {
            aglballocatedcnt = parseInt(data);

        }

        if (aglballocatedcnt == maxcnt) {

            var m = trans('messages.msg_license_reach'); //'Max allowed for this license has been already reached.';
            data = {
                is_error: "danger",
                msg: m,
                html: ""
            };
            window.scrollTo(0, 0);
            showResponse(JSON.stringify(data), '', 'msg_div');
        } else if (aglballocatedcnt >= maxcnt)

        {
            var m = trans('messages.msg_license_reach'); //'Max allowed for this license has been already reached.';
            data = {
                is_error: "danger",
                msg: m,
                html: ""
            };            
            window.scrollTo(0, 0);
            showResponse(JSON.stringify(data), '', 'msg_div');

        } else {

            $('#myModal1').modal('show');
            //var id = $(this).attr('data-id');
            $("#software_license_id").val(id);
            // maxcnt = $(this).attr('data-max');
            // $("#max_installation").val(maxcnt);
            // alert($("#max_installation").val(maxcnt))
            // $("#swassetsallocate_count").hide();
        }

    });
}