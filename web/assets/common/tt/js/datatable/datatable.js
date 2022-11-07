var data_table = null;
$(document).ready(function(){

	if($(".dataTable").length > 0)
	{
		var ajax_url = $('.dataTable').attr("ajax_url");
		var ajax_params = $('.dataTable').attr("parameters");
		if(ajax_params) ajax_params = ajax_params.split(",");
		//
		var FOOTER_CALL_BACK = $('.dataTable').attr("footerCallBack");
		//
		var RESPONSIVE = $('.dataTable').attr("responsive") || "false";
		RESPONSIVE = (RESPONSIVE==true || RESPONSIVE=="true");
		var FIXED_HEADER = $('.dataTable').attr("fixedHeader") || "false";
		FIXED_HEADER = (FIXED_HEADER==true || FIXED_HEADER=="true");
		FIXED_COLUMNS = $('.dataTable').attr("fixedColumn") || "false";;
		FIXED_COLUMNS = (FIXED_COLUMNS==true || FIXED_COLUMNS=="true");
		AUTO_WIDTH = $('.dataTable').attr("autoWidth") || true;

		var COLUMNS 		= [];
		var COLUMNS_DATA 	= [];

		//
		//
		//
		window.getSelectedRow=function()
		{
			return data_table.row('.selected');
		}

		window.deleteSelectedRow=function(selectedRow)
		{
			window.deleteRow(window.getSelectedRow());
		}

		window.deleteRow=function(selectedRow)
		{
			selectedRow.remove().draw( false );
		}

		window.getCellIndexById=function(id)
		{
			return COLUMNS[id].index;
		}

		window.reloadTable=function()
		{
			data_table.ajax.reload();
		}

		window.drawTable=function()
		{
			data_table.draw();
		}
		
		window.adjustColumns=function(refreshData)
		{
			data_table.columns.adjust();
			if(refreshData) window.drawTable();
		}

		window.getCellIdByIndex=function(index)
		{			
			var id = null;
			for(var colId in COLUMNS)
			{
				if(COLUMNS[colId].index == index) id = colId;
			}
			return id;
		}
		
		window.getCellByIndex=function(index)
		{			
			var id = window.getCellIdByIndex(index);
			var col = null;
			if(id != null) col = COLUMNS[id];
			//
			return col;
		}

		window.createdRowFunction=function( row, data, dataIndex ) {

			var row_handler = $(".dataTable").attr("row_handler") != undefined ? $(".dataTable").attr("row_handler") : "";

			if( row_handler != "" ){
				try
				{
					row_handler = eval(row_handler);
		
					if( typeof row_handler == 'function' ){
						row_handler(row, data, dataIndex);
					}
				}catch(e)
				{
					console.log("ERROR calling DT row handler");
					console.error(e);
				}
			}
		}

		window.createdCellFunction=function(td, cellData, rowData, row, col ){

			var cell = window.getCellByIndex(col);
			if(cell != null)
			{
				var cell_handler = cell.cell_handler != undefined ? cell.cell_handler.nodeValue : "";

				if( cell_handler != "" ){
					var cell_handling = eval(cell_handler);
	
					if( typeof cell_handling == 'function' ){
						cell_handling(td, cellData, rowData, row, col);
					}
				}
				else{
					var general_cell_handling = $('.dataTable').attr("cell_handler");

					if(general_cell_handling && general_cell_handling!='')
					{
						general_cell_handling = eval(general_cell_handling);

						if (general_cell_handling != "" && typeof general_cell_handling == 'function') {
							general_cell_handling(td, cellData, rowData, row, col);
						}
					}
				}
			}
		}
		//
		//
		//

		$(".dataTable thead tr th").each(function(i) {

			var this_id = $(this).attr("id");
			var this_visible = $(this).attr("visible") != undefined ? $(this).attr("visible") : "true" ;
			this_visible = (this_visible != "false");
			var this_searchable = $(this).attr("search") != undefined ? $(this).attr("search") : "true" ;
			this_searchable = (this_searchable != "false");
			var this_ordered = $(this).attr("ordered") != undefined ? $(this).attr("ordered") : "true" ;
			this_ordered = (this_ordered != "false");
			var render = $(this).attr("render") != undefined ? $(this).attr("render") : null ;
			var defaultContent = $(this).attr("defaultContent") != undefined ? $(this).attr("defaultContent") : "" ;
			var dataName = $(this).attr("dbname");
			var classname = $(this).attr("classname") != undefined ? $(this).attr("classname") : null ;
			var type = $(this).attr("type") != undefined ? $(this).attr("type") : null ;
			//
			var formatter = $(this).attr("formatter") != undefined ? $(this).attr("formatter") : null ;
			var thousands = $(this).attr("number-thousands") != undefined ? $(this).attr("number-thousands") : ",";
			var decimals = $(this).attr("number-decimals") != undefined ? $(this).attr("number-decimals") : ".";
			var prefix = $(this).attr("prefix") != undefined ? $(this).attr("prefix") : "";
			var suffix = $(this).attr("suffix") != undefined ? $(this).attr("suffix") : "";
			var precision = $(this).attr("number-precision") != undefined ? $(this).attr("number-precision") : 2;
			var width = $(this).attr("width") != undefined ? $(this).attr("width") : null;
			if(width!=null)
			{
				//FIXED_COLUMNS = true;
				//
				$(this).css("min-width", (!isNaN(width) ? (width + "px") : width) );
			}
			//
			var skipDb = $(this).attr("skipDb") != undefined ? $(this).attr("skipDb") : false ;
			skipDb = (skipDb == "true" || skipDb == true);
			if(skipDb)
			{
				this_searchable = false;
				orderable = false;
				dataName = null;
			}

			var alias = $(this).attr("alias");
			if(skipDb || !dataName || dataName=="" || dataName==undefined) alias = null;

			COLUMNS[this_id] = this.attributes;
			COLUMNS[this_id].index = i;

			var col = {data: dataName, name: alias, visible: this_visible, searchable: this_searchable, orderable: this_ordered, skipDb: skipDb, defaultContent: defaultContent, className: classname, type: type, width: width};
			if(col.name == undefined || (col.name && col.name.split(" ").join("")=="")) delete col.name;

			switch(formatter)
			{
				case 'number':
					col.render = $.fn.dataTable.render.number(thousands, decimals, precision, prefix, suffix);
					break;
			}

			if(render) eval("col.render = window." + render);

			COLUMNS_DATA.push(col);
		});
		
		var displayStart = $('.dataTable').attr("display_start");
		if(!displayStart || displayStart == "" || displayStart == undefined) displayStart = 0;
		var pageLength = $('.dataTable').attr("nb_records_per_page");
		if(!pageLength || pageLength == "" || pageLength == undefined) pageLength = 10;
		var default_order_id = $('.dataTable').attr("default_order_column_id");
		if(!default_order_id || default_order_id == "" || default_order_id == undefined) default_order_id = 0;
		var default_order_direction = $('.dataTable').attr("default_order_direction");
		if(!default_order_direction || default_order_direction == "" || default_order_direction == undefined) default_order_direction = 'asc';
		var pagination_dropdown = [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]];
		if($('.dataTable').attr("pagination_dropdown") && $('.dataTable').attr("pagination_dropdown") != "" && $('.dataTable').attr("pagination_dropdown") != undefined) pagination_dropdown = $('.dataTable').attr("pagination_dropdown").split(",");

		var new_pagination_dropdown = [];
		var new_pagination_index_dropdown = [];
		var new_pagination_value_dropdown = [];

		if(typeof(pagination_dropdown[0]) == "object")
		{
			for (var j = 0; j < pagination_dropdown[0].length; j++) {
				var pag_val = pagination_dropdown[0][j];
				var pag_disp = pagination_dropdown[1][j];
				new_pagination_index_dropdown[j] = parseInt(pag_val);
				new_pagination_value_dropdown[j] = pag_disp;
			}		
		}else
			{
				for (var j = 0; j < pagination_dropdown.length; j++) {
					var pag_val = pagination_dropdown[j];
					new_pagination_index_dropdown[j] = parseInt(pag_val);
					new_pagination_value_dropdown[j] = pag_val;
				}
			}
		//
		//new_pagination_index_dropdown.push(-1);
		//new_pagination_value_dropdown.push("All");

		new_pagination_dropdown.push(new_pagination_index_dropdown);
		new_pagination_dropdown.push(new_pagination_value_dropdown);

		var default_order_index = window.getCellIndexById(default_order_id);

		var tblSettings = {			
				"responsive": RESPONSIVE,
				"fixedHeader": FIXED_HEADER,
				"processing": true,
				"serverSide": true,
				"columns": COLUMNS_DATA,
				"autoWidth": AUTO_WIDTH,
				"createdRow": window.createdRowFunction,
				"lengthMenu": new_pagination_dropdown,
				"displayStart": parseInt(displayStart),
				"pageLength" : parseInt(pageLength),
				"order" : [[default_order_index,default_order_direction]],
				"columnDefs": [ 
					{
						"targets": "_all",
						"createdCell": window.createdCellFunction
					}
				],
				"fixedColumns": FIXED_COLUMNS,
				"pagingType": "full_numbers",
				"paging": true,
				"searching": true,
				"language": {
					"decimal": ",",
					"thousands": ".",
				    "paginate": {
				      "previous": "<",
				      "next" : ">",
				      "first" : "<<",
				      "last": ">>"
				    },
				    "lengthMenu": "Records to display _MENU_",
		            "zeroRecords": "No records found",
		            //"info": "Showing _PAGE_ of _PAGES_",
		            "infoEmpty": "No available records",
		            "infoFiltered": "(filtered from _MAX_ total records)"
				  },
				  "scrollX": true,
				  "footerCallback": (FOOTER_CALL_BACK ? eval(FOOTER_CALL_BACK) : null)
			};
		//
		if( ajax_params ){
			tblSettings["ajax"] = {
									"url": ajax_url,
									"type" : "POST",
									"data": function ( d ) {
										d.params = {};
										ajax_params.forEach(function(currentValue, index, arr)
										{
											var value= null;
											eval("value = window." + currentValue);
											if(value!=null) d.params[currentValue] = value;
										});
										// return JSON.stringify( d );
										return d;
									}
								};
		}
		else{
            tblSettings["ajax"] = {
                "url": ajax_url,
                "type" : "POST"
            };
		}
		//
		data_table = $('.dataTable').DataTable( tblSettings );
		//
		//
	    $('.dataTable').on( 'click', 'tr', function () {
	        if ( $(this).hasClass('selected') ) {
	            $(this).removeClass('selected');
	        }
	        else {
	        	data_table.$('tr.selected').removeClass('selected');
	            $(this).addClass('selected');
	        }
	    } );
		//
		//
		$(window).on("resize", function(){
			window.adjustColumns(false);
		})
		//

	}
});

