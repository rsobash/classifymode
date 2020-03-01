<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title></title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script src="jquery-1.8.1.min.js" type="text/javascript" charset="utf-8"></script>
<script src="poll.js" type="text/javascript" charset="utf-8"></script>
</head>

<body>
<div id="container">

<div id="header">
	<div id="leftheader"><img src="noaa.png" style="width:4%;padding-right:10px;"><img src="ncar.jpg" style="width:10%; padding-right:10px;"><a href="">Classifying convective mode in CAMs</a></div>
        <div id="rightheader"><span class="username"></span><br/><span class="numclassified"></span></div>
</div>

<div id="main">
	<div id="progressbar"><div id="expnum"></div></div>

<div id="text1">

<div id="intro">
	<div id="intro1text">
	<b>Welcome to the classifying convective mode in CAMs project!</b><br/>
	</div>

	<div id="identry">
	Please enter your username and press Continue.<br/><br/>
	<input id="idnumber" type="text" name="idnumber" maxlength="12"><br/>
        <input id="text1button" type="submit" name="reset" value="Continue">
	</div>
</div>

</div> <!-- end text1 -->

<div id="text2">
<div id="rules">
<h2>Thanks for your interest in participating in the classifying convective mode in convection-allowing models project!</h2>
You will be presented with a series of images of simulated convective storms that were extracted from 3-km WRF model simulations across a diverse collection of convective events that occurred within the conterminous United States.<br/><br/>
Using your meteorological knowledge and available imagery, you will label the convective mode of each simulated storm. Three primary categories (quasi-linear convective system, supercell, and disorganized) are used for classification and were chosen to mimic the classifications used in <a href="https://journals.ametsoc.org/doi/full/10.1175/WAF-D-11-00115.1">Smith et al. (2012)</a>. Subcategories exist that provide added detail within each of the three primary categories. An annotated version of the classification interface is shown below:<br/><br/>
<img src="classify.png" style="display:block;margin:0 auto;width:60%;"/><br/>
Each storm is located at the center of the image and is outlined with a thick black contour. The images contain a filled composite reflectivity field, 10-m surface wind barbs, and both positive and negative 2–5-km updraft helicity contours. Other imagery includes 2-m temperature, with composite reflectivity contours overlaid.<br/><br/>
To help with classification, images 2 hours before and after the storm can be accessed with rollover links. There often will be other storms within each image; <b><i>only classify the storm that is outlined with a black contour at the central time.</b></i><br/><br/>
In addition to the mode classification, you will provide a confidence level on a scale of 1-5 (5 indicating high confidence, and 1 indicating low confidence). <b><i>You must select one mode classification and a confidence rating for each storm to proceed to the next storm.</b></i> If you are unsure of how to classify a particular storm, provide your best guess for the classification and indicate your uncertainty with a low confidence rating.<br/><br/>
Please direct any questions or feedback to sobash@ucar.edu. Thanks again for contributing! Click "Begin" below to start classifying.
</div>

<input id="text2button" type="submit" name="trial" value="Begin"></div>

</div> <!-- end text2 -->

<div id="img-container">
    <div id="img"></div>

    <div id="rollover-container" class="shaded-block">
    <div id="m2" class="rollover hour">-2hr</div>
    <div id="m1" class="rollover hour">-1hr</div>
    <div id="p0" class="rollover hour">0hr</div>
    <div id="p1" class="rollover hour">+1hr</div>
    <div id="p2" class="rollover hour">+2hr</div>

    <div id="cref" class="rollover field">CREF</div>
    <div id="t2" class="rollover field">T2</div>

    <br style="clear:both;"/>
    </div>

</div>

<div id="text-container">

<div id="classify-container">

<div id="classify-type" class="shaded-block">

<div id="subclassify-qlcs" class="subclassify">
<h4>Quasi-Linear Convective System</h4>
<button type="button" class="typebutton" id="qlcs1" name="Q1">Well-developed Bow Echo</button><br/>
<button type="button" class="typebutton" id="qlcs2" name="Q2">Squall Line or smaller-scale convective line</button>
</div>

<div id="subclassify-supercell" class="subclassify">
<h4>Supercell</h4> 
<button type="button" class="typebutton" id="supercell1" name="S1">Discrete Supercell</button><br/>
<button type="button" class="typebutton" id="supercell2" name="S2">Supercell embedded within line</button><br/>
<button type="button" class="typebutton" id="supercell3" name="S3">Supercell embedded within cell cluster</button>
</div>

<div id="subclassify-disorganized" class="subclassify">
<h4>Disorganized</h4>
<button type="button" class="typebutton" id="disorganized1" name="D1">Discrete cell</button><br/>
<button type="button" class="typebutton" id="disorganized2" name="D2">Discrete cluster</button>
</div>

</div> <!-- end classify-type -->
<br/>
<div id="classify-confidence" class="shaded-block">
<h4>Classification confidence</h4>
<button class="confbutton" name="1" type="button">1</button>
<button class="confbutton" name="2" type="button">2</button>
<button class="confbutton" name="3" type="button">3</button>
<button class="confbutton" name="4" type="button">4</button>
<button class="confbutton" name="5" type="button">5</button><br/>
Low confidence (1)  -----  High confidence (5)
</div>
<br/>
<div id="classify-submit" class="shaded-block">
<button class="submitbutton" type="button">Classify!</button>
</div>

</div>

</div> <!-- end text-container -->


</div>
</body>
</html>
