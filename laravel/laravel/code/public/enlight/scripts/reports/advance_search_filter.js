function submitAdvanceSearch()
{    
    emLoader('show', 'Submiting...');
    var submit_url = buildAdvanceSearchDataUrl("home");
    var form_data     = $('#form-advanced-search1').serialize();
    var final_data = '?'+form_data;
    var path_name = window.location.pathname;
    window.location.href = submit_url;
    
}
 
function addAdvanceSeachCustomeOptions() 
{
    emLoader('show', 'Submiting...');
    var submit_url = buildAdvanceSearchDataUrl();
    if(submit_url != 1)
    {
        window.location.href = submit_url;
    }
}
 
function buildAdvanceSearchDataUrl(callFrom = ""){
    var form_data = $('#form-advanced-search1').serialize();
    var adv_filter_data = $('#form-advanced-search2').serialize();
    var adv_filter_dataArr = $('#form-advanced-search2').serializeArray();

    var rowCnt = -1; 
    var flag = 0;
    
    $(".complete_row input, .advsearch_all_rows input, .complete_row.original select, .advsearch_all_rows select").css('border-color','#ddd');
    
    jQuery.each( adv_filter_dataArr, function( i, field ) {
        var innercount = i;
        if(innercount % 3 == 0){
            rowCnt++;
        }    
        if(field.value == ""){  
            if($("#row-id-"+rowCnt+" .criteria_ex_selector").val() != "exists"){
                $("#row-id-"+rowCnt+" input[name='"+field.name+"'], #row-id-"+rowCnt+" select[name='"+field.name+"']").css('border-color','red');
                flag = 1
            }
        }
    });

    emLoader('hide');
    if(flag == 0 || callFrom == "home"){
        var final_data = '?'+form_data+'&'+adv_filter_data;
        var path_name = window.location.pathname;
        var submit_url =  path_name+final_data;   
        return submit_url;
    }else{
        return 1;
    }
}
