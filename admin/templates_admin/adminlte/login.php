<?
if(!defined("site_root")){exit();}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Admin panel - Log in</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=site_root?>/admin/templates_admin/<?=$admin_template?>/assets/dist/css/style.css" rel="stylesheet" type="text/css" />
    <link href="../images/favicon.gif" type="image/gif" rel="icon">
	<link href="../images/favicon.gif" type="image/gif" rel="shortcut icon">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <b>Admin</b>Panel
      </div>
      <div class="login-box-body">
        <p class="login-box-msg"><?=word_lang("auth")?></p>
        <?if($slova!=""){?>
       	 	<div class="alert alert-warning alert-dismissable">
       	 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
       	 		<?=$slova?>
       	 	</div>
        <?}?>
        <form method="post" action="check2.php">
          <div class="form-group has-feedback">
            <input type="text"  name="login" class="form-control" placeholder="<?=word_lang("login")?>" />
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="<?=word_lang("password")?>" />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
				<?$rr=rand(0,9);?>
				<input id="rn1" name="rn1" type="text" value=""  style="width:70px;float:left"  class="form-control"><input name="rn2" type="hidden" value="<?=$rr?>"><img src="<?=site_root?>/images/c<?=$rr?>.gif" width="80" height="30" style="margin-left:9px">
            </div>
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat"><?=word_lang("login")?></button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js" type="text/javascript"></script>
  </body>
</html>
