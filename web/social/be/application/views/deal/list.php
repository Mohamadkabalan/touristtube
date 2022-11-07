<div class="title"><?= $title?>   </div>
<div class="add-deal"><a href="deal/add">add deal</a></div>
<br />
<div class="search-bar">
    <div class="search-content">
        <input type="text" id="txtName" placeholder="Search a deal"/>
        <button type="button" id="btnFilter">Find</button>
    </div>
</div>
<br />
<div id="listContainer">
    <?php $this->load->view('deal/ajax_list');
    ?>
</div>