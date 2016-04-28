<?php require('check.php');
require_once('settings.php'); 
include('header.php');

if(!$_SERVER['QUERY_STRING']) {
	?>
	<h1>Card Claim</h1>
	<center>Use this form to add cards to the database.</center>
	<form method="post" action="claim.php?added">
	<table class="w50 zest">
	<tr><td><textarea name="claim" rows="5" cols="80" placeholder="card01, card02, card03"><?php
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
?></textarea></td></tr>
	<tr><td><input type="submit" name="submit" value=" Add! " /></td></tr>
	</table>
	</form>
	<?php
}

elseif($_SERVER['QUERY_STRING']=="added") {
	if (!isset($_POST['submit']) || $_SERVER['REQUEST_METHOD'] != "POST") {
	    exit("<p>You did not press the submit button; this page should not be accessed directly.</p>");
	}
	else {
	    $exploits = "/(content-type|bcc:|cc:|document.cookie|onclick|onload|javascript|alert)/i";
	    $profanity = "/(beastial|bestial|blowjob|clit|cum|cunilingus|cunillingus|cunnilingus|cunt|ejaculate|fag|felatio|fellatio|fuck|fuk|fuks|gangbang|gangbanged|gangbangs|hotsex|jism|jiz|kock|kondum|kum|kunilingus|orgasim|orgasims|orgasm|orgasms|phonesex|phuk|phuq|porn|pussies|pussy|spunk|xxx)/i";
	    $spamwords = "/(viagra|phentermine|tramadol|adipex|advai|alprazolam|ambien|ambian|amoxicillin|antivert|blackjack|backgammon|texas|holdem|poker|carisoprodol|ciara|ciprofloxacin|debt|dating|porn)/i";
	    $bots = "/(Indy|Blaiz|Java|libwww-perl|Python|OutfoxBot|User-Agent|PycURL|AlphaServer)/i";

	    if (preg_match($bots, $_SERVER['HTTP_USER_AGENT'])) {
	        exit("<h1>Error</h1>\nKnown spam bots are not allowed.");
	    }
	    foreach ($_POST as $key => $value) {
	        $value = trim($value);

	        if (empty($value)) {
	            exit("<h1>Error</h1>\nAll fields are required. Please go back and complete the form.");
	        }
	        elseif (preg_match($exploits, $value)) {
	            exit("<h1>Error</h1>\nExploits/malicious scripting attributes aren't allowed.");
	        }
	        elseif (preg_match($profanity, $value) || preg_match($spamwords, $value)) {
	            exit("<h1>Error</h1>\nThat kind of language is not allowed through this form.");
	        }
	        $_POST[$key] = stripslashes(strip_tags($value));
	    }
	$add =  escape_sql(CleanUp($_POST['claim']));
	$array = explode(', ',$add);
	$array_count = count($array);
	for($i=0; $i<=($array_count -1); $i++) {

		$insert = "INSERT INTO claim (`id`, `card`) VALUES ('', '$array[$i]')";
		mysql_query($insert, $connect) or die(mysql_error());

	}
	        echo "<h1>Success</h1>\n";
	        echo "<center>The cards were successfully added to the database.\n";
	        echo "Want to <a href=\"claim.php\">add</a> another?</center>";

	}
}
?>
<h1>Version</h1>
<?php
if (ini_get('allow_url_fopen') == '1') {
	$installed = file_get_contents('claim.txt');
	$version = file_get_contents('http://absolute-chaos.net/check/claim.txt');
	if ($version !== false) {
		if ($installed == $version) { //version numbers are the same
			echo "You are using Card Claim version ".$installed.". That is the latest version.";
		}
		else if ($installed != $version) { //version numbers are not the same
			echo "<p>You are using Card Claim version ".$installed.". Please update to <a href=\"http://www.absolute-chaos.net/\" target=_blank>Card Claim ".$version."</a>.";
		}
	}
	else {
		// an error happened
		echo "Could not check for updates. Please make sure you use the latest version of <a href=\"http://www.absolute-chaos.net/\">Card Claim</a>.";
	}
}
else {
   echo "Could not check for updates. Please make sure you use the latest version of <a href=\"http://www.absolute-chaos.net/\">Card Claim</a>.";
}
include('footer.php'); ?>