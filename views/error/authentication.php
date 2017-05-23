<?php
 /**
  * Display authentication
  *
  * Authentication error display for unauthorized access in crm,
  * all access from crm front-end are not allowed.
  *
  * PHP VERSION 5
  *
  * @author  Rey Lim Jr <junreyjr1029@gmail.com>
  * @version 1.0
  * @license Paid copyright telequest BPO
  */
  ?>
	<div class="error">
		<h1>Authentication Error!</h1>
		<p>Please Check your login authentication.</p>
		<a href="<?php echo URL; ?>../main-crm">Go to Site Manager</a>
	</div>

  <style>
  .error {
	padding: 10px;
	width: 80%;
	border: 1px solid #929292;
	margin: 0 auto;
	color: #848484;
	text-align: center;
	box-shadow: 1px 1px 2px #BEBEBE;
  }
  .error a {
  	color: #434343;
  	text-decoration: none;
  	color: #333;
  }
  .error a:hover {
  	color: #003477;
  }
  </style>