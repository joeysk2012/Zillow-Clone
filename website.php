<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">

    <!-- Latest compiled and minified CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/readable/bootstrap.min.css" rel="stylesheet" integrity="sha384-Li5uVfY2bSkD3WQyiHX8tJd0aMF91rMrQP5aAewFkHkVSTT2TmD2PehZeMmm7aiL" crossorigin="anonymous">

</head>
<body>

<header>
</header>
<!-- A navbar-->
<section class="container-fluid">

    <div class="bs-component">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Real Estate App</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="http://www.zillow.com">Zillow API <span class="sr-only">(current)</span></a></li>
                        <li><a href="http://ip-api.com/docs/api:json">Location API</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Other Links <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="http://freecodecamp.com">Freecodcamp</a></li>
                                <li><a href="http:///getbootstrap.com">Bootstrap</a></li>
                                <li><a href="http:///biggerpockets.com">BiggerPockets</a></li>
                                <li><a href="#"></a></li>
                                <li class="divider"></li>

                            </ul>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
    </div>


</section>

<!-- end navbar -->

<div class="container">
    <div class="row">
        <div class="col-lg-7">

            <p class="bs-component">
                <a class="btn btn-default" id="propertychart">Property Chart</a>
                <a class="btn btn-primary" id="nhoodchart">Neighborhood Chart</a>
                <a class="btn btn-success">Success</a>
            </p>
<!-- A Map of the area -->
<!--end map-->


<!--some php-->
<?php
    $zillow_id = 'X1-ZWz19g3j9ffabv_7galu'; #the zillow web service ID that you got from your email
    $search = $_GET['address']; #gets input text field with name address
    $citystate =  $_GET['citystatezip']; #gets input text field with name citystatezip
    $address = urlencode($search); #makes string usable for a url
    $citystatezip = urlencode($citystate); #makes string usable for a url
    $url1 = "http://www.zillow.com/webservice/GetDeepSearchResults.htm?zws-id=$zillow_id&address=$address&citystatezip=$citystatezip";
    $result1 = file_get_contents($url1); #this command converts the file into a string.
    $data1 = simplexml_load_string($result1); #takes a well-formed XML string and returns object.
    $zpid=$data1->response->results->result[0]->zpid; #get the zpid
    $url2 = "http://www.zillow.com/webservice/GetChart.htm?zws-id=$zillow_id&unit-type=percent&zpid=$zpid&width=600&height=300"; #api call to get the chart with the property value
    $results2= file_get_contents($url2);#get the file contets
    $data2=simplexml_load_string($results2); #get
    $chart=$data2->response->url; #url tag of the chart
    $street=$data1->response->results->result[0]->address->street; #from here down we get various variables to put into our table.
    $city=$data1->response->results->result[0]->address->city;
    $state=$data1->response->results->result[0]->address->state;
    $zipcode=$data1->response->results->result[0]->address->zipcode;
    $address="$street $city, $state $zipcode";
    $zestimate=$data1->response->results->result[0]->zestimate->amount;
    $zestimate_num= number_format(floatval($zestimate)); #convert to a standard comma format.
    $lastupdated=$data1->response->results->result[0]->zestimate->{'last-updated'};
    $vlow=$data1->response->results->result[0]->zestimate->valuationRange->low;
    $vlow_num= number_format(floatval($vlow));#convert to a standard comma format.
    $vhigh=$data1->response->results->result[0]->zestimate->valuationRange->high;
    $vhigh_num= number_format(floatval($vhigh));#convert to a standard comma format.
    $url3 ="http://www.zillow.com/webservice/GetRegionChart.htm?zws-id=$zillow_id&unit-type=dollar&width=600&height=300&chartDuration=10yrs&city=$city&state=$state"; #this api is broken does not work.
    $result3 = file_get_contents($url3);
    $data3 = simplexml_load_string($result3);
    $nchart=$data3->response->url;

    ?>
<h1 class="'text-primary">Property Chart</h1>
<img src="<?php echo$chart?>"/>

<!--end php--->



    <h1 class='text-primary'>Property Lookup</h1>
    <form action="website.php" method="get" class="form-inline" id="textForm">
        <div class="form-group">
            <input type="text" class="form-control" id="stateInput" placeholder="Address" name="address"/>
            <input type="text" class="form-control" id="stateInput" placeholder="City State Zip" name="citystatezip"/>
        </div>
        <div class="form-group">
            <button id="getcity" type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

    <!-- table -->




    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h1 id="tables">Data:</h1>
            </div>
            <div class="bs-component">
                <table class="table table-striped table-hover ">
                    <thead>
                    <tr>
                        <th id="city"></th>
                        <th id="country"></th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Address</td>
                        <td id="address"><?php echo($address)?></td>
                    </tr>
                    <tr>
                        <td>zpid</td>
                        <td id="zpid"><?php echo($zpid)?></td>
                    </tr>
                    <tr>
                        <td>zestimate</td>
                        <td id="zestimate"><?php echo($zestimate_num)?></td>
                    </tr>
                    <tr>
                        <td>last-updated</td>
                        <td id="last-updated"><?php echo($lastupdated)?></td>
                    </tr>
                    <tr>
                        <td>valuation low</td>
                        <td id="low"><?php echo($vlow_num)?></td>
                    </tr>
                    <tr>
                        <td>valuation high</td>
                        <td id="high"><?php echo($vhigh_num)?></td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- /example -->
        </div>
    </div>
    <!-- end table -->


    </section>
    <footer>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFNugRcffgCxskh8aN8wNTK04eNSinvUk&callback=initMap"
                async defer></script>
        <div class="wrapper">
            RealEstate App- by Joe K.
        </div>
    </footer>
        </div>
</div>
</body>
</html>