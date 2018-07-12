$(document).ready(function(){
"use strict";
	/*input multiselect*/
	if ($(".chosen-multi-select").length) {
		$('.chosen-multi-select').chosen({ width: "100%" });
	}
	
	/****************alerts****************/
	/*ad campaign image upload*/
	if ($("#imgUpload").length) {
		$("#imgUpload").change(function() {
		  uploadImgURL(this);
		});
	}
	
	//cancel campaign warning
	if ($(".cancelCampaign").length) {
		$(".cancelCampaign").click(function() {
			swal({
				title: "Cancel Campaign",
				text: "Are you sure you want to cancel this campaign?", 
				icon: "warning",
				buttons: true,
				dangerMode: true,
			}).then((cancel) => {
				if (cancel) {
					window.location.href = "publisher-sites.html";
				}
			}); 
		});
	}
	
	if ($("#CancelNewCampaign").length) {
		$("#CancelNewCampaign").click(function() {
			swal({
				title: "Cancel Campaign",
				text: "Are you sure you want to cancel this campaign?", 
				icon: "warning",
				buttons: true,
				dangerMode: true,
			}).then((cancel) => {
				if (cancel) {
					window.location.href = "advertise-campaign.html";
				}
			}); 
		});
	}
	
	if ($("#completeCampaign").length) {
		$("#completeCampaign").click(function () {
			swal({
			  title: "Success!",
			  text: "A new campaign has been created.",
			  icon: "success",
			}).then(() => {
				window.location.replace("advertise-campaign.html");
        	});
		});
	}
	
	function uploadImgURL(input) {
	  if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function(e) {
		  $('#newCampaignImg').attr('src', e.target.result);
		};

		reader.readAsDataURL(input.files[0]);
	  }
	}
				  
	//Ad Campaign: preview website url 
	if ($("input#websiteUrl").length) {
		$("input#websiteUrl").change(function(){
			var	linkurl = $("#websiteUrl").val();

			if (linkurl === "") {
				$("#urlLink").attr('href', "#");
				$("#urlLink").removeAttr('target', '_blank');
			} else {
				$("#urlLink").attr('href', "http://" + linkurl);
				$("#urlLink").attr('target', '_blank');
			}
		});
	}
	
	/*date table filtering only*/
	var oTable = $('.dataTableSearchOnly').DataTable({
		"oLanguage": {
		  "sSearch": "Search Table"
		}, pageLength: 25,
		responsive: true
	});

	//table download remove headers from displaying
	
	
//	var buttonCommon = 
//     exportOptions: {
//       format: {
//         body: function(data, column, row) {
//           data = data.replace(/<div class="flagtext"\">/, '');
//           data = data.replace(/<.*?>/g, "");
//           return data;
//         }
//       }
	
	/*table filtering and save document*/
	var extTable = $('.dataTableSearch').DataTable({
		pageLength: 25,
		responsive: true,
		dom: '<"html5buttons"B>lTfgitp',
		buttons: [
			{ extend: 'copy', },
			{extend: 'csv'},
			{extend: 'excel', title: 'ExampleFile'},
			{extend: 'pdf', title: 'ExampleFile'},

			{extend: 'print',
			 customize: function (win){
				$(win.document.body).addClass('white-bg');
				$(win.document.body).css('font-size', '10px');

				$(win.document.body).find('table')
						.addClass('compact')
						.css('font-size', 'inherit');
			}
			}
		]
	});
	
	/*get calendar date custom filters*/
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	
	var yesterday = new Date(new Date().setDate(new Date().getDate()-1));
	var last7Days = new Date(new Date().setDate(new Date().getDate()-8));
	var last30Days = new Date().setDate(today.getDate()-30);
	var lastMonthStart = new Date(today.getFullYear(), today.getMonth() - 1, 1);
	var lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
	var currentMonth = new Date();
	
	if(dd<10) {
		dd = '0'+dd;
	} 

	if(mm<10) {
		mm = '0'+mm;
	} 
	
	currentMonth = mm + '/' + 1 + '/' + yyyy;
		
	/*daterange calendar*/
	$('.dateRangeFilter').daterangepicker({
    "autoApply": true,
    "ranges": {
        "Yesterday": [
            yesterday,
            yesterday
        ],
        "Last 7 Days": [
            last7Days,
            yesterday
        ],
        "Last 30 Days": [
            last30Days,
            yesterday
        ],
        "This Month": [
            currentMonth,
            yesterday
        ],
        "Last Month": [
            lastMonthStart,
            lastMonthEnd
        ]
    },
    "startDate": last7Days,
    "endDate": yesterday, 
	"maxDate": yesterday,
	"opens": "right",
	"onSelect": function(date) {
      minDateFilter = new Date(date).getTime();
      oTable.draw();
    }		
});									  								  
	var getDateRangeVal = $(".dateRangeFilter").val();
	var setKeyUp = jQuery.Event("keyup");	
	setKeyUp.ctrlKey = false;
	setKeyUp.which = 40;
	
	// Date range filter; seperate date ranges and add them to hidden fields and trigger keyup event
 	$("#filterSubmit" ).click(function() {
		minDateFilter = $.trim(getDateRangeVal.substr(0, getDateRangeVal.indexOf('-')));
		maxDateFilter = getDateRangeVal.substr( getDateRangeVal.indexOf('-') + 1);

		$("#datepicker_from").val(minDateFilter);
		$("#datepicker_to").val(maxDateFilter);

		//initialize a keyup event; this will cause the table date to start filtering
		$("#datepicker_from").trigger(setKeyUp);
		$("#datepicker_to").trigger(setKeyUp);
	});	
	
	$("#resetFilter").click(function() {
		minDateFilter = "";
		maxDateFilter = "";

		$("#datepicker_from").val(minDateFilter);
		$("#datepicker_to").val(maxDateFilter);

		//initialize a keyup event; this will cause the table date to start filtering
		$("#datepicker_from").trigger(setKeyUp);
		$("#datepicker_to").trigger(setKeyUp);
	});
	
	var applyFilterTable = "";
	
	if ($(".dataTableSearchOnly").length) {
		applyFilterTable = oTable;
	} 
	
	if ($(".dataTableSearch").length) {
		applyFilterTable = extTable;
	}
	
	//hidden date inputs will trigger the table date filtering
	$("#datepicker_from").datepicker({
		"onSelect": function(date) {
		  minDateFilter = new Date(date).getTime();
		  applyFilterTable.draw();
		}
	}).keyup(function() {
		minDateFilter = new Date(this.value).getTime();
		applyFilterTable.draw();
	});

	$("#datepicker_to").datepicker({
		"onSelect": function(date) {
		  maxDateFilter = new Date(date).getTime();
		  applyFilterTable.draw();
		}
	}).keyup(function() {
		maxDateFilter = new Date(this.value).getTime();
		applyFilterTable.draw();
	});	
	
}); //end of document ready	
		 

 minDateFilter = "";
 maxDateFilter = "";

$.fn.dataTableExt.afnFiltering.push(function(oSettings, aData, iDataIndex) { 
	"use strict";
	iDataIndex = "";	
	
    if (typeof aData._date === 'undefined') {
      aData._date = new Date(aData[0]).getTime();
    }

    if (minDateFilter && !isNaN(minDateFilter)) {
      if (aData._date < minDateFilter) {
        return false;
      }
    }

    if (maxDateFilter && !isNaN(maxDateFilter)) {
      if (aData._date > maxDateFilter) {
        return false;
      }
    }
	
    return true;	  
	
});