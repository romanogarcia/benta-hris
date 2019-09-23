<div class="float-lg-right">
 <form action="?" method="get" class="form-inline justify-content-sm-center">
   <input type="hidden" value="{{$rows->currentPage()}}" name="page">
  <label class="tbl_length_lbl">Show </label>
   <select  class="form-control form-control-sm tbl_length_select" name="perpage" onchange="this.form.submit()" style="width: auto;">
     <option value="100" {{app('request')->input('perpage') == 100 ? 'selected':''}}>100</option>
     <option value="200" {{app('request')->input('perpage') == 200 ? 'selected':''}}>200</option>
     <option value="500" {{app('request')->input('perpage') == 500 ? 'selected':''}}>500</option>
     <option value="1000" {{app('request')->input('perpage') == 1000 ? 'selected':''}}>1000</option>
   </select>
   <label class="tbl_length_lbl">entries</label>
 </form>
</div>