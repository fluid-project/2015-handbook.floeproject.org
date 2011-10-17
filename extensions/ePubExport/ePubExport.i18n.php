<?php
/**
 * Internationalisation file for ePubExport extension.
 *
 * @addtogroup Extensions
*/

/*

For translators:
================
Please copy the English version and paste it at the and of the file, then update 
the language code and translate the sentences at right side of each arrow ("=>").

The English version contains documentation for each sentence. You don't have to 
keep it in your language - please keep the file as clean as possible.

This page must be saved as "utf8 without BOM". Notepad++ is a good software that 
capable of editing and saving in this format.

For testing, a native language mediawiki is needed as well as some ereader 
software (and hardware, if available). For ereader software, you may try the 
EPUBReader firefox extension.

*/

global $wgSitename;
 
$messages = array();
 
$messages['en'] = array(
		/* Text of the link to the ePub special page, in the "Special Pages" page */
        'epubprint' => 'ePub Export' ,
		/* Toolbox link text */
        'ePub_print_link' => 'Export as ePub',
		/* Title of the special page */
		'ePub_special_page_title' => '=Export Pages as ePub for e-books=',
		/* ePub Special page instractions to user, to enter page names */
        'ePub_print_text' => 'Enter a list of one or more pages to export to ePub, one page name per line',
		/* ePub Special page instractions to the user to enter an optional book description */
		'ePub_enter_description' => 'Enter a description for the ebook (optional)',
		/* ePub Special page submit button text */
        'ePub_submit' => 'Generate ePub',
		/* Text before the requested output file name field in the ePub special page*/
        'ePub_filename' => 'file name',
		/* Default ebook description that can be changed by the user (ePub Special page)*/
		'default_description' => 'Group of pages from ' . $wgSitename,
		/* This text apears in the ebook (the epub file) uitself on the cover page. $1 and $2 will 
		   automatically be changed to current date and time */
		'credit_text' => "Exported from " . $wgSitename . " at $1, $2.",
);

$messages['he'] = array(
        'epubprint' => ' ייצא עבור ספרים אלקטרוניים (epub)' ,
        'ePub_print_link' => 'ייצא דף כepub',
		'ePub_special_page_title' => 'ייצא דפים עבור קורא ספרים אלקטרוני',
        'ePub_print_text' => 'הכנס רשימת שמות דפים ליצוא, שורה אחת עבור כל שם של דף.',
		'ePub_enter_description' => 'כתוב תיאור קצר של הספר (אופציונאלי)',
        'ePub_submit' => 'ייצא',
        'ePub_filename' => 'file name',
		'default_description' => 'אוסף מאמרים מיוצאים מ' . $wgSitename,
		'credit_text' => 'יוצא מ' . $wgSitename . " ב$1, $2.",
);

// Dutch translation by yourix: yourix@users.sourceforge.net
$messages['nl'] = array(
        'epubprint' => 'ePub Export' ,
        'ePub_print_link' => 'Exporteren als ePub',
		'ePub_special_page_title' => '=Pagina\'s exporteren als ePub voor e-books=',
        'ePub_print_text' => 'Voer een lijst in van één of meerdere pagina\'s voor export naar ePub, één paginanaam per lijn',
		'ePub_enter_description' => 'Geef een beschrijving voor de e-book (optioneel)',
        'ePub_submit' => 'Maak ePub aan',
        'ePub_filename' => 'bestandsnaam',
		'default_description' => 'Paginagroep van ' . $wgSitename,
		'credit_text' => "Geëxporteerd van " . $wgSitename . "op $1, om $2",
);

// French translation by yourix: yourix@users.sourceforge.net
$messages['fr'] = array(
        'epubprint' => 'Exportation d\'ePub' ,
        'ePub_print_link' => 'Exporter comme ePub',
		'ePub_special_page_title' => '=Exporter Pages comme ePub pour e-books=',
        'ePub_print_text' => 'Entrez une liste d\'une ou plusieurs pages pour l\'exportation vers ePub, un nom de page par ligne',
		'ePub_enter_description' => 'Donnez une déscription pour l\'e-book (optionnel)',
        'ePub_submit' => 'Construire ePub',
        'ePub_filename' => 'nom de fichier',
		'default_description' => 'Groupe de pages de ' . $wgSitename,
		'credit_text' => "Exporté de " . $wgSitename . " le $1, à $2",
);

