<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}

    .dev_link{
        text-decoration: none;
        color: #000000;
    }
	</style>
</head>
<body>

<div id="container">
	<h1>Welcome to Auto-Controller Class Generator!</h1>

	<div id="body">
		<code>
			<a href="<?php echo base_url(); ?>model_generator">Goto Model Generator</a>
		</code>
		<p>This Module is case-sensitive, Please Enter Table Name Correctly.</p>
		<form action="<?php echo base_url(); ?>controller_generator/create_controller" method="post">
        <p>Enter Controller Name:</p>
        <code><input type="text" id="ctrl_name" name="ctrl_name" required="required"/></code>

        <p>Enter Table Name:</p>
		<code><select id="table_name" name="table_name">
		<?php foreach($result as $row){ ?>
		<option value="<?php echo $row['TABLES']; ?>"><?php echo $row['TABLES']; ?></option>
		<?php } ?>
		</select></code>

        <p>Enter Model Name:</p>
        <code><input type="text" id="model_name" name="model_name" /><i>(Optional)</i></code>

		<p><input type="submit" value="Submit" /></p>
		</form>

		<?php
		if(isset($_GET['success'])) {
            $path_to_model = realpath(dirname(__FILE__) . '/../controllers');
			echo '<div style="color:green;font-weight:bold;" >The Controller has been generated successfully. You may <a href="'. base_url($_GET['controller']) .'" target="_blank">try it now</a>. </div><code><i>Directory: '.$path_to_model.'\\'.$_GET['controller'].'.php </i></code>';
		}
		if(isset($_GET['error'])){
			echo '<div style="color:red;font-weight:bold;">Table Not Exist!</div>';
		}
	?>
	</div>

	<p class="footer">Created By: <a href="http://pk.linkedin.com/in/waqass/" target="_blank" class="dev_link"><strong>Waqas Shahid</strong></a></p>
</div>

</body>
</html>