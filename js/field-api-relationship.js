(function ($) {

	acf.fields.api_relationship = acf.fields.relationship.extend({
		type: 'api_relationship',
		focus: function () {

			// get elements
			this.$el = this.$field.find('.acf-relationship');
			this.$input = this.$el.find('.acf-hidden input');
			this.$choices = this.$el.find('.choices'),
				this.$values = this.$el.find('.values');

			// get options
			this.o = acf.get_data(this.$el);

		},
		fetch: function () {
			console.log('Fetching...');

			// reference
			var self = this;
			var $field = this.$field;

			// add class
			this.$el.addClass('is-loading');
			// abort XHR if this field is already loading AJAX data
			if (this.o.xhr) {
				this.o.xhr.abort();
				this.o.xhr = false;
			}


			// add to this.o
			this.o.field_key = $field.data('key');
			this.o.post_id = acf.get('post_id');


			// ready for ajax
			var ajax_data = acf.prepare_for_ajax(this.o);

			// clear html if is new query
			if (ajax_data.paged == 1) {
				this.$choices.children('.list').html('')
			}


			// add message
			this.$choices.find('ul:last').append('<p><i class="acf-loading"></i> ' + acf._e('relationship', 'loading') + '</p>');
				
			
			// get results
			var xhr = $.ajax({
				url: this.o.api,
				dataType: 'json',
				type: 'post',
				method: 'GET',
				data: {
					order:'asc',
					orderby:'title',
					search: ajax_data.s,
					page: ajax_data.paged
				},
				success: function (json) {
					// render
					self.doFocus($field);
					self.render( self.decode_data(json) );
				}

			});
			// update el data
			this.$el.data('xhr', xhr);
		},
		decode_data: function (api_data) {
			// bail ealry if no data
			if (!api_data) {
				return [];
			}

			var data = [];
			//loop
			$.each(api_data, function (k, v) {
				var title = v.title || v.name;
				title = title.rendered || title;
				data[ k ] = {
					id: v.id,
					text: title
				};

			});
			// return
			return {
				results: data
			};
		}
	});
})(jQuery);
