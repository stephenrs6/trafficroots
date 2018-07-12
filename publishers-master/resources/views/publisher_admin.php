@extends('layouts.app') 
@section('title', 'pblisher_admin')



<!DOCTYPE html>
<!-- saved from url=(0038)https://trafficroots.com/widgets.html# -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traffic Roots | Publisher Stats</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet" >
	<link href="css/dropzone.css" rel="stylesheet">
	<link href="css/daterangepicker-bs3.css" ref="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="pace-done">
    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>

    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu" style="display: block;">
                    <li class="">
                        <div class="dropdown profile-element">
                            <span>
                            <img alt="image" class="" src="img/logo.png" width="100%">
                            </span>
                        </div>
                        <div class="logo-element">
                            T.R.
                        </div>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">Publisher</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="publisher-admin.html">Admin</a></li>
                            <li><a href="publisher-dashboard.html">Dashboard</a></li>
                            <li><a href="publisher-sites.html">Sites</a></li>
                            <li><a href="publisher-stats.html">Stats</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-bullhorn"></i> <span class="nav-label">Advertiser</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li><a href="advertise-admin.html">Admin</a></li>
                            <li><a href="advertiser-dashboard.html">Dashboard</a></li>
                            <li><a href="advertise-campaign.html">Campaigns</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-bug"></i> <span class="nav-label">Support</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-plug"></i> <span class="nav-label">Logout</span></a>
                    </li>
                </ul>

            </div>
        </nav>
        <!-- end of navigation -->

        <div id="page-wrapper" class="gray-bg" style="min-height: 755px;">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted">User Name</span>
                        </li>
                        <li class="dropdown">

                            <ul class="dropdown-menu dropdown-messages">
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="https://trafficroots.com/profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="./INSPINIA _ Widgets_files/a7.jpg">
                                        </a>
                                        <div class="media-body">
                                            <small class="pull-right">46h ago</small>
                                            <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                            <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="https://trafficroots.com/profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="./INSPINIA _ Widgets_files/a4.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right text-navy">5h ago</small>
                                            <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                            <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="https://trafficroots.com/profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="./INSPINIA _ Widgets_files/profile.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">23h ago</small>
                                            <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                            <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="https://trafficroots.com/mailbox.html">
                                            <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="https://trafficroots.com/mailbox.html">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="https://trafficroots.com/profile.html">
                                        <div>
                                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                            <span class="pull-right text-muted small">12 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="https://trafficroots.com/grid_options.html">
                                        <div>
                                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="https://trafficroots.com/notifications.html">
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="https://trafficroots.com/login.html">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row">
                <span class="title-blue">Publisher Admin</span>
            </div>           
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tabs-container">
                            <ul class="nav nav-tabs">
                                <li role="presentation" class="active"><a data-toggle="tab" href="#tab-1">My Profile</a></li>
                                <li role="presentation" class=""><a data-toggle="tab" href="#tab-2">Accounting</a></li>                               
                            </ul>
                            <div class="tab-content">
                                <div id="tab-2" class="tab-pane">
                                    <div class="panel-body">
                                        <!-- 1st Panel -->
                                        <div class="col-lg-12">
                                            <div class="ibox">
                                                <div class="col-md-8 col-md-offset-2">
                                                    <div class="panel panel-default m-t-lg">
                                                        <div class="panel-body">
                                                            
                                                            <h2 class="text-success text-center"><strong>Account Information</strong></h2>
                                                            <table class="table">
                                                                <tr>
                                                                    <td><strong>Account Status:</strong></td>
                                                                    <td>Approved</td><!-- Place status of the account -->
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Minimum Payout</strong></td>
                                                                    <td>$250</td><!-- Enter the total monthly payout -->
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Payment Terms</strong></td>
                                                                    <td>Monthly</td> <!-- Status of the payment terms -->
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Next Payment Due</strong></td>
                                                                    <td>Monday, October 30, 2017</td><!-- the day the next payment is due -->
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Next Payment Due</strong></td>
                                                                    <td>Monday, October 30, 2017</td><!-- the day the next payment is due -->
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Next Payment Period</strong></td>
                                                                    <td>2017-01-01 to 2017-10-30</td><!-- the total length of the campaign -->
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Due Next Period</strong></td>
                                                                    <td>$0</td><!-- What is owed next payment period -->
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Total</strong></td>
                                                                    <td>Monday, October 30, 2017</td><!-- total amount owed -->
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-md-offset-2">
                                                    <div class="panel panel-default">
                                                        <div class="panel-body">
                                                            
                                                            <h2 class="text-success text-center" style="padding-bottom: 2%"><strong>Payment History</strong></h2>
                                                            
                                                            <table class="table">
                                                                <thead>
                                                                <tr>
                                                                    <th>Period</th>
                                                                    <th>Amount</th>
                                                                    <th>Total</th>
                                                                    <th>Date</th>
                                                                    <th>Status</th>
                                                                    <th>Method</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>2017-01-01 to 2017-10-30</td><!-- Total lenght of the campaign -->
                                                                    <td>$250</td>
                                                                    <td>$260</td>
                                                                    <td>2017-10-30</td>
                                                                    <td>Active</td>
                                                                    <td>PayPal</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-1" class="tab-pane active">
                                    <div class="panel-body">
                                    <!-- First Panel -->
                                        <div class="col-lg-12">
                                            <div class="ibox ">
                                                <form role="form">
                                                        <div class="col-sm-6">                                                      
                                                    <h2 class="text-success" align="left" style="font-weight: bold;">Account Contact</h2>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">First Name</label>
                                                                <div class="col-sm-8" style="padding-bottom: 3%;">
                                                                    <input type="text" placeholder="First name" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Last Name</label>
                                                                <div class="col-sm-8" style="padding-bottom: 3%;"><input placeholder="Last Name" class="form-control" type="text"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Email</label>
                                                                <div class="col-sm-8" style="padding-bottom: 3%;"><input placeholder="Email" class="form-control" type="text"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Phone Number</label>
                                                                <div class="col-sm-8" style="padding-bottom: 3%;"><input type="text" class="form-control" data-mask="(999) 999-9999" placeholder="Phone Number"></div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Company Name</label>
                                                                <div class="col-sm-8" style="padding-bottom: 3%;"><input placeholder="Company Name" class="form-control" type="text"></div>
                                                            </div>                                                      
                                                    <h2 class="text-success" align="left" style="font-weight: bold;">Billing Information</h2>
                                                                <div class="form-group">                                                              
                                                                    <label class="col-sm-4 control-label">Address</label>
                                                                    <div class="col-sm-8" style="padding-bottom: 3%;"><input type="text" placeholder="Address" class="form-control"></div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">City</label>
                                                                    <div class="col-sm-8" style="padding-bottom: 3%;"><input placeholder="City" class="form-control" type="text"></div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">Zip Code</label>
                                                                    <div class="col-sm-8" style="padding-bottom: 3%;"><input placeholder="Zip" class="form-control" type="text"></div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">Country</label>                                                                           
                                                                    <div class="col-sm-8" style="padding-bottom: 5%;">
                                                                        <select class="form-control" required>
                                                                            <option>Select</option>
                                                                            <option>Company</option>
                                                                            <option>Individual</option>
                                                                        </select>
                                                                    </div>
                                                                </div>                                                      
                                                                <!-- <div id="StayConnected">
                                                                    <h3>Stay Connected</h3>
                                                                        
                                                                        <label class="col-xs-2 col-md-1 control-label"><i class="fa fa-linkedin-square"></i></label>
                                                                        <div class="col-xs-10 col-md-5"><input type="text" placeholder="LinkedIn Account" class="form-control"></div>
                                                                        <label class="col-xs-2 col-md-1 control-label"><i class="fa fa-facebook-square"></i></label>
                                                                        <div class="col-xs-10 col-md-5"><input type="text" placeholder="Facebook Account" class="form-control"></div>
                                                                        <label class="col-xs-2 col-md-1 control-label"><i class="fa fa-instagram"></i></label>
                                                                        <div class="col-xs-10 col-md-5"><input type="text" placeholder="Instragram Account" class="form-control"></div>
                                                                        <label class="col-xs-2 col-md-1 control-label"><i class="fa fa-twitter-square"></i></label>
                                                                        <div class="col-xs-10 col-md-5"><input type="text" placeholder="Twitter Account" class="form-control"></div>
                                                                        
                                                                </div> -->
                                                        </div>
                                                    <br />
                                                        <!-- right box -->
                                                        <div class="col-sm-6 b-r">
                                                            
                                                                <br/>                                                                                                            
                                                                
                                                                <h2 class="text-success" align="left" style="font-weight: bold;">Payment Information</h2>
                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">Payment Method</label>    
                                                                    <div class="col-sm-8" style="padding-bottom: 3%;">
                                                                        <select class="form-control" required>
                                                                            <option>Select</option>
                                                                            <option>Pay-Pal</option>
                                                                            <option>Wire-Bank(Fee May Apply)</option>
                                                                            <option>ACH</option>
                                                                        </select>
                                                                    </div>
                                                                    <label class="col-sm-4 control-label">Minimum Payout</label>    
                                                                    <div class="col-sm-8" style="padding-bottom: 3%;">
                                                                        <select class="form-control" required>
                                                                            <option>Select</option>
                                                                            <option>250</option>
                                                                            <option>500</option>
                                                                            <option>1000</option>
                                                                            <option>5000</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <h2 class="text-success" align="left" style="font-weight: bold;">Tax Info</h2>
                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">Tax Status</label>    
                                                                    <div class="col-sm-8" style="padding-bottom: 3%;">
                                                                        <select class="form-control" required>
                                                                            <option>Select</option>
                                                                            <option>Company</option>
                                                                            <option>Individual</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Vat/Tax ID</label>
                                                                <div class="col-sm-8" style="padding-bottom: 3%;"><input placeholder="Vat/Tax ID" class="form-control" type="text"></div>
                                                            </div>
                                                            <div class="col-sm-8" align="mid" style="padding-bottom: 3%;"><input placeholder="Future W9 Form" class="form-control" type="text"></div>                                                 
                                                            
                                                        </div>
                                                    <div>
                                                        
                                                        <div class="col-xs-12">
                                                        <div align="center" style="padding-bottom: 3%; padding-top: 3%;"><button type="button" class="btn btn-primary btn-lg">Submit</button></div>
                                                        <hr>
                                                        <h2 class="text-success text-center" align="left" style="font-weight: bold; padding-top: 3%;">Change Password</h2>
                                                        </div>
                                                                <div class="form-group">
                                                                    <label align="right" class="col-sm-4 control-label" align-items="center" display="flex">Existing Password</label>
                                                                    <div class="col-sm-8" style="padding-bottom: 3%; min-width: 100px; max-width: 550px;"><input placeholder="Password" class="form-control" type="password"> </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label align="right" class="col-sm-4 control-label" align-items="center" display="flex">New Password</label>
                                                                    <div class="col-sm-8" style="padding-bottom: 3%; min-width: 100px; max-width: 550px;"><input type="password" class="form-control" name="password" placeholder="Change password"></div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label align="right" class="col-sm-4 control-label" align-items="center" display="flex">Confirm Password</label>
                                                                    <div class="col-sm-8" style="padding-bottom: 2%; min-width: 100px; max-width: 550px;"><input type="password" class="form-control" name="password" placeholder="Confirm password"></div>
                                                                </div>
                                                                
                                                                <br/>
                                                            <div class="col-xs-12">
                                                                    <style>
                                                                            hr { 
                                                                                display: block;
                                                                                margin-top: 0.5em;
                                                                                margin-bottom: 0.5em;
                                                                                margin-left: auto;
                                                                                margin-right: auto;
                                                                                border-style: inset;
                                                                                border-width: 1px;
                                                                                border-color:#1c84c6
                                                                            } 
                                                                    </style>
                                                                  
                                                                <div align="center" style="padding-bottom: 3%;"><button type="button" class="btn btn-primary btn-lg">Submit</button></div>
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
                </div>
            </div>
        </div>
    </div>
<!-- Mainly scripts -->
<script src="js/jquery-2.1.1.js"></script>
<script src="js/jquery-ui-1.10.4.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.metisMenu.js"></script>
<script src="js/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="js/inspinia.js"></script>
<script src="js/pace.min.js"></script>

<!-- iCheck -->
<script src="js/icheck.min.js"></script>

<!-- Jvectormap -->
<script src="js/jquery-jvectormap-2.0.2.min.js"></script>
<script src="js/jquery-jvectormap-world-mill-en.js"></script>

<!-- Flot -->
<script src="js/jquery.flot.js"></script>
<script src="js/jquery.flot.tooltip.min.js"></script>
<script src="js/jquery.flot.resize.js"></script>

<!-- file upload -->
<script src="js/dropzone.js"></script>
	
<!--DateRange-->
	    <script src="js/ion.rangeSlider.min.js"></script>	
<!-- Input Mask-->	
	<script src="js/jasny-bootstrap.min.js"></script>
<!-- Date range use moment.js same as full calendar plugin -->
	<script src="js/moment.min.js"></script>
	
	<script src="js/bootstrap-datepicker.js"></script>	

	<script src="js/daterangepicker.js"></script>	
	
 	<script src="js/main.js"></script>

</body>
</html>