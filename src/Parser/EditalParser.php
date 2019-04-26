<?php

namespace Forseti\Bot\Name\Parser;

use Forseti\Bot\Name\Iterator\EditalDownloadIterator;
use Forseti\Bot\Name\Iterator\EditalIdAnexoIterator;
use Forseti\Bot\Name\Iterator\ViewStateDownloadIterator;
use Forseti\Bot\Name\Iterator\DespesasIterator;
use Forseti\Bot\Name\Iterator\EditaisIterator;
use Forseti\Bot\Name\Iterator\NovidadesIterator;


class EditalParser extends AbstractParser
{
    public function getFileName($xpath = '(//table[contains(@id, "form1:j_id_jsp_")]//td[position() = 2])')
    {
        return new EditalDownloadIterator($this->getHtml(), $xpath);
    }

    public function getEditaisIterator($xpath)
    {
        return new EditaisIterator($this->getHtml(), $xpath);
    }

    public function getIdAnexo($xpath = '(//a[contains(@onclick, "idAnexo")][contains(@id, "downloadLink")])')
    {
        return new EditalIdAnexoIterator($this->getHtml(), $xpath);
    }

    public function getViewStateForDownload($xpath = '(//input[@id="javax.faces.ViewState"])')
    {
        return new ViewStateDownloadIterator($this->getHtml(), $xpath);
    }
    
    public function getDespesasIterator($xpath)
    {
        return new DespesasIterator($this->getHtml(), $xpath);
    }
    public function getNovidadesIterator($xpath)
    {
        return new NovidadesIterator($this->getHtml(), $xpath);
    }

}