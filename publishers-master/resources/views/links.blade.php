            <div class="ibox">
                        <div class="ibox-title" id="links_heading">My Links<div class="pull-right">@include('link_upload')</div></div>
                        <div class="ibox-content table-responsive" id="links_div">
                        @if (count($links))
                           <table class="table table-hover table-border table-striped table-condensed" name="links_table" id="links_table" width="100%">
                            <thead>
                            <tr><th>Link Name</th><th>Category</th><th>URL</th><th>Status</th><th>Date Created</th></tr>
                            </thead>
                            <tbody>
                            @foreach ($links as $link)
                                <tr class="link_row" id="link_row_{{ $link->id }}">
                                    <td>{{ $link->link_name }} </td>
                                    <td> {{ $categories[$link->category] }} </td>
                                    <td> <a href="{{ $link->url }}" target="blank">{{substr($link->url,0,25)}}</a></td>
                                    <td> {{ $status_types[$link->status] }} </td>
                                    <td> {{ Carbon\Carbon::parse($link->created_at)->toDayDateTimeString() }} </td>
                                </tr>
                            @endforeach
                            </tbody>
                            </table>


                        @else
                            <h3>No Links Defined</h3>
                        @endif
                        {{-- <br /><br /><a href="/links"><button class="btn-u" type="button" id="add_link">Add Links</button></a> --}}
                        </div>
            </div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="mytitle">Preview</h4>
      </div>
      <div class="modal-body" id="mybody">
       <p>Content</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

