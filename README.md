# phpmailer
phpmailer skript

Mein etwas älteres standard Mailskript um mit PHP ein Mailformular zu realisieren.

Die Defaulteinstellugnen für Betreff, Absender und Empfänger des E-Mail Formulars könne mit folgneden versteckten Formularfeldern überschrieben werden:

- Absender: <input name="Email" type="text" id="Email" />
- Empfänger: <input name="recipient" type="text" id="recipient"value="test@mail.de" />
- Betreff: <input name="subject" type="text" id="subject" value="Mail-Formular" />

Die Pfichtfelder können voragb im Browser mittels Javascript geprüft werden, bzw die Überprüfung erfolgt im Mailskript.
Dazu müssen die Pflichtfelder z.B.: 
$pflicht_keys= array ( 'Vorname','Nachname','Strasse','PLZ','Telefon','Email' );
im Mailerskript angepasst werden.

Die Überprüfung mittels Javaskript habe ich mir von Dreamweaver geborgt :-)

<input name="Senden" type="submit" id="Senden" onclick="MM_validateForm('PLZ','','NisNum','Email','','RisEmail');return document.MM_returnValue" value="Abschicken" />
