<?php
require '../includes/connect.php';

require 'includes/playerdata.php';

$mapnum = $_GET['map'];
$mapnum = stripslashes($mapnum);
$mapnum = mysql_real_escape_string($mapnum);
?>
<html>
<head>
<title>War Room</title>
<link rel="stylesheet" type="text/css" href="mapstyles.css" />
<script type="text/javascript" src="includes/jquery.js"></script>
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js"></script>
</head>
<body>
<?php
	$getplayertroops = mysql_query("SELECT troops FROM $factiondb WHERE factionuser='$username' AND factionname = '$chosencolony'");
	$playertroops = mysql_result($getplayertroops, 0);
			
		switch($allegiance)
		{
			case 'authority':
			$bombtype='Conversion Bombs';
			$shieldtype='Theatre Shields';
			break;
			case 'solidarity':
			$bombtype='Nuclear Warheads';
			$shieldtype='Bunkers';
			break;
			case 'mercantile':
			$bombtype='Kinetic Impactors';
			$shieldtype='Interceptors';
			break;
		}
?>

<script type="text/javascript">
$(document).ready(function() {
	$(".tab_content").hide();
	$(".tab_content:first").show(); 

	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");
		$(".tab_content").hide();
		var activeTab = $(this).attr("rel"); 
		$("#"+activeTab).fadeIn(); 
	});
});

