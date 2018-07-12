<button type="button"
        class="btn btn-xs btn-primary"
        data-toggle="modal"
        data-target="#addLink"><i class="fa fa-plus-square-o"></i>&nbsp;&nbsp;Add URL&nbsp;&nbsp;&nbsp;&nbsp;</button>
<div class="modal inmodal"
     id="addLink"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated fadeIn">
            <div class="modal-header">
                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">New URL</h4>
            </div>
            <form name="link_form"
                  id="link_form"
                  class="form-horizontal"
                  role="form"
          method="POST"
          onsubmit="return submitLinkForm();"
                  action="{{ url('/links') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text"
                               placeholder="Link name"
                               class="form-control"
                               name="link_name"
                               required>

                        <label class="error hide"
                               for="link_name"></label>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control m-b" id="link_category_id"
                                name="link_category"
                                required>
                            <option value="">Choose category</option>
                            @foreach(App\Category::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->category }}</option>
                            @endforeach
                        </select>
                        <label class="error hide"
                               for="link_category"></label>
                    </div>

                    <div class="form-group">
                        <label>URL</label>
                        <input type="url"
                               placeholder="Must be a valid URL, with http:// or https://"
                               class="form-control"
                               name="url"
                               required>
                        <input type="hidden" 
                               name="return_url"
                               id="return_url"
            @if( $_SERVER['REQUEST_URI'] == '/campaign')
                               value="campaign">
                        @else
                               value="library">
                    @endif
                        <label class="error hide"
                               for="url"></label>
                    </div>                    
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-white"
                            data-dismiss="modal">Cancel</button>
                    <button type="submit"
                            name="submit"
                            class="btn btn-primary">Submit</button>
                </div>
        </form>
        </div>
    </div>
</div>
<script type="text/javascript">
function submitLinkForm(){
    $.post('/links', $('#link_form').serialize())
        .done(function (response) {
            toastr.success('Link Added Successfully!', '', 
               {onHidden: function () {
                   if(window.location.href.indexOf("/library") > -1) {
                        window.location.href = "/library";
                   }
               }});
            $('#addLink').modal('hide');
          }).fail(function (response) {
            toastr.error('Failed to add Link!');
            $('addLink').modal('hide');
         });
    return false;
}
</script>
