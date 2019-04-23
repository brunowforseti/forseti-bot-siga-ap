<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 09:00
 */

namespace Forseti\Bot\Name\Parser;

use Forseti\Bot\Name\Iterator\EditalDownloadIterator;
use Forseti\Bot\Name\Iterator\EditalIdAnexoIterator;
use Forseti\Bot\Name\Iterator\ViewStateDownloadIterator;


class EditalParser extends AbstractParser
{
    public function getFileName($xpath = '(//table[contains(@id, "form1:j_id_jsp_")]//td[position() = 2])')
    {
        return new EditalDownloadIterator($this->getHtml(), $xpath);
    }

    public function getIdAnexo($xpath = '(//a[contains(@onclick, "idAnexo")][contains(@id, "downloadLink")])')
    {
        return new EditalIdAnexoIterator($this->getHtml(), $xpath);
    }

    public function getViewStateForDownload($xpath = '(//input[@id="javax.faces.ViewState"])')
    {
        return new ViewStateDownloadIterator($this->getHtml(), $xpath);
    }

}