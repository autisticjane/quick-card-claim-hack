# quick-card-claim-hack
Have the MyTCG randomizer pull filenames in the textarea field at random, so all you have to do is click "add"! It's automatically set to pull 50 random filenames, separated by commas (except the last filename).

##Just want the randomizer code?
```
<?php
$result=mysql_query("SELECT * FROM `$table_cards` WHERE `worth`='1'") or die("Unable to select from database.");
$min=1;
$max=mysql_num_rows($result);
for($i=0; $i<50; $i++) {
mysql_data_seek($result,rand($min,$max)-1);
$row=mysql_fetch_assoc($result);
$digits = rand(01,$row['count']);
if ($digits < 10) { $_digits = "0$digits"; } else { $_digits = $digits;}
$card = "$row[filename]$_digits";
$randclaim .= $card.", ";
}
$randclaim = substr_replace($randclaim,"",-2);
echo "$randclaim";
?>
```
##Change the number of cards pulled
Find **line 15**: ``for($i=0; $i<50; $i++) {`` and change ``50`` to the number of filenames you want pulled.

##Notes

* The more filenames you have it pull, the longer the query will take to process.
* The more cards you have loading on one page, the longer players will be waiting - and the more likely chance the page will time out, they will be blocked from the server (depending on the settings), or their experience with the site will quickly turn sour, and...well...who would want that?
