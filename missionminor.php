<?php
set_time_limit(0);
ignore_user_abort(true);
include 'includes/connect.php';

$password = $_GET['pass'];
$password = stripslashes($password);
$password = mysql_real_escape_string($password);

if($password == 'missionDELtick2401REFRESH')
{

/* +++SECTION DETAILS+++
This next section handles all ongoing missions and checks for completion.
If not completed (1 tick left), the number of ticks is removed by 1.
If completed, enact the mission effects and record the missions completion if needed.
Send a mission report to the owner.
*/
	//Count Down all ticks for missions
	$getallmissions = mysql_query("SELECT * FROM activemissions");
	while($allmissions = mysql_fetch_array($getallmissions))
	{
		$missionid = $allmissions['id'];
		if($allmissions['ticksleft'] == 1)
		{
			$missionselect = $allmissions['mission'];
			$missionzone = $allmissions['battlezone'];
			//Get Mission Details
			$missioninfo = mysql_query("SELECT * FROM missions WHERE id='$missionselect'");
			$missiondetails = mysql_fetch_array($missioninfo);
			$missiontype = $missiondetails['missiontype'];
			//Get Battlezone Details
			$zoneinfo = mysql_query("SELECT * FROM battlezones WHERE zoneid='$missionzone'");
			$zonedetails = mysql_fetch_array($zoneinfo);
			//Check Mission type
			if($missiontype == 'killtroops')
			{
				//Evaluate Success
				$successrate = 60;
				$successroll = rand(0, 100);
				$successroll = $successroll * $allmissions['skill'];
				//Evaluate Possible Casualties
				$casualtybase = 1;
				$casualtypercent = $casualtybase*$allmissions['tier'];
				//Perform Mission Actions
				if($successroll > $successrate)
				{
					//Decide Targets
					if($allmissions['allegiance'] == 'authority')
					{
						$soltroops = $zonedetails['solidaritytroops'];
						$merctroops = $zonedetails['mercantiletroops'];
						$solkills = $casualtypercent * 0.01;
						$solkills = $soltroops * $solkills;
						$solkills = round($solkills);
						$merckills = $casualtypercent * 0.01;
						$merckills = $merctroops * $merckills;
						$merckills = round($merckills);
						
						//Update Troops
						$newsoltroops = $zonedetails['solidaritytroops'] - $solkills;
						if($newsoltroops < 0)
						{
							$newsoltroops = 0;
						}
						$newmerctroops = $zonedetails['mercantiletroops'] - $merckills;
						if($newmerctroops < 0)
						{
							$newmerctroops = 0;
						}
						$updatetroops = mysql_query("UPDATE battlezones SET solidaritytroops=$newsoltroops, mercantiletroops=$newmerctroops WHERE zoneid='$missionzone'");
						
						//Remove from individual deployments
						$getsolidaritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='solidarity'") or die(mysql_error());
						$solidaritydeployments = mysql_num_rows($getsolidaritydeployments);
						$getmercantiledeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='mercantile'") or die(mysql_error());
						$mercantiledeployments = mysql_num_rows($getmercantiledeployments);
						
						//Remove from Solidarity Deployments
						if($solidaritydeployments > 0)
						{
							while($solidaritydeploymentrow = mysql_fetch_array($getsolidaritydeployments))
							{
								$solratio = $solidaritydeploymentrow['deployment']/$zonedetails['solidaritytroops'];
								$solcasualties = $solkills*$solratio;
								$solcasualties = ceil($solcasualties);
								$deploymentid = $solidaritydeploymentrow['deploymentid'];
								$updatesoldeploy = mysql_query("UPDATE deployments SET deployment=deployment-$solcasualties WHERE deploymentid='$deploymentid'");
							}
						}
						//Remove from Mercantile Deployments
						if($mercantiledeployments > 0)
						{
							while($mercantiledeploymentrow = mysql_fetch_array($getmercantiledeployments))
							{
								$mercratio = $mercantiledeploymentrow['deployment']/$zonedetails['mercantiletroops'];
								$merccasualties = $merckills*$mercratio;
								$merccasualties = ceil($merccasualties);
								$deploymentid = $mercantiledeploymentrow['deploymentid'];
								$updatemercdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$merccasualties WHERE deploymentid='$deploymentid'");
							}
						}
					}
					elseif($allmissions['allegiance'] == 'mercantile')
					{
						$soltroops = $zonedetails['solidaritytroops'];
						$authtroops = $zonedetails['authoritytroops'];
						$solkills = $casualtypercent * 0.01;
						$solkills = $soltroops * $solkills;
						$solkills = round($solkills);
						$authkills = $casualtypercent * 0.01;
						$authkills = $authtroops * $authkills;
						$authkills = round($authkills);
						
						//Update Troops
						$newsoltroops = $zonedetails['solidaritytroops'] - $solkills;
						if($newsoltroops < 0)
						{
							$newsoltroops = 0;
						}
						$newauthtroops = $zonedetails['authoritytroops'] - $authkills;
						if($newauthtroops < 0)
						{
							$newauthtroops = 0;
						}
						$updatetroops = mysql_query("UPDATE battlezones SET solidaritytroops=$newsoltroops, authoritytroops=$newauthtroops WHERE zoneid='$missionzone'");
						
						//Remove from individual deployments
						$getsolidaritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='solidarity'") or die(mysql_error());
						$solidaritydeployments = mysql_num_rows($getsolidaritydeployments);
						$getauthoritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='authority'") or die(mysql_error());
						$authoritydeployments = mysql_num_rows($getauthoritydeployments);
						
						//Remove from Solidarity Deployments
						if($solidaritydeployments > 0)
						{
							while($solidaritydeploymentrow = mysql_fetch_array($getsolidaritydeployments))
							{
								$solratio = $solidaritydeploymentrow['deployment']/$zonedetails['solidaritytroops'];
								$solcasualties = $solkills*$solratio;
								$solcasualties = ceil($solcasualties);
								$deploymentid = $solidaritydeploymentrow['deploymentid'];
								$updatesoldeploy = mysql_query("UPDATE deployments SET deployment=deployment-$solcasualties WHERE deploymentid='$deploymentid'");
							}
						}
						//Remove from authority Deployments
						if($authoritydeployments > 0)
						{
							while($authoritydeploymentrow = mysql_fetch_array($getauthoritydeployments))
							{
								$authratio = $authoritydeploymentrow['deployment']/$zonedetails['authoritytroops'];
								$authcasualties = $authkills*$authratio;
								$authcasualties = ceil($authcasualties);
								$deploymentid = $authoritydeploymentrow['deploymentid'];
								$updateauthdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$authcasualties WHERE deploymentid='$deploymentid'");
							}
						}
					}
					elseif($allmissions['allegiance'] == 'solidarity')
					{
						$merctroops = $zonedetails['mercantiletroops'];
						$authtroops = $zonedetails['authoritytroops'];
						$merckills = $casualtypercent * 0.01;
						$merckills = $merctroops * $merckills;
						$merckills = round($merckills);
						$authkills = $casualtypercent * 0.01;
						$authkills = $authtroops * $authkills;
						$authkills = round($authkills);
						
						//Update Troops
						$newmerctroops = $zonedetails['mercantiletroops'] - $merckills;
						if($newmerctroops < 0)
						{
							$newmerctroops = 0;
						}
						$newauthtroops = $zonedetails['authoritytroops'] - $authkills;
						if($newauthtroops < 0)
						{
							$newauthtroops = 0;
						}
						$updatetroops = mysql_query("UPDATE battlezones SET mercantiletroops=$newmerctroops, authoritytroops=$newauthtroops WHERE zoneid='$missionzone'");
						
						//Remove from individual deployments
						$getmercantiledeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='mercantile'") or die(mysql_error());
						$mercantiledeployments = mysql_num_rows($getmercantiledeployments);
						$getauthoritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='authority'") or die(mysql_error());
						$authoritydeployments = mysql_num_rows($getauthoritydeployments);
						
						//Remove from Mercantile Deployments
						if($mercantiledeployments > 0)
						{
							while($mercantiledeploymentrow = mysql_fetch_array($getmercantiledeployments))
							{
								$mercratio = $mercantiledeploymentrow['deployment']/$zonedetails['mercantiletroops'];
								$merccasualties = $merckills*$mercratio;
								$merccasualties = ceil($merccasualties);
								$deploymentid = $mercantiledeploymentrow['deploymentid'];
								$updatemercdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$merccasualties WHERE deploymentid='$deploymentid'");
							}
						}
						//Remove from authority Deployments
						if($authoritydeployments > 0)
						{
							while($authoritydeploymentrow = mysql_fetch_array($getauthoritydeployments))
							{
								$authratio = $authoritydeploymentrow['deployment']/$zonedetails['authoritytroops'];
								$authcasualties = $authkills*$authratio;
								$authcasualties = ceil($authcasualties);
								$deploymentid = $authoritydeploymentrow['deploymentid'];
								$updateauthdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$authcasualties WHERE deploymentid='$deploymentid'");
							}
						}
					}
					$removeallzeroes = mysql_query("DELETE FROM deployments WHERE deployment<='0'");
					//Pass SUCCESS message
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>Siege Enemy Holdings<br>
					Your mission was a success! You managed to kill ".$casualtypercent."% of the enemy troops in the system, crippling their combat potential.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$allegiance = $allmissions['allegiance'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
				else
				{
					//Pass FAIL message.
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>Siege Enemy Holdings<br>
					Your mission was a failure! Your special forces were intercepted en route to their objective, and were unable to organise the assault. Enemy forces remain undamaged.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$allegiance = $allmissions['allegiance'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			elseif($missiontype == 'assassin')
			{
				//Evaluate Success
				$successrate = 60;
				$successroll = rand(0, 100);
				$successroll = $successroll * $allmissions['skill'];
				//Evaluate Possible Casualties
				$casualtybase = 1;
				$casualtypercent = $casualtybase*$allmissions['tier'];
				//Perform Mission Actions
				if($successroll > $successrate)
				{
					//Decide Targets
					if($allmissions['allegiance'] == 'authority')
					{
						//Remove from individual deployments
						$getsolidaritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='solidarity'") or die(mysql_error());
						$solidaritydeployments = mysql_num_rows($getsolidaritydeployments);
						$getmercantiledeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='mercantile'") or die(mysql_error());
						$mercantiledeployments = mysql_num_rows($getmercantiledeployments);
						
						//Remove from Solidarity Deployments
						if($solidaritydeployments > 0)
						{
							while($solidaritydeploymentrow = mysql_fetch_array($getsolidaritydeployments))
							{
								$solratio = $solidaritydeploymentrow['deployment']/100;
								$solcasualties = $casualtypercent*$solratio;
								$solcasualties = ceil($solcasualties);
								$deploymentid = $solidaritydeploymentrow['deploymentid'];
								$updatesoldeploy = mysql_query("UPDATE deployments SET deployment=deployment-$solcasualties WHERE deploymentid='$deploymentid'");
							}
						}
						//Remove from Mercantile Deployments
						if($mercantiledeployments > 0)
						{
							while($mercantiledeploymentrow = mysql_fetch_array($getmercantiledeployments))
							{
								$mercratio = $mercantiledeploymentrow['deployment']/100;
								$merccasualties = $casualtypercent*$mercratio;
								$merccasualties = ceil($merccasualties);
								$deploymentid = $mercantiledeploymentrow['deploymentid'];
								$updatemercdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$merccasualties WHERE deploymentid='$deploymentid'");
							}
						}
					}
					elseif($allmissions['allegiance'] == 'mercantile')
					{
						//Remove from individual deployments
						$getsolidaritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='solidarity'") or die(mysql_error());
						$solidaritydeployments = mysql_num_rows($getsolidaritydeployments);
						$getauthoritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='authority'") or die(mysql_error());
						$authoritydeployments = mysql_num_rows($getauthoritydeployments);
						
						//Remove from Solidarity Deployments
						if($solidaritydeployments > 0)
						{
							while($solidaritydeploymentrow = mysql_fetch_array($getsolidaritydeployments))
							{
								$solratio = $solidaritydeploymentrow['deployment']/100;
								$solcasualties = $casualtypercent*$solratio;
								$solcasualties = ceil($solcasualties);
								$deploymentid = $solidaritydeploymentrow['deploymentid'];
								$updatesoldeploy = mysql_query("UPDATE deployments SET deployment=deployment-$solcasualties WHERE deploymentid='$deploymentid'");
							}
						}
						//Remove from authority Deployments
						if($authoritydeployments > 0)
						{
							while($authoritydeploymentrow = mysql_fetch_array($getauthoritydeployments))
							{
								$authratio = $authoritydeploymentrow['deployment']/100;
								$authcasualties = $casualtypercent*$authratio;
								$authcasualties = ceil($authcasualties);
								$deploymentid = $authoritydeploymentrow['deploymentid'];
								$updateauthdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$authcasualties WHERE deploymentid='$deploymentid'");
							}
						}
					}
					elseif($allmissions['allegiance'] == 'solidarity')
					{
						//Remove from individual deployments
						$getmercantiledeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='mercantile'") or die(mysql_error());
						$mercantiledeployments = mysql_num_rows($getmercantiledeployments);
						$getauthoritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='authority'") or die(mysql_error());
						$authoritydeployments = mysql_num_rows($getauthoritydeployments);
						
						//Remove from Mercantile Deployments
						if($mercantiledeployments > 0)
						{
							while($mercantiledeploymentrow = mysql_fetch_array($getmercantiledeployments))
							{
								$mercratio = $mercantiledeploymentrow['deployment']/100;
								$merccasualties = $casualtypercent*$mercratio;
								$merccasualties = ceil($merccasualties);
								$deploymentid = $mercantiledeploymentrow['deploymentid'];
								$updatemercdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$merccasualties WHERE deploymentid='$deploymentid'");
							}
						}
						//Remove from authority Deployments
						if($authoritydeployments > 0)
						{
							while($authoritydeploymentrow = mysql_fetch_array($getauthoritydeployments))
							{
								$authratio = $authoritydeploymentrow['deployment']/100;
								$authcasualties = $casualtypercent*$authratio;
								$authcasualties = ceil($authcasualties);
								$deploymentid = $authoritydeploymentrow['deploymentid'];
								$updateauthdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$authcasualties WHERE deploymentid='$deploymentid'");
							}
						}
					}
					$removeallzeroes = mysql_query("DELETE FROM deployments WHERE deployment<='0'");
					//Pass SUCCESS message
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>Assassinate General<br>
					Your mission was a success! You managed to assassinate a high ranking enemy commander, leaving several units disorganised and stranded.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$allegiance = $allmissions['allegiance'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
				else
				{
					//Pass FAIL message.
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>Assassinate General<br>
					Your mission was a failure! Your assassin was killed by the target's bodyguards, and enemy forces are still fully organised.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$allegiance = $allmissions['allegiance'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			elseif($missiontype == 'sabotageammo')
			{
				//Evaluate Success
				$successrate = 60;
				$successroll = rand(0, 100);
				$successroll = $successroll * $allmissions['skill'];
				//Evaluate Possible Casualties
				$effectbase = 1;
				$effectpenalty = $effectbase*$allmissions['tier'];
				$effectpenalty = $effectpenalty * 0.01;
				//Perform Mission Actions
				if($successroll > $successrate)
				{
					//Decide Targets
					if($allmissions['allegiance'] == 'authority')
					{
						//Update Troop Skill
						$updatebattleskill = mysql_query("UPDATE battlezones SET soleffective=soleffective-$effectpenalty, merceffective=merceffective-$effectpenalty WHERE zoneid='$zonedetails[zoneid]'");
					}
					elseif($allmissions['allegiance'] == 'mercantile')
					{
						//Update Troop Skill
						$updatebattleskill = mysql_query("UPDATE battlezones SET soleffective=soleffective-$effectpenalty, autheffective=autheffective-$effectpenalty WHERE zoneid='$zonedetails[zoneid]'");
					}
					elseif($allmissions['allegiance'] == 'solidarity')
					{
						//Update Troop Skill
						$updatebattleskill = mysql_query("UPDATE battlezones SET autheffective=autheffective-$effectpenalty, merceffective=merceffective-$effectpenalty WHERE zoneid='$zonedetails[zoneid]'");
					}
					//Pass SUCCESS message
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>Attack Supply Depots<br>
					Your mission was a success! Enemy units have been forced to conserve ammunition, making them less effective on the battlefield.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$allegiance = $allmissions['allegiance'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
					//Record Mission Effect
					$battlezone = $zonedetails['zoneid'];
					$missioneffect = mysql_query("INSERT INTO missioneffects(missiontype, allegiance, battlezone, effectamount) VALUES('$missiontype', '$allegiance', '$battlezone', '$effectpenalty')");
				}
				else
				{
					//Pass FAIL message.
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>Attack Supply Depots<br>
					Your mission was a failure! Your unit was caught while deploying the explosives and executed. Enemy forces still operate at peak efficiency.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$allegiance = $allmissions['allegiance'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			elseif($missiontype == 'reconbasic')
			{
				//Evaluate Success
				$succesrate = 60;
				$successroll = rand(0, 100);
				$successroll = $successroll * $allmissions['skill'];
				//Evaluate Reveal
				$effectbase = 1;
				$effectbonus = $effectbase*$allmissions['tier'];
				//Length
				$effectlength = $allmissions['tier']/3;
				$effectlength = ceil($effectlength);
				$effectlength = $effectlength * 2;
				if($successroll > $succesrate)
				{
					//Perform Actions
					$battlezone = $zonedetails['zoneid'];
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$allegiance = $allmissions['allegiance'];
					$enterecon = mysql_query("INSERT INTO missioneffects(missiontype, battlezone, allegiance, ticksleft, effectamount) VALUES('$missiontype', '$battlezone', '$allegiance', '$effectlength', '$effectbonus')");
					//Pass SUCCESS message
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>Reconnaissance<br>
					Your mission was a success! Recon operations have revealed enemy information within the targeted star system.<br>Enemy troop numbers have been revealed.<br><br>";
					if($effectbonus > 4)
					{
						$missiontext = $missiontext.'<br>Enemy defences have been revealed.';
					}
					$missiontext = $missiontext.'<br>The information will be available for '.$effectlength.' weeks';
					
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
				else
				{
					//Pass FAIL message.
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>Reconnaissance<br>
					Your mission was a failure! Enemy counter espionage units managed to prevent your Recon Operations unit from completing their mission.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$allegiance = $allmissions['allegiance'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			elseif($missiontype == 'supportbasic')
			{
				//Evaluate Success
				$successrate = 60;
				$successroll = rand(0, 100);
				$successroll = $successroll * $allmissions['skill'];
				//Evaluate Possible Casualties
				$effectbase = 1;
				$effectbonus = $effectbase*$allmissions['tier'];
				$effectbonus = $effectbonus * 0.01;
				//Perform Mission Actions
				if($successroll > $successrate)
				{
					//Decide Targets
					if($allmissions['allegiance'] == 'authority')
					{
						//Update Troop Skill
						$updatebattleskill = mysql_query("UPDATE battlezones SET autheffective=autheffective+$effectbonus WHERE zoneid='$zonedetails[zoneid]'");
					}
					elseif($allmissions['allegiance'] == 'mercantile')
					{
						//Update Troop Skill
						$updatebattleskill = mysql_query("UPDATE battlezones SET merceffective=merceffective+$effectbonus WHERE zoneid='$zonedetails[zoneid]'");
					}
					elseif($allmissions['allegiance'] == 'solidarity')
					{
						//Update Troop Skill
						$updatebattleskill = mysql_query("UPDATE battlezones SET soleffective=soleffective+$effectbonus WHERE zoneid='$zonedetails[zoneid]'");
					}
					//Pass SUCCESS message
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Establish Medical Centres';
					if($allegiance == 'authority')
					{
						$missiontitle = 'Establish Repair Bays';
					}
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a success! Your units have been bolstered by your support units, increasing their combat effectiveness.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					
					$battlezonetarget = $zonedetails['zoneid'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
					//Mission Effects
					$battlezone = $zonedetails['zoneid'];
					$missioneffect = mysql_query("INSERT INTO missioneffects(missiontype, allegiance, battlezone, effectamount) VALUES('$missiontype', '$allegiance', '$battlezonetarget', '$effectbonus')");
				}
				else
				{
					//Pass FAIL message.
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Establish Medical Centres';
					if($allegiance == 'authority')
					{
						$missiontitle = 'Establish Repair Bays';
					}
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a failure! Enemy strike forces sabotaged attempts by your support units to reinforce your own armies.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			elseif($missiontype == 'jamcomms')
			{
				//Evaluate Success
				$successrate = 74;
				$successroll = rand(1,10);
				$successroll = $successroll*$allmissions['skill'];
				
				if($successroll > $successrate)
				{
					//Pass SUCCESS message
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Jam Communications';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a success! Offensive AIs have sabotaged enemy communications, leaving them without important information related to the battlespace.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					
					$battlezonetarget = $zonedetails['zoneid'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
					//Mission Effects
					$battlezone = $zonedetails['zoneid'];
					$missioneffect = mysql_query("INSERT INTO missioneffects(missiontype, allegiance, battlezone, effectamount, ticksleft) VALUES('$missiontype', '$allegiance', '$battlezonetarget', '1', '2')");
				}
				else
				{
					//Pass FAIL message.
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Jam Communications';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a failure! Your units were unable to insert the AI viruses into the enemy communication networks. Enemy communications are still operational.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			elseif($missiontype == 'wormhole')
			{
				//Evaluate Success
				$successrate = 81;
				$successroll = rand(1,10);
				$successroll = $successroll*$allmissions['skill'];
				
				if($successroll > $successrate)
				{
					//Pass SUCCESS message
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Artificial Wormhole Creation';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a success! A temporary wormhole has been formed leading to an enemy star system!<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					
					$battlezonetarget = $zonedetails['zoneid'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
					//Mission Effects
					$battlezone = $zonedetails['zoneid'];
					$missioneffect = mysql_query("INSERT INTO missioneffects(missiontype, allegiance, battlezone, effectamount, ticksleft) VALUES('$missiontype', '$allegiance', '$battlezonetarget', '1', '5')");
					$setdeploy = mysql_query("UPDATE battlezones SET authdeploy='1' WHERE zoneid='$battlezone'");
				}
				else
				{
					//Pass FAIL message.
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Artificial Wormhole Creation';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a failure! The formed wormhole proved too unstable and collapsed shortly after it was created.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			elseif($missiontype == 'rollbarrage')
			{
				//Evaluate Success
				$successrate = 50;
				$successroll = rand(1,5);
				$successroll = $successroll*$allmissions['skill'];
				
				if($successroll > $successrate)
				{
					//Pass SUCCESS message
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Rolling Barrage';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a success! Your combined assault forced the enemy back while suffering heavy casualties. Your forces have successfully gained more ground in the system.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					
					$battlezonetarget = $zonedetails['zoneid'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
					//Mission Effects
					$battlezone = $zonedetails['zoneid'];
					//Kill
					$casualties = 1000*$successroll;
					if($casualties > 100000)
					{
						$casualties = 100000;
					}
					$authtroops = $zonedetails['authoritytroops'];
					$merctroops = $zonedetails['mercantiletroops'];
					$newauthtroops = $authtroops - $casualties;
					//authority self repair
					$getrepair = mysql_query("SELECT tier FROM technology WHERE techtype='troopregen'");
					$repair = mysql_result($getrepair, 0);
					$repair = $repair*0.1;
					$repaired = $casualties*$repair;
					$newauthtroops = $newauthtroops + $repaired;
					if($newauthtroops < 0)
					{
						$newauthtroops = 0;
					}
					$newmerctroops = $merctroops - $casualties;
					if($newmerctroops < 0)
					{
						$newmerctroops = 0;
					}
					//Remove from individual deployments
					$getmercantiledeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='mercantile'") or die(mysql_error());
					$mercantiledeployments = mysql_num_rows($getmercantiledeployments);
					$getauthoritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$missionzone' AND allegiance='authority'") or die(mysql_error());
					$authoritydeployments = mysql_num_rows($getauthoritydeployments);
					
					//Remove from Mercantile Deployments
					if($mercantiledeployments > 0)
					{
						while($mercantiledeploymentrow = mysql_fetch_array($getmercantiledeployments))
						{
							$mercratio = $mercantiledeploymentrow['deployment']/$zonedetails['mercantiletroops'];
							$merccasualties = $casualties*$mercratio;
							$merccasualties = ceil($merccasualties);
							$deploymentid = $mercantiledeploymentrow['deploymentid'];
							$updatemercdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$merccasualties WHERE deploymentid='$deploymentid'");
						}
					}
					//Remove from authority Deployments
					if($authoritydeployments > 0)
					{
						while($authoritydeploymentrow = mysql_fetch_array($getauthoritydeployments))
						{
							$authratio = $authoritydeploymentrow['deployment']/$zonedetails['authoritytroops'];
							$authcasualties = $casualties*$authratio;
							$authcasualties = ceil($authcasualties);
							$deploymentid = $authoritydeploymentrow['deploymentid'];
							$updateauthdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$authcasualties WHERE deploymentid='$deploymentid'");
						}
					}
					//Influence
					$influencegain = ceil($successroll/2);
					if($influencegain > 6)
					{
						$influencegain = 6;
					}
					$authinf = $zonedetails['authorityinfluence'];
					$mercinf = $zonedetails['mercantileinfluence'];
					$infsplit = ceil($influencegain/2);
					$infselect = rand(1,4);
					if($infselect >= 3)
					{
						if($authinf < $infsplit)
						{
							$infsplit = $authinf;
							$newauthinf = 0;
						}
						else
						{
							$newauthinf = $authinf-$infsplit;
						}
						
						$infleft = $influencegain - $infsplit;
						if($mercinf < $infleft)
						{
							$infleft = $mercinf;
							$newmercinf = 0;
						}
						else
						{
							$newmercinf = $mercinf-$infleft;
						}
						$influencegain = $infsplit + $infleft;
					}
					else
					{
						if($mercinf < $infsplit)
						{
							$infsplit = $mercinf;
							$newmercinf = 0;
						}
						else
						{
							$newmercinf = $mercinf-$infsplit;
						}
						$infleft = $influencegain - $infsplit;
						if($authinf < $infleft)
						{
							$infleft = $authinf;
							$newauthinf = 0;
						}
						else
						{
							$newauthinf = $authinf-$infleft;
						}
						$influencegain = $infsplit + $infleft;
					}
					$update = mysql_query("UPDATE battlezones SET authoritytroops='$newauthtroops', mercantiletroops='$newmerctroops', authorityinfluence='$newauthinf', mercantileinfluence='$newmercinf', solidarityinfluence=solidarityinfluence+$influencegain WHERE zoneid='$battlezonetarget'");
				}
				else
				{
					//Pass FAIL message.
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Rolling Barrage';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a failure! Enemy forces resisted your assault better than expected. No ground has been gained, and enemy forces hace successfully regrouped.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			elseif($missiontype == 'offensiveprep')
			{
				//Evaluate Success
				$successrate = 61;
				$successroll = rand(1,7);
				$successroll = $successroll*$allmissions['skill'];
				
				if($successroll > $successrate)
				{
					//Pass SUCCESS message
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Offensive Preparations';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a success! Enemy forces have been left in disarray after your covert attack destroyed a number of enemy defences.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					
					$battlezonetarget = $zonedetails['zoneid'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
					//Mission Effects
					$battlezone = $zonedetails['zoneid'];
					$authshield = $zonedetails['authorityshields'];
					$authdamage = ceil($authshield/2);
					if($authdamage > 10)
					{
						$authdamage = 10;
					}
					$mercshield = $zonedetails['mercantileshields'];
					$mercdamage = ceil($mercshield/2);
					if($mercdamage > 10)
					{
						$mercdamage = 10;
					}
					$autheffect = $zonedetails['autheffective'];
					$autheffectdam = 0.02;
					$autheffect = $autheffect-$autheffectdam;
					$authneweffect = $autheffect;
					
					$merceffect = $zonedetails['merceffective'];
					$merceffectdam = 0.02;
					$merceffect = $merceffect-$merceffectdam;
					$mercneweffect = $merceffect;

					$missioneffect = mysql_query("INSERT INTO missioneffects(missiontype, allegiance, battlezone, effectamount, ticksleft) VALUES('$missiontype', '$allegiance', '$battlezonetarget', '0.2', '2')");
					$update = mysql_query("UPDATE battlezones SET autheffective='$authneweffect', merceffective='$mercneweffect' WHERE zoneid='$battlezonetarget'");
				}
				else
				{
					//Pass FAIL message.
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Offensive Preparations';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a failure! Your covert attack proved to do insufficient damage to enemy defences, leaving their morale intact.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			elseif($missiontype == 'bribery')
			{
				//Evaluate Success
				$successrate = 80;
				$successroll = rand(1,9);
				$successroll = $successroll*$allmissions['skill'];
				
				if($successroll > $successrate)
				{
					//Pass SUCCESS message
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Bribery';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a success! Through various means, your generals have managed to gain control of a contingent of enemy forces.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					
					$battlezonetarget = $zonedetails['zoneid'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
					//Mission Effects
					$battlezone = $zonedetails['zoneid'];
					$authtroops = $zonedetails['authoritytroops'];
					$soltroops = $zonedetails['solidaritytroops'];
					$authgain = $authtroops*0.05;
					$solgain = $soltroops*0.05;
					$totalgain = $authgain + $solgain;
					$update = mysql_query("UPDATE battlezones SET authoritytroops=authoritytroops-$authgain, mercantiletroops=mercantiletroops+$totalgain, solidaritytroops=solidaritytroops-$solgain WHERE zoneid='$battlezonetarget'");
					//Remove from Deployments
					$getauthoritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$zoneid' AND allegiance='authority'") or die(mysql_error());
					$authoritydeployments = mysql_num_rows($getauthoritydeployments);
					$getsolidaritydeployments = mysql_query("SELECT * FROM deployments WHERE battlezone='$zoneid' AND allegiance='solidarity'") or die(mysql_error());
					$solidaritydeployments = mysql_num_rows($getsolidaritydeployments);
					//Remove from Authority Deployments
					if($authoritydeployments > 0)
					{
						while($authoritydeploymentrow = mysql_fetch_array($getauthoritydeployments))
						{
							$authratio = $authoritydeploymentrow['deployment']/$authoritytroops;
							$authcasualties = $authgain*$authratio;
							$authcasualties = ceil($authcasualties);
							$deploymentid = $authoritydeploymentrow['deploymentid'];
							$updateauthdeploy = mysql_query("UPDATE deployments SET deployment=deployment-$authcasualties WHERE deploymentid='$deploymentid'");
						}
					}
					//Solidarity Deployments
					if($solidaritydeployments > 0)
					{
						while($solidaritydeploymentrow = mysql_fetch_array($getsolidaritydeployments))
						{
							$solratio = $solidaritydeploymentrow['deployment']/$solidaritytroops;
							$solcasualties = $solgain*$solratio;
							$solcasualties = ceil($solcasualties);
							$deploymentid = $solidaritydeploymentrow['deploymentid'];
							$updatesoldeploy = mysql_query("UPDATE deployments SET deployment=deployment-$solcasualties WHERE deploymentid='$deploymentid'");
						}
					}
					$removeallzeroes = mysql_query("DELETE FROM deployments WHERE deployment<='0'");
				}
				else
				{
					//Pass FAIL message.
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Bribery';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a failure! Your units were incapable of subverting the enemy chain of command, and were killed in the attempt.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			elseif($missiontype == 'minefield')
			{
				//Evaluate Success
				$successrate = 70;
				$successroll = rand(1,9);
				$successroll = $successroll*$allmissions['skill'];
				
				if($successroll > $successrate)
				{
					//Pass SUCCESS message
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Minefield Deployment';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a success! Minefield have been constructed in the path of the enemy advance.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					
					$battlezonetarget = $zonedetails['zoneid'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
					//Mission Effects
					$battlezone = $zonedetails['zoneid'];
					$missioneffect = mysql_query("INSERT INTO missioneffects(missiontype, allegiance, battlezone, effectamount, ticksleft) VALUES('$missiontype', '$allegiance', '$battlezonetarget', '1', '1')");
				}
				else
				{
					//Pass FAIL message.
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Minefield Deployment';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a failure! Your minefield construction crews were intercepted and could not deploy the minefield.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			elseif($missiontype=='lockdown')
			{
				//Evaluate Success
				$successrate = 150;
				$successroll = rand(10,20);
				$successroll = $successroll*$allmissions['skill'];
				
				if($successroll > $successrate)
				{
					//Pass SUCCESS message
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'System Lockdown';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a success! Enemy gateways have been temporarily destabilised. Enemy forces will be unable to deploy or withdraw for the next tick.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					
					$battlezonetarget = $zonedetails['zoneid'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
					//Mission Effects
					$battlezone = $zonedetails['zoneid'];
					$missioneffect = mysql_query("INSERT INTO missioneffects(missiontype, allegiance, battlezone, effectamount, ticksleft) VALUES('$missiontype', '$allegiance', '$battlezonetarget', '1', '1')");
				}
				else
				{
					//Pass FAIL message.
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'System Lockdown';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a failure! Despite their best efforts, your units were unable to destabilise the enemy gateways..<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			elseif($missiontype=='utility')
			{
				//Evaluate Success
				$successrate = 210;
				$successroll = rand(10,20);
				$successroll = $successroll*$allmissions['skill'];
				
				if($successroll > $successrate)
				{
					//Pass SUCCESS message
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'Utility Fog';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a success! Nanomachines have been deployed in vast clouds throughout the system, vastly improving the quality of your drones while hampering enemy forces. Drones have received a 10% bonus. Enemy troops have suffered a 2% penalty.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					
					$battlezonetarget = $zonedetails['zoneid'];
					//Update Troop Skill
					$updatebattleskill = mysql_query("UPDATE battlezones SET autheffective=autheffective+10, soleffective=soleffective-2, merceffective=merceffective-2 WHERE zoneid='$battlezonetarget'");
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
					//Mission Effects
					$battlezone = $zonedetails['zoneid'];
					$missioneffect = mysql_query("INSERT INTO missioneffects(missiontype, allegiance, battlezone, effectamount, ticksleft) VALUES('$missiontype', '$allegiance', '$battlezonetarget', '1', '1')");
				}
				else
				{
					//Pass FAIL message.
					$allegiance = $allmissions['allegiance'];
					$missiontitle = 'System Lockdown';
					$missiontext = "<b>Location: </b>".$zonedetails['zonename']."<br><b>Mission: </b>".$missiontitle."<br>
					Your mission was a failure! The nanofog proved ineffective and was quickly brought offline by enemy forces.<br><br>";
					$user = $allmissions['user'];
					$colony = $allmissions['colony'];
					$missionresult = mysql_query("INSERT INTO missionresults(user, colony, allegiance, missionresults) VALUES('$user', '$colony', '$allegiance', '$missiontext')");
				}
			}
			$deletemission = mysql_query("DELETE FROM activemissions WHERE id='$missionid'");
		}
		else
		{
			$updatemission = mysql_query("UPDATE activemissions SET ticksleft=ticksleft-1 WHERE id='$missionid'");
		}
	}
}