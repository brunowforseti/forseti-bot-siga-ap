<?php
/**
 * Created by PhpStorm.
 * User: joaosilva
 * Date: 01/02/19
 * Time: 09:30
 */

namespace Forseti\Bot\Name\Iterator;


class DefaultIterator extends AbstractIterator
{
    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        $node = $this->iterator->current();

        $company = $node->getElementsByTagName('td')->item(0)->textContent;
        $contact = $node->getElementsByTagName('td')->item(1)->textContent;
        $country = $node->getElementsByTagName('td')->item(2)->textContent;

        $customer = new \stdClass();

        $customer->company = $company;
        $customer->contact = $contact;
        $customer->country = $country;

        return $customer;
    }
}