$(document).ready(function() {
   $("#bgPopup").data("state",0);
   //Fortitude Sector
   $("#fortitudepop").click(function(){
		showLoading('3');
		centerPopup();
		loadPopup();
   });
   $("#rheapop").click(function(){
		showLoading('4');
		centerPopup();
		loadPopup();
   });
   $("#ximupop").click(function(){
		showLoading('5');
		centerPopup();
		loadPopup();
   });
   $("#novogradpop").click(function(){
		showLoading('6');
		centerPopup();
		loadPopup();
   });
   $("#amibiapop").click(function(){
		showLoading('7');
		centerPopup();
		loadPopup();
   });
   $("#gagarinpop").click(function(){
		showLoading('8');
		centerPopup();
		loadPopup();
   });
   $("#yekaterinbergpop").click(function(){
		showLoading('9');
		centerPopup();
		loadPopup();
   });
    $("#shenzenpop").click(function(){
		showLoading('10');
		centerPopup();
		loadPopup();
   });
   $("#tshwanepop").click(function(){
		showLoading('11');
		centerPopup();
		loadPopup();
   });
   $("#novosibirskpop").click(function(){
		showLoading('12');
		centerPopup();
		loadPopup();
   });
   //Liberty Sector
   $("#munduspop").click(function(){
		showLoading('2');
		centerPopup();
		loadPopup();   
   });
   $("#londonpop").click(function(){
		showLoading('13');
		centerPopup();
		loadPopup();  
   });
   $("#nihonpop").click(function(){
		showLoading('14');
		centerPopup();
		loadPopup();  
   });
   $("#shikokupop").click(function(){
		showLoading('15');
		centerPopup();
		loadPopup();  
   });
   $("#sierrapop").click(function(){
		showLoading('16');
		centerPopup();
		loadPopup();  
   });
   $("#titaniapop").click(function(){
		showLoading('17');
		centerPopup();
		loadPopup();  
   });
   $("#osirispop").click(function(){
		showLoading('18');
		centerPopup();
		loadPopup();  
   });
   $("#silverpop").click(function(){
		showLoading('19');
		centerPopup();
		loadPopup();  
   });
   $("#serenitypop").click(function(){
		showLoading('20');
		centerPopup();
		loadPopup();  
   });
   $("#zephyrpop").click(function(){
		showLoading('21');
		centerPopup();
		loadPopup();  
   });
   //Centauri Sector
   $("#centauripop").click(function(){
		showLoading('1');
		centerPopup();
		loadPopup();  
   });
   $("#solpop").click(function(){
		showLoading('22');
		centerPopup();
		loadPopup();  
   });
   $("#wolfpop").click(function(){
		showLoading('23');
		centerPopup();
		loadPopup();  
   });
   $("#procyonpop").click(function(){
		showLoading('24');
		centerPopup();
		loadPopup();  
   });
   $("#lalandepop").click(function(){
		showLoading('25');
		centerPopup();
		loadPopup();  
   });
   $("#geridanipop").click(function(){
		showLoading('26');
		centerPopup();
		loadPopup();  
   });
   $("#kapteynpop").click(function(){
		showLoading('27');
		centerPopup();
		loadPopup();  
   });
   $("#luytenpop").click(function(){
		showLoading('28');
		centerPopup();
		loadPopup();  
   });
   $("#eridanipop").click(function(){
		showLoading('29');
		centerPopup();
		loadPopup();  
   });
   $("#siriuspop").click(function(){
		showLoading('30');
		centerPopup();
		loadPopup();  
   });
   //Erebus Sector
   $("#hr753pop").click(function(){
		showLoading('31');
		centerPopup();
		loadPopup();  
   });
   $("#draconispop").click(function(){
		showLoading('32');
		centerPopup();
		loadPopup();  
   });
   $("#avernuspop").click(function(){
		showLoading('33');
		centerPopup();
		loadPopup();  
   });
   $("#styxpop").click(function(){
		showLoading('34');
		centerPopup();
		loadPopup();  
   });
   $("#omskpop").click(function(){
		showLoading('35');
		centerPopup();
		loadPopup();  
   });
   $("#samarapop").click(function(){
		showLoading('36');
		centerPopup();
		loadPopup();  
   });
   $("#tianjinpop").click(function(){
		showLoading('37');
		centerPopup();
		loadPopup();  
   });
   $("#shantoupop").click(function(){
		showLoading('38');
		centerPopup();
		loadPopup();  
   });
   $("#saratovpop").click(function(){
		showLoading('39');
		centerPopup();
		loadPopup();  
   });
   $("#pretoriapop").click(function(){
		showLoading('40');
		centerPopup();
		loadPopup();  
   });
   //Hadrian Sector
   $("#khartoumpop").click(function(){
		showLoading('41');
		centerPopup();
		loadPopup();  
   });
   $("#kazanpop").click(function(){
		showLoading('42');
		centerPopup();
		loadPopup();  
   });
   $("#kamchatkapop").click(function(){
		showLoading('43');
		centerPopup();
		loadPopup();  
   });
   $("#hangzoupop").click(function(){
		showLoading('44');
		centerPopup();
		loadPopup();  
   });
   $("#irkutskpop").click(function(){
		showLoading('45');
		centerPopup();
		loadPopup();  
   });
   $("#vanaheimpop").click(function(){
		showLoading('46');
		centerPopup();
		loadPopup();  
   });
   $("#melbournepop").click(function(){
		showLoading('47');
		centerPopup();
		loadPopup();  
   });
   $("#newmountpop").click(function(){
		showLoading('48');
		centerPopup();
		loadPopup();  
   });
   $("#yutanipop").click(function(){
		showLoading('49');
		centerPopup();
		loadPopup();  
   });
   $("#avalonpop").click(function(){
		showLoading('50');
		centerPopup();
		loadPopup();  
   });
   //Maginot Sector
   $("#capellapop").click(function(){
		showLoading('51');
		centerPopup();
		loadPopup();  
   });
   $("#polluxpop").click(function(){
		showLoading('52');
		centerPopup();
		loadPopup();  
   });
   $("#aquilaepop").click(function(){
		showLoading('53');
		centerPopup();
		loadPopup();  
   });
   $("#prosperitypop").click(function(){
		showLoading('54');
		centerPopup();
		loadPopup();  
   });
   $("#solemnitypop").click(function(){
		showLoading('55');
		centerPopup();
		loadPopup();  
   });
   $("#fortunapop").click(function(){
		showLoading('56');
		centerPopup();
		loadPopup();  
   });
   $("#emergencepop").click(function(){
		showLoading('57');
		centerPopup();
		loadPopup();  
   });
   $("#hiberniapop").click(function(){
		showLoading('58');
		centerPopup();
		loadPopup();  
   });
   $("#naogeddonpop").click(function(){
		showLoading('59');
		centerPopup();
		loadPopup();  
   });
   $("#whiterainpop").click(function(){
		showLoading('60');
		centerPopup();
		loadPopup();  
   });
   //Everything Else
   $("#popupClose").click(function(){
	   	disablePopup();
   });
   $(document).keypress(function(e){
		if(e.keyCode==27) {
			disablePopup();	
		}
	});
	
});


