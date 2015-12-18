<!-- Login Modal-->
<div class="modal fade" id="morselLoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <button id="show-mrsl-login-btn" type="button" class="btn btn-danger btn-xs pull-right">Login</button>
         <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
        <!-- Sign Up div-->
        <div class="main-view" >
        <div id="login-messages" class="center-block"></div>
          <div id="mrsl-signup-section">
            <div class="container-fluid join-page">
              <div class="row">
                <div class="col-md-12">
                  <h1 class="text-center">Please Sign Up</h1>
                  <div id="mrsl-signup-error-box" class="alert alert-danger" style="display:none"></div>
                  <div class="join-landing" ui-view="landing"></div>
                  <div ui-view="basicInfo" class="">
                    <form novalidate="" class="padded-form" method="post" name="basicInfoForm" id="mrsl-signup-form" enctype="multipart/form-data">
                      <div class="row">
                        <!-- <div class="col-md-10 col-md-offset-1">
                          <div class="alert alert-danger"></div>
                        </div> -->
                      </div>
                      <div class="row">
                        <div class="col-sm-5 col-md-4 col-md-offset-1">
                          <div class="form-group">
                            <div class="avatar-add image-add">
                              <div data-original-title="Click to select or drag and drop a photo from your computer" data-toggle="tooltip" data-placement="bottom" class="img-circle">
                                <div class="drop-box"></div>
                                <span class="h1 plus-sign">+</span>
                                <input type="file" name="user[photo]" id="mrsl_user_photo">
                                <div class="image-preview" style="display:none"></div>
                              </div>
                            </div>
                            <label for="photo" class="control-label center-block text-center">Profile Photo</label>
                          </div>
                        </div>
                        <div class="col-sm-7 col-md-6">
                          <div class="form-group">
                            <label for="user[first_name]" class="control-label required">First Name</label>
                            <input type="text" name="user[first_name]" id="mrsl_user_first_name" class="form-control" placeholder="John" required="required">
                            <p class="help-block"></p>
                          </div>
                          <div class="form-group">
                            <label for="user[last_name]" class="control-label required">Last Name</label>
                            <input type="text" name="user[last_name]" id="mrsl_user_last_name" class="form-control" placeholder="Smith" required="required">
                            <p class="help-block"></p>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                          <div class="form-group">
                            <label for="user[username]" class="control-label required">Username</label>
                            <input type="text" name="user[username]" id="mrsl_user_username" class="form-control" placeholder="johnnyboy" required="required">
                            <p class="help-block">Must be alphanumeric and not include spaces</p>
                            <span class="help-block" id="mrsl_username_error"></span>
                          </div>

                          <div class="form-group">
                            <label for="user[email]" class="control-label required">Email</label>
                            <input type="email" name="user[email]" id="mrsl_user_email" class="form-control" placeholder="johnsmith@example.com" required="required">
                            <p class="help-block ng-binding"></p>
                          </div>
                          <div class="form-group">
                            <label for="user[password]" class="control-label required">Password</label>
                            <input type="password" name="user[password]" id="mrsl_user_password" class="form-control" placeholder="" required="required">
                            <p class="help-block"></p>
                          </div>
                          <div class="form-group">
                            <label for="verification" class="control-label required">Confirm Password</label>
                            <input type="password" name="verification" id="verification" class="form-control" placeholder="" required="required">
                            <p class="help-block"></p>
                          </div>
                          <div class="form-group">
                            <div class="checkbox">
                              <label for="user[professional]" class="control-label" style="width:100%">
                                <input type="checkbox" value="true" name="user[professional]" id="mrsl_user_professional" class="">I am a professional chef, sommelier, mixologist, etc.</label>
                            </div>
                            <p class="help-block"></p>
                          </div>
                          <!-- <div id="morsel-progress" class="progress" style="display:none;">
                            <div style="width:100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100%" role="progressbar" class="progress-bar progress-bar-striped active">Your request is processing, please wait.</div>
                          </div> -->
                          <div class="form-group clearfix">
                            <span id="mrsl-signup-submit-btn-span" data-original-title="Please complete all required fields" data-toggle="tooltip" data-placement="top" class="btn-submit-wrap btn-submit-block disabled">
                              <button id="mrsl-signup-submit-btn" class="btn btn-primary btn-lg" type="submit">Sign Up</button>
                            </span>
                          </div>
                          <div>By continuing you indicate that you have read and agree to our <a target="_blank" href="http://eatmorsel.com/terms">Terms of Service</a></div>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div ui-view="additionalInfo" class=""></div>
                </div>
              </div>
            </div>
          </div>
          <!-- #mrsl-signup-section -->
          <!-- login div-->
          <div id="mrsl-login-section" style="display:none">
            <div class="container-fluid login-page">
                <h1 class="text-center">Log In to <?php echo ucwords($blog_title = get_bloginfo('name')); ?></h1>
                <form action="<?php echo site_url()?>/index.php" class="padded-form" method="post" name="loginForm" id="morsel-front-login-form">
                    <div class="row">
                      <div class="col-md-12 center-block">
                          <div class="form-group">
                            <label for="login" class="control-label required">Email or Username</label>
                            <input type="text" name="user[login]" id="mrsl-login" class="form-control " placeholder="johnsmith@example.com or johnnyboy" >
                            <p class="help-block"></p>
                          </div>
                          <div class="form-group">
                            <label class="control-label required">Password</label>
                            <input type="password" name="user[password]" id="mrsl-password" class="form-control" placeholder="" >
                            <p class="help-block"></p>
                        </div>
                        <div class="form-group clearfix" >
                          <span id="mrsl-submit-btn-span" class="btn-submit-wrap btn-submit-block disabled" title="Please complete all required fields" data-toggle="tooltip" data-placement="top">
                            <button id="mrsl-submit-btn" class="btn btn-primary btn-lg" type="submit" >Login</button>
                          </span>
                        </div>
                        <div class="text-center"><a class="open-site-link" data-toggle="modal" data-src="https://www.eatmorsel.com/auth/password-reset" data-height=500 data-width=100% data-target="#forgetPasswordModal" >Forgot your password?</a></div>
                        <div class="have-an-account text-center"><span id="dontHaveAccount">Don't have an account?</span><span id="notMyAccount" style="display:none;">That is not my username and email.</span> <a target="_blank" href="#">Sign up here.</a></div>
                      </div>
                    </div> <!-- End row class -->
                    <!-- <input type="hidden" name="pagename" value="morsel_user_login"> -->
                    <input type="hidden" name="pagename" value="morsel_ajax_user_login">
                  </form>
              </div>
          </div> <!-- #mrsl-login-section -->
          <div id="morsel-progress" class="progress" style="display:none;">
            <div style="width:100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100%" role="progressbar" class="progress-bar progress-bar-striped active">Your request is processing, please wait.</div>
          </div>
        </div>
        <div class="powered-by-morsel">
          <a target="_blank" href="http://www.eatmorsel.com/">Powered by Morsel</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- forget password  -->
