jQuery(document).ready(($) => {

	'use strict';

	let fileFrame;
	let myMap;
	let mapList = {};

	let spMetaBox = {
		actions: function(){
			
			window.onload = function() {
				spMetaBox.makeSortable();
			}

			$('body').on('change', 	'.sp-field-checkbox', 		spMetaBox.checked);
			$('body').on('click', 	'.sp-add-image', 			spMetaBox.addImage);
			$('body').on('click', 	'.sp-change-image', 		spMetaBox.changeImage);
			$('body').on('click', 	'.sp-remove-image', 		spMetaBox.removeImage);
		},

		checked: function(){
			let elId = $(this).attr('id'),
				val  = $(this).is(':checked') ? 'y' : 'n';

			$('#' + elId + '_hd').val(val);
		},

		addImage: function(){

			let elID = $(this).attr('data-id');

			if (fileFrame) fileFrame.close();

			let fieldName 	= $('#id_'+elID).attr('data-name'),
			 	changeTitle = $('#id_'+elID).attr('data-change'),
				removeTitle = $('#id_'+elID).attr('data-remove');
			
			fileFrame = wp.media.frames.fileFrame = wp.media({
				title: $(this).data('uploader-title'),
				button: {
					text: $(this).data('uploader-button-text'),
				},
				multiple: true
			});

			fileFrame.on('select', function() {
				let listIndex 	= $('#id_'+elID+' li').index($('#id_'+elID+' li:last')),
					selection 	= fileFrame.state().get('selection'),
					index 		= 0,
					appendStr 	= '';

				selection.map(function(attachment, i) {
					attachment = attachment.toJSON();
					index = listIndex + (i + 1);
					appendStr = '<li><input type="hidden" name="'+ fieldName +'[' + index + ']" value="' + attachment.id + '">';
					appendStr += '<img class="sp-image-preview" src="' + attachment.sizes.thumbnail.url + '">';
					appendStr += '<a class="sp-change-image button button-small" href="#" data-uploader-title="'+changeTitle+'" data-uploader-button-text="'+changeTitle+'">'+changeTitle+'</a><br>';
					appendStr += '<small><a class="sp-remove-image" data-id="'+elID+'" href="#">'+removeTitle+'</a></small></li>';
					$('#id_'+elID).append(appendStr);
				});
			});

			spMetaBox.makeSortable();

			fileFrame.open();

			return false;
		},

		changeImage: function(){
			let that = $(this);

			if (fileFrame) fileFrame.close();

			fileFrame = wp.media.frames.fileFrame = wp.media({
				title: $(this).data('uploader-title'),
				button: {
					text: $(this).data('uploader-button-text'),
				},
				multiple: false
			});

			fileFrame.on( 'select', function() {
				let attachment = fileFrame.state().get('selection').first().toJSON();

				that.parent().find('input:hidden').attr('value', attachment.id);
				that.parent().find('img.sp-image-preview').attr('src', attachment.sizes.thumbnail.url);
			});

			fileFrame.open();

			return false;
		},

		removeImage: function(){

			let elID = $(this).attr('data-id')

			$(this).parents('li').animate({ opacity: 0 }, 200, function() {
				$(this).remove();
				spMetaBox.resetIndexImage();
			});

			let fieldName 	= $('#id_'+elID).attr('data-name'),
				appendStr = '<li><input type="hidden" name="'+ fieldName +'[0]" value=""></li>';

			$('#id_'+elID).append(appendStr);

			return false;
		},
		
		makeSortable: function(){
			if($('.sp-gallery-metabox-list').length > 0){
				$('.sp-gallery-metabox-list').sortable({
					opacity: 0.8,
				});
			}
		},
		
		init: function(){
			spMetaBox.actions();
		},
	}

	let spAdminYandexMap = {

		addMarker: function(itemID) {
			ymaps.ready(function(){

				let address = document.getElementById('id_'+itemID).value,
					map 	= mapList[itemID+'_map'];
				
				if(address != ''){
					ymaps.geocode(address, {
				        results: 1
				    }).then(function (res) {

				    	map.geoObjects.removeAll();
						
				    	let first 	= res.geoObjects.get(0),
			            	coords 	= first.geometry.getCoordinates(),
							marker 	= new ymaps.Placemark(coords, {}, {draggable: true });

						document.getElementById('id_'+itemID+'_coords').value = coords;
						
						map.geoObjects.add(marker);
						marker.options.set('preset', 'islands#redIcon');
						                   
						marker.events.add("dragend", function (e) {
						    coords = this.geometry.getCoordinates();
						    document.getElementById('id_'+itemID+'_coords').value = coords;
						}, marker);

						map.setBounds(map.geoObjects.getBounds(), {
						    checkZoomRange: true,
						    zoomMargin: 35
						});

				    });
				}

			});	
		}, 

		initMap: function(){
			ymaps.ready(function(){

				let items = document.querySelectorAll('.sp-field-map');

				for (let item of items) {
					let itemID 	= item.getAttribute('id'),
						name 	= item.getAttribute('data-name');

					
					mapList[itemID] = new ymaps.Map(itemID, {
				        center: [0, 0],
				        zoom: 12,
				    });
					
					let coords 	= document.getElementById('id_'+name+'_coords').value.split(',');

					if(coords!=''){

					    let	marker 	= new ymaps.Placemark(coords, {}, {draggable: true }),
					    	map 	= mapList[itemID];

					   	map.geoObjects.add(marker);
					    marker.options.set('preset', 'islands#redIcon');
					                       
					    marker.events.add("dragend", function (e) {
					        coords = this.geometry.getCoordinates();
					        document.getElementById('id_'+name+'_coords').value = coords;

					    }, marker);

					    map.setBounds(map.geoObjects.getBounds(), {
					        checkZoomRange: true,
					        zoomMargin: 35
					    }); 
					}      
				}
				
			});	
		},

		init: function() {
			addEventListener('click', function(event){
				let target 		= event.target,
					classList 	= target.getAttribute('class');

				if(classList.includes('sp-add-marker') !== null){	

					if(classList.includes('sp-add-marker')){ 
						let itemID = target.getAttribute('data-id');
						spAdminYandexMap.addMarker(itemID);
					}
				}	
				
	 		});
			spAdminYandexMap.initMap();
		}
	}

	let initSpMetaBox = spMetaBox.init();

	if($('.sp-field-map').length){
		let initYandexMap = spAdminYandexMap.init();
	}

});