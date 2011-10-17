<?php

$wgHooks['ParserAfterStrip'][] = 'wfNextAndPreviousRender';

function wfNextAndPreviousRender(&$parser, &$text)
{
    global $wgRequest;
    
    // do not display the next and previous links unless the page is just being viewed
    $action = $wgRequest->getText('action', 'view');
    if ('view' != $action and 'purge' != $action)
    {
        return true;
    }
    $diff = $wgRequest->getVal('diff');
    if (!is_null($diff))
    {
        return true;
    }

    $title = $parser->mTitle;
    if ($title && 0 === $title->getNamespace())
    {
        // get the page title as saved in the database
        $pageTitle = strtolower($title->getDBkey());
        $parser->disableCache();
        
        $dbr = &wfGetDB(DB_SLAVE);
        // use this function to add any database prefix
        $pageTable = $dbr->tableName('page');
        
        $links = '<div><table width="100%"><tr>';
        
        // find previous link
        $res = $dbr->query("
            SELECT page_id
            FROM $pageTable
            WHERE page_namespace=0 AND \"$pageTitle\" > lower(page_title)
            ORDER BY lower(page_title) DESC
            LIMIT 1");
        if ($dbr->numRows($res))
        {
            $row = $dbr->fetchObject($res);
            $prev = Title::newFromID($row->page_id);
            if ($prev)
            {               
                $links .= '<td width="50%" align="left">[[' . $prev->getPrefixedText() .  '| previous page]]</td>';
            }
        }
        else
        {
            $links .= '<td width="50%" align="left"></td>';
        }
        $dbr->freeResult($res);
        
        // find next link            
        $res = $dbr->query("
            SELECT page_id
            FROM $pageTable
            WHERE page_namespace=0 AND \"$pageTitle\" < lower(page_title)
            ORDER BY lower(page_title)
            LIMIT 1");
        if ($dbr->numRows($res))
        {
            $row = $dbr->fetchObject($res);
            $next = Title::newFromID($row->page_id);
            if ($next)
            {               
                $links .= '<td width="50%" align="right">[[' . $next->getPrefixedText() .  '| next page]]</td>';
            }
        }
        else
        {
            $links .= '<td width="50%" align="right"></td>';
        }
        $dbr->freeResult($res);
        
        $links .= '</tr></table></div><hr/>';
        // position the links at the top of the page
        $text = $links . "\n" . $text;
    }
    return true;
}

?> 