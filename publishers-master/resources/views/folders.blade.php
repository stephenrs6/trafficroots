            <div class="ibox">
                        <div class="ibox-title" id="creative_heading">My Folders<a href="/folder" class="btn btn-xs btn-primary pull-right">Upload HTML5 Folder</a></div>
                        <div class="ibox-content table-responsive" id="folder_div">
                        @if (count($folders))
                            <table class="table table-hover table-border table-striped table-condensed" name="folders_table" id="folders_table" width="100%">
                            <thead>
                            <tr><th>Folder Name</th><th>Category</th><th>Location Type</th><th>Status</th><th>Date Uploaded</th><th>Preview</th></tr>
                            </thead>
                            <tbody>
                            @foreach ($folders as $folder)
                                <tr class="media_row" id="media_row_{{ $folder->id }}">
                                    <td>{{ $folder->folder_name }} </td>
                                    <td> {{ $categories[$folder->category] }} </td>
                                    <td> {{ $location_types[$folder->location_type] }} </td>
                                    <td> {{ $status_types[$folder->status] }} </td>
                                    <td> {{ Carbon\Carbon::parse($folder->created_at)->toDayDateTimeString() }} </td>
                                    <td> <a href="#" class="tr-iframe" data-toggle="modal" data-target="#myModal" id="view_folder_{{ $width[$folder->location_type] }}_{{ $height[$folder->location_type] }}_{{ $folder->file_location }}"><i class="fa fa-camera-retro" aria-hidden="true"></a></i></td>
                                </tr>
                            @endforeach
                            </tbody>
                            </table>

                        @else
                            <h3>No Folders Defined</h3>
                        @endif
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
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('[data-toggle="popover"]').popover({
            html: true,
        });
        $('.tr-iframe').click(function(){
            var str =  $(this).attr('id');
            var res = str.split("_");
            var url = 'https://publishers.trafficroots.com' + res[4];
            $('#mybody').html('<iframe width="100%" height="100%" frameborder="0" src="' + url + '"></iframe>');
            $('#mybody').height(res[3]);
            $('#mybody').width(res[2]);
        });
        if($('#alert_div'))
        {
            $('#alert_div').fadeOut(1600, function(){

             });
        }
    });

</script>
