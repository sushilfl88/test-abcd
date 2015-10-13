<?php

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  Script: Maian Support
  Written by: David Ian Bennett
  E-Mail: support@maianscriptworld.co.uk
  Software Website: http://www.maiansupport.com
  Script Portal: http://www.maianscriptworld.co.uk

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  
  This File: class.page.php
  Description: Class File

  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

class pagination {

public function pagination($count,$query) {
  global $msg_script42;
  $this->total      = $count;
  $this->start      = 0;
  $this->text       = $msg_script42;
  $this->query      = $query;
  $this->split      = 10;
}

public function display() {
  global $page;
  $html            = '';
  // How many pages?
  $this->num_pages = ceil($this->total/PER_PAGE);
  // If pages less than or equal to 1, display nothing..
  if ($this->num_pages <= 1) {
    return $html;
  }
  // Build pages..
  $current_page  = $page;
  $begin         = $current_page-$this->split;
  $end           = $current_page+$this->split;
  if ($begin<1) {
    $begin = 1;
    $end   = $this->split*2;
  }
  if ($end>$this->num_pages) {
    $end   = $this->num_pages;
    $begin = $end-($this->split*2);
    $begin++;
    if ($begin < 1) {
      $begin = 1;
    }
  }
  if ($current_page!=1) {
    $html .= '<li><a title="'.mswSpecialChars($this->text[0]).'" href="'.$this->query.'1" rel="nofollow">'.$this->text[0].'</a></li>'.mswDefineNewline();
    $html .= '<li><a title="'.mswSpecialChars($this->text[1]).'" href="'.$this->query.($current_page-1).'" rel="nofollow">'.$this->text[1].'</a></li>'.mswDefineNewline();
  } else {
    $html .= '<li class="disabled"><a href="#" rel="nofollow">'.$this->text[0].'</a></li>'.mswDefineNewline();
    $html .= '<li class="disabled"><a href="#" rel="nofollow">'.$this->text[1].'</a></li>'.mswDefineNewline();
  }
  for ($i=$begin; $i<=$end; $i++) {
    if ($i!=$current_page) {
      $html .= '<li><a title="'.$i.'" href="'.$this->query.$i.'" rel="nofollow">'.$i.'</a></li>'.mswDefineNewline();
    } else {
      $html .= '<li class="active"><a href="#" rel="nofollow">'.$i.'</a></li>'.mswDefineNewline();
    }
  }
  if ($current_page!=$this->num_pages) {
    $html .= '<li><a title="'.mswSpecialChars($this->text[2]).'" href="'.$this->query.($current_page+1).'" rel="nofollow">'.$this->text[2].'</a></li>'.mswDefineNewline();
    $html .= '<li><a title="'.mswSpecialChars($this->text[3]).'" href="'.$this->query.$this->num_pages.'" rel="nofollow">'.$this->text[3].'</a></li>'.mswDefineNewline();
  } else {
    $html .= '<li class="disabled"><a href="#" rel="nofollow">'.$this->text[2].'</a></li>'.mswDefineNewline();
    $html .= '<li class="disabled"><a href="#" rel="nofollow">'.$this->text[3].'</a></li>'.mswDefineNewline();
  }
  return '<ul>'.mswDefineNewline().trim($html).mswDefineNewline().'</ul>';
}

}

?>