//Recenter the popup on resize - Thanks @Dan Harvey [http://www.danharvey.com.au/]
$(window).resize(function() {
centerPopup();
});
</script>
<br><center><ul class="navul"><li><a href="index.php">Return to Main</a></li></ul></center>
<?php
if($mapnum > 0)
{
	echo '<br><center><ul class="navul"><li><a href="warmap2.php?map=0">Galaxy Overview</a></li></ul></center>';
}
?>
<br><br>
<center>
<?php
if($mapnum==0)
{
	echo'<img src="warmapfiles/starfield3.jpg" usemap="#warmap"></center>
	<map name="warmap">
		<area shape="poly" coords="2,10,10,25,19,40,31,53,43,70,51,81,62,95,72,106,84,120,95,133,110,145,124,156,136,166,149,177,166,190,180,200,198,213,215,225,234,235,249,243,265,251,281,255,295,263,316,271,336,279,355,285,374,292,395,296,427,301,446,304,468,306,490,306,512,306,537,308,556,308,574,303,595,299,620,295,646,292,661,287,686,280,707,273,727,265,745,257,766,250,783,242,798,233,816,226,829,217,846,207,863,195,876,185,891,176,908,162,921,144,932,136,944,125,954,110,965,96,975,84,984,70,993,57,999,44,1006,34,1009,19,1016,9,1014,3,4,6," href="warmap2.php?map=1" alt="Fortitude Sector" title=""   />
		<area shape="poly" coords="7,762,447,759,448,738,447,721,444,705,442,691,441,678,438,662,433,650,430,638,426,625,421,613,416,599,411,587,403,573,395,559,388,544,379,531,369,516,359,504,347,492,334,478,319,465,307,450,290,432,274,426,268,416,251,404,238,393,211,381,189,375,168,364,146,356,128,351,104,348,88,341,57,341,32,337,7,339," href="warmap2.php?map=3" alt="Liberty Sector" title=""   />
		<area shape="poly" coords="570,762,1014,759,1016,335,1000,337,975,338,952,341,930,344,909,351,886,359,861,367,842,372,822,381,802,392,782,403,762,415,745,427,728,442,709,460,694,478,678,498,666,513,655,529,645,543,629,567,619,584,609,605,602,624,594,644,587,666,583,695,580,714,577,736,576,753," href="warmap2.php?map=2" alt="Centauri Sector" title=""   />
		<area shape="poly" coords="525,339,526,360,527,382,532,399,541,416,550,432,560,446,570,458,583,470,598,482,612,490,628,499,644,486,658,475,675,460,687,446,703,436,716,422,738,407,752,394,767,386,783,375,802,368,813,360,831,352,852,345,872,337,888,332,910,327,933,322,953,320,973,319,996,317,1015,314,1018,68,1006,87,994,105,980,120,966,136,954,149,938,166,921,182,901,195,885,209,859,229,837,243,814,256,794,267,770,277,747,286,728,296,704,300,680,310,656,316,623,321,599,325,571,328,548,330,527,331," href="warmap2.php?map=4" alt="Erebus Sector" title=""   />
		<area shape="poly" coords="7,60,8,293,35,296,58,298,82,302,104,307,127,311,151,316,173,324,194,334,218,342,235,352,252,364,270,373,287,383,304,396,318,408,335,426,350,441,367,457,381,475,390,492,409,492,426,483,443,474,457,461,469,449,479,436,489,421,489,410,483,391,485,373,484,361,485,346,484,332,463,327,438,325,416,323,394,320,372,315,352,311,329,304,309,299,287,292,267,281,237,272,219,260,191,246,171,233,157,223,136,208,121,197,105,181,81,159,62,139,48,118,33,96,22,85," href="warmap2.php?map=5" alt="Hadrian Sector" title=""   />
		<area shape="poly" coords="410,524,431,515,455,502,473,488,493,473,504,459,518,479,533,490,551,505,568,517,585,527,602,534,607,549,596,566,589,585,584,601,575,612,570,629,566,648,560,672,555,694,553,711,552,733,548,756,517,756,494,759,473,758,468,741,468,714,465,695,461,674,458,654,452,632,445,614,441,594,432,581,426,560,417,545,416,536," href="warmap2.php?map=6" alt="Maginot Sector" title=""   />
	</map>';
}
elseif($mapnum==1)
{
	echo'<img src="warmapfiles/Fortitude2.png" usemap="#fortsectormap"></center>
	<map name="fortsectormap">
		<area shape="circle" coords="495,53,18" href="#" id="fortitudepop" alt="Fortitude">
		<area shape="circle" coords="457,162,18" href="#" id="rheapop" alt="Rhea">
		<area shape="circle" coords="687,180,18" href="#" id="ximupop" alt="Xi-Mu">
		<area shape="circle" coords="277,217,18" href="#" id="novogradpop" alt="Novograd">
		<area shape="circle" coords="512,276,18" href="#" id="amibiapop" alt="Amibia">
		<area shape="circle" coords="795,297,18" href="#" id="gagarinpop" alt="Gagarin">
		<area shape="circle" coords="89,350,18" href="#" id="tshwanepop" alt="Tswhane">
		<area shape="circle" coords="296,385,18" href="#" id="shenzenpop" alt="Shenzen">
		<area shape="circle" coords="705,407,18" href="#" id="yekaterinbergpop" alt="Yekaterinberg">
		<area shape="circle" coords="925,387,18" href="#" id="novosibirskpop" alt="Novosibirsk">
	</map>';
}
elseif($mapnum==2)
{
	echo'<img src="warmapfiles/Centauri2.png" usemap="#centauriwarmap"></center>
	<map name="centauriwarmap">
		<area shape="circle" coords="856,638,18" href="#" id="centauripop" alt="Alpha Centauri">
		<area shape="circle" coords="808,434,18" href="#" id="solpop" alt="Sol">
		<area shape="circle" coords="554,590,18" href="#" id="lalandepop" alt="Lalande 21185">
		<area shape="circle" coords="620,42,18" href="#" id="eridanipop" alt="p Eridani">
		<area shape="circle" coords="465,92,18" href="#" id="geridanipop" alt="G Eridani">
		<area shape="circle" coords="644,250,18" href="#" id="kapteynpop" alt="Kapteyn">
		<area shape="circle" coords="506,392,18" href="#" id="wolfpop" alt="Wolf 359">
		<area shape="circle" coords="80,298,18" href="#" id="luytenpop" alt="Luyten\'s Star">
		<area shape="circle" coords="76,543,18" href="#" id="procyonpop" alt="Procyon">
		<area shape="circle" coords="300,459,18" href="#" id="siriuspop" alt="Sirius">
	</map>';
}
elseif($mapnum==3)
{
	echo'<img src="warmapfiles/liberty.png" usemap="#libertywarmap"></center>
	<map name="libertywarmap">
		<area shape="circle" coords="84,530,18" href="#" id="munduspop" alt="Sanctuary">
		<area shape="circle" coords="102,410,18" href="#" id="londonpop" alt="Londinium">
		<area shape="circle" coords="320,506,18" href="#" id="nihonpop" alt="Nihon">
		<area shape="circle" coords="244,386,18" href="#" id="shikokupop" alt="Shikoku">
		<area shape="circle" coords="410,362,18" href="#" id="sierrapop" alt="Sierra">
		<area shape="circle" coords="134,208,18" href="#" id="titaniapop" alt="Titania">
		<area shape="circle" coords="560,480,18" href="#" id="osirispop" alt="Osiris">
		<area shape="circle" coords="508,184,18" href="#" id="silverpop" alt="Silvermonde">
		<area shape="circle" coords="268,117,18" href="#" id="serenitypop" alt="Serenity">
		<area shape="circle" coords="52,52,18" href="#" id="zephyrpop" alt="Zephyrrous">
	</map>';
}
elseif($mapnum==4)
{
	echo'<img src="warmapfiles/Erebus2.png" usemap="#erebuswarmap"></center>
	<map name="erebuswarmap">
		<area shape="circle" coords="571,301,18" href="#" id="hr753pop" alt="HR 753">
		<area shape="circle" coords="793,318,18" href="#" id="draconispop" alt="Draconis 6188">
		<area shape="circle" coords="77,407,18" href="#" id="avernuspop" alt="Avernus">
		<area shape="circle" coords="188,281,18" href="#" id="styxpop" alt="Styx">
		<area shape="circle" coords="317,280,18" href="#" id="omskpop" alt="Omsk">
		<area shape="circle" coords="151,159,18" href="#" id="samarapop" alt="Samara">
		<area shape="circle" coords="383,197,18" href="#" id="tianjinpop" alt="Tianjin">
		<area shape="circle" coords="571,197,18" href="#" id="shantoupop" alt="Shantou">
		<area shape="circle" coords="755,169,18" href="#" id="saratovpop" alt="Saratov">
		<area shape="circle" coords="58,263,18" href="#" id="pretoriapop" alt="Pretoria">
	</map>';
}
elseif($mapnum==5)
{
	echo'<img src="warmapfiles/Hadrian2.PNG" usemap="#hadrianwarmap"></center>
	<map name="hadrianwarmap">
		<area shape="circle" coords="54,89,18" href="#" id="khartoumpop" alt="Khartoum">
		<area shape="circle" coords="281,127,18" href="#" id="kazanpop" alt="Kazan">
		<area shape="circle" coords="435,156,18" href="#" id="kamchatkapop" alt="Kamchatka">
		<area shape="circle" coords="625,192,18" href="#" id="hangzoupop" alt="Hangzou">
		<area shape="circle" coords="790,155,18" href="#" id="irkutskpop" alt="Irkutsk">
		<area shape="circle" coords="180,173,18" href="#" id="vanaheimpop" alt="Vanaheim">
		<area shape="circle" coords="142,282,18" href="#" id="melbournepop" alt="Melbourne">
		<area shape="circle" coords="318,244,18" href="#" id="newmountpop" alt="Newmount">
		<area shape="circle" coords="616,300,18" href="#" id="yutanipop" alt="Yutani">
		<area shape="circle" coords="771,318,18" href="#" id="avalonpop" alt="Avalon">
	</map>';
}
elseif($mapnum==6)
{
	echo'<img src="warmapfiles/Maginot2.PNG" usemap="#maginotwarmap"></center>
	<map name="maginotwarmap">
		<area shape="circle" coords="230,83,18" href="#" id="capellapop" alt="Capella">
		<area shape="circle" coords="411,109,18" href="#" id="aquilaepop" alt="Aquilae">
		<area shape="circle" coords="538,146,18" href="#" id="polluxpop" alt="Pollux">
		<area shape="circle" coords="230,184,18" href="#" id="prosperitypop" alt="Prosperity">
		<area shape="circle" coords="135,245,18" href="#" id="fortunapop" alt="San Fortuna">
		<area shape="circle" coords="287,264,18" href="#" id="emergencepop" alt="Emergence">
		<area shape="circle" coords="402,281,18" href="#" id="hiberniapop" alt="Hibernia">
		<area shape="circle" coords="353,183,18" href="#" id="solemnitypop" alt="Solemnity">
		<area shape="circle" coords="605,263,18" href="#" id="naogeddonpop" alt="Naogeddon">
		<area shape="circle" coords="210,352,18" href="#" id="whiterainpop" alt="Whiterain">
	</map>';
}
?>
<div id="Popup"> 
<a href="#" id="popupClose">x</a>
<div class="loading">
		<center><img src="warmapfiles/ajax-loader.gif" width="50px" height="50px"></center>
