<?php
#######################################################
#     PHP-Mailer Skript                               #
#     Version 1.1  (c) by Andreas Weick               #
#     Datum: 12.07.2011                               #
#     V1.1:  Hinzufügen von Pflichtfeldern            #
#                                                     #
#######################################################

#Fehlermodus:
	error_reporting(E_ALL);
#	error_reporting(E_ERROR);



# Zuweisung der Dateinamen von:
# - Antwort-HTML-Datei wenn kein Fehler bei der Übtragung der E-Mail aufgetreten ist
# - Antwort-HTML-Datei wenn ein Fehler bei der Übtragung der E-Mail aufgetreten ist
# - Mail Textdatei die versendet wird.

  $fmtResponse= implode("", file("mail_okay.html")); 
  $fmtResponseerror= implode("", file("mail_fehler.html"));
  $fmtMail= implode("", file("mail.txt"));

  # Vorbelegung, für den Fall das "subject", "recipient" und "Email" nicht im HTML-Formular definiert sind!
############################################################
  $to="geier99@gmx.de";  # <<===================== anpassen:   Empfänger des Kontaktformulars
############################################################

  $from=$to;
#  $subject="Internet Mailformular  von  " . $HTTP_SERVER_VARS["SERVER_NAME"] . " ( " . $HTTP_SERVER_VARS['PATH_INFO'] . ")" ;
   $subject="Internet Mailformular  von  " . $HTTP_SERVER_VARS["SERVER_NAME"] . " ( " . $HTTP_SERVER_VARS['SCRIPT_NAME'] . ")" ;


############## Pflichtfelder hier anpassen ################
    $pflicht_keys= array ( 'Vorname','Nachname','Strasse','PLZ','Telefon','Email' );
    $pflicht_status=0;

    $error_feldnamen="";
	$error_text="<br>&nbsp;<br>Bitte alle Pflichtfelder (*) ausfüllen!<br>";             #Fehlertext wenn nicht alle Pflichtfelder ausgefüllt wurden
    $error_text_allg="<br>&nbsp;<br>Bitte versuchen Sie es sp&auml;ter nocheinmal!<br>"; #Allgemeiner Fehlertext
#############  ende Pflichtfelder #########################





# Variablen für die Loginforamtionen vom Absender anlegen
  $str_logtext = "Rechner: " . gethostbyaddr($HTTP_SERVER_VARS["REMOTE_ADDR"]) . " (" . date("d.m.Y - H:i") . ")";
  $fmtMail= str_replace("<loginfo>", $str_logtext, $fmtMail);

  if ( isset($HTTP_POST_VARS["subject"]) && $HTTP_POST_VARS["subject"] != "") {   			# Subjekt von der Kontaktformularseite übernehmen
       $subject=$HTTP_POST_VARS["subject"];
  }
 
  if ( isset($HTTP_POST_VARS["recipient"]) && $HTTP_POST_VARS["recipient"] != "") {         # Empfänger von der Kontaktseite übernehmen
       $to=$HTTP_POST_VARS["recipient"];
       $from=$to;
  }
  if ( isset($HTTP_POST_VARS["Email"]) && $HTTP_POST_VARS["Email"] != "") {                 # Absender vom Formular übernehmen
       $from=$HTTP_POST_VARS["Email"];
  }
  $from="From: $from\nBCc: geier99@web.de";						# Absender Parameter für den mailsend Befehl erstellen

  foreach($HTTP_POST_VARS as $key=> $val) {						# Schleife über alle Formularfelder durchlaufen
     # wenn Array, dann Array zu String konvertieren
     if (is_array($val)) $val = implode(", ",$val); 

     $fmtResponse= str_replace("<$key>", $val, $fmtResponse);   # Alle Formularfelder in den 3 Vorlagendateien übernehmen
     $fmtMail= str_replace("<$key>", $val, $fmtMail);
     $fmtResponseerror= str_replace("<$key>", $val, $fmtResponseerror);
	 
	if (empty($val)) {      # Abfrage ob es sich bei dem leeren Feld um ein Pflichtfeld handelt
		if ( in_array ( $key, $pflicht_keys ) ) {
	       $pflicht_status=1;
		   $error_feldnamen =  $error_feldnamen . "<br>\n<b>" . $key . "</b>";
#		   echo "<br>\ndebug: $key:" . empty($val) . "\n"; 
	    }
	}		
	 
	 
	 
	 
	 
  }
  # eventuell nicht ausgef&uuml;llte Felder mit keine Angabe belegen (gilt nur für die E-mail die gesendet wird)

################### ab hier anpassen <<====                         ################################################
     $fmtMail= str_replace("<Anfrage>", "keine Angabe", $fmtMail);
	 $fmtMail= str_replace("<Anrede>", "keine Angabe", $fmtMail);
	 $fmtMail= str_replace("<Land>", "keine Angabe", $fmtMail);
	 $fmtMail= str_replace("<Email>", "keine Angabe", $fmtMail);
	 $fmtMail= str_replace("<Vorname>", "keine Angabe", $fmtMail);
################## bis hier anpassen ################################################

	 $fmtMail= str_replace("<subject>", $subject, $fmtMail);	# Wenn keine Subject im Formular angegen ist, das standard Subjekt nehmen


# # echo $fmtMail;
# if ( @mail($to, $subject, $fmtMail, $from)) {			#  E-Mail versenden
#      echo $fmtResponse;                               # keine Fehler ==> normale Antwortseite ausgeben
# }
# else {
#      echo  $fmtResponseerror;                          # es ist ein Fehler aufgetreten==> Fehlerseite ausgeben.
# }
 
 
 if($pflicht_status==1) {
    $error_text = $error_text . $error_feldnamen;  # die fehlenden Felder eintragen,  <<=== auskommentieren, wenn dies nicht gewünscht ist
    $fmtResponseerror= str_replace("<error>", $error_text, $fmtResponseerror);
    echo  $fmtResponseerror;
 }
 elseif ( mail($to, $subject, $fmtMail, $from)) {
    echo $fmtResponse;
    } 
    else { 
    $fmtResponseerror= str_replace("<error>", $error_text_allg, $fmtResponseerror);
     echo  $fmtResponseerror;
  }
 
 
 
 
 
?>