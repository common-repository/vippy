<?php
 
/************************************************************\
  *
  * PHP Array Pagination Copyright 2007 - Derek Harvey
  * www.lotsofcode.com
  *
  * This file is part of PHP Array Pagination .
  *
  * PHP Array Pagination is free software; you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation; either version 2 of the License, or
  * (at your option) any later version.
  *
  * PHP Array Pagination is distributed in the hope that it will be useful,
  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  * GNU General Public License for more details.
  *
  * You should have received a copy of the GNU General Public License
  * along with PHP Array Pagination ; if not, write to the Free Software
  * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
  *
  \************************************************************/
 
class vippyPagination
{
var $paged = 1; // Current Page
var $perPage = 10; // Items on each paged, defaulted to 10
var $showFirstAndLast = false; // if you would like the first and last paged options.
 
function generate($array, $perPage = 10)
{
// Assign the items per paged variable
if (!empty($perPage))
$this->perPage = $perPage;
 
// Assign the paged variable
if (!empty($_GET['paged'])) {
$this->paged = $_GET['paged']; // using the get method
} else {
$this->paged = 1; // if we don't have a paged number then assume we are on the first paged
}
 
// Take the length of the array
$this->length = count($array);
 
// Get the number of pageds
$this->pageds = ceil($this->length / $this->perPage);
 
// Calculate the starting point
$this->start = ceil(($this->paged - 1) * $this->perPage);
 
// Return the part of the array we have requested
return array_slice($array, $this->start, $this->perPage);
}
 
function links()
{
// Initiate the links array
$plinks = array();
$links = array();
$slinks = array();
 
// Concatenate the get variables to add to the paged numbering string
if (count($_GET)) {
$queryURL = '';
foreach ($_GET as $key => $value) {
if ($key != 'paged') {
$queryURL .= '&'.$key.'='.$value;
}
}
}
 
// If we have more then one pageds
if (($this->pageds) > 1)
{
// Assign the 'previous paged' link into the array if we are not on the first paged
if ($this->paged != 1) {
if ($this->showFirstAndLast) {
$plinks[] = ' <a href="?paged=1'.$queryURL.'">&laquo;&laquo; First </a> ';
}
$plinks[] = ' <a href="?paged='.($this->paged - 1).$queryURL.'">&laquo; Prev</a> ';
}
 
// Assign all the paged numbers & links to the array
for ($j = 1; $j < ($this->pageds + 1); $j++) {
if ($this->paged == $j) {
$links[] = ' <a class="selected">'.$j.'</a> '; // If we are on the same paged as the current item
} else {
$links[] = ' <a href="?paged='.$j.$queryURL.'">'.$j.'</a> '; // add the link to the array
}
}
 
// Assign the 'next paged' if we are not on the last paged
if ($this->paged < $this->pageds) {
$slinks[] = ' <a href="?paged='.($this->paged + 1).$queryURL.'"> Next &raquo; </a> ';
if ($this->showFirstAndLast) {
$slinks[] = ' <a href="?paged='.($this->pageds).$queryURL.'"> Last &raquo;&raquo; </a> ';
}
}
 
// Push the array into a string using any some glue
return implode(' ', $plinks).implode($this->implodeBy, $links).implode(' ', $slinks);
}
return;
}
}