</div>
<ul class="tabs"> 
        <li class="active" rel="tab1">Deployments</li>
        <li rel="tab2"> Special Operations</li>
</ul>
<div class="tab_container"> 
     <div id="tab1" class="tab_content"> 
 
         <span id="zoneinfo">  
		<h1 id="battlezone">Fortitude</h1>   
		<b>Owned By: </b> Solidarity
		</span>

     </div><!-- #tab1 -->
     <div id="tab2" class="tab_content"> 

       <p> <br />
	   <span id="specops_zone">
      	<strong>
      	Special Operations Goes Here.
      	</strong>
		</span>
       </p>

     </div><!-- #tab2 -->  
 </div> <!-- .tab_container --> 
</div>   
<div id="bgPopup"></div>
<br><br><span id="tester" color="#FFFFFF">Can this be seen</span>
<script type="text/javascript" src="includes/jquery.js"></script>      
<script type="text/javascript">
function checkBattlezone(var1)
{
		$.get("warmapfiles/zoneinfo.php?zonetarget="+var1, function(data){
		$("#zoneinfo").html(data);} );
		$.get("warmapfiles/missioninfo.php?zonetarget="+var1, function(data){
		$("#specops_zone").html(data);} );
}

function loadPopup(){
	//loads popup only if it is disabled
	if($("#bgPopup").data("state")==0){
		$("#bgPopup").css({
			"opacity": "0.7"
		});
		$("#bgPopup").fadeIn("medium");
		$("#Popup").fadeIn("medium");
		$("#bgPopup").data("state",1);
	}
}

