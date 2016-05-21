# language-relay-game-summarizer
Simple environment for easy summarization and display of language relay games.

By Pauli "Gwaur" Marttinen

## Configuration

§ In order to be able to see hidden relays, on line 3 of variables.php there is the $getstoseeall variable. Change the content of that variable to your IP address.

§ If usernames in the community using a site using this engine have a constant prefix, like /u/ in Reddit communities, change the $prefix variable on line 7 of variables.php into that prefix. If there is no prefix, leave the variable empty ('' or "") instead of removing it.

§ On line 10 of variables.php the name of the community will be set. This name will appear in headers.

§ On line 13 of variables.php the name of the website host will be set.

§ The 17th row of variables.php is "//$danger = 1;". If you're tweaking the programming, you might want to uncomment this. It will make a visitor see a message saying that tweaking is going on and bugs are possible.

§ The lines 20-27 of variables.php are messages to print out in place of missing information.

## To create a new relay

§ To create a new relay with the ID number X, create a text file named "relayX" in the same directory as index.php, and a subdirectory named "relayXdata".

§ Relay numbering starts from 1, not 0.

§ When listing the relays in the front page, the list stops at the first "relayX" that does not exist.

§ In the file "relayX", the first line is the title of the relay. If the title is followed by a tab "\t" and the word 'hidden', the relay will be hidden. The rest of the lines list the steps of the relay so that a line begins with the name of the language used in the step, followed by a tab, followed by the participant's own name.

§ In the subdirectory "relayXdata" create these files for each turn:

* "username_text" for the text.
* "username_engl" for the translation.
* "username_gloss" for the glossing.
* "username_glossary" for the dictionary.
* "username_grammar" for the grammar description.
* "username_ipa" for the pronunciation.

§ Keep the username in the file names lowercase. This way, if you accidentally miscase someone's name, you can fix it in a single step by editing the "relayX" file.

§ The contents of each file is formatted using an original markup.

## Markup

###Single lines

§ Every line separated by a single newline is considered a line in the same paragraph/verse. A single newline will be turned into a &lt;br /&gt; by the parser.

###Paragraphs

§ Lines separated by two newlines are considered different paragraphs/verses. The parser will surround paragraphs/verses with &lt;p&gt; and &lt;/p&gt;.

###Headers

§ Lines beginning with 1-6 colons (:) and surrounded by empty lines are considered headers. The more colons a line starts with, the deeper a subheader it is. One colon becomes &lt;h1&gt;.

###Lists

§ Lines beginning with commas (,) are considered list items. The more commas a line starts with, the deeper the list item is. The whole list is to be surrounded by empty lines.

###Tables

§ Tables are created by having a line saying "&lt;table&gt;" and then writing the table contents by separating each sell with a tab and each row eith a newline. The table is to be separated by newlines, unless you want the table to be inside a list element, in which case the &lt;table&gt; is to be added on the same line as the list element in which it resides.

#### Example 1

<pre>&lt;table&gt;
	John	Michael
Height	181 cm	177 cm
Weight	79 kg	104 kg</pre>

#### Example 2

<pre>,Accusative:
,,The accusative case is marked differently depending on gender and number.&lt;table&gt;
	Singular	Dual	Plural
Common	-t	-ak	-kl
Neuter	-n	-en	-rn
,,The accusative is only used for completed actions.</pre>

## Future

§ I'll create an administration side where all the relay creation and editing and configuration will be done.

§ I'll redesign the way the information of a relay step is displayed, including so that the interlinear gloss will be, you know, interlinear.

§ Expand the markup language.
