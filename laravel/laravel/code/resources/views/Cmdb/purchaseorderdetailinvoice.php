<div class="panel invoice-panel">
                    <div class="panel-heading">
                        <span class="panel-title">
                            <span class="glyphicon glyphicon-print"></span> Printable Invoice</span>
                        <div class="panel-header-menu pull-right mr10">
                            <button type="button" class="btn btn-xs btn-default btn-gradient mr5"> <i class="fa fa-plus-square pr5"></i> New Invoice</button>
                            <a href="javascript:window.print()" class="btn btn-xs btn-default btn-gradient mr5"> <i class="fa fa-print fs13"></i> </a>
                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-default btn-gradient dropdown-toggle" data-toggle="dropdown">
                                    <span class="glyphicons glyphicons-cogwheel"></span>
                                </button>
                                <ul class="dropdown-menu checkbox-persist pull-right text-left" role="menu">
                                    <li>
                                        <a><i class="fa fa-user"></i> View Profile </a>
                                    </li>
                                    <li>
                                        <a><i class="fa fa-envelope-o"></i> Message </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body p20" id="invoice-item">

                        <div class="row mb30">
                            <div class="col-md-4">
                                <div class="pull-left">
                                    <h1 class="lh10 mt10"> INVOICE </h1>
                                    <h5 class="mn"> Created: Nov 23 2013 </h5>
                                    <h5 class="mn"> Status: <b class="text-success">Paid - On Time</b> </h5>
                                </div>
                            </div>
                            <div class="col-md-4"> <img src="assets/img/logos/logo.png" class="img-responsive center-block mw200 hidden-xs" alt="AdminDesigns"> </div>
                            <div class="col-md-4">
                                <div class="pull-right text-right">
                                    <h2 class="invoice-logo-text hidden lh10">AdminDesigns</h2>
                                    <h5> Sales Rep: <b class="text-primary">Michael Ronny</b> </h5>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="invoice-info">
                            <div class="col-md-4">
                                <div class="panel panel-alt">
                                    <div class="panel-heading">
                                        <span class="panel-title"> <i class="fa fa-user"></i> Bill To: </span>
                                        <div class="panel-btns pull-right ml10">
                                            <span class="panel-title-sm"> Edit</span>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <address>
                                            <strong>Cannon Camera</strong>
                                            <br> 151 Sandy Ave, Suite 200
                                            <br> San Jose, CA 91503
                                            <br>
                                            <abbr title="Phone">P:</abbr> (123) 456-7890
                                        </address>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-alt">
                                    <div class="panel-heading">
                                        <span class="panel-title"> <i class="fa fa-location-arrow"></i> Ship To:</span>
                                        <div class="panel-btns pull-right ml10">
                                            <span class="panel-title-sm"> Edit</span>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <address>
                                            <strong>Amazon, Inc.</strong>
                                            <br> 795 Folsom Ave, Suite 600
                                            <br> San Francisco, CA 94107
                                            <br>
                                            <abbr title="Phone">P:</abbr> (123) 456-7890
                                        </address>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-alt">
                                    <div class="panel-heading">
                                        <span class="panel-title"> <i class="fa fa-info"></i> Invoice Details: </span>
                                        <div class="panel-btns pull-right ml10"> </div>
                                    </div>
                                    <div class="panel-body">
                                        <ul class="list-unstyled">
                                            <li> <b>Invoice #:</b> 58126332</li>
                                            <li> <b>Invoice Date:</b> 10 Oct 2013</li>
                                            <li> <b>Due Date:</b> 21 Dec 2013</li>
                                            <li> <b>Terms:</b> Ten Forty</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="invoice-table">
                            <div class="col-md-12">
                                <table class="table table-striped table-condensed">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Description</th>
                                            <th style="width: 135px;">Quanitity</th>
                                            <th>Rate</th>
                                            <th class="text-right pr10">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>3</b>
                                            </td>
                                            <td>Net Code Revamp</td>
                                            <td>Worked on Design and Structure (per hour)</td>
                                            <td>16</td>
                                            <td>$35.00</td>
                                            <td>$560.00</td>
                                        </tr>
                                        <tr>
                                            <td><b>1</b>
                                            </td>
                                            <td>Developer Newsletter </td>
                                            <td>Year Subscription X2</td>
                                            <td>2</td>
                                            <td>$12.99</td>
                                            <td>$25.98</td>
                                        </tr>
                                        <tr>
                                            <td><b>3</b>
                                            </td>
                                            <td>Web Development</td>
                                            <td>Worked on Design and Structure (per hour)</td>
                                            <td>23</td>
                                            <td>$30.00</td>
                                            <td>$690.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row" id="invoice-footer">
                            <div class="col-md-12">
                                <div class="pull-left mt20 fs15 text-primary"> Thank you for your business.</div>
                                <div class="pull-right">
                                    <table class="table" id="invoice-summary">
                                        <thead>
                                            <tr>
                                                <th><b>Sub Total:</b>
                                                </th>
                                                <th>$1375.98</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><b>Total:</b>
                                                </td>
                                                <td>$1375.98</td>
                                            </tr>
                                            <tr>
                                                <td><b>Payments</b>
                                                </td>
                                                <td>(-)0.00</td>
                                            </tr>
                                            <tr>
                                                <td><b>Total</b>
                                                </td>
                                                <td>$230.00</td>
                                            </tr>
                                            <tr>
                                                <td><b>Balance Due:</b>
                                                </td>
                                                <td>$1375.98</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                             
 
                            </div>
                        </div>

                    </div>
                </div>