function disablePopup(){
	if ($("#bgPopup").data("state")==1){
		$("#bgPopup").fadeOut("medium");
		$("#Popup").fadeOut("medium");
		$("#bgPopup").data("state",0);
	}
}

function centerPopup(){
	var winw = $(window).width();
	var winh = $(window).height();
	var popw = $('#Popup').width();
	var poph = $('#Popup').height();
	$("#Popup").css({
        "position" : "fixed",
        "top" : "50px",
        "left" : winw/2-popw/2
	});
	//IE6
	$("#bgPopup").css({
		"height": winh	
	});
}

function prepForm(){
	$(".error").hide();
	$("#deployButton").click(function(e)
	{
	e.preventDefault();
	//validate and process form
	$(".deploy_error").hide();
	$('#deploymessage').html("");
	$('#deploymessage2').html("");
	$('#deploymessage3').html("");
	var deployment = $("#deploy").val();
	var allegiance = $("#allegiance").val();
	var location = $("#location").val();
	var maxtroops = <?php echo $playertroops; ?>;
	var zoneid = $("#zoneid").val();
	var formoutput = null;
		if(deployment > maxtroops)
		{
			$("#deploy_error").show();  
			$("#deploy").focus();
			return false;
		}
		var dataString = 'deploy='+ deployment + '&allegiance=' + allegiance + '&location=' + location;
		$.ajax({
			url: "battledeploy.php",
			type: "POST",    
			data: dataString,  
			success: function(output) {
			alert(output);
			showLoading(zoneid);
			}  
		});
		// $("#zoneinfo").hide();
		// $("#zoneinfo").load("warmapfiles/zoneinfo.php?zonetarget="+zoneid);
		// window.setTimeout(refreshZone, 150);
		return false;
	});
	
	//Withdrawals
	$("#withdrawButton").click(function(event)
	{
	event.preventDefault();
	//validate and process form
	$(".withdraw_error").hide();
	$('#deploymessage').html("");
	$('#deploymessage2').html("");
	$('#deploymessage3').html("");
	var withdrawal = parseInt($("#troopwithdraw").val());
	var allegiance = $("#withdrawallegiance").val();
	var location = $("#withdrawlocation").val();
	var maxwithdraw = parseInt($("#withdrawmax").val());
	var zoneid = $("#zoneid").val();
		if(withdrawal > maxwithdraw)
		{
			$("#withdraw_error").show();  
			$("#troopwithdraw").focus();
			return false;
		}
		
		var dataString = 'deploy='+ withdrawal + '&allegiance=' + allegiance + '&location=' + location;
		$.ajax({
			url: "battlewithdraw.php",
			type: "POST",    
			data: dataString,  
			success: function(output) {
			alert(output);
			showLoading(zoneid);
			}  
		});
		// $("#zoneinfo").hide();
		// $("#zoneinfo").load("warmapfiles/zoneinfo.php?zonetarget="+zoneid);
		// window.setTimeout(refreshZone, 150);
		return false;
	
	});
	
	//Weapons
	$("#weaponButton").click(function(event)
	{
	event.preventDefault();
	//validate and process form
	$(".weapon_error").hide();
	$('#deploymessage').html("");
	$('#deploymessage2').html("");
	$('#deploymessage3').html("");
	var weaponamount = parseInt($("#weapondeploynum").val());
	var weapontype = $("#weapontype").val();
	var allegiance = $("#weaponallegiance").val();
	var location = $("#weapondeployid").val();
	var maxbombs = parseInt($("#bombamount").val());
	var maxshields = parseInt($("#shieldamount").val());
	var zoneid = $("#weapondeployid").val();
	var target = $('input:radio[name=targetzone]:checked').val();;
		if(weapontype == 'bombs')
		{
			if(weaponamount > maxbombs)
			{
				$("#weapon_error").show();  
				$("#weapondeploy").focus();
				return false;
			}
			else
			{
				var dataString = 'weapons='+ weaponamount + '&location=' + location + '&weapontype=' + weapontype + '&targetzone=' +target;
			}
		}
		else if(weapontype=='shields')
		{
			if(weaponamount > maxshields)
			{
				$("#weapon_error").show();  
				$("#weapondeploy").focus();
				return false;
			}
			else
			{
				var dataString = 'weapons='+ weaponamount + '&location=' + location + '&weapontype=' + weapontype;
			}
		}
		$.ajax({
			url: "warmapfiles/weaponsdeploy.php",
			type: "POST",    
			data: dataString,  
			success: function(output) {
			alert(output);
			showLoading(zoneid);
			}  
		});
		// $("#zoneinfo").hide();
		// $("#zoneinfo").load("warmapfiles/zoneinfo.php?zonetarget="+zoneid);
		// window.setTimeout(refreshZone, 150);
		return false;
	
	});
}

