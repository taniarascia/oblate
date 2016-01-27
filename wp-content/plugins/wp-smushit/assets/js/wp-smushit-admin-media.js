/**
 * Adds a smush button in grid view, Show the stats
 * @type {WP_Smush|*|{}}
 */
var WP_Smush = WP_Smush || {};
jQuery(function ($) {
	"use strict";
	if (!wp.media) return;

	var manualUrl = ajaxurl + '?action=wp_smushit_manual';

	var SmushButton = Backbone.View.extend({
		className: "media-lib-wp-smush-el",
		tagName: "div",
		events: {
			"click .media-lib-wp-smush-icon": "click"
		},
		template: _.template('<span class="dashicons dashicons-media-archive media-lib-wp-smush-icon"></span>'),
		initialize: function () {
			this.render();
		},
		is_smushed: function () {
			var self = this,
				arr = _.filter(wp_smushit_data.smushed, function (id) {
					return id == self.model.get("id").toString();
				});
			return typeof arr == "object" ? arr.length : false;
		},
		render: function () {
			var data = this.model.toJSON();


			this.$el.html(this.template());
			this.$button = this.$(".media-lib-wp-smush-icon");

			if (this.is_smushed()) {
				this.$el.addClass("is_smushed");
			} else {
				this.$el.addClass("active");
				this.$button.prop("title", wp_smush_msgs.smush_now)
			}

			this.$button.data("id", data.id);
		},
		click: function (e) {
			var ajax = WP_Smush.ajax(this.model.get("id"), manualUrl, 0),
				self = this;

			e.preventDefault();
			e.stopPropagation();

			this.$button.css({display: "block"});
			this.$button.prop("disabled", true);
			this.$button.addClass("active spinner");
			ajax.complete(function (res) {
				self.$button.prop("disabled", false);
				self.$button.removeClass("spinner");
				self.$button.removeClass("active");
				self.$el.removeClass("active");
				self.$el.addClass("is_smushed");
			});
		}
	});


	/**
	 * Add smush it button to the image thumb
	 */
	WP_Smush.Attachments = wp.media.view.Attachments.extend({
		createAttachmentView: function (attachment) {

			var view = wp.media.view.Attachments.__super__.createAttachmentView.apply(this, arguments);

			_.defer(function () {
				var smush_button = new SmushButton({model: view.model});
				view.$el.append(smush_button.el);
				view.$el.addClass("has-smush-button");
			});

			return view;
		}
	});
	//wp.media.view.Attachments = WP_Smush.Attachments;
});
