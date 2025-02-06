//var postData = null;
$(document).ready(function()
{
    userlogList();
    $('body').on('click', function (e) {
        //did not click a popover toggle or popover
        if ($(e.target).data('toggle') !== 'popover'
            && $(e.target).parents('.popover.in').length === 0) { 
            $('[data-toggle="popover"]').popover('hide');
        }
    });
	
});

function closeMsgAuto(div_id)
{
	setTimeout(function() { $("#"+div_id).fadeIn('slow').empty(); }, 10000);
}
function userlogList()
{
	//closeMsgBox('msg_div');
	closeMsgAuto('msg_div');
	emLoader('show', 'Loading User Logs');
	var url = SITE_URL+'/userlogs/list';
    var postData = $("#frmdevices").serialize();
   // alert(postData);
    var userlogsajax = ajaxCall(userlogsajax,url,postData,function(data)
        {
            showResponse(data, 'grid_data', 'msg_div' );
            emLoader('hide');
        });
 
    setTimeout(function()
    {
       //$('[data-toggle="popover"]').popover(); 
       $("[data-toggle=popover]").popover({
        html: true, 
        content: function() {
            $(".popover").hide();
            var id = $(this).attr('id');
              return $('#popover-content_'+id).html();
            }
    });
    }, 1000);
}