<div class="modal fade" id="forgetPasswordModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
       <div class="modal-content">
          <div class="modal-body">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <iframe frameborder="0"></iframe>
          </div>
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end forget password  -->
<script type="text/javascript">
  jQuery(function ($) {

    //login btn calling mrsl login by ajax in morsel.p
    $("#mrsl-submit-btn").click(function(event) {
      event.preventDefault();

      jQuery.ajax({
          url: "<?php echo site_url()?>" + "/index.php",
          data : $("#morsel-front-login-form").serialize(),
          type : 'POST',
          async : false,
          beforeSend: function(xhr){
            jQuery('#morsel-progress').show();
            $("#login-messages").html("");
          },
          complete: function(){
            jQuery('#morsel-progress').hide();
          },
          success: function(response){
            console.log("response get in login time",response);
            response = JSON.parse(response);
            console.log("after JSON.parse response get in login time",response);

            for (var i=0; i < response.msg.length; i++ ) {
              console.log("msg object in loop",response.msg[i]);
              if(response.status){
                $("#login-messages").append('<div class="alert alert-success text-center" role="alert">'+response.msg[i]+'</div>');
              } else {
                $("#login-messages").append('<div class="alert alert-danger text-center" role="alert">'+response.msg[i]+'</div>');
              }
            }

            if(response.status){
              setTimeout(function(){ location.reload(); }, 1000);
            }
          },
          error:function(response){ alert("Opps something wrong happend!"); }
        });
      return false;
    });

    jQuery(".open-morsel-login").click(function(event) {
      event.preventDefault();
      jQuery('#notMyAccount').hide();
      jQuery('#dontHaveAccount').show();
      jQuery("#morselLoginModal").modal('show');
    });

    jQuery( "#show-mrsl-login-btn" ).click(function() {
      jQuery('#notMyAccount').hide();
      jQuery('#dontHaveAccount').show();
      //clear error messages genrated by morsel login
      $("#login-messages").html("");
    });

    jQuery("#mrsl-signup-submit-btn").click(function(event){

      event.preventDefault();
      var signupForm = jQuery("#mrsl-signup-form");

      console.log("photo file ",document.getElementById("mrsl_user_photo").files[0]);

      var fd = new FormData();
      fd.append("user[email]",jQuery( "#mrsl_user_email" ).val());
      fd.append("user[password]",jQuery( "#mrsl_user_password" ).val());
      fd.append("user[username]",jQuery( "#mrsl_user_username" ).val());
      fd.append("user[first_name]",jQuery( "#mrsl_user_first_name" ).val());
      fd.append("user[last_name]",jQuery( "#mrsl_user_last_name" ).val());

      if(document.getElementById("mrsl_user_photo").files[0]){
        fd.append("user[photo]",document.getElementById("mrsl_user_photo").files[0]);
      }

      fd.append("user[professional]",jQuery( "#mrsl_user_professional" ).val());
        jQuery.ajax({
          url: "<?php echo MORSEL_API_URL.'users.json';?>",
          data : fd,
          type:'POST',
          contentType: false,
          cache: false,
          processData:false,
          beforeSend: function(xhr){
              /*jQuery("#morselLoginModal").modal('hide');
              waitingDialog.show('Your request is processing, please wait.');*/
              jQuery('#morsel-progress').show();
              jQuery("#mrsl-signup-submit-btn").hide();
              xhr.setRequestHeader('host-site',"<?php echo get_site_url(); ?>");
              xhr.setRequestHeader('host-site',"<?php echo get_site_url(); ?>");
              xhr.setRequestHeader('share-by',"morsel-plugin")
              xhr.setRequestHeader('activity','sign-up');
              xhr.setRequestHeader('morsel-id',"<?php echo $_REQUEST['morselid'];?>");
              xhr.setRequestHeader('user-id',"0");
          },
          complete: function(){
              //jQuery("#morselLoginModal").modal('show');
              //waitingDialog.hide();
              jQuery('#morsel-progress').hide();
              jQuery("#mrsl-signup-submit-btn").show();
          },
          success: function(response){

            if(response.meta.status == 200){

              //set for host login
              jQuery("#mrsl-login").val(response.data.username);
              jQuery("#mrsl-password").val(jQuery("#mrsl_user_password").val());
              //jQuery("#morsel-front-login-form").submit();
              jQuery("#mrsl-submit-btn").trigger("click");

            } else {
              alert("Opps Something wrong happend!");
              return false;
            }
          },
          error:function(response){
              console.log("Error response :: ",response);
              if( (typeof response.responseJSON.errors.email != "undefined") && response.responseJSON.errors.email[0] === "has already been taken"){
                var signUpEmail = jQuery( "#mrsl_user_email" ).val();
                alert("Looks like you already have an account, please log in.");
                jQuery("#show-mrsl-login-btn").text('SignUp');
                jQuery('#mrsl-signup-form')[0].reset();
                jQuery('#mrsl-signup-section').hide();
                jQuery( "#mrsl-login" ).val(signUpEmail);
                jQuery('#dontHaveAccount').hide();
                jQuery('#notMyAccount').show();
                jQuery('#mrsl-login-section').show();

              } else {

              var err = response.responseJSON.errors;

              if(err.first_name){
                  jQuery("#mrsl_user_first_name").parent(".form-group").append('<label for="mrsl_user_first_name" class="error" style="display: inline-block;">First Name '+err.first_name[0]+'</label>');
                  jQuery("#mrsl_user_first_name").parent(".form-group").addClass("has-error");
                }

                if(err.last_name){
                  jQuery("#mrsl_user_last_name").parent(".form-group").append('<label for="mrsl_user_last_name" class="error" style="display: inline-block;">Last Name '+err.last_name[0]+'</label>');
                  jQuery("#mrsl_user_last_name").parent(".form-group").addClass("has-error")
                }

                if(err.photo){
                  jQuery("#mrsl-signup-error-box").html("Photo "+err.photo[0]);
                  jQuery("#mrsl-signup-error-box").show();
                }

                if(err.username){
                  jQuery("#mrsl_user_username").parent(".form-group").append('<label for="mrsl_user_username" class="error" style="display: inline-block;">Username '+err.username[0]+'</label>');
                  jQuery("#mrsl_user_username").parent(".form-group").addClass("has-error");
                }

                if(err.password){
                  jQuery("#mrsl_user_password").parent(".form-group").append('<label for="mrsl_user_password" class="error" style="display: inline-block;">Password '+err.password[0]+'</label>');
                  jQuery("#mrsl_user_password").parent(".form-group").addClass("has-error");
                }

                if(err.email){
                  jQuery("#mrsl_user_email").parent(".form-group").append('<label for="mrsl_user_email" class="error" style="display: inline-block;">Email '+err.email[0]+'</label>');
                  jQuery("#mrsl_user_email").parent(".form-group").addClass("has-error");
                }
              }
            }
        });
      });
  });
</script>