// Polish translation by myszon666: myszon666@users.sourceforge.net
$messages['pl'] = array(
		'epubprint' => 'ePub Export',
		'ePub_print_link' => 'Eksportuj jako ePub',
		'ePub_special_page_title' => '=Eksportuj strony jako e-book ePub=',
		'ePub_print_text' => 'Wpisz jedną lub więcej stron aby wyeksportować je do ePub, każda strona w nowej linii',
		'ePub_submit' => 'Stwórz ePub',
		'ePub_filename' => 'nazwa pliku',
		'default_description' => 'Grupa stron z ' . $wgSitename,
		'credit_text' => "Wyeksportowano z " . $wgSitename . "($1, $2)",
);

// German translation by fhaag: fhaag@users.sourceforge.net
$messages['de'] = array(
        'epubprint' => 'ePub-Export' ,
        'ePub_print_link' => 'Als ePub exportieren',
		'ePub_special_page_title' => '=Seiten als ePub für E-Books exportieren=',
        'ePub_print_text' => 'Geben Sie eine Liste mit einer oder mehr Seiten ein, die als ePub exportiert werden sollen (nur eine Seite pro Zeile).',
		'ePub_enter_description' => 'Geben Sie eine Beschreibung für das eBook ein (optional).',
        'ePub_submit' => 'ePub erzeugen',
        'ePub_filename' => 'Dateiname',
		'default_description' => 'Gruppe von Seiten aus ' . $wgSitename,
		'credit_text' => "Exportiert aus " . $wgSitename . " am $1, $2.",
);

// Russian translation by dmitriy-opt: dmitriy-opt@users.sourceforge.net
$messages['ru'] = array(
        'epubprint' => 'Экспорт ePub' ,
        'ePub_print_link' => 'Экспортировать в ePub',
		'ePub_special_page_title' => '=Экспортировать страницы в ePub для электронных книг=',
        'ePub_print_text' => 'Введите список из одной или нескольких страниц для экспорта в ePub, по одному названию страницы в каждой строке',
		'ePub_enter_description' => 'Введите описание для электронной книги (по желанию)',
        'ePub_submit' => 'Создать ePub',
		'ePub_filename' => 'имя файла',
		'default_description' => 'Группа страниц с ' . $wgSitename,
		'credit_text' => "Экспортировано с " . $wgSitename . "($1, $2)",
);

/* Italian traslation by Yuri Cervoni: yuro85@users.sourceforge.net */
$messages['it'] = array(
        'epubprint' => 'Esportazione ePub' ,
        'ePub_print_link' => 'Esporta come ePub',
		'ePub_special_page_title' => '=Esporta pagine come ePub per e-books (libro)=',
        'ePub_print_text' => 'Inserisci una lista di una o più pagine da esportare su ePub, ogni riga contiene un nome di una pagina',
		'ePub_enter_description' => 'Inserisci una descrizione per il libro(opzionale)',
        'ePub_submit' => 'Crea ePub',
        'ePub_filename' => 'nome del file',
		'default_description' => 'Gruppi di pagine da.. ' . $wgSitename,
		'credit_text' => "Esporta da.. " . $wgSitename . " a $1, $2.",
);

/* Portuguese translation by pbsilva: pbsilva@users.sourceforge.net */
$messages['pt'] = array(
        'epubprint' => 'Exportação ePub' ,
        'ePub_print_link' => 'Exportar como ePub',
		'ePub_special_page_title' => '=Exportar páginas como ePub para E-Books=',
        'ePub_print_text' => 'Introduza uma lista com uma ou mais páginas para exportação como ePub (apenas uma página por linha).',
		'ePub_enter_description' => 'Introduza uma descrição para o eBook (opcional).',
        'ePub_submit' => 'Construir ePub',
        'ePub_filename' => 'Nome do ficheiro',
		'default_description' => 'Grupo de páginas de ' . $wgSitename,
		'credit_text' => "Exportado de" . $wgSitename . " em $1, $2.",
);
?>
