<?php
/*
              _____           ____
            |   __|_____ _ _|    \ ___ _ _ ___
            |   __|     | | |  |  | -_| | |_ -|
            |_____|_|_|_|___|____/|___|\_/|___|
     Copyright (C) 2013 EmuDevs <http://www.emudevs.com/>
 */
 
	connect::selectDB('webdb');
    if (!isset($GLOBALS['playersOnline']))
        return;
	$result = mysql_query("SELECT id,name FROM realms WHERE id='".$GLOBALS['playersOnline']['realm_id2']."'");
	$row = mysql_fetch_assoc($result);
	$rid = $row['id'];
	$realmname = $row['name'];
	
	connect::connectToRealmDB($rid);
	
	$count = mysql_query("SELECT COUNT(*) FROM characters WHERE name!='' AND online=1");
?>
<div class="box_one">
<div class="box_one_title">Jogadores Online - <?php echo $realmname; ?></div>
<?php
if(mysql_result($count,0)==0)
	echo '<b>Não há nenhum jogador online agora!</b>';
else
{		   
		   ?>
<table width="100%">
        <tr>
            <th>Nome</th>
            <th>Guilda</th>
            <th>Abates</th>
            <th>Nível</th>
        </tr>
        <?php
		if($GLOBALS['playersOnline']['moduleResults']>0)
		{
			$result = mysql_query("SELECT guid, name, totalKills, level, race, class, gender, account FROM characters WHERE name!='' 
								AND online=1 LIMIT ".$GLOBALS['playersOnline']['moduleResults']);
		}
		else
		{
			$result = mysql_query("SELECT guid, name, totalKills, level, race, class, gender, account FROM characters WHERE name!='' 
								  AND online=1");
		}
		while($row = mysql_fetch_assoc($result)) 
		{
			connect::connectToRealmDB($rid);
			$getGuild = mysql_query("SELECT guildid FROM guild_member WHERE guid='".$row['guid']."'");
			if(mysql_num_rows($getGuild)==0)
			   $guild = "Nenhuma";
			else
			{
				$g = mysql_fetch_assoc($getGuild);
				$getGName = mysql_query("SELECT name FROM guild WHERE guildid='".$g['guildid']."'");
				$x = mysql_fetch_assoc($getGName);
				$guild = '&lt; '.$x['name'].' &gt;';
			}
			
			if($GLOBALS['playersOnline']['display_GMS']==false)
			{
			//Check if GM.
				connect::selectDB('logondb');
				$checkGM = mysql_query("SELECT COUNT(*) FROM account_access WHERE id='".$row['account']."' AND gmlevel >0");
				if(mysql_result($checkGM,0)==0)
				{
					echo 
					'<tr style="text-align: center;">
						<td>'.$row['name'].'</td>
						<td>'.$guild.'</td>
						<td>'.$row['totalKills'].'</td>
						<td>'.$row['level'].'</td>
					</tr>';
				}
			}
			else
			{
				echo 
				'<tr style="text-align: center;">
					<td>'.$row['name'].'</td>
					<td>'.$guild.'</td>
					<td>'.$row['totalKills'].'</td>
					<td>'.$row['level'].'</td>
				</tr>';
			}
		}
		?>
</table>
<?php
if($GLOBALS['playersOnline']['enablePage']==true)
{
?>
<hr/>
<a href="?p=playersonline2">Ver mais...</a>
<?php 
	}
} 
?>
</div>