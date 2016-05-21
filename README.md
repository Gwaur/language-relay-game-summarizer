# language-relay-game-summarizer
Simple environment for easy summarization and display of language relay games.

By Pauli "Gwaur" Marttinen

## Configuration

§ In order to be able to see hidden relays, on line 3 of <code>variables.php</code> there is the <code>$getstoseeall</code> variable. Change the content of that variable to your IP address.

§ If usernames in the community using a site using this engine have a constant prefix, like <code>/u/</code> in Reddit communities, change the <code>$prefix</code> variable on line 7 of <code>variables.php</code> into that prefix. If there is no prefix, leave the variable empty (<code>''</code> or <code>""</code>) instead of removing it.

§ On line 10 of <code>variables.php</code> the name of the community will be set. This name will appear in headers.

§ On line 13 of <code>variables.php</code> the name of the website host will be set.

§ The 17th row of <code>variables.php</code> is <code>//$danger = 1;</code>. If you're tweaking the programming, you might want to uncomment this. It will make a visitor see a message saying that tweaking is going on and bugs are possible.

§ The lines 20–27 of <code>variables.php</code> are messages to print out in place of missing information.

## To create a new relay

§ To create a new relay with the ID number X, create a text file named <code>relayX</code> in the same directory as <code>index.php</code>, and a subdirectory named <code>relayXdata</code>.

§ Relay numbering starts from 1, not 0.

§ When listing the relays in the front page, the list stops at the first <code>relayX</code> that does not exist.

§ In the file <code>relayX</code>, the first line is the title of the relay. If the title is followed by a tab <code>\t</code> and the word <code>hidden</code>, the relay will be hidden. The rest of the lines list the steps of the relay so that a line begins with the name of the language used in the step, followed by a tab, followed by the participant's own name.

§ In the subdirectory <code>relayXdata</code> create these files for each turn:

* <code>username_text</code> for the text.
* <code>username_engl</code> for the translation.
* <code>username_gloss</code> for the glossing.
* <code>username_glossary</code> for the dictionary.
* <code>username_grammar</code> for the grammar description.
* <code>username_ipa</code> for the pronunciation.

§ Keep the username in the file names lowercase. This way, if you accidentally miscase someone's name, you can fix it in a single step by editing the <code>relayX</code> file.

§ The contents of each file is formatted using an original markup.

## Markup

###Single lines

§ Every line separated by a single newline is considered a line in the same paragraph/verse. A single newline will be turned into a <code>&lt;br /&gt;</code> by the parser.

###Paragraphs

§ Lines separated by two newlines are considered different paragraphs/verses. The parser will surround paragraphs/verses with <code>&lt;p&gt;</code> and <code>&lt;/p&gt;</code>.

###Headers

§ Lines beginning with 1–6 colons (<code>:</code>) and surrounded by empty lines are considered headers. The more colons a line starts with, the deeper a subheader it is. One colon becomes <code>&lt;h1&gt;</code>.

###Lists

§ Lines beginning with commas (<code>,</code>) are considered list items. The more commas a line starts with, the deeper the list item is. The whole list is to be surrounded by empty lines.

###Tables

§ Tables are created by having a line saying <code>&lt;table&gt;</code> and then writing the table contents by separating each sell with a tab and each row eith a newline. The table is to be separated by newlines, unless you want the table to be inside a list element, in which case the <code>&lt;table&gt;</code> is to be added on the same line as the list element in which it resides.

#### Example 1

<pre><code>&lt;table&gt;
	John	Michael
Height	181 cm	177 cm
Weight	79 kg	104 kg</code></pre>

#### Example 2

<pre><code>,Accusative:
,,The accusative case is marked differently depending on gender and number.&lt;table&gt;
	Singular	Dual	Plural
Common	-t	-ak	-kl
Neuter	-n	-en	-rn
,,The accusative is only used for completed actions.</code></pre>

## Future

§ I'll create an administration side where all the relay creation and editing and configuration will be done.

§ I'll redesign the way the information of a relay step is displayed, including so that the interlinear gloss will be, you know, interlinear.

§ Expand the markup language.