function refreshZone()
{
	window.setTimeout(prepForm, 100);
	$(".error").hide();
	$("#zoneinfo").fadeIn(2000);
}

function refreshMission()
{
	window.setTimeout(prepOps, 100);
	$("#specops_zone").fadeIn(2000);
}

function showLoading(var1)
{
	
	$(".tab_container").hide();
	$(".tabs").hide();
	$(".loading").fadeIn(250);
	/*if(var1 == 3)
	{
		checkBattlezone('fort');
	}
	else if(var1 == 2)
	{
		checkBattlezone('mundus');
	}
	else if(var1 == 1)
	{
		checkBattlezone('cent');
	}*/
	checkBattlezone(var1);
	window.setTimeout(prepForm, 1000);
	window.setTimeout(prepOps, 1000);
	window.setTimeout(hideLoading, 1000);
}

function hideLoading()
{
	$(".loading").fadeOut(500);
	$(".tab_container").fadeIn(500);
	$(".tabs").fadeIn(500);
}

function prepOps()
{
	$(".missioninfo").hide();
	$("#mission1").show();
	$("#missionchoice").change(function(){
        $(".missioninfo").hide();
        $('#mission' + $(this).val()).show();});
		
	$(".specButton").click(function(e)
	{
		e.preventDefault();
		//validate and process form
		var buttonPress = $(this).closest("form").attr('id');
		var formSpan = $('#'+buttonPress).closest("span").attr('id');
		var missiontype = $('#'+buttonPress+' :input[name="formmissiontype"]').val();
		var battlezonemission = $('#'+buttonPress+' :input[name="formmissionzone"]').val();
		var missionid = $('#'+buttonPress+' :input[name="formmissionid"]').val();
		var troopid = $('#'+buttonPress+' :input[name="formmissiontroops"]').val();
		var dataString = 'formmissiontype='+ missiontype + '&formmissionid=' + missionid + '&formmissionzone=' + battlezonemission + '&formmissiontroops=' + troopid;
		$.ajax({
			url: "warmapfiles/specOpsdeploy.php",
			type: "POST",    
			data: dataString,  
			success: function(data) {
			alert('Mission underway.');
			showLoading(battlezonemission);
			}  
		});
	});
	$(".specButton2").click(function(e)
	{
		e.preventDefault();
		//validate and process form
		var buttonPress = $(this).closest("form").attr('id');
		var formSpan = $('#'+buttonPress).closest("span").attr('id');
		var missiontype = $('#'+buttonPress+' :input[name="formmissiontype"]').val();
		var missiontroops = $('#'+buttonPress+' :input[name="formmissionreq"]').val();
		var battlezonemission = $('#'+buttonPress+' :input[name="formmissionzone"]').val();
		var missionid = $('#'+buttonPress+' :input[name="formmissionid"]').val();
		var troopid = $('#'+buttonPress+' :input[name="formmissiontroops"]').val();
		var troopid2 = 'none';
		var troopid3 = 'none';
		if(missiontroops >= '2')
		{
			troopid2=$('#'+buttonPress+' :input[name="formmissiontroops2"]').val();
		}
		if(missiontroops == '3')
		{
			troopid3=$('#'+buttonPress+' :input[name="formmissiontroops3"]').val();
		}
		var resource = $('#'+buttonPress+' :input[name="formmissionres"]').val();
		var resourceamount = $('#'+buttonPress+' :input[name="formmissionresreq"]').val();
		var dataString = 'formmissiontype='+ missiontype + '&formmissionid=' + missionid + '&formmissionzone=' + battlezonemission + '&formmissiontroops=' + troopid + 
		'&formmissiontroops2='+troopid2+'&formmissiontroops3='+troopid3+'&formmissionres='+resource+'&formmissionresreq='+resourceamount;
		$.ajax({
			url: "warmapfiles/specOpsdeploy.php",
			type: "POST",    
			data: dataString,  
			success: function(data) {
			alert('Mission underway');
			showLoading(battlezonemission);
			}  
		});
	});
}
</script>
</body>
</html>

