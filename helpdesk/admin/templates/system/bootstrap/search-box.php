      <div class="btn-toolbar" id="b2" style="display:none">
       <div class="input-append">
        <input class="input-large" type="text" name="keys" value="<?php echo (isset($_GET['keys']) ? urlencode(mswSpecialChars($_GET['keys'])) : ''); ?>" onkeypress="if(msKeyCodeEvent(event)==13){mswDoSearch('<?php echo (isset($searchBoxUrl) ? $searchBoxUrl : $_GET['p']); ?>')}">
        <button class="btn btn-info" type="button" onclick="mswDoSearch('<?php echo (isset($searchBoxUrl) ? $searchBoxUrl : $_GET['p']); ?>')"><i class="icon-search"></i></button>
       </div>
      </div>