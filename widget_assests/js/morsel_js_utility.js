jQuery(function($) {
  //console.log("script_data : ",script_data);
  /*check that check box associate field is enabled or not*/
  $("#mrsl-associate-checkbox").click(function(){
    var checked = $("input#mrsl-associate-checkbox:checked").length;
    var isDisabled = $("#morsel-associate-btn").is(':disabled');
    if(checked){
      if (isDisabled) {
        $("#morsel-associate-btn").prop('disabled', false);
      }
    } else {
      if (!isDisabled) {
        $("#morsel-associate-btn").prop('disabled', true);
      }
    }
  });

  /*Directly open create morsel iframe modal*/
  $("#create-morsel-btn-1").click(function(event){
    event.preventDefault();
    if(script_data.morsel_user){
      var user = script_data.morsel_user;
      var src = script_data.morsel_site+"auth/loginifrm?id="+user.id+"&token="+user.auth_token;
      $("#website-iframe-modal iframe").attr({'src':src,'height':450,'width':'100%'});
    }else {
      alert("Please get sign-in by your eatmorsel account!");
    }
  });

  /*associate logged-in user with administrator*/
  $("#morsel-associate-btn").click(function(event){
      event.preventDefault();

      console.log("script_data.morsel_user : ",script_data.morsel_user);
      //if user is logged in than proceed ahead
      if(script_data.morsel_user){
        var user = script_data.morsel_user;

        var accociateUrl = script_data.morsel_api_user_url+user.id+"/create_association_request";
        var activity = 'morsel-associate-user';
        var key = user.id+":"+user.auth_token;

        var post_data = {
                          association_request_params:{
                            name_or_email:user.username,is_admin:true
                          },
                          api_key:key
                        };
        console.log("post_data : ",post_data);
        jQuery.ajax({
            url: accociateUrl,
            type: 'POST',
            data: post_data,
            complete: function(){
              waitingDialog.hide();
            },
            beforeSend: function(xhr) {
              xhr.setRequestHeader('share-by',"morsel-plugin");
              xhr.setRequestHeader('activity',activity);
              xhr.setRequestHeader('activity-id',script_data.current_morsel_id);
              xhr.setRequestHeader('activity-type',"Morsel");
              xhr.setRequestHeader('user-id',user.id);
              waitingDialog.show('Loading...');
            },
            success: function(response, status){
              console.log("response :: ",response);
              console.log("status :: ",status);

              if(status == 'success'){
                //alert("You have been associate with host admin successfully.");

                //change btn attributes so that after associate the
                $("#create-morsel-btn").attr({'data-target':"#website-iframe-modal",'id':"create-morsel-btn-1"});

                //open eatmorsel website in a iframe
                var src = script_data.morsel_site+"auth/loginifrm?id="+user.id+"&token="+user.auth_token;
                $("#website-iframe-modal iframe").attr({'src':src,'height':450,'width': '100%'});
                $("#website-iframe-modal").modal();
              } else {
                alert("Opps Something wrong happend!");
              }
            },
            error:function(response, status, xhr){
                console.log("error response :: ",response);
            }
        });
      } else {
        jQuery(".open-morsel-login").trigger('click');
        return;
      }
  });
});
