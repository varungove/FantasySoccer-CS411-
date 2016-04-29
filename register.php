<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fantasy Premier League Draft Registration | </title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
  <link href="css/animate.min.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">
  <link href="css/icheck/flat/green.css" rel="stylesheet">
  <script src="js/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
          $("#user_register").click(function(){
          var data = $("#register_form :input").serializeArray();
        $.post($("#register_form").attr("action"), 
        data, 
              function(info){
                if (info == "User created successfully") {
                  var username = $("#uid").val();
                  document.getElementById('register_form').action = 'home.php?uid='+username;
                  $("#register_form").submit();
                } else {
                  $("#comments").html(info);
                }
                
              })
              .fail(function() {
              alert( "Something went wrong. Please try again" );
          });
        });         
    });
  </script>
</head>


<body class="nav-md">
  <div class="container body">
    <div class="main_container">
          <nav class="" role="navigation">
          </nav>
        </div>
        <div class="">
          <div class="page-title">
            <div class="well" style="text-align: center;">
              <h3 style="color: black;">Fantasy Premier League Draft</h3>
            </div>
        </div>
          </div>



          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center; margin-top: 100px;">
              <div class="x_panel" style="width: 60%; margin: auto;">
                <div class="x_title" style="font-size: 25px;">
                  Registration
                  <div class="clearfix"></div>
                </div>
                <br>
                <div class="x_content">
                  <form id="register_form" action="queries/register_query.php" method="post" class="form-horizontal form-label-left" novalidate>

                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="uid" id="uid" required="required" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>
                    <div class="item form-group">
                      <label for="password" class="control-label col-md-3">Password</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="password" name="pass" id="pass" class="form-control col-md-7 col-xs-12" required="required">
                      </div>
                    </div>
                    
                    <div class="item form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="telephone">Team Name<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="tname" id="tname" required="required" class="form-control col-md-7 col-xs-12">
                      </div>
                    </div>

                    <div class="ln_solid"></div>
                    
                    <div class="form-group">
                      <div style="text-align: center;">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        	<button type="button" id="user_register" name="user_register" class="btn btn-success">Register</button>
                      </div>

                      </div>
                    </div>
                  </form>            
                </div>

              </div>
            </div>
          </div>
      </div>
    </div>
  </div>


  <p id="comments"></p>

  <script src="js/bootstrap.min.js"></script>
  <script src="js/progressbar/bootstrap-progressbar.min.js"></script>
  <script src="js/icheck/icheck.min.js"></script>
  <script src="js/pace/pace.min.js"></script>
  <script src="js/custom.js"></script>
  <script src="js/validator/validator.js"></script>

</body